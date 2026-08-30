<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title', 200);
            $table->string('attachment');
            $table->enum('file_type', ['image', 'excel', 'word', 'pdf']);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'deleted_at']);
            $table->index(['user_id', 'file_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
