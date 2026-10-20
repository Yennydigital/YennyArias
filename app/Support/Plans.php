<?php

namespace App\Support;

use App\Models\Plan;
use App\Models\Subscription;

class Plans
{
    public static function free(): Plan
    {
        // Si la tabla aun no tiene datos, el sitio sigue funcionando con estos valores.
        return Plan::where('code', 'free')->first() ?? new Plan([
            'code' => 'free', 'name' => 'Free', 'max_photos' => 3, 'max_days' => 30,
            'allow_music' => false, 'premium_templates' => false,
        ]);
    }

    public static function for($user): Plan
    {
        if (! $user) {
            return self::free();
        }

        $sub = Subscription::with('plan')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->where('current_period_end', '>', now())
            ->orderByDesc('current_period_end')
            ->first();

        return $sub && $sub->plan ? $sub->plan : self::free();
    }
}
