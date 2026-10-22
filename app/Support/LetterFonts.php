<?php

namespace App\Support;

class LetterFonts
{
    public static function all(): array
    {
        return [
            'default'    => ['label' => 'Del tema', 'stack' => ''],
            'clasica'    => ['label' => 'Clásica',  'stack' => "Georgia,'Times New Roman',serif"],
            'elegante'   => ['label' => 'Elegante', 'stack' => "'Palatino Linotype',Palatino,'Book Antiqua',serif"],
            'moderna'    => ['label' => 'Moderna',  'stack' => "system-ui,-apple-system,'Segoe UI',Roboto,Arial,sans-serif"],
            'redondeada' => ['label' => 'Redondeada', 'stack' => "'Trebuchet MS',Verdana,sans-serif"],
            'maquina'    => ['label' => 'Máquina de escribir', 'stack' => "'Courier New',Courier,monospace"],
            'manuscrita' => ['label' => 'Manuscrita', 'stack' => "'Segoe Script','Bradley Hand','Brush Script MT','Comic Sans MS',cursive"],
            'playfair' => ['label' => 'Playfair Display', 'stack' => "'Playfair Display',serif"],
            'lora' => ['label' => 'Lora', 'stack' => "'Lora',serif"],
            'montserrat' => ['label' => 'Montserrat', 'stack' => "'Montserrat',sans-serif"],
            'dancing' => ['label' => 'Dancing Script', 'stack' => "'Dancing Script',cursive"],
            'pacifico' => ['label' => 'Pacifico', 'stack' => "'Pacifico',cursive"],
            'caveat' => ['label' => 'Caveat', 'stack' => "'Caveat',cursive"],
            'greatvibes' => ['label' => 'Great Vibes', 'stack' => "'Great Vibes',cursive"],
            'specialelite' => ['label' => 'Special Elite', 'stack' => "'Special Elite',monospace"],
        ];
    }

    public static function clean($key): string
    {
        $key = is_string($key) ? $key : '';

        return array_key_exists($key, static::all()) ? $key : 'default';
    }
}
