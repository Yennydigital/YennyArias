<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplatePurchase extends Model
{
    protected $fillable = ['user_id', 'template', 'payment_ref', 'paid_at'];

    protected $casts = ['paid_at' => 'datetime'];
}
