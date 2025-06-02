<?php

namespace App\Models;

use App\Models\Geo\City;
use App\Models\Geo\Province;
use App\Models\Traits\Cacheable;
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
    // use Cacheable;
    use InteractsWithMedia, SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'profile_id',
        'city_id',
        'is_featured',
        'is_vip',
        'is_active',
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
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'rating_stats' => 'array',
        'featured_until' => 'datetime',
        'vip_until' => 'datetime',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'media' => 'array',
    ];

    // protected $appends = ['is_new'];

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
            ->useDisk(config('media-library.disk_name', 'public'));
    }

    /**
     * Register media conversions
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $sizes = config('glide.sizes', [
            'thumb' => ['w' => 300, 'h' => 300],
            'medium' => ['w' => 600, 'h' => 400],
            'large' => ['w' => 1200, 'h' => 800],
        ]);

        $format = config('glide.format', 'jpg');
        $quality = config('glide.quality', 90);
        $fit = config('glide.fit', 'crop');

        foreach ($sizes as $name => $params) {
            $conversion = $this->addMediaConversion($name)
                ->format($format)
                ->quality($quality)
                ->nonQueued(); // Process immediately, or remove for queue processing

            if (isset($params['w']) && isset($params['h'])) {
                match ($fit) {
                    'crop' => $conversion->fit('crop', $params['w'], $params['h']),
                    'contain' => $conversion->fit('contain', $params['w'], $params['h']),
                    'fill' => $conversion->width($params['w'])->height($params['h']),
                    default => $conversion->fit('crop', $params['w'], $params['h']),
                };
            } elseif (isset($params['w'])) {
                $conversion->width($params['w']);
            } elseif (isset($params['h'])) {
                $conversion->height($params['h']);
            }
        }
    }

    /**
     * Helper method to get image URLs with conversions
     */
    public function getImageUrls(): array
    {
        return $this->getMedia('images')->map(function (Media $media) {
            $conversions = [];
            $sizes = array_keys(config('glide.sizes', []));

            foreach ($sizes as $size) {
                try {
                    $conversions[$size] = $media->getUrl($size);
                } catch (\Exception $e) {
                    // Conversion might not exist yet
                    $conversions[$size] = null;
                }
            }

            return [
                'id' => $media->id,
                'original' => $media->getUrl(),
                'conversions' => $conversions,
                'name' => $media->name,
                'file_name' => $media->file_name,
                'mime_type' => $media->mime_type,
                'size' => $media->size,
                'order' => $media->order_column,
            ];
        })->toArray();
    }

    /**
     * Get the main/featured image
     */
    public function getFeaturedImage(): ?Media
    {
        return $this->getFirstMedia('images');
    }

    /**
     * Get featured image URL with optional conversion
     */
    public function getFeaturedImageUrl(string $conversion = ''): ?string
    {
        $featuredImage = $this->getFeaturedImage();

        return $featuredImage ? $featuredImage->getUrl($conversion) : null;
    }

    /**
     * Update the media JSON field for backward compatibility
     */
    public function updateMediaJson(): void
    {
        $mediaData = $this->getImageUrls();
        $this->update(['media' => $mediaData]);
    }

    /**
     * Boot method to auto-update media JSON when media changes
     */
    protected static function boot()
    {
        parent::boot();

        // Update media JSON when the model is saved
        static::saved(function ($listing) {
            // Only update if we have media
            if ($listing->getMedia('images')->count() > 0) {
                $listing->updateMediaJson();
            }
        });
    }

    // Existing relationships
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

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }
}
