<?php

namespace App\Console\Commands;

use App\Models\Sticker;
use App\Models\StickerPack;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportStickers extends Command
{
    protected $signature = 'stickers:import {name} {dir} {--plan=free}';

    protected $description = 'Crea un pack de stickers con las imagenes (png, webp, gif) de una carpeta';

    public function handle(): int
    {
        $dir = rtrim((string) $this->argument('dir'), '/');
        $plan = (string) $this->option('plan');
        if (! in_array($plan, ['free', 'plus', 'premium'], true)) {
            $this->error('--plan debe ser free, plus o premium');

            return self::FAILURE;
        }
        $files = is_dir($dir) ? glob($dir . '/*.{png,webp,gif,PNG,WEBP,GIF}', GLOB_BRACE) : [];
        sort($files);
        if (count($files) === 0) {
            $this->error("No hay imagenes png, webp o gif en $dir");

            return self::FAILURE;
        }

        $pack = StickerPack::create([
            'name' => (string) $this->argument('name'),
            'min_plan' => $plan,
            'sort' => (int) StickerPack::max('sort') + 1,
        ]);
        $slug = Str::slug($pack->name) . '-' . $pack->id;

        $n = 0;
        foreach ($files as $f) {
            if (filesize($f) > 1024 * 1024 || @getimagesize($f) === false) {
                $this->warn('Se omite (pesa mas de 1 MB o no es imagen): ' . basename($f));

                continue;
            }
            $dest = "stickers/$slug/" . Str::random(12) . '.' . strtolower(pathinfo($f, PATHINFO_EXTENSION));
            Storage::disk('public')->put($dest, file_get_contents($f));
            Sticker::create(['sticker_pack_id' => $pack->id, 'path' => $dest, 'sort' => $n]);
            if ($n === 0) {
                $pack->update(['cover_path' => $dest]);
            }
            $n++;
        }
        $this->info("Pack '{$pack->name}' (plan $plan) con $n stickers. id=" . $pack->id);

        return self::SUCCESS;
    }
}
