<?php

namespace App\Models;

use App\Models\Geo\City;
use App\Models\Geo\Province;
use App\Models\Traits\Cacheable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Listing extends Model
{
    // use Cacheable;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'profile_id',
        'city_id',
        'is_featured',
        'phone_number',
        'whatsapp_number',
        'title',
        'slug',
        'date_birth',
        'location',
        'description',
        'pricing',
        'ref_site',
        'lon',
        'lat',
        'is_verified',
        'media',
    ];

    protected $casts = [
        'deleted' => 'boolean',
        'is_vip' => 'boolean',
        'is_verified' => 'boolean',
        'media' => 'json',
    ];

    // protected $appends = ['is_new'];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function province(): HasOneThrough
    {
        return $this->hasOneThrough(
            Province::class,
            City::class,
            'id',
            'id',
            'city_id',
            'province_id'
        );
    }

    public function taxonomies(): BelongsToMany
    {
        return $this->belongsToMany(Taxonomy::class, 'listing_taxonomies');
    }
}
