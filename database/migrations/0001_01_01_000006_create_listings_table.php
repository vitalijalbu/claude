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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('profile_id')->constrained('profiles')->onDelete('cascade');
            $table->foreignId('city_id')->nullable()->constrained('geo_cities')->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('phone_number', 25)->nullable();
            $table->string('whatsapp_number', 25)->nullable();
            $table->integer('date_birth')->nullable();
            $table->string('nationality')->nullable();
            $table->decimal('pricing', 8, 2)->nullable();
            $table->string('location')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lon', 11, 8)->nullable();
            $table->text('ref_site')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_vip')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->enum('status', ['draft', 'published', 'suspended', 'expired'])->default('draft');
            $table->unsignedInteger('views_count')->default(0);
            $table->json('rating_stats')->nullable();
            $table->unsignedInteger('reviews_count')->default(0);
            $table->timestamp('featured_until')->nullable();
            $table->timestamp('vip_until')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Performance indexes for common queries
            $table->index(['category_id', 'city_id', 'status', 'is_active']);
            $table->index(['is_featured', 'is_vip', 'status', 'published_at']);
            $table->index(['profile_id', 'status', 'is_active']);
            $table->index(['status', 'is_active']);
            $table->index(['lat', 'lon']);
            $table->index(['city_id', 'status', 'is_active']);
            $table->index('phone_number');
            $table->index('whatsapp_number');
            $table->index('views_count');
            $table->index('published_at');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
