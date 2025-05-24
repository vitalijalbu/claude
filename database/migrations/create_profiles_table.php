<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('phone_number', 25)->nullable()->unique();
            $table->string('whatsapp_number', 25)->nullable()->unique();
            $table->string('avatar')->nullable();
            $table->json('media')->nullable();
            $table->string('nationality')->nullable();
            $table->text('bio')->nullable();
            $table->json('working_hours')->nullable();
            $table->integer('date_birth')->nullable();
            $table->integer('total_reviews')->nullable();
            $table->integer('pricing')->nullable()->min(0)->max(5);
            $table->integer('rating')->nullable()->max(10)->min(0);
            $table->float('lat')->nullable();
            $table->float('lon')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
