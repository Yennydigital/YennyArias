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

    protected function messages(): array
    {
        return [
            'name.required' => __('Escribí tu nombre.'),
            'name.min' => __('El nombre es demasiado corto.'),
            'name.max' => __('El nombre es demasiado largo.'),
            'email.required' => __('Escribí tu email.'),
            'email.email' => __('Ingresá un email válido.'),
            'email.max' => __('El email es demasiado largo.'),
            'message.required' => __('Escribí tu mensaje.'),
            'message.min' => __('El mensaje debe tener al menos :min caracteres.'),
            'message.max' => __('El mensaje es demasiado largo.'),
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
            $this->addError('message', __('Enviaste varios mensajes seguidos. Probá de nuevo más tarde.'));
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
            $this->addError('message', __('No pude enviar el mensaje en este momento. Probá más tarde.'));
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
