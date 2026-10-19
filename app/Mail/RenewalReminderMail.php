<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class RenewalReminderMail extends Mailable
{
    public function __construct(public $user, public $sub)
    {
    }

    public function build()
    {
        return $this->subject('Tu plan de Umbra vence pronto')->view('emails.renewal');
    }
}
