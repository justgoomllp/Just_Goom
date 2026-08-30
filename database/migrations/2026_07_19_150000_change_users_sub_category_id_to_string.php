<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'sub_category_id')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('sub_category_id', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'sub_category_id')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_category_id')->nullable()->change();
        });
    }
};
