<?php

namespace App\Support\Billing;

use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class SubscriptionActivator
{
    /** Idempotente: un pago aprobado activa o extiende UNA sola vez. */
    public static function fromPayment(Payment $payment): ?Subscription
    {
        return DB::transaction(function () use ($payment) {
            $p = Payment::whereKey($payment->id)->lockForUpdate()->first();

            if ($p->subscription_id) {
                return Subscription::find($p->subscription_id);
            }
            if ($p->status !== 'approved') {
                return null;
            }

            $current = Subscription::where('user_id', $p->user_id)
                ->where('plan_id', $p->plan_id)
                ->where('status', 'active')
                ->where('current_period_end', '>', now())
                ->orderByDesc('current_period_end')
                ->first();

            $start = $current ? $current->current_period_end : now();
            $end = $p->billing_cycle === 'yearly' ? $start->copy()->addYear() : $start->copy()->addMonth();

            if ($current) {
                $current->update(['current_period_end' => $end]);
                $sub = $current;
            } else {
                $sub = Subscription::create([
                    'user_id' => $p->user_id,
                    'plan_id' => $p->plan_id,
                    'status' => 'active',
                    'billing_cycle' => $p->billing_cycle,
                    'current_period_start' => now(),
                    'current_period_end' => $end,
                ]);
            }

            $p->update(['subscription_id' => $sub->id, 'paid_at' => $p->paid_at ?? now()]);

            return $sub;
        });
    }
}
