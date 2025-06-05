<?php

namespace App\Models\Geo;

use App\Models\Listing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Province extends Model
{
    public $timestamps = false;

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

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }
}
