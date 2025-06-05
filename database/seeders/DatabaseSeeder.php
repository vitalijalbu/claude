<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategoriesSeeder::class,
            CountriesSeeder::class,
            RegionsSeeder::class,
            ProvincesSeeder::class,
            CitiesSeeder::class,
            TagsSeeder::class,
        ]);
    }
}
