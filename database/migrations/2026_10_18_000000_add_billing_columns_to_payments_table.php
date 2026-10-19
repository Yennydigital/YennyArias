<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount_usd', 10, 2)->nullable()->after('amount');
            $table->decimal('fx_rate', 12, 4)->nullable()->after('amount_usd');
            $table->text('provider_data')->nullable()->after('provider_payment_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['amount_usd', 'fx_rate', 'provider_data']);
        });
    }
};
