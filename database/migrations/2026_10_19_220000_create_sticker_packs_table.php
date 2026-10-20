<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sticker_packs', function (Blueprint $t) {
            $t->id();
            $t->string('name', 80);
            $t->string('cover_path', 500)->nullable();
            $t->string('min_plan', 20)->default('free');
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sticker_packs');
    }
};
