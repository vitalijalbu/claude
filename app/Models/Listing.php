<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Geo\City;
use App\Models\Geo\Province;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Listing extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'category_id',
        'profile_id',
        'city_id',
        'is_featured',
        'is_vip',
        'is_verified',
        'phone_number',
        'whatsapp_number',
        'title',
        'slug',
        'description',
        'date_birth',
        'nationality',
        'location',
        'pricing',
        'ref_site',
        'lon',
        'lat',
        'status',
        'views_count',
        'reviews_count',
        'featured_until',
        'vip_until',
        'published_at',
        'expires_at',
        'rating_stats',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_vip' => 'boolean',
        'is_verified' => 'boolean',
        'rating_stats' => 'array',
        'featured_until' => 'datetime',
        'vip_until' => 'datetime',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'date_birth' => 'date',
        'pricing' => 'decimal:2',
        'lon' => 'decimal:8',
        'lat' => 'decimal:8',
        'views_count' => 'integer',
        'reviews_count' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Listing $listing): void {
            if (empty($listing->published_at)) {
                $listing->published_at = now();
            }
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
            ->singleFile(false);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->crop('crop-center')
            ->sharpen(10)
            ->quality(85)
            ->format('webp')
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->width(600)
            ->height(400)
            ->crop('crop-center')
            ->quality(90)
            ->format('webp')
            ->nonQueued();

        $this->addMediaConversion('large')
            ->width(1200)
            ->height(800)
            ->crop('crop-center')
            ->quality(95)
            ->format('webp')
            ->nonQueued();
    }

    // Relationships
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
        return $this->belongsToMany(Taxonomy::class, 'listing_taxonomies')
            ->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('is_verified', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true)
            ->where(function (Builder $q): void {
                $q->whereNull('featured_until')
                    ->orWhere('featured_until', '>', now());
            });
    }

    public function scopeVip(Builder $query): Builder
    {
        return $query->where('is_vip', true)
            ->where(function (Builder $q): void {
                $q->whereNull('vip_until')
                    ->orWhere('vip_until', '>', now());
            });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published_at', '<=', now())
            ->where(function (Builder $q): void {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function scopePriceRange(Builder $query, float $min, float $max): Builder
    {
        return $query->whereBetween('pricing', [$min, $max]);
    }

    public function scopeAgeRange(Builder $query, int $minAge, int $maxAge): Builder
    {
        $maxBirthDate = now()->subYears($minAge)->format('Y-m-d');
        $minBirthDate = now()->subYears($maxAge)->format('Y-m-d');

        return $query->whereBetween('date_birth', [$minBirthDate, $maxBirthDate]);
    }

    public function scopeWithinRadius(Builder $query, float $lat, float $lon, float $radiusKm): Builder
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        return $query->selectRaw(
            "*, (
                {$earthRadius} * acos(
                    cos(radians(?)) * 
                    cos(radians(lat)) * 
                    cos(radians(lon) - radians(?)) + 
                    sin(radians(?)) * 
                    sin(radians(lat))
                )
            ) AS distance",
            [$lat, $lon, $lat]
        )
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance');
    }

    // Accessors & Mutators
    public function getAgeAttribute(): ?int
    {
        return $this->date_birth ? now()->diffInYears($this->date_birth) : null;
    }

    public function getIsFeaturedActiveAttribute(): bool
    {
        return $this->is_featured &&
               ($this->featured_until === null || $this->featured_until->isFuture());
    }

    public function getIsVipActiveAttribute(): bool
    {
        return $this->is_vip &&
               ($this->vip_until === null || $this->vip_until->isFuture());
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    // Helper methods
    public function getImageUrls(): array
    {
        // Fix: Ensure media is properly loaded
        if (! $this->relationLoaded('media')) {
            $this->loadMissing('media');
        }

        return $this->getMedia('images')
            ->map(function (Media $media): array {
                return [
                    'id' => $media->id,
                    'original' => $media->getUrl(),
                    'thumb' => $media->getUrl('thumb'),
                    'medium' => $media->getUrl('medium'),
                    'large' => $media->getUrl('large'),
                    'name' => $media->name,
                    'alt' => $media->getCustomProperty('alt', $this->title),
                    'order' => $media->getCustomProperty('order', 0),
                ];
            })
            ->sortBy('order')
            ->values()
            ->toArray();
    }

    public function getFeaturedImageUrl(string $conversion = ''): ?string
    {
        if (! $this->relationLoaded('media')) {
            $this->loadMissing('media');
        }

        $featuredImage = $this->getFirstMedia('images');

        return $featuredImage?->getUrl($conversion);
    }

    public function updateRatingStats(): void
    {
        $reviews = $this->reviews()->where('is_approved', true);

        $this->update([
            'reviews_count' => $reviews->count(),
            'rating_stats' => [
                'average' => round($reviews->avg('rating'), 2),
                'total' => $reviews->count(),
                'distribution' => $reviews->groupBy('rating')
                    ->map->count()
                    ->toArray(),
            ],
        ]);
    }

    // Query optimization methods
    public static function withBasicRelations(): Builder
    {
        return static::with(['category', 'city']);
    }

    public static function withFullRelations(): Builder
    {
        return static::with([
            'category',
            'city.province',
            'profile',
            'taxonomies',
            'reviews' => fn ($q) => $q->latest()->limit(5),
        ]);
    }
}
