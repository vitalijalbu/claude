<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $timestamps = false;

    protected $table = 'geo_countries';

    protected $fillable = ['code', 'name', 'slug', 'phone_code', 'is_active'];

    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    // Accessor corretto per ottenere il numero di regioni
    public function getTotalRegionsAttribute()
    {
        return $this->regions()->count();
    }
}
