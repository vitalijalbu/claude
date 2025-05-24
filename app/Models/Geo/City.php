<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class City extends Model
{
    protected $table = 'geo_cities';

    public $timestamps = false;

    public $fillable = [
        'name',
        'province_id',
        'nation_id',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function region(): HasOneThrough
    {
        return $this->hasOneThrough(Region::class, Province::class);
    }
}
