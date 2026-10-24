<?php

namespace App\Support\Billing;

use App\Models\Payment;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PayPalGateway
{
    private function base(): string
    {
        return config('billing.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com'\;
    }

    private function token(): string
    {
        $id = (string) config('billing.paypal.client_id');
        $secret = (string) config('billing.paypal.secret');
        if ($id === '' || $secret === '') {
            throw new RuntimeException('Faltan PAYPAL_CLIENT_ID o PAYPAL_SECRET.');
        }

        $key = 'paypal_token_' . config('billing.paypal.mode') . '_' . substr(sha1($id), 0, 8);

        return Cache::remember($key, 60 * 25, function () use ($id, $secret) {
            $res = Http::withBasicAuth($id, $secret)->asForm()->acceptJson()->timeout(20)
                ->post($this->base() . '/v1/oauth2/token', ['grant_type' => 'client_credentials']);
            if (! $res->successful() || ! $res->json('access_token')) {
                Log::error('[PayPal] no se pudo obtener token', ['status' => $res->status()]);
                throw new RuntimeException('PayPal rechazo las credenciales.');
            }

            return $res->json('access_token');
        });
    }

    private function http()
    {
        return Http::withToken($this->token())->acceptJson()->timeout(25);
    }

    /** Crea la orden. Devuelve ['id' => ..., 'url' => ...]. */
    public function createOrder(Payment $payment, Plan $plan): array
    {
        $cycle = $payment->billing_cycle === 'yearly' ? 'anual' : 'mensual';
        $title = 'Umbra ' . $plan->name . ' (' . $cycle . ')';
        $return = route('billing.paypal.return');
        $cancel = route('billing.paypal.cancel');

        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => $payment->external_reference,
                'custom_id' => $payment->external_reference,
                'description' => $title,
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => number_format((float) $payment->amount, 2, '.', ''),
                ],
            ]],
            'payment_source' => ['paypal' => ['experience_context' => [
                'brand_name' => 'Umbra',
                'user_action' => 'PAY_NOW',
                'shipping_preference' => 'NO_SHIPPING',
                'return_url' => $return,
                'cancel_url' => $cancel,
            ]]],
        ];

        $res = $this->http()
            ->withHeaders(['PayPal-Request-Id' => $payment->external_reference])
            ->post($this->base() . '/v2/checkout/orders', $payload);
        $body = $res->json() ?? [];

        $url = null;
        foreach (($body['links'] ?? []) as $l) {
            if (in_array($l['rel'] ?? '', ['payer-action', 'approve'], true)) {
                $url = $l['href'];
                break;
            }
        }

        if (! $res->successful() || empty($body['id']) || ! $url) {
            Log::error('[PayPal] orden rechazada', ['status' => $res->status(), 'body' => $body]);
            throw new RuntimeException('Orden rechazada por PayPal.');
        }

        return ['id' => $body['id'], 'url' => $url];
    }

    public function fetchOrder(string $id): ?array
    {
        try {
            $res = $this->http()->get($this->base() . '/v2/checkout/orders/' . rawurlencode($id));

            return $res->successful() ? $res->json() : null;
        } catch (\Throwable $e) {
            Log::error('[PayPal] error consultando orden ' . $id . ': ' . $e->getMessage());

            return null;
        }
    }

    /** Captura una orden aprobada por el comprador. Si ya estaba capturada, devuelve la orden. */
    public function captureOrder(string $id, string $requestId): ?array
    {
        try {
            $res = $this->http()
                ->withHeaders(['PayPal-Request-Id' => 'cap_' . $requestId])
                ->withBody('{}', 'application/json')
                ->post($this->base() . '/v2/checkout/orders/' . rawurlencode($id) . '/capture');

            if ($res->successful()) {
                return $res->json();
            }

            $issue = $res->json('details.0.issue');
            if ($issue === 'ORDER_ALREADY_CAPTURED') {
                return $this->fetchOrder($id);
            }

            Log::error('[PayPal] captura rechazada', ['status' => $res->status(), 'body' => $res->json()]);

            return $this->fetchOrder($id);
        } catch (\Throwable $e) {
            Log::error('[PayPal] error capturando ' . $id . ': ' . $e->getMessage());

            return null;
        }
    }

    /** Verifica la firma del webhook contra PayPal. Sin PAYPAL_WEBHOOK_ID se rechaza. */
    public function validSignature(Request $r): bool
    {
        $webhookId = (string) config('billing.paypal.webhook_id');
        if ($webhookId === '') {
            Log::warning('[PayPal] PAYPAL_WEBHOOK_ID vacio: webhook rechazado.');

            return false;
        }

        $event = json_decode($r->getContent(), true);
        if (! is_array($event)) {
            return false;
        }

        try {
            $res = $this->http()->post($this->base() . '/v1/notifications/verify-webhook-signature', [
                'auth_algo' => $r->header('paypal-auth-algo'),
                'cert_url' => $r->header('paypal-cert-url'),
                'transmission_id' => $r->header('paypal-transmission-id'),
                'transmission_sig' => $r->header('paypal-transmission-sig'),
                'transmission_time' => $r->header('paypal-transmission-time'),
                'webhook_id' => $webhookId,
                'webhook_event' => $event,
            ]);

            return $res->successful() && $res->json('verification_status') === 'SUCCESS';
        } catch (\Throwable $e) {
            Log::error('[PayPal] error verificando firma: ' . $e->getMessage());

            return false;
        }
    }

    /** Estado interno a partir de una orden ya consultada a la API. */
    public static function mapStatus(array $order): string
    {
        $capture = $order['purchase_units'][0]['payments']['captures'][0] ?? null;
        if ($capture) {
            return match ($capture['status'] ?? '') {
                'COMPLETED' => 'approved',
                'REFUNDED', 'PARTIALLY_REFUNDED' => 'refunded',
                'DECLINED', 'FAILED' => 'rejected',
                default => 'pending',
            };
        }

        return ($order['status'] ?? '') === 'VOIDED' ? 'rejected' : 'pending';
    }

    /**
     * Aplica el estado de una orden YA consultada a la API.
     * Devuelve el estado interno o null si no coincide con el Payment.
     */
    public function apply(Payment $payment, array $order): ?string
    {
        $unit = $order['purchase_units'][0] ?? [];
        $ref = $unit['custom_id'] ?? ($unit['reference_id'] ?? null);
        if ($ref !== $payment->external_reference) {
            Log::warning('[PayPal] external_reference no coincide', ['payment' => $payment->id]);

            return null;
        }

        $status = self::mapStatus($order);
        $capture = $unit['payments']['captures'][0] ?? null;

        if ($status === 'approved') {
            $cur = strtoupper((string) ($capture['amount']['currency_code'] ?? ''));
            $amount = (float) ($capture['amount']['value'] ?? 0);
            if ($cur !== strtoupper((string) $payment->currency) || abs($amount - (float) $payment->amount) > 0.01) {
                Log::error('[PayPal] moneda o monto no coinciden', [
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
            'provider_data' => json_encode([
                'paypal_order_id' => $order['id'] ?? null,
                'paypal_capture_id' => $capture['id'] ?? null,
                'status' => $order['status'] ?? null,
                'capture_status' => $capture['status'] ?? null,
            ]),
        ]);

        if ($status === 'approved') {
            SubscriptionActivator::fromPayment($payment);
        }

        return $status;
    }
}
