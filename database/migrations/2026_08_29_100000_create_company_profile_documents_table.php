<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_profile_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_profile_id');
            $table->unsignedBigInteger('user_id');
            $table->string('business_type', 50);
            $table->string('document_name', 50);
            $table->string('value', 100);
            $table->string('front_image')->nullable();
            $table->string('back_image')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_profile_id', 'business_type']);
            $table->index(['user_id', 'business_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_profile_documents');
    }
};
