<?php

use App\Models\Plan;
use App\Support\PricingCatalog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (! Schema::hasColumn('plans', 'max_offer_count')) {
                $table->unsignedInteger('max_offer_count')->default(0)->after('max_document_size_mb');
            }
        });

        foreach (PricingCatalog::databaseRecords() as $plan) {
            Plan::withTrashed()->updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (Schema::hasColumn('plans', 'max_offer_count')) {
                $table->dropColumn('max_offer_count');
            }
        });
    }
};
