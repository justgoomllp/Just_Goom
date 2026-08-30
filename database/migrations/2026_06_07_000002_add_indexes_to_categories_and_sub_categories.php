<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $categoryIndexes = collect(DB::select('SHOW INDEX FROM categories'))->pluck('Key_name')->map(fn ($n) => strtolower($n));

        if (! $categoryIndexes->contains('categories_status_deleted_at_index')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->index(['status', 'deleted_at']);
            });
        }

        $subCategoryIndexes = collect(DB::select('SHOW INDEX FROM sub_categories'))->pluck('Key_name')->map(fn ($n) => strtolower($n));

        if (! $subCategoryIndexes->contains('sub_categories_category_id_status_deleted_at_index')) {
            Schema::table('sub_categories', function (Blueprint $table) {
                $table->index(['category_id', 'status', 'deleted_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['status', 'deleted_at']);
        });

        Schema::table('sub_categories', function (Blueprint $table) {
            $table->dropIndex(['category_id', 'status', 'deleted_at']);
        });
    }
};
