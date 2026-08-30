<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id');
            $table->date('purchase_date');
            $table->date('next_purchase_date');

            $table->index(['user_id', 'next_purchase_date']);
            $table->index('next_purchase_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_plans');
    }
};
