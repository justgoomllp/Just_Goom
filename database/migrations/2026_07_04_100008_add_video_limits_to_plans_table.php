<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->unsignedInteger('max_video_size_mb')->default(0)->after('duration_days');
            $table->unsignedInteger('max_video_count')->default(0)->after('max_video_size_mb');
            $table->unsignedInteger('max_project_count')->default(0)->after('max_video_count');
            $table->unsignedInteger('max_article_count')->default(0)->after('max_project_count');
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['max_video_size_mb', 'max_video_count', 'max_project_count', 'max_article_count']);
        });
    }
};
