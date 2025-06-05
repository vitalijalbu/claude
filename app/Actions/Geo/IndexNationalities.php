<?php

declare(strict_types=1);

namespace App\Actions\Geo;

use App\Models\Geo\Nationality;
use Illuminate\Database\Eloquent\Collection;

class IndexNationalities
{
    public function handle(array $params = []): Collection
    {
        $query = Nationality::with('country');

        if (isset($params['country_id'])) {
            $query->where('country_id', $params['country_id']);
        }

        return $query->orderBy('name')->get();
    }
}
