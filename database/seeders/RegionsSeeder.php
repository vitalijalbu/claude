<?php

namespace Database\Seeders;

use App\Models\Geo\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = database_path('seeders/data/gi_regioni.json');
        $data = json_decode(File::get($file), true);
        $countryId = Country::where('code', 'it')->value('id');

        foreach ($data as $item) {
            $denominazione = $item['denominazione_regione'];
            $name = Str::contains($denominazione, '/') ? Str::before($denominazione, '/') : $denominazione;

            DB::table('geo_regions')->insert([
                'name' => $name,
                'name_extra' => Str::contains($denominazione, '/') ? Str::after($denominazione, '/') : null,
                'slug' => Str::slug($name),
                'code' => $item['codice_regione'],
                'country_id' => $countryId,
            ]);
        }
    }
}
