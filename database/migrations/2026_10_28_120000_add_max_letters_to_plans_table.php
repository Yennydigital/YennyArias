<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $t) {
            $t->unsignedInteger('max_letters')->nullable()->after('max_days'); // null = sin tope
        });
        DB::table('plans')->where('code', 'free')->update(['max_letters' => 3]);
    }

    public function down(): void
    {
        Schema::table('plans', fn (Blueprint $t) => $t->dropColumn('max_letters'));
    }
};
