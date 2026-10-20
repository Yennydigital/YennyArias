<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stickers', function (Blueprint $t) {
            $t->id();
            $t->foreignId('sticker_pack_id')->constrained('sticker_packs')->cascadeOnDelete();
            $t->string('path', 500);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stickers');
    }
};
