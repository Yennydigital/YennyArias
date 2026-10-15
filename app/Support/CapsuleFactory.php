<?php

namespace App\Support;

use App\Models\Capsule;
use Carbon\Carbon;

/**
 * Misma lógica de sellado que CapsuleController::store, reutilizable
 * desde el wizard de cartas. CapsuleController no se toca.
 */
class CapsuleFactory
{
    public static function make(array $payload, ?string $opensRaw, ?string $tz, $keep, bool $burn): array
    {
        $tz = in_array($tz ?? '', timezone_identifiers_list(), true)
            ? $tz
            : 'America/Argentina/Buenos_Aires';

        $opensAt = null;
        if (! empty($opensRaw)) {
            $parsed = Carbon::parse($opensRaw, $tz)->setTimezone(config('app.timezone'));
            if ($parsed->gt(now()->addMinutes(2))) {
                $opensAt = $parsed;
            }
        }

        $keep = in_array((string) $keep, ['7', '30', '90', '365'], true) ? (int) $keep : 30;
        $expiresAt = ($opensAt ?? now())->copy()->addDays($keep);

        $code = CapsuleVault::newCode();
        [$salt, $blob] = CapsuleVault::seal($payload, $code);

        $capsule = Capsule::create([
            'code_hash' => CapsuleVault::lookup($code),
            'salt' => $salt,
            'ciphertext' => $blob,
            'opens_at' => $opensAt,
            'expires_at' => $expiresAt,
            'burn_after_read' => $burn,
        ]);

        return [
            'capsule' => $capsule,
            'code' => $code,
            'opens_at' => $opensAt,
            'expires_at' => $expiresAt,
        ];
    }
}
