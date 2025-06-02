<?php

declare(strict_types=1);

namespace App\Actions\Geo;

use App\Models\Geo\Country;
use Illuminate\Database\Eloquent\Collection;

class IndexCountries
{
    public function handle(array $params = []): Collection
    {
        $query = Country::query();

        if (isset($params['is_active'])) {
            $query->where('is_active', $params['is_active']);
        }

        return $query->orderBy('name')->get();
    }
}
