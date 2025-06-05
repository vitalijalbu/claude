<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Nationality extends Model
{
    use HasTranslations;

    public array $translatable = ['name'];

    public $timestamps = false;

    protected $fillable = [
        'country_id',
        'name',
    ];

    protected $table = 'geo_nationalities';

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
