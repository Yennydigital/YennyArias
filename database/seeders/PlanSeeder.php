<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::updateOrCreate(['code' => 'free'], [
            'name' => 'Free', 'currency' => 'USD', 'price_monthly' => 0, 'price_yearly' => null,
            'max_photos' => 3, 'max_days' => 30, 'allow_music' => false,
            'premium_templates' => false, 'is_active' => true, 'position' => 0,
        ]);

        Plan::updateOrCreate(['code' => 'plus'], [
            'name' => 'Plus', 'currency' => 'USD', 'price_monthly' => 5, 'price_yearly' => 50,
            'max_photos' => 5, 'max_days' => 365, 'allow_music' => true,
            'premium_templates' => true, 'is_active' => true, 'position' => 1,
        ]);
    }
}
