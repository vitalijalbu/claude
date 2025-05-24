<?php

namespace Database\Seeders;

use App\Models\Geo\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = database_path('seeders/data/gi_comuni.json');

        if (! File::exists($file)) {
            $this->command->error("File non trovato: $file");

            return;
        }

        $data = json_decode(File::get($file), true);
        $records = [];
        $skipped = 0;

        foreach ($data as $item) {
            $provinceCode = $item['sigla_provincia'];
            $province = Province::where('code', $provinceCode)->first();

            if ($province) {
                $records[] = [
                    'province_id' => $province->id,
                    'name' => $item['denominazione_ita'],
                    'name_extra' => $item['denominazione_ita_altra'],
                    'code_istat' => $item['codice_belfiore'],
                    'slug' => Str::slug($item['denominazione_ita']),
                    'lat' => $item['lat'],
                    'lon' => $item['lon'],
                ];
            } else {
                $skipped++;
                $this->command->warn("Provincia non trovata: {$provinceCode}");
            }
        }

        if (! empty($records)) {
            DB::table('geo_cities')->upsert($records, ['code_istat'], [
                'province_id',
                'name',
                'name_extra',
                'slug',
                'lat',
                'lon',
            ]);
            $this->command->info('Inserite '.count($records).' città.');
        } else {
            $this->command->warn('Nessuna città inserita.');
        }

        if ($skipped > 0) {
            $this->command->warn("$skipped città ignorate perché la provincia non è stata trovata.");
        }
    }
}
