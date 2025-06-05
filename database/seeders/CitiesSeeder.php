<?php

namespace Database\Seeders;

use App\Models\Geo\City;
use App\Models\Geo\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CitiesSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/gi_comuni.json');

        if (! File::exists($file)) {
            $this->command->error("File non trovato: $file");

            return;
        }

        $data = json_decode(File::get($file), true);
        $skipped = 0;
        $processed = 0;

        foreach ($data as $item) {
            $province = Province::where('code', $item['sigla_provincia'])->first();

            if ($province) {
                $baseSlug = Str::slug($item['denominazione_ita']);

                // Controlla se esiste già una città con questo slug nella stessa provincia
                $existingCity = City::where('slug', $baseSlug)
                    ->where('province_id', $province->id)
                    ->first();

                // Se esiste, aggiungi il codice istat al slug per renderlo unico
                $finalSlug = $existingCity ? $baseSlug.'-'.$item['codice_belfiore'] : $baseSlug;

                City::updateOrCreate(
                    [
                        'code_istat' => $item['codice_belfiore'],
                        'province_id' => $province->id,
                    ],
                    [
                        'name' => $item['denominazione_ita'],
                        'name_extra' => $item['denominazione_ita_altra'],
                        'slug' => $finalSlug,
                        'lat' => $item['lat'],
                        'lon' => $item['lon'],
                    ]
                );
                $processed++;
            } else {
                $skipped++;
                $this->command->warn("Provincia non trovata: {$item['sigla_provincia']}");
            }
        }

        $this->command->info("Processate $processed città.");
        if ($skipped > 0) {
            $this->command->warn("$skipped città ignorate.");
        }
    }
}
