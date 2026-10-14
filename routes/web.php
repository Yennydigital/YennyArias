<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\Site\SiteController::class, 'index'])->name('index');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Gmail: conexión única, solo en local
if (app()->environment('local')) {
    Route::prefix('gmail')->name('gmail.')->group(function () {
        Route::get('/connect', [\App\Http\Controllers\Gmail\GmailController::class, 'connect'])->name('connect');
        Route::get('/callback', [\App\Http\Controllers\Gmail\GmailController::class, 'callback'])->name('callback');
    });
}

Route::get('/lang/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['es', 'en'], true), 404);
    session(['locale' => $locale]);

    return redirect()->back();
})->name('lang.switch');
