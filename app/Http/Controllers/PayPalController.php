<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Plan;
use App\Support\Billing\Checkout;
use App\Support\Billing\PayPalGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayPalController extends Controller
{
    public function start(Request $request, PayPalGateway $pp)
    {
        $data = $request->validate([
            'plan_id' => 'required|integer|exists:plans,id',
            'cycle' => 'required|in:monthly,yearly',
        ]);

        $plan = Plan::findOrFail($data['plan_id']);

        try {
            $payment = Checkout::start($request->user(), $plan, $data['cycle'], 'paypal');
        } catch (\InvalidArgumentException $e) {
            return back()->with('billing_err', $e->getMessage());
        }

        try {
            $order = $pp->createOrder($payment, $plan);
        } catch (\Throwable $e) {
            Log::error('[PayPal] no se pudo crear la orden: ' . $e->getMessage());
            $payment->update(['status' => 'rejected']);

            return back()->with('billing_err', 'No pudimos iniciar el pago con PayPal. Intenta de nuevo.');
        }

        // provider_payment_id guarda el id de la orden de PayPal (sirve para ubicar el pago al volver).
        $payment->update([
            'provider_payment_id' => $order['id'],
            'provider_data' => json_encode(['paypal_order_id' => $order['id']]),
        ]);

        return redirect()->away($order['url']);
    }

    // Regreso del navegador: se captura y se confirma contra la API, nunca con la URL.
    public function back(Request $request, PayPalGateway $pp)
    {
        $token = (string) $request->query('token');
        if ($token === '' || ! ctype_alnum($token)) {
            return redirect()->route('plans.index');
        }

        $payment = Payment::where('method', 'paypal')
            ->where('provider_payment_id', $token)
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $payment) {
            return redirect()->route('plans.index');
        }

        if ($payment->status === 'pending') {
            $order = $pp->fetchOrder($token);
            if ($order && ($order['status'] ?? '') === 'APPROVED') {
                $order = $pp->captureOrder($token, $payment->external_reference);
            }
            if ($order) {
                $pp->apply($payment, $order);
            }
            $payment->refresh();
        }

        return match ($payment->status) {
            'approved' => redirect()->route('plans.index')->with('billing_ok', 'Pago aprobado: tu plan ya esta activo.'),
            'pending' => redirect()->route('plans.index')->with('billing_ok', 'Estamos esperando la confirmacion del pago. Se activara solo.'),
            default => redirect()->route('plans.index')->with('billing_err', 'El pago no se completo.'),
        };
    }

    public function cancel(Request $request)
    {
        $token = (string) $request->query('token');
        if ($token !== '' && ctype_alnum($token)) {
            Payment::where('method', 'paypal')
                ->where('provider_payment_id', $token)
                ->where('user_id', $request->user()->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);
        }

        return redirect()->route('plans.index')->with('billing_err', 'Cancelaste el pago con PayPal.');
    }

    // Webhook publico, sin sesion y excluido de CSRF. La firma se verifica contra PayPal.
    public function webhook(Request $request, PayPalGateway $pp)
    {
        if (! $pp->validSignature($request)) {
            return response('', 401);
        }

        $event = (string) $request->input('event_type');
        $resource = (array) $request->input('resource', []);

        $orderId = match (true) {
            $event === 'CHECKOUT.ORDER.APPROVED' => $resource['id'] ?? null,
            str_starts_with($event, 'PAYMENT.CAPTURE.') => $resource['supplementary_data']['related_ids']['order_id'] ?? null,
            default => null,
        };

        if (! $orderId || ! ctype_alnum((string) $orderId)) {
            return response('', 200);
        }

        $order = $pp->fetchOrder((string) $orderId);
        if (! $order) {
            return response('', 422); // PayPal reintenta
        }

        $ref = $order['purchase_units'][0]['custom_id'] ?? ($order['purchase_units'][0]['reference_id'] ?? '');
        $payment = Payment::where('external_reference', (string) $ref)->where('method', 'paypal')->first();
        if (! $payment) {
            Log::warning('[PayPal] webhook sin Payment', ['order' => $orderId]);

            return response('', 200);
        }

        // El comprador aprobo pero no volvio al sitio: se captura desde aca.
        if (($order['status'] ?? '') === 'APPROVED' && $payment->status === 'pending') {
            $order = $pp->captureOrder((string) $orderId, $payment->external_reference) ?? $order;
        }

        $pp->apply($payment, $order);

        return response('ok', 200);
    }
}
