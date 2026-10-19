<?php

return [
    // Cotizacion usada SOLO para cobrar con MercadoPago en moneda local.
    'mp_currency' => env('BILLING_MP_CURRENCY', 'ARS'),
    'fx_rate' => (float) env('BILLING_FX_RATE', 0),

    'providers' => ['mercadopago', 'stripe'],

    'mercadopago' => [
        'token' => env('MERCADO_PAGO_ACCESS_TOKEN'),
        'webhook_secret' => env('MERCADO_PAGO_WEBHOOK_SECRET'),
        // URL publica https (tunel o dominio). Vacio = url('/')
        'public_url' => env('MP_PUBLIC_URL'),
        'qr_pos_id' => env('MP_QR_POS_ID'),
    ],
];
