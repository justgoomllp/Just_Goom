<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('section_type', 30)
                ->default('normal')
                ->after('type')
                ->comment('normal, real_estate, ecommerce');
            $table->json('meta')->nullable()->after('thumbnail');
            $table->index(['user_id', 'section_type', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'section_type', 'deleted_at']);
            $table->dropColumn(['section_type', 'meta']);
        });
    }
};
