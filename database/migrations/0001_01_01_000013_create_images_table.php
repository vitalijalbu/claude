<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create images table
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('path')->index();
            $table->string('alt')->nullable();
            $table->string('extension_original', 10);
            $table->unsignedInteger('file_size')->nullable();
            $table->unsignedSmallInteger('width')->nullable();
            $table->unsignedSmallInteger('height')->nullable();
            $table->unsignedTinyInteger('order_column')->default(0);
            $table->timestamps();

            $table->index(['created_at']);
            $table->index(['order_column']);
        });

        // Create imageables pivot table
        Schema::create('imageables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('image_id')->constrained()->onDelete('cascade');
            $table->morphs('imageable');
            $table->timestamps();

            $table->index(['image_id', 'imageable_type', 'imageable_id'], 'imageables_image_morph_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imageables');
        Schema::dropIfExists('images');
    }
};
