<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Letter extends Model
{
    protected $fillable = ['user_id', 'template', 'title', 'sections', 'photos', 'music_url'];

    protected $casts = [
        'sections' => 'array',
        'photos' => 'array',
        'paid_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Letter $letter) {
            $letter->slug ??= Str::lower(Str::random(16));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
