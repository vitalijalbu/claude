<?php

namespace Database\Seeders;

use App\Models\Geo\Country;
use App\Models\Geo\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RegionsSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/gi_regioni.json');
        $data = json_decode(File::get($file), true);
        $country = Country::where('code', 'it')->first();

        if (! $country) {
            $this->command->error('Paese Italia non trovato');

            return;
        }

        foreach ($data as $item) {
            $denominazione = $item['denominazione_regione'];
            $name = Str::contains($denominazione, '/') ? Str::before($denominazione, '/') : $denominazione;

            Region::updateOrCreate(
                ['code' => $item['codice_regione']],
                [
                    'name' => $name,
                    'name_extra' => Str::contains($denominazione, '/') ? Str::after($denominazione, '/') : null,
                    'slug' => Str::slug($name),
                    'country_id' => $country->id,
                ]
            );
        }
    }
}
