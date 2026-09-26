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
            if (! Schema::hasColumn('plans', 'max_team_count')) {
                $table->unsignedInteger('max_team_count')->default(0)->after('max_article_count');
            }
            if (! Schema::hasColumn('plans', 'max_service_count')) {
                $table->unsignedInteger('max_service_count')->default(0)->after('max_team_count');
            }
            if (! Schema::hasColumn('plans', 'max_document_count')) {
                $table->unsignedInteger('max_document_count')->default(0)->after('max_service_count');
            }
            if (! Schema::hasColumn('plans', 'max_document_size_mb')) {
                $table->unsignedInteger('max_document_size_mb')->default(5)->after('max_document_count');
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
            $columns = array_filter([
                Schema::hasColumn('plans', 'max_team_count') ? 'max_team_count' : null,
                Schema::hasColumn('plans', 'max_service_count') ? 'max_service_count' : null,
                Schema::hasColumn('plans', 'max_document_count') ? 'max_document_count' : null,
                Schema::hasColumn('plans', 'max_document_size_mb') ? 'max_document_size_mb' : null,
            ]);

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
