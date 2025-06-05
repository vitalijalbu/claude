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
            $table->foreignId('city_id')->nullable()->constrained('geo_cities')->onDelete('set null'); // Fixed reference
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('name', 255)->nullable();
            $table->string('phone_number', 25)->nullable();
            $table->string('whatsapp_number', 25)->nullable();
            $table->string('avatar')->nullable();
            $table->string('nationality')->nullable();
            $table->text('bio')->nullable();
            $table->json('working_hours')->nullable();
            $table->integer('date_birth')->nullable();
            $table->unsignedInteger('total_reviews')->default(0);
            $table->unsignedTinyInteger('pricing')->nullable();
            $table->json('rating_stats')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lon', 11, 8)->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Performance indexes
            $table->index(['city_id', 'category_id', 'is_active']);
            $table->index(['is_active', 'is_verified']);
            $table->index(['is_featured']);
            $table->index(['user_id', 'is_active']);
            $table->index(['lat', 'lon']); // For geographic queries
            $table->index('phone_number');
            $table->index('whatsapp_number');
            $table->index('last_active_at');
            $table->unique('user_id');
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
