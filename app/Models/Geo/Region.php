<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Region extends Model
{
    public $timestamps = false;

    protected $table = 'geo_regions';

    protected $fillable = ['code', 'name', 'slug', 'phone_code'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function provinces()
    {
        return $this->hasMany(Province::class);
    }

    public function cities(): HasManyThrough
    {
        return $this->hasManyThrough(City::class, Province::class);
    }
}
