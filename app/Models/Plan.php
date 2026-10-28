<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'code', 'name', 'currency', 'price_monthly', 'price_yearly',
        'max_photos', 'max_days', 'max_letters', 'allow_music', 'premium_templates',
        'is_active', 'position',
    ];

    protected $casts = [
        'allow_music' => 'boolean',
        'premium_templates' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function priceFor(string $cycle): float
    {
        return (float) ($cycle === 'yearly' ? ($this->price_yearly ?? $this->price_monthly * 12) : $this->price_monthly);
    }
}
