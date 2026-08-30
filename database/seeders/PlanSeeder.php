<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Support\PricingCatalog;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PricingCatalog::databaseRecords() as $plan) {
            Plan::updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }

        Plan::query()
            ->whereNotIn('name', PricingCatalog::purchasableNames())
            ->delete();
    }
}
