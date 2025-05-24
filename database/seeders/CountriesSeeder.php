<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CountriesSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/geo_countries.json');
        $data = json_decode(File::get($file), true);

        foreach ($data as $item) {
            $countryId = DB::table('geo_countries')->insertGetId([
                'code' => $item['alpha2'],
                'name' => $item['en'],
                'slug' => Str::slug($item['en']),
            ]);

            // Italian default version
            DB::table('geo_nationalities')->insert([
                'country_id' => $countryId,
                'site_id' => 1,
                'name' => $item['it'],
            ]);

            // English version
            if (! empty($item['en'])) {
                DB::table('geo_nationalities')->insert([
                    'country_id' => $countryId,
                    'name' => [
                        'it' => $item['it'],
                        'en' => $item['en'],
                    ],
                ]);
            }
        }
    }
}
