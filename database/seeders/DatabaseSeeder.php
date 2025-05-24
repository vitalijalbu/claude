<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // SitesSeeder::class,
            CategoriesSeeder::class,
            CountriesSeeder::class,
            RegionsSeeder::class,
            ProvincesSeeder::class,
            CitiesSeeder::class,
            // UsersSeeder::class,
        ]);
    }
}
