<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabella Paesi
        Schema::create('geo_countries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique();
            $table->string('name');
            $table->string('slug')->unique();
        });

        // Tabella Regioni
        Schema::create('geo_regions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')
                ->nullable()
                ->constrained('geo_countries')
                ->onDelete('set null');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('name_extra')->nullable();
            $table->string('slug')->unique();
        });

        // Tabella Province
        Schema::create('geo_provinces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')
                ->nullable()
                ->constrained('geo_countries')
                ->onDelete('set null');
            $table->foreignId('region_id')
                ->constrained('geo_regions')
                ->onDelete('set null');
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('name_extra')->nullable();
            $table->string('slug')->unique();
        });

        // Tabella Città
        Schema::create('geo_cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')
                ->nullable()
                ->constrained('geo_provinces')
                ->onDelete('set null');
            $table->string('code_istat')->nullable();
            $table->string('name');
            $table->string('name_extra')->nullable();
            $table->string('slug')->unique();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->boolean('is_featured')->nullable()->default(false);
        });

        // Tabella Nazionalità
        Schema::create('geo_nationalities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->nullable()->constrained('geo_countries')->onDelete('set null');
            $table->json('name');
            $table->unique(['country_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('geo_nationalities');
        Schema::dropIfExists('geo_cities');
        Schema::dropIfExists('geo_provinces');
        Schema::dropIfExists('geo_regions');
        Schema::dropIfExists('geo_countries');
    }
};
