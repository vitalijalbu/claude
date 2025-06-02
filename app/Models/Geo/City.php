<?php

declare(strict_types=1);

namespace App\Models\Geo;

use App\Models\Listing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class City extends Model
{
    protected $table = 'geo_cities';

    public $timestamps = false;

    public $fillable = [
        'name',
        'slug',
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

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }
}
