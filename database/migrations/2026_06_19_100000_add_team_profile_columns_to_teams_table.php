<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->string('department', 100)->nullable()->after('phone');
            $table->unsignedTinyInteger('status')->default(1)->after('department');
            $table->boolean('is_primary')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn(['department', 'status', 'is_primary']);
        });
    }
};
