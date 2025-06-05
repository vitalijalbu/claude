<?php

namespace Database\Seeders;

use App\Models\Geo\Country;
use App\Models\Geo\Nationality;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CountriesSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/geo_countries.json');
        $data = json_decode(File::get($file), true);

        foreach ($data as $item) {
            $country = Country::updateOrCreate(
                ['code' => $item['alpha2']],
                [
                    'name' => $item['en'],
                    'slug' => Str::slug($item['en']),
                ]
            );

            // Nationality con Spatie Translatable
            Nationality::updateOrCreate(
                ['country_id' => $country->id],
                [
                    'name' => [
                        'it' => $item['it'],
                        'en' => $item['en'],
                    ],
                ]
            );
        }
    }
}
