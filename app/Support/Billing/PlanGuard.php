<?php

namespace App\Support\Billing;

use App\Models\Plan;
use App\Models\Subscription;

class PlanGuard
{
    /** true si $plan es menor al plan vigente del usuario */
    public static function isDowngrade($user, Plan $plan): bool
    {
        $sub = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('current_period_end', '>', now())
            ->orderByDesc('current_period_end')
            ->first();

        if (!$sub) {
            return false;
        }

        $current = Plan::find($sub->plan_id);

        return $current && $plan->max_days < $current->max_days;
    }
}
