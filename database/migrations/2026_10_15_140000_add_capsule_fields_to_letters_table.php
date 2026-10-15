<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('letters', function (Blueprint $table) {
            if (! Schema::hasColumn('letters', 'capsule_id')) {
                $table->foreignId('capsule_id')->nullable()->constrained('capsules')->nullOnDelete();
            }
            if (! Schema::hasColumn('letters', 'sealed_at')) {
                $table->dateTime('sealed_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('letters', function (Blueprint $table) {
            if (Schema::hasColumn('letters', 'capsule_id')) {
                $table->dropConstrainedForeignId('capsule_id');
            }
            if (Schema::hasColumn('letters', 'sealed_at')) {
                $table->dropColumn('sealed_at');
            }
        });
    }
};
