<?php

namespace App\Support;

use App\Models\Sticker;
use Illuminate\Support\HtmlString;

class Stickers
{
    public const TIERS = ['free' => 0, 'plus' => 1, 'premium' => 2];

    /** Nivel del plan vigente del usuario: 0 free, 1 plus, 2 premium. */
    public static function tierOf($user): int
    {
        return self::TIERS[Plans::for($user)->code] ?? 0;
    }

    /** Escapa TODO el texto y recien despues reemplaza las marcas [[s:ID]] por imagenes. */
    public static function render(?string $text, int $max = 30): HtmlString
    {
        $safe = e((string) $text);
        preg_match_all('/\[\[s:(\d+)\]\]/', $safe, $m);
        $ids = array_slice(array_unique($m[1]), 0, $max);
        if (count($ids) === 0) {
            return new HtmlString($safe);
        }

        $map = Sticker::whereIn('id', $ids)->get()->keyBy('id');
        $n = 0;
        $out = preg_replace_callback('/\[\[s:(\d+)\]\]/', function ($mm) use ($map, &$n, $max) {
            $st = $map[(int) $mm[1]] ?? null;
            if ($st === null || $n >= $max) {
                return '';
            }
            $n++;

            return '<img src="' . e($st->url) . '" alt="" class="stk" loading="lazy" style="height:96px;width:auto;vertical-align:middle">';
        }, $safe);

        return new HtmlString($out);
    }

    /** Quita las marcas de stickers que no existen, estan inactivos o son de un pack que el plan no incluye. */
    public static function sanitize(?string $text, int $tier): string
    {
        return preg_replace_callback('/\[\[s:(\d+)\]\]/', function ($m) use ($tier) {
            $st = Sticker::with('pack')->find((int) $m[1]);
            if (! $st || ! $st->pack || ! $st->pack->is_active) {
                return '';
            }

            return (self::TIERS[$st->pack->min_plan] ?? 0) <= $tier ? $m[0] : '';
        }, (string) $text);
    }
}
