<?php

namespace App\Support\Billing;

use App\Models\Payment;
use App\Models\Plan;
use Illuminate\Support\Str;
use InvalidArgumentException;

class Checkout
{
    /** Crea un Payment pendiente. El monto sale SIEMPRE del plan en la base. */
    public static function start($user, Plan $plan, string $cycle, string $provider): Payment
    {
        if (! in_array($provider, config('billing.providers'), true)) {
            throw new InvalidArgumentException('Proveedor no valido.');
        }
        if (! in_array($cycle, ['monthly', 'yearly'], true)) {
            throw new InvalidArgumentException('Ciclo no valido.');
        }
        if (! $plan->is_active || (float) $plan->price_monthly <= 0) {
            throw new InvalidArgumentException('Este plan no se puede contratar.');
        }
        if ($cycle === 'yearly' && $plan->price_yearly === null) {
            throw new InvalidArgumentException('Este plan no tiene precio anual.');
        }

        $usd = round($plan->priceFor($cycle), 2);
        $amount = $usd;
        $currency = 'USD';
        $rate = null;

        if ($provider === 'mercadopago') {
            $rate = (float) config('billing.fx_rate');
            if ($rate <= 0) {
                throw new InvalidArgumentException('Falta configurar BILLING_FX_RATE.');
            }
            $amount = round($usd * $rate, 2);
            $currency = config('billing.mp_currency');
        }

        return Payment::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'billing_cycle' => $cycle,
            'amount' => $amount,
            'amount_usd' => $usd,
            'fx_rate' => $rate,
            'currency' => $currency,
            'method' => $provider,
            'status' => 'pending',
            'external_reference' => 'umb_' . Str::uuid(),
        ]);
    }
}
