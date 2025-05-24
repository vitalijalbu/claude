<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nationality extends Model
{
    protected $table = 'geo_nationalities';

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
