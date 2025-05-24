<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'slug' => 'massagges',
                'title' => [
                    'it' => 'Massaggi',
                    'en' => 'Massages',
                    'de' => 'Massagen',
                ],
            ],
            [
                'slug' => 'escort',
                'title' => [
                    'it' => 'Escort',
                    'en' => 'Escort',
                    'de' => 'Begleitservice',
                ],
            ],
            [
                'slug' => 'trans',
                'title' => [
                    'it' => 'Trans',
                    'en' => 'Trans',
                    'de' => 'Transsexuelle',
                ],
            ],
        ];

        foreach ($services as $service) {
            Category::updateOrCreate(
                ['slug' => $service['slug']],
                ['title' => $service['title']]
            );
        }
    }
}
