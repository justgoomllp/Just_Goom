<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('type', 20)->default('document')->comment('document, video, link');
            $table->string('file_path', 500)->nullable();
            $table->string('external_url', 500)->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'type', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
