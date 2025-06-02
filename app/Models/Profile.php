<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Geo\City;
use App\Models\Geo\Province;
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
    use InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'name', 'user_id', 'city_id', 'phone_number', 'nationality', 'whatsapp_number',
        'lon', 'lat', 'bio', 'avatar', 'website', 'working_hours', 'date_birth', 'rating',
    ];

    protected $casts = [
        'working_hours' => 'json',
        'rating' => 'float',
        'media' => 'array',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(10)
            ->quality(85)
            ->format('webp')
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->width(300)
            ->height(300)
            ->quality(90)
            ->format('webp')
            ->nonQueued();
    }

    // Relationships
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
        return $this->hasOneThrough(Province::class, City::class, 'id', 'id', 'city_id', 'province_id');
    }

    public function taxonomies(): BelongsToMany
    {
        return $this->belongsToMany(Taxonomy::class, 'profile_taxonomies');
    }

    // Scopes
    public function scopeRatingRange($query, $min, $max)
    {
        return $query->whereBetween('rating', [$min, $max]);
    }

    public function scopeWithActiveListings($query)
    {
        return $query->whereHas('listings', function ($q) {
            $q->where('is_active', true);
        });
    }
}
