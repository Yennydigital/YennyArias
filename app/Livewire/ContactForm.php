<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $message = '';
    public string $website = ''; // honeypot
    public bool $sent = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:80',
            'email' => 'required|email|max:120',
            'message' => 'required|string|min:10|max:2000',
        ];
    }

    public function send(): void
    {
        $this->validate();

        if ($this->website !== '') {
            $this->sent = true;
            return;
        }

        $key = 'contact:' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $this->addError('message', 'Enviaste varios mensajes seguidos. Probá de nuevo más tarde.');
            return;
        }

        $body = "Nombre: {$this->name}\nEmail: {$this->email}\n\n{$this->message}";

        try {
            Mail::raw($body, function ($mail) {
                $mail->to(config('services.contact.to'))
                    ->replyTo($this->email, $this->name)
                    ->subject('Portfolio: mensaje de ' . $this->name);
            });
        } catch (\Throwable $e) {
            report($e);
            $this->addError('message', 'No pude enviar el mensaje en este momento. Probá más tarde.');
            return;
        }

        RateLimiter::hit($key, 3600);

        $this->reset(['name', 'email', 'message', 'website']);
        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
