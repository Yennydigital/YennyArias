<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('slug', 32)->unique();
            $table->string('template')->default('love-letter');
            $table->string('title')->nullable();
            $table->json('sections')->nullable();
            $table->json('photos')->nullable();
            $table->string('music_url', 500)->nullable();
            $table->string('status')->default('draft'); // draft | pending_payment | paid
            $table->string('payment_ref')->nullable()->unique();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
