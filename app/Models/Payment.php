<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 'plan_id', 'subscription_id', 'billing_cycle', 'amount', 'currency',
        'method', 'status', 'amount_usd', 'fx_rate', 'provider_data', 'external_reference', 'provider_payment_id', 'paid_at',
    ];

    protected $casts = ['paid_at' => 'datetime', 'amount' => 'decimal:2'];
}
