<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $needsBackfill = ! Schema::hasColumn('agent_commission_rates', 'india_profile_percent');

        Schema::table('agent_commission_rates', function (Blueprint $table) {
            if (! Schema::hasColumn('agent_commission_rates', 'india_profile_percent')) {
                $table->decimal('india_profile_percent', 5, 2)->default(0)->after('india_percent');
            }
            if (! Schema::hasColumn('agent_commission_rates', 'global_profile_percent')) {
                $table->decimal('global_profile_percent', 5, 2)->default(0)->after('global_percent');
            }
        });

        if (! $needsBackfill) {
            return;
        }

        // Old india/global percent was a combined rate (50% payment + 50% profile).
        DB::update(
            'UPDATE agent_commission_rates SET
                india_profile_percent = ROUND(india_percent / 2, 2),
                global_profile_percent = ROUND(global_percent / 2, 2),
                india_percent = ROUND(india_percent / 2, 2),
                global_percent = ROUND(global_percent / 2, 2)
             WHERE india_profile_percent = 0
               AND global_profile_percent = 0
               AND (india_percent > 0 OR global_percent > 0)'
        );
    }

    public function down(): void
    {
        if (Schema::hasColumn('agent_commission_rates', 'india_profile_percent')) {
            DB::update(
                'UPDATE agent_commission_rates SET
                    india_percent = ROUND(india_percent + india_profile_percent, 2),
                    global_percent = ROUND(global_percent + global_profile_percent, 2)'
            );
        }

        Schema::table('agent_commission_rates', function (Blueprint $table) {
            if (Schema::hasColumn('agent_commission_rates', 'india_profile_percent')) {
                $table->dropColumn('india_profile_percent');
            }
            if (Schema::hasColumn('agent_commission_rates', 'global_profile_percent')) {
                $table->dropColumn('global_profile_percent');
            }
        });
    }
};
