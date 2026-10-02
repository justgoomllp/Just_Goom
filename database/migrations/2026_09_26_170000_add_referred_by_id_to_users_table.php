<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'referred_by_id')) {
                $table->unsignedBigInteger('referred_by_id')->nullable()->after('referral_code');
                $table->index('referred_by_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'referred_by_id')) {
                $table->dropIndex(['referred_by_id']);
                $table->dropColumn('referred_by_id');
            }
        });
    }
};
