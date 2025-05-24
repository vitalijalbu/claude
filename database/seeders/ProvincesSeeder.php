<?php

namespace Database\Seeders;

use App\Models\Geo\Country;
use App\Models\Geo\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProvincesSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/gi_province.json');
        $data = json_decode(File::get($file), true);
        $countryId = Country::where('code', 'it')->value('id');

        foreach ($data as $item) {
            $denominazione = $item['denominazione_provincia'];
            $name = Str::contains($denominazione, '/') ? Str::before($denominazione, '/') : $denominazione;
            $regionId = Region::where('code', $item['codice_regione'])->first()->id;

            DB::table('geo_provinces')->insert([
                'name' => $name,
                'name_extra' => Str::contains($denominazione, '/') ? Str::after($denominazione, '/') : null,
                'slug' => Str::slug($name),
                'code' => $item['sigla_provincia'],
                'country_id' => $countryId,
                'region_id' => $regionId,
            ]);
        }
    }
}
