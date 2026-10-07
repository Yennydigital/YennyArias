<?php

namespace App\Models\Gmail;

use Illuminate\Database\Eloquent\Model;

class GmailConfig extends Model
{
    protected $fillable = [
        'redirect_uri', 'client_id', 'client_secret', 'email',
        'refresh_token', 'access_token', 'access_token_expires_at', 'is_active',
    ];

    protected $hidden = ['client_secret', 'refresh_token', 'access_token'];

    protected $casts = [
        'client_secret'           => 'encrypted',
        'refresh_token'           => 'encrypted',
        'access_token'            => 'encrypted',
        'access_token_expires_at' => 'datetime',
        'is_active'               => 'boolean',
    ];

    public static function active(): ?self
    {
        return static::where('is_active', true)->latest()->first();
    }

    public function isTokenExpired(): bool
    {
        if (! $this->access_token || ! $this->access_token_expires_at) {
            return true;
        }

        return now()->addMinute()->gte($this->access_token_expires_at);
    }
}
