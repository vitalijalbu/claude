<?php

declare(strict_types=1);

namespace App\Actions\Geo;

use App\Models\Geo\Province;
use Illuminate\Database\Eloquent\Collection;

class IndexProvinces
{
    public function handle(array $params = []): Collection
    {
        $query = Province::with('region');

        if (isset($params['region_id'])) {
            $query->where('region_id', $params['region_id']);
        }

        return $query->orderBy('name')->get();
    }
}