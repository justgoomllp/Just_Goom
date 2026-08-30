<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('videos') && ! Schema::hasColumn('videos', 'thumbnail')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->string('thumbnail')->nullable()->after('link');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('videos') && Schema::hasColumn('videos', 'thumbnail')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->dropColumn('thumbnail');
            });
        }
    }
};
