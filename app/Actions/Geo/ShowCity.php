<?php

declare(strict_types=1);

namespace App\Actions\Geo;

use App\Models\Geo\City;

class ShowCity
{
    public function handle(string $name): ?City
    {
        return City::where('name', $name)->first();
    }
}