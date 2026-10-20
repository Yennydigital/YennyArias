<?php

namespace App\Support;

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

        if (! $user) {
            return false;
        }

        if (Plans::for($user)->premium_templates) {
            return true;
        }

        // Las plantillas premium se desbloquean solo por plan, igual para todos.
        return false;
    }
}
