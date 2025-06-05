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
        Schema::create('tag_groups', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('origin_id')->nullable()->constrained('tag_groups')->onDelete('set null');
            $table->json('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for better performance
            $table->index(['is_active', 'slug']);
            $table->index('origin_id');
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origin_id')->nullable()->constrained('tags')->onDelete('cascade');
            $table->foreignId('group_id')->constrained('tag_groups')->onDelete('cascade');
            $table->json('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for better performance
            $table->index(['group_id', 'is_active']);
            $table->index(['origin_id', 'group_id']);
            $table->index('slug');
        });

        Schema::create('listing_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained('listings')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->timestamps();

            // Prevent duplicate relationships and improve query performance
            $table->unique(['listing_id', 'tag_id']);
            $table->index('tag_id');
        });

        Schema::create('profile_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('profiles')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->timestamps();

            // Prevent duplicate relationships and improve query performance
            $table->unique(['profile_id', 'tag_id']);
            $table->index('tag_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_tags');
        Schema::dropIfExists('listing_tags');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('tag_groups');
    }
};
