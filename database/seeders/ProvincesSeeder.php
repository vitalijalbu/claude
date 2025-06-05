<?php

namespace Database\Seeders;

use App\Models\Geo\Country;
use App\Models\Geo\Province;
use App\Models\Geo\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProvincesSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/gi_province.json');
        $data = json_decode(File::get($file), true);
        $country = Country::where('code', 'it')->first();

        if (! $country) {
            $this->command->error('Paese Italia non trovato');

            return;
        }

        foreach ($data as $item) {
            $region = Region::where('code', $item['codice_regione'])->first();

            if (! $region) {
                $this->command->warn("Regione non trovata: {$item['codice_regione']}");

                continue;
            }

            $denominazione = $item['denominazione_provincia'];
            $name = Str::contains($denominazione, '/') ? Str::before($denominazione, '/') : $denominazione;

            Province::updateOrCreate(
                ['code' => $item['sigla_provincia']],
                [
                    'name' => $name,
                    'name_extra' => Str::contains($denominazione, '/') ? Str::after($denominazione, '/') : null,
                    'slug' => Str::slug($name),
                    'country_id' => $country->id,
                    'region_id' => $region->id,
                ]
            );
        }
    }
}
