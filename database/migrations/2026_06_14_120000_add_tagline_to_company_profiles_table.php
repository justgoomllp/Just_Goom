<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('company_profiles', 'tagline')) {
                $table->string('tagline', 255)->nullable()->after('owner_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('company_profiles', 'tagline')) {
                $table->dropColumn('tagline');
            }
        });
    }
};
