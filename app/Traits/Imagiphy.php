<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait Imagiphy
{
    /**
     * Get all images for this model ordered by order_column
     */
    public function images(): MorphToMany
    {
        return $this->morphToMany(Image::class, 'imageable')
            ->withTimestamps()
            ->orderBy('images.order_column');
    }

    /**
     * Sync images with the model - improved error handling
     */
    public function syncImage(Request $request): bool
    {
        if (! $request->has('image') || ! $request->image) {
            return false;
        }

        try {
            $imageIds = is_array($request->image) ? $request->image : [$request->image];

            // Validate that all image IDs exist
            $validImageIds = Image::whereIn('id', $imageIds)->pluck('id')->toArray();

            if (empty($validImageIds)) {
                Log::warning('No valid image IDs found for sync', [
                    'model' => get_class($this),
                    'model_id' => $this->id,
                    'requested_ids' => $imageIds,
                ]);

                return false;
            }

            $this->images()->sync($validImageIds);
            $this->touch();

            return true;

        } catch (\Exception $e) {
            Log::error('Error syncing images', [
                'model' => get_class($this),
                'model_id' => $this->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Attach images to the model with validation
     */
    public function attachImages(array $imageIds): void
    {
        if (empty($imageIds)) {
            return;
        }

        try {
            // Validate that all image IDs exist and aren't already attached
            $validImageIds = Image::whereIn('id', $imageIds)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('imageables')
                        ->whereColumn('imageables.image_id', 'images.id')
                        ->where('imageables.imageable_type', get_class($this))
                        ->where('imageables.imageable_id', $this->id);
                })
                ->pluck('id')
                ->toArray();

            if (! empty($validImageIds)) {
                $this->images()->attach($validImageIds);

                Log::info('Images attached successfully', [
                    'model' => get_class($this),
                    'model_id' => $this->id,
                    'attached_count' => count($validImageIds),
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error attaching images', [
                'model' => get_class($this),
                'model_id' => $this->id,
                'image_ids' => $imageIds,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get the primary image (first by order) with caching
     */
    public function getImage(): ?Image
    {
        static $primaryImageCache = [];

        $cacheKey = get_class($this).'_'.$this->id;

        if (! isset($primaryImageCache[$cacheKey])) {
            $primaryImageCache[$cacheKey] = $this->images()
                ->orderBy('images.order_column')
                ->first();
        }

        return $primaryImageCache[$cacheKey];
    }

    /**
     * Get primary image URL for specific size with fallback
     */
    public function getPrimaryImageUrl(string $format = 'webp', string $size = 'md'): ?string
    {
        $primaryImage = $this->getImage();

        if (! $primaryImage) {
            return null;
        }

        try {
            return $primaryImage->path($format, $size);
        } catch (\Exception $e) {
            Log::warning('Error getting primary image URL', [
                'model' => get_class($this),
                'model_id' => $this->id,
                'format' => $format,
                'size' => $size,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Process images from S3 for this model with better error handling
     */
    public function processImagesFromS3(array $s3Filenames, string $phoneNumber): array
    {
        if (empty($s3Filenames)) {
            Log::info('No S3 filenames provided for processing', [
                'model' => get_class($this),
                'model_id' => $this->id,
                'phone_number' => $phoneNumber,
            ]);

            return [];
        }

        try {
            Log::info('Starting S3 image processing', [
                'model' => get_class($this),
                'model_id' => $this->id,
                'phone_number' => $phoneNumber,
                'file_count' => count($s3Filenames),
            ]);

            $processedImages = Image::processImagesFromS3($s3Filenames, $phoneNumber);

            if (! empty($processedImages)) {
                $imageIds = collect($processedImages)->pluck('id')->toArray();
                $this->attachImages($imageIds);

                Log::info('S3 images processed and attached successfully', [
                    'model' => get_class($this),
                    'model_id' => $this->id,
                    'processed_count' => count($processedImages),
                ]);
            }

            return $processedImages;

        } catch (\Exception $e) {
            Log::error('Error processing S3 images', [
                'model' => get_class($this),
                'model_id' => $this->id,
                'phone_number' => $phoneNumber,
                'filenames' => $s3Filenames,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return empty array instead of throwing to avoid breaking the main process
            return [];
        }
    }

    /**
     * Get all image URLs with all sizes (e-commerce optimized) with caching
     */
    public function getImageUrls(): array
    {
        static $imageUrlsCache = [];

        $cacheKey = get_class($this).'_'.$this->id.'_urls';

        if (! isset($imageUrlsCache[$cacheKey])) {
            try {
                $imageUrlsCache[$cacheKey] = $this->images()->get()->map(function (Image $image) {
                    return [
                        'id' => $image->id,
                        'alt' => $image->alt,
                        'order' => $image->order_column,
                        'is_primary' => $image->order_column === 0,
                        'urls' => $image->getAllSizeUrls(),
                        'responsive' => [
                            'xs' => $image->getResponsiveUrls('xs'),
                            'sm' => $image->getResponsiveUrls('sm'),
                            'md' => $image->getResponsiveUrls('md'),
                            'lg' => $image->getResponsiveUrls('lg'),
                        ],
                    ];
                })->toArray();
            } catch (\Exception $e) {
                Log::error('Error getting image URLs', [
                    'model' => get_class($this),
                    'model_id' => $this->id,
                    'error' => $e->getMessage(),
                ]);
                $imageUrlsCache[$cacheKey] = [];
            }
        }

        return $imageUrlsCache[$cacheKey];
    }

    /**
     * Reorder images by updating order_column with transaction
     */
    public function reorderImages(array $imageIds): void
    {
        if (empty($imageIds)) {
            return;
        }

        try {
            DB::transaction(function () use ($imageIds) {
                foreach ($imageIds as $order => $imageId) {
                    Image::where('id', $imageId)
                        ->whereHas('listings', function ($query) {
                            $query->where('imageable_id', $this->id)
                                ->where('imageable_type', get_class($this));
                        })
                        ->update(['order_column' => $order]);
                }
            });

            Log::info('Images reordered successfully', [
                'model' => get_class($this),
                'model_id' => $this->id,
                'reorder_count' => count($imageIds),
            ]);

        } catch (\Exception $e) {
            Log::error('Error reordering images', [
                'model' => get_class($this),
                'model_id' => $this->id,
                'image_ids' => $imageIds,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove specific images with cleanup
     */
    public function detachImages(array $imageIds): void
    {
        if (empty($imageIds)) {
            return;
        }

        try {
            $this->images()->detach($imageIds);

            Log::info('Images detached successfully', [
                'model' => get_class($this),
                'model_id' => $this->id,
                'detached_count' => count($imageIds),
            ]);

        } catch (\Exception $e) {
            Log::error('Error detaching images', [
                'model' => get_class($this),
                'model_id' => $this->id,
                'image_ids' => $imageIds,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get images for e-commerce display with error handling
     */
    public function getEcommerceImages(): array
    {
        try {
            $images = $this->images()->get();

            if ($images->isEmpty()) {
                return [
                    'primary' => null,
                    'gallery' => [],
                    'thumbnails' => [],
                ];
            }

            return [
                'primary' => $images->first()?->getAllSizeUrls(),
                'gallery' => $images->map(fn ($img) => $img->getAllSizeUrls())->toArray(),
                'thumbnails' => $images->map(fn ($img) => $img->path('webp', 'sm'))->toArray(),
            ];

        } catch (\Exception $e) {
            Log::error('Error getting e-commerce images', [
                'model' => get_class($this),
                'model_id' => $this->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'primary' => null,
                'gallery' => [],
                'thumbnails' => [],
            ];
        }
    }

    /**
     * Clear image cache for this model
     */
    public function clearImageCache(): void
    {
        // Implementation depends on your caching strategy
        // This is a placeholder for cache clearing logic
    }

    /**
     * Boot the trait
     */
    protected static function bootImagiphy(): void
    {
        static::deleting(function ($model) {
            try {
                // When model is deleted, detach all images
                $model->images()->detach();

                Log::info('Images detached during model deletion', [
                    'model' => get_class($model),
                    'model_id' => $model->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Error detaching images during model deletion', [
                    'model' => get_class($model),
                    'model_id' => $model->id,
                    'error' => $e->getMessage(),
                ]);
            }
        });

        static::updated(function ($model) {
            // Clear cache when model is updated
            $model->clearImageCache();
        });
    }
}
