<?php

namespace App\Support\Billing;

use App\Models\Payment;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MercadoPagoGateway
{
    private const API = 'https://api.mercadopago.com';

    private function token(): string
    {
        $t = (string) config('billing.mercadopago.token');
        if ($t === '') {
            throw new RuntimeException('Falta MERCADO_PAGO_ACCESS_TOKEN.');
        }

        return $t;
    }

    /** Crea la preferencia de Checkout Pro. Devuelve ['id' => ..., 'url' => ...]. */
    public function createPreference(Payment $payment, $user, Plan $plan): array
    {
        $base = rtrim(config('billing.mercadopago.public_url') ?: url('/'), '/');
        $back = $base . route('billing.return', [], false);
        $cycle = $payment->billing_cycle === 'yearly' ? 'anual' : 'mensual';

        $payload = [
            'items' => [[
                'title' => 'Umbra ' . $plan->name . ' (' . $cycle . ')',
                'quantity' => 1,
                'unit_price' => (float) $payment->amount,
                'currency_id' => $payment->currency,
            ]],
            'payer' => ['email' => $user->email],
            'external_reference' => $payment->external_reference,
            'binary_mode' => true,
            'back_urls' => ['success' => $back, 'pending' => $back, 'failure' => $back],
        ];

        if (str_starts_with($base, 'https://')) {
            $payload['auto_return'] = 'approved';
        }
        if (! in_array(parse_url($base, PHP_URL_HOST), ['127.0.0.1', 'localhost'], true)) {
            $payload['notification_url'] = $base . route('billing.mercadopago.webhook', [], false);
        }

        $res = Http::withToken($this->token())->acceptJson()->timeout(20)
            ->post(self::API . '/checkout/preferences', $payload);
        $body = $res->json() ?? [];

        $test = str_starts_with($this->token(), 'TEST-');
        $url = $test
            ? ($body['sandbox_init_point'] ?? $body['init_point'] ?? null)
            : ($body['init_point'] ?? null);

        if (! $res->successful() || ! $url) {
            Log::error('[MP] preferencia rechazada', ['status' => $res->status(), 'body' => $body]);
            throw new RuntimeException('Preferencia rechazada por MercadoPago.');
        }

        return ['id' => $body['id'] ?? null, 'url' => $url];
    }

    public function fetchPayment($id): ?array
    {
        try {
            $res = Http::withToken($this->token())->acceptJson()->timeout(20)
                ->get(self::API . '/v1/payments/' . rawurlencode((string) $id));

            return $res->successful() ? $res->json() : null;
        } catch (\Throwable $e) {
            Log::error('[MP] error consultando pago ' . $id . ': ' . $e->getMessage());

            return null;
        }
    }

    /** Verifica x-signature si hay secreto configurado. */
    public function validSignature(Request $r): bool
    {
        $secret = (string) config('billing.mercadopago.webhook_secret');
        if ($secret === '') {
            Log::warning('[MP] MERCADO_PAGO_WEBHOOK_SECRET vacio: firma sin verificar (el pago igual se confirma contra la API).');

            return true;
        }

        $ts = $v1 = null;
        foreach (explode(',', (string) $r->header('x-signature')) as $part) {
            [$k, $v] = array_pad(explode('=', trim($part), 2), 2, null);
            if ($k === 'ts') {
                $ts = $v;
            }
            if ($k === 'v1') {
                $v1 = $v;
            }
        }
        if (! $ts || ! $v1) {
            return false;
        }

        $id = $r->query('data_id') ?? $r->query('data.id') ?? $r->input('data.id');
        $manifest = '';
        if ($id !== null && $id !== '') {
            $id = ctype_alnum((string) $id) ? strtolower((string) $id) : (string) $id;
            $manifest .= 'id:' . $id . ';';
        }
        if ($rid = $r->header('x-request-id')) {
            $manifest .= 'request-id:' . $rid . ';';
        }
        $manifest .= 'ts:' . $ts . ';';

        return hash_equals(hash_hmac('sha256', $manifest, $secret), $v1);
    }

    /**
     * Aplica el estado de un pago YA consultado a la API.
     * Devuelve el estado interno o null si no coincide con el Payment.
     */
    public function apply(Payment $payment, array $data): ?string
    {
        if (($data['external_reference'] ?? null) !== $payment->external_reference) {
            Log::warning('[MP] external_reference no coincide', ['payment' => $payment->id]);

            return null;
        }

        $status = match ($data['status'] ?? '') {
            'approved' => 'approved',
            'rejected', 'cancelled' => 'rejected',
            'refunded', 'charged_back' => 'refunded',
            default => 'pending',
        };

        if ($status === 'approved') {
            $cur = strtoupper((string) ($data['currency_id'] ?? ''));
            $amount = (float) ($data['transaction_amount'] ?? 0);
            if ($cur !== strtoupper((string) $payment->currency) || abs($amount - (float) $payment->amount) > 0.01) {
                Log::error('[MP] moneda o monto no coinciden', [
                    'payment' => $payment->id, 'got' => [$cur, $amount], 'expected' => [$payment->currency, $payment->amount],
                ]);

                return null;
            }
        }

        if ($payment->status === 'approved' && $status !== 'refunded') {
            SubscriptionActivator::fromPayment($payment);

            return 'approved';
        }

        $payment->update([
            'status' => $status,
            'provider_payment_id' => (string) ($data['id'] ?? ''),
            'provider_data' => json_encode([
                'status' => $data['status'] ?? null,
                'status_detail' => $data['status_detail'] ?? null,
            ]),
        ]);

        if ($status === 'approved') {
            SubscriptionActivator::fromPayment($payment);
        }

        return $status;
    }
}
