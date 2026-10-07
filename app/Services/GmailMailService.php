<?php

namespace App\Services;

use App\Models\Gmail\GmailConfig;
use Illuminate\Support\Facades\Http;

class GmailMailService
{
    private GmailConfig $config;

    public function __construct(?GmailConfig $config = null)
    {
        $this->config = $config ?? GmailConfig::firstOrCreate(['id' => 1], ['is_active' => true]);
    }

    public function authUrl(string $state): string
    {
        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id'     => config('services.gmail.client_id'),
            'redirect_uri'  => config('services.gmail.redirect_uri'),
            'response_type' => 'code',
            'scope'         => 'openid email https://www.googleapis.com/auth/gmail.send',
            'access_type'   => 'offline',
            'prompt'        => 'consent',
            'state'         => $state,
        ]);
    }

    public function getAccessToken(): string
    {
        if (! $this->config->isTokenExpired()) {
            return $this->config->access_token;
        }

        $data = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type'    => 'refresh_token',
            'client_id'     => config('services.gmail.client_id'),
            'client_secret' => config('services.gmail.client_secret'),
            'refresh_token' => (config('services.gmail.refresh_token') ?: $this->config->refresh_token),
        ])->json();

        if (! isset($data['access_token'])) {
            throw new \RuntimeException('No se pudo renovar el access token de Gmail.');
        }

        $this->config->update([
            'access_token'            => $data['access_token'],
            'access_token_expires_at' => now()->addSeconds($data['expires_in'] ?? 3500),
        ]);

        return $data['access_token'];
    }

    public function exchangeCode(string $code): void
    {
        $data = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code'          => $code,
            'client_id'     => config('services.gmail.client_id'),
            'client_secret' => config('services.gmail.client_secret'),
            'redirect_uri'  => config('services.gmail.redirect_uri'),
            'grant_type'    => 'authorization_code',
        ])->json();

        if (! isset($data['access_token'])) {
            throw new \RuntimeException('Google rechazó el código: ' . ($data['error_description'] ?? $data['error'] ?? 'sin detalle'));
        }

        if (! str_contains($data['scope'] ?? '', 'gmail.send')) {
            throw new \RuntimeException('Google no entregó el permiso de envío.');
        }

        $update = [
            'access_token'            => $data['access_token'],
            'access_token_expires_at' => now()->addSeconds($data['expires_in'] ?? 3500),
        ];

        if (! empty($data['refresh_token'])) {
            $update['refresh_token'] = $data['refresh_token'];
        }

        $this->config->update($update);

        $info = Http::withToken($data['access_token'])
            ->get('https://openidconnect.googleapis.com/v1/userinfo')->json();

        if (isset($info['email'])) {
            $this->config->update(['email' => $info['email']]);
        }
    }

    public function sendRaw(string $mime): void
    {
        $raw = rtrim(strtr(base64_encode($mime), '+/', '-_'), '=');

        $response = Http::withToken($this->getAccessToken())
            ->post('https://gmail.googleapis.com/gmail/v1/users/me/messages/send', ['raw' => $raw]);

        if (! $response->successful()) {
            throw new \RuntimeException('Gmail API error: ' . $response->body());
        }
    }
}
