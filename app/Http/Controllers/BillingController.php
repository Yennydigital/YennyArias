<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Plan;
use App\Support\Billing\Checkout;
use App\Support\Billing\MercadoPagoGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BillingController extends Controller
{
    public function start(Request $request, MercadoPagoGateway $mp)
    {
        $data = $request->validate([
            'plan_id' => 'required|integer|exists:plans,id',
            'cycle' => 'required|in:monthly',
            'provider' => 'required|in:mercadopago,stripe',
        ]);

        if ($data['provider'] !== 'mercadopago') {
            return back()->with('billing_err', 'Ese metodo de pago todavia no esta disponible.');
        }

        $plan = Plan::findOrFail($data['plan_id']);
        if (\App\Support\Billing\PlanGuard::isDowngrade($request->user(), $plan)) {
            return redirect()->route('plans.index')->withErrors(['plan' => 'Ese plan es menor al tuyo actual. Podrás contratarlo cuando venza.']);
        }

        try {
            $payment = Checkout::start($request->user(), $plan, $data['cycle'], 'mercadopago');
        } catch (\InvalidArgumentException $e) {
            return back()->with('billing_err', $e->getMessage());
        }

        try {
            $pref = $mp->createPreference($payment, $request->user(), $plan);
        } catch (\Throwable $e) {
            Log::error('[Billing] no se pudo crear la preferencia: ' . $e->getMessage());
            $payment->update(['status' => 'rejected']);

            return back()->with('billing_err', 'No pudimos iniciar el pago. Intenta de nuevo.');
        }

        $payment->update(['provider_data' => json_encode(['preference_id' => $pref['id']])]);

        return redirect()->away($pref['url']);
    }

    // Regreso del navegador: se confirma contra la API, nunca con la URL.
    public function back(Request $request, MercadoPagoGateway $mp)
    {
        $payment = Payment::where('external_reference', (string) $request->query('external_reference'))
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $payment) {
            return redirect()->route('plans.index');
        }

        $pid = $request->query('payment_id') ?: $request->query('collection_id');
        if ($pid && $pid !== 'null' && ($data = $mp->fetchPayment($pid))) {
            $mp->apply($payment, $data);
        }

        $payment->refresh();

        return match ($payment->status) {
            'approved' => redirect()->route('plans.index')->with('billing_ok', 'Pago aprobado: tu plan ya esta activo.'),
            'pending' => redirect()->route('plans.index')->with('billing_ok', 'Estamos esperando la confirmacion del pago. Se activara solo.'),
            default => redirect()->route('plans.index')->with('billing_err', 'El pago no se completo.'),
        };
    }

    // Webhook publico, sin sesion y excluido de CSRF.
    public function webhook(Request $request, MercadoPagoGateway $mp)
    {
        if (! $mp->validSignature($request)) {
            return response('', 401);
        }

        if ($request->input('type')) {
            $topic = $request->input('type');
            $id = $request->input('data.id') ?? $request->query('data_id') ?? $request->query('data.id');
        } else {
            $topic = $request->input('topic');
            $id = $request->query('id') ?? $request->input('id');
        }
        if (in_array($topic, ['order', 'orders'], true) && $id) {
            $order = $mp->fetchOrder($id);
            $found = ! empty($order['external_reference']) ? $mp->findPaymentByReference($order['external_reference']) : null;
            if (! $found || empty($found['id'])) {
                return response('', 200);
            }
            $topic = 'payment';
            $id = $found['id'];
        }
        if ($topic !== 'payment' || ! $id) {
            return response('', 200);
        }

        $data = $mp->fetchPayment($id);
        if (! $data) {
            return response('', 422); // MP reintenta
        }

        $payment = Payment::where('external_reference', (string) ($data['external_reference'] ?? ''))->first();
        if (! $payment) {
            Log::warning('[MP] webhook sin Payment', ['payment_id' => $id]);

            return response('', 200);
        }

        $mp->apply($payment, $data);

        return response('ok', 200);
    }

    public function qr(Request $request, MercadoPagoGateway $mp)
    {
        $plan = \App\Models\Plan::findOrFail((int) $request->input('plan_id'));
        if (\App\Support\Billing\PlanGuard::isDowngrade($request->user(), $plan)) {
            return redirect()->route('plans.index')->withErrors(['plan' => 'Ese plan es menor al tuyo actual. Podrás contratarlo cuando venza.']);
        }
        $cycle = 'monthly';

        try {
            $payment = \App\Support\Billing\Checkout::start($request->user(), $plan, $cycle, 'mercadopago');
            $qr = $mp->createQrOrder($payment, $plan);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('[MP] QR: ' . $e->getMessage());
            return redirect()->route('plans.index')->with('billing_err', 'No pudimos generar el QR. Probá de nuevo.');
        }

        $payment->update(['provider_data' => json_encode(['qr_order_id' => $qr['id']])]);

        return view('billing.qr', ['payment' => $payment, 'plan' => $plan, 'qrData' => $qr['qr_data']]);
    }

    public function qrStatus(Request $request, MercadoPagoGateway $mp, string $ref)
    {
        $payment = Payment::where('external_reference', $ref)->where('user_id', $request->user()->id)->firstOrFail();

        if ($payment->status === 'pending') {
            if ($data = $mp->findPaymentByReference($payment->external_reference)) {
                $mp->apply($payment, $data);
                $payment->refresh();
            }
        }

        return response()->json(['status' => $payment->status]);
    }
}
