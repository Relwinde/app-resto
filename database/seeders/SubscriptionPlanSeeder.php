<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'price' => 0,
                'features' => json_encode([
                    'max_caisses' => 1,
                    'max_users' => 2,
                    'max_products' => 50,
                    'analytics' => false,
                    'api_access' => false,
                ]),
            ],
            [
                'name' => 'Starter',
                'price' => 29.99,
                'features' => json_encode([
                    'max_caisses' => 3,
                    'max_users' => 5,
                    'max_products' => 500,
                    'analytics' => true,
                    'api_access' => false,
                ]),
            ],
            [
                'name' => 'Pro',
                'price' => 79.99,
                'features' => json_encode([
                    'max_caisses' => 10,
                    'max_users' => 20,
                    'max_products' => 5000,
                    'analytics' => true,
                    'api_access' => true,
                ]),
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
