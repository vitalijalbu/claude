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
        Schema::create('taxonomy_groups', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('origin_id')->nullable()->constrained('taxonomy_groups')->onDelete('set null');
            $table->json('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('taxonomies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origin_id')->nullable()->constrained('taxonomies')->onDelete('cascade');
            $table->foreignId('group_id')->constrained('taxonomy_groups')->onDelete('cascade');
            $table->json('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('listing_taxonomies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('taxonomy_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('profile_taxonomies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('taxonomy_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxonomy_groups');
        Schema::dropIfExists('taxonomies');
        Schema::dropIfExists('profile_taxonomy');
        Schema::dropIfExists('listing_taxonomy');
    }
};
