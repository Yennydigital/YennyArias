<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\Site\SiteController::class, 'index'])->name('index');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    \App\Http\Middleware\RedirectAdminToPanel::class,
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

// Crear: solo con sesión iniciada, dentro del dashboard
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', \App\Http\Middleware\RedirectAdminToPanel::class])->group(function () use ($capsuleController) {
    Route::redirect('/write', '/letters/create')->name('capsulas.crear');
    Route::get('/sealed', [$capsuleController, 'done'])->name('capsulas.listo');
});

// Abrir: público (el destinatario no tiene cuenta, solo el código)
\Illuminate\Support\Facades\Route::get('/open', [$capsuleController, 'openForm'])->name('capsulas.abrir');
\Illuminate\Support\Facades\Route::post('/open', [$capsuleController, 'open'])->middleware('throttle:10,1')->name('capsulas.abrir.enviar');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', \App\Http\Middleware\RedirectAdminToPanel::class])->group(function () {
    Route::get('/letters', [\App\Http\Controllers\LetterController::class, 'index'])->name('letters.index');
    Route::delete('/letters/{letter}', [\App\Http\Controllers\LetterController::class, 'destroy'])->name('letters.destroy');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', \App\Http\Middleware\RedirectAdminToPanel::class])->group(function () {
    Route::view('/letters/create', 'letters.create')->name('letters.create');
});

// Planes: vista para usuarios (misma definicion para todos)
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->get('/plans', [\App\Http\Controllers\PlanController::class, 'index'])->name('plans.index');

// Cobros
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::post('/billing/checkout', [\App\Http\Controllers\BillingController::class, 'start'])->name('billing.checkout');
    Route::get('/billing/return', [\App\Http\Controllers\BillingController::class, 'back'])->name('billing.return');
    Route::post('/billing/qr', [\App\Http\Controllers\BillingController::class, 'qr'])->name('billing.qr');
    Route::get('/billing/qr/{ref}/status', [\App\Http\Controllers\BillingController::class, 'qrStatus'])->name('billing.qr.status');
});
Route::post('/billing/mercadopago/webhook', [\App\Http\Controllers\BillingController::class, 'webhook'])
    ->middleware('throttle:60,1')->name('billing.mercadopago.webhook');

// Admin: planes y suscripciones (solo users.is_admin = 1; el resto recibe 404)
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', \App\Http\Middleware\EnsureAdmin::class])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::redirect('/', '/admin/plans');
        Route::get('visitas', [\App\Http\Controllers\Admin\VisitsController::class, 'index'])->name('visits');
        Route::view('dashboard', 'dashboard')->name('dashboard');
        Route::get('letters', [\App\Http\Controllers\LetterController::class, 'index'])->name('letters.index');
        Route::view('letters/create', 'letters.create')->name('letters.create');
        Route::get('sealed', [\App\Http\Controllers\CapsuleController::class, 'done'])->name('capsulas.listo');
        Route::resource('plans', \App\Http\Controllers\Admin\PlanController::class)->except(['destroy']);
        Route::get('subscriptions', [\App\Http\Controllers\Admin\SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::post('subscriptions', [\App\Http\Controllers\Admin\SubscriptionController::class, 'store'])->name('subscriptions.store');
        Route::get('subscriptions/{subscription}/edit', [\App\Http\Controllers\Admin\SubscriptionController::class, 'edit'])->name('subscriptions.edit');
        Route::put('subscriptions/{subscription}', [\App\Http\Controllers\Admin\SubscriptionController::class, 'update'])->name('subscriptions.update');
        Route::post('subscriptions/{subscription}/extend', [\App\Http\Controllers\Admin\SubscriptionController::class, 'extend'])->name('subscriptions.extend');
        Route::post('subscriptions/{subscription}/cancel', [\App\Http\Controllers\Admin\SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    });

// PayPal
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::post('/billing/paypal/checkout', [\App\Http\Controllers\PayPalController::class, 'start'])->name('billing.paypal.checkout');
    Route::get('/billing/paypal/return', [\App\Http\Controllers\PayPalController::class, 'back'])->name('billing.paypal.return');
    Route::get('/billing/paypal/cancel', [\App\Http\Controllers\PayPalController::class, 'cancel'])->name('billing.paypal.cancel');
});
Route::post('/billing/paypal/webhook', [\App\Http\Controllers\PayPalController::class, 'webhook'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class])
    ->middleware('throttle:60,1')->name('billing.paypal.webhook');
