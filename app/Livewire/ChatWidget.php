<?php

namespace App\Livewire;

use App\Services\PortfolioChatService;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class ChatWidget extends Component
{
    public const SUGGESTIONS = [
        'Ver proyectos' => '¿Qué proyectos ha desarrollado Yenny?',
        'Tecnologías' => '¿Qué tecnologías utiliza Yenny?',
        'Disponibilidad' => '¿Cuál es la disponibilidad de Yenny?',
    ];

    public string $input = '';
    public array $messages = [];
    public bool $pending = false;

    public function mount(): void
    {
        $this->resetChat();
    }

    private function resetChat(): void
    {
        $this->messages = [[
            'role' => 'assistant',
            'content' => 'Bienvenido/a. Soy el asistente virtual de Yenny Arias. ¿En qué puedo ayudarle?',
        ]];
        $this->input = '';
        $this->pending = false;
        $this->resetValidation();
    }

    public function clear(): void
    {
        $this->resetChat();
    }

    public function ask(int $index): void
    {
        $texts = array_values(self::SUGGESTIONS);
        if (! isset($texts[$index])) {
            return;
        }
        $this->input = $texts[$index];
        $this->send();
    }

    public function send(): void
    {
        if ($this->pending) {
            return;
        }

        $this->validate(['input' => 'required|string|max:500'], [
            'input.required' => 'Escriba su consulta.',
            'input.max' => 'El mensaje no puede superar los 500 caracteres.',
        ]);

        $this->messages[] = ['role' => 'user', 'content' => trim($this->input)];
        $this->messages = array_slice($this->messages, -30);
        $this->input = '';
        $this->pending = true;
    }

    public function reply(): void
    {
        if (! $this->pending) {
            return;
        }

        $key = 'chat:' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 10)) {
            $answer = 'Alcanzó el límite de consultas por hora. Puede escribirle a Yenny desde la sección Contacto.';
        } else {
            RateLimiter::hit($key, 3600);
            $answer = app(PortfolioChatService::class)->reply($this->messages);
        }

        $this->messages[] = ['role' => 'assistant', 'content' => $answer];
        $this->pending = false;
    }

    public function render()
    {
        return view('livewire.chat-widget', ['suggestions' => array_keys(self::SUGGESTIONS)]);
    }
}
