<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Umbra: borra las cápsulas vencidas una vez al día
\Illuminate\Support\Facades\Schedule::call(function () {
    \App\Models\Capsule::where('expires_at', '<', now())->delete();
})->daily();
