<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('company_name', 200);
            $table->string('slug')->unique();
            $table->string('owner_name', 200)->nullable();
            $table->string('logo')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('whatsapp_no', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('business_desc')->nullable();
            $table->string('address', 500)->nullable();
            $table->string('zipcode', 20)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->json('business_hours')->nullable();
            $table->string('social_website')->nullable();
            $table->string('social_subwebsite')->nullable();
            $table->string('social_facebook')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['city', 'state', 'country', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
};
