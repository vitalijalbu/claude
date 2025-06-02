<?php

declare(strict_types=1);

namespace App\Actions\Geo;

use App\Models\Geo\Region;
use Illuminate\Database\Eloquent\Collection;

class IndexRegions
{
    public function handle(array $params = []): Collection
    {
        $query = Region::with('country');

        if (isset($params['country_id'])) {
            $query->where('country_id', $params['country_id']);
        }

        return $query->orderBy('name')->get();
    }
}
