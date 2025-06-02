<?php

namespace App\Models;

use App\Models\Geo\City;
use App\Models\Geo\Province;
use App\Models\Traits\Cacheable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Profile extends Model implements HasMedia
{
    // use Cacheable;
    use InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
        'city_id',
        'phone_number',
        'nationality',
        'whatsapp_number',
        'lon',
        'lat',
        'bio',
        'avatar',
        'website',
        'working_hours',
        'date_birth',
    ];

    protected $casts = [
        'working_hours' => 'json',
        'rating' => 'float',
        'media' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
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
        return $this->belongsToMany(Taxonomy::class, 'profile_taxonomies');
    }

    public function mergedMedia(): Collection
    {
        // Media propri del profilo (es. avatar, immagini specifiche)
        $profileMedia = $this->getMedia('profile');

        // Media dai listing
        $listingMedia = $this->listings->flatMap(function ($listing) {
            return $listing->getMedia('listing');
        });

        // Ordina: prima i media del profilo, poi quelli dei listing
        return $profileMedia->concat($listingMedia);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile();
    }
}
