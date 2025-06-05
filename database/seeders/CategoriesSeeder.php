<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
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

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }
    }
}
