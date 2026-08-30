<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('user_plan_id')->nullable();
            $table->string('invoice_number', 40)->nullable()->unique();
            $table->string('gateway', 30)->default('razorpay');
            $table->string('razorpay_order_id', 80)->unique();
            $table->string('razorpay_payment_id', 80)->nullable()->unique();
            $table->string('razorpay_signature', 255)->nullable();
            $table->decimal('amount', 10, 2);
            $table->unsignedInteger('amount_paise');
            $table->string('currency', 10)->default('INR');
            $table->string('status', 30)->default('created');
            $table->string('method', 40)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('contact', 30)->nullable();
            $table->string('receipt', 40)->nullable();
            $table->json('payload')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('invoice_sent_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['plan_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};
