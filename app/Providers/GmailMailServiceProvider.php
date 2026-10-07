<?php

namespace App\Providers;

use App\Mail\GmailTransport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class GmailMailServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Mail::extend('gmail', fn (array $config) => new GmailTransport());
    }
}
