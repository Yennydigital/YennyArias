<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Capsule extends Model
{
    protected $guarded = [];

    protected $casts = [
        'opens_at' => 'datetime',
        'expires_at' => 'datetime',
        'opened_at' => 'datetime',
        'burn_after_read' => 'boolean',
    ];
}
