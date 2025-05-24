<?php

declare(strict_types=1);

namespace App\Actions\Province;

use App\Models\Geo\Region;

final class IndexProvince
{
    public function handle()
    {
        $regions = Region::orderBy('name', 'asc')
            ->with(['provinces' => function ($query) {
                $query->orderBy('name', 'asc');
            }])
            ->get();

        return $regions;
    }
}
