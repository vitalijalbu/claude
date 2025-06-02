<?php

namespace App\Observers;

use App\Models\Geo\City;
use Illuminate\Support\Facades\Cache;

class CityCacheObserver
{
    public function saved(City $city): void
    {
        Cache::tags(['cities', 'geo'])->flush();
    }

    public function deleted(City $city): void
    {
        Cache::tags(['cities', 'geo'])->flush();
    }
}
