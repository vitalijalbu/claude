<?php

declare(strict_types=1);

namespace App\Actions\Geo;

use App\Models\Geo\City;
use Illuminate\Database\Eloquent\Collection;

class IndexCities
{
    public function handle(array $params = []): Collection
    {
        $query = City::with('province');

        if (isset($params['province_id'])) {
            $query->where('province_id', $params['province_id']);
        }

        return $query->orderBy('name')->get();
    }
}
