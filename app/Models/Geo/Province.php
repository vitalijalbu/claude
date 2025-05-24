<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Province extends Model
{
    protected $table = 'geo_provinces';

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function cities(): HasOneThrough
    {
        return $this->hasOneThrough(Province::class, Region::class);
    }

    public function country(): HasOneThrough
    {
        return $this->hasOneThrough(Country::class, Region::class);
    }
}
