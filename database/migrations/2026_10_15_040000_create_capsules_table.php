<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('capsules', function (Blueprint $table) {
            $table->id();
            $table->string('code_hash', 64)->unique();
            $table->string('salt', 32);
            $table->longText('ciphertext');
            $table->dateTime('opens_at')->nullable();
            $table->dateTime('expires_at')->index();
            $table->dateTime('opened_at')->nullable();
            $table->boolean('burn_after_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('capsules');
    }
};
