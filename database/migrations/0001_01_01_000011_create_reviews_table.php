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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('listing_id')->constrained('listings')->onDelete('cascade'); // Reviews for listings
            $table->foreignId('profile_id')->constrained('profiles')->onDelete('cascade'); // Profile being reviewed
            $table->string('title')->nullable(); // Review title/summary
            $table->text('content');
            $table->unsignedTinyInteger('rating'); // 1-5 or 1-10 star rating
            $table->json('detailed_ratings')->nullable(); // Breakdown ratings (service, communication, etc.)
            $table->boolean('is_approved')->default(false); // Moderation required
            $table->boolean('is_verified')->default(false); // Verified purchase/interaction
            $table->boolean('is_featured')->default(false); // Highlight important reviews
            $table->boolean('is_anonymous')->default(false); // Anonymous review option
            $table->string('reviewer_name')->nullable(); // Display name for anonymous reviews
            $table->unsignedInteger('helpful_votes')->default(0); // Users found this helpful
            $table->unsignedInteger('unhelpful_votes')->default(0); // Users found this unhelpful
            $table->text('admin_response')->nullable(); // Business/admin can respond
            $table->timestamp('admin_response_at')->nullable();
            $table->foreignId('admin_responder_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('flags')->nullable(); // User reports (spam, fake, inappropriate)
            $table->enum('status', ['pending', 'approved', 'rejected', 'flagged'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            // Performance indexes
            $table->index(['listing_id', 'status', 'is_approved']);
            $table->index(['profile_id', 'status', 'is_approved']);
            $table->index(['user_id', 'status']);
            $table->index(['status', 'is_approved', 'rating']);
            $table->index(['is_featured', 'rating', 'approved_at']);
            $table->index(['is_verified', 'status', 'rating']);
            $table->index('approved_at');
            $table->index('helpful_votes');

            // Prevent duplicate reviews from same user for same listing
            $table->unique(['user_id', 'listing_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
