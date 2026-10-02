<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('payment_log_id');
            $table->unsignedBigInteger('plan_id');
            $table->string('region', 20);
            $table->string('type', 30);
            $table->decimal('payment_amount', 10, 2);
            $table->unsignedTinyInteger('profile_percent')->default(0);
            $table->decimal('rate_percent', 5, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->string('status', 20)->default('earned');
            $table->timestamps();

            $table->unique(['payment_log_id', 'type']);
            $table->index(['agent_id', 'created_at']);
            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_commissions');
    }
};
