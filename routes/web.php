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

// Umbra: cápsulas de mensajes con código
$capsuleController = \App\Http\Controllers\CapsuleController::class;
\Illuminate\Support\Facades\Route::get('/write', [$capsuleController, 'create'])->name('capsulas.crear');
\Illuminate\Support\Facades\Route::post('/write', [$capsuleController, 'store'])->middleware('throttle:20,1')->name('capsulas.guardar');
\Illuminate\Support\Facades\Route::get('/sealed', [$capsuleController, 'done'])->name('capsulas.listo');
\Illuminate\Support\Facades\Route::get('/open', [$capsuleController, 'openForm'])->name('capsulas.abrir');
\Illuminate\Support\Facades\Route::post('/open', [$capsuleController, 'open'])->middleware('throttle:10,1')->name('capsulas.abrir.enviar');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/letters', [\App\Http\Controllers\LetterController::class, 'index'])->name('letters.index');
    Route::delete('/letters/{letter}', [\App\Http\Controllers\LetterController::class, 'destroy'])->name('letters.destroy');
});
