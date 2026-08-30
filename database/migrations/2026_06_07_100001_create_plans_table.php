<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->decimal('rate', 10, 2)->default(0);
            $table->unsignedInteger('duration_days')->comment('15=free, 180=6 months, 365=12 months');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
