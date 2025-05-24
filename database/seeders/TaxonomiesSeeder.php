<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class TaxonomiesSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'slug' => 'massagges',
                'name' => [
                    'it' => 'Massaggi',
                    'en' => 'Massages',
                    'de' => 'Massagen',
                ],
            ],
            [
                'slug' => 'escort',
                'name' => [
                    'it' => 'Escort',
                    'en' => 'Escort',
                    'de' => 'Begleitservice',
                ],
            ],
            [
                'slug' => 'trans',
                'name' => [
                    'it' => 'Trans',
                    'en' => 'Trans',
                    'de' => 'Transsexuelle',
                ],
            ],
        ];

        foreach ($services as $service) {
            Category::updateOrCreate(
                ['slug' => $service['slug']],
                ['name' => $service['name']]
            );
        }
    }
}
