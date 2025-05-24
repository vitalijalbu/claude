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
            $table->boolean('is_featured')->nullable()->default(false);
            $table->boolean('is_vip')->nullable()->default(false);
            $table->int('date_birth')->nullable();
            $table->string('nationality')->nullable();
            $table->string('title');
            $table->string('phone_number', 25)->nullable()->unique();
            $table->string('whatsapp_number', 25)->nullable()->unique();
            $table->string('slug')->unique()->index();
            $table->text('description');
            $table->json('media')->nullable();
            $table->decimal('pricing')->nullable()->default(1);
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->text('ref_site')->nullable();
            $table->string('location')->nullable();
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
        Schema::dropIfExists('listings');
    }
};
