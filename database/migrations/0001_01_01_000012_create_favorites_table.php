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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('listing_id')->constrained('listings')->onDelete('cascade');
            $table->json('tags')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Performance indexes for common queries
            $table->index(['user_id', 'created_at']);
            $table->index(['listing_id', 'created_at']);
            $table->index('created_at');

            // Prevent duplicate favorites
            $table->unique(['user_id', 'listing_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
