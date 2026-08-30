<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('link_url', 500)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_featured')->default(false);
            $table->string('status', 20)->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'start_date', 'end_date', 'deleted_at']);
            $table->index(['user_id', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
