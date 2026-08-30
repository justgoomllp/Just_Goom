<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('documents') && ! Schema::hasColumn('documents', 'status')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->unsignedTinyInteger('status')->default(1)->after('file_type');
            });
        }

        if (Schema::hasTable('projects') && ! Schema::hasColumn('projects', 'status')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->unsignedTinyInteger('status')->default(1)->after('type');
            });
        }

        if (Schema::hasTable('videos') && ! Schema::hasColumn('videos', 'status')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->unsignedTinyInteger('status')->default(1)->after('link');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('documents') && Schema::hasColumn('documents', 'status')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        if (Schema::hasTable('projects') && Schema::hasColumn('projects', 'status')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        if (Schema::hasTable('videos') && Schema::hasColumn('videos', 'status')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
