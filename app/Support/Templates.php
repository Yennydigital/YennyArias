<?php

namespace App\Support;

use App\Models\TemplatePurchase;

class Templates
{
    public const PREMIUM = ['starry-night', 'pixel-city'];

    public static function isPremium(string $key): bool
    {
        return in_array($key, self::PREMIUM, true);
    }

    public static function canUse(string $key, $user): bool
    {
        if (! self::isPremium($key)) {
            return true;
        }

        return $user && TemplatePurchase::where('user_id', $user->id)
            ->where('template', $key)
            ->whereNotNull('paid_at')
            ->exists();
    }
}
