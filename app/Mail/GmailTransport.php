<?php

namespace App\Mail;

use App\Services\GmailMailService;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;

class GmailTransport extends AbstractTransport
{
    protected function doSend(SentMessage $message): void
    {
        (new GmailMailService())->sendRaw($message->toString());
    }

    public function __toString(): string
    {
        return 'gmail';
    }
}
