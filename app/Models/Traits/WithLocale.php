<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait WithLocale
{
    protected static function bootWithLocale()
    {
        static::retrieved(function (Model $model) {
            $model->attachLocaleData();
        });
    }

    public function attachLocaleData(): void
    {
        $locale = App::getLocale();
        $cacheKey = "{$this->getTable()}_{$this->id}_locale_{$locale}";

        $localeData = Cache::remember($cacheKey, 3600, function () use ($locale) {
            return $this->getLocaleData($locale);
        });

        // Attach locale data, excluding category_id
        foreach ($localeData as $key => $value) {
            if ($key !== 'category_id') {
                $this->setAttribute($key, $value);
            }
        }
    }

    public function getLocaleData(string $locale): array
    {
        $dataTable = $this->getTable().'_data';
        $foreignKey = $this->foreignKey ?? $this->determineForeignKey($dataTable);

        $localeData = DB::table($dataTable)
            ->where($foreignKey, $this->id)
            ->where('locale', $locale)
            ->firstOrFail();

        return $localeData ? (array) $localeData : [];
    }

    // Updated: Simplified foreign key determination
    public function determineForeignKey(string $dataTable): string
    {
        // Check if the model has a predefined foreign key or fall back to default
        return $this->getTable().'_id';
    }

    protected function translatedAttributes(): array
    {
        return property_exists($this, 'translated') ? $this->translated : [];
    }
}
