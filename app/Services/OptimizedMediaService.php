<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Listing;
use App\Models\Profile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OptimizedMediaService
{
    public function attachImagesFromLocalRaw(Profile $profile, Listing $listing, array $filenames): array
    {
        $sourceDisk = Storage::disk(config('glide.disk_source', 's3'));
        $output = [];
        $isFirst = true;

        try {
            foreach ($filenames as $filename) {
                $rawPath = config('glide.s3.raw_path', 'raw')."/{$profile->phone_number}/{$filename}";

                if (!$sourceDisk->exists($rawPath)) {
                    Log::warning("Raw image not found: {$rawPath}");
                    continue;
                }

                try {
                    // Get file contents
                    $contents = $sourceDisk->get($rawPath);
                    $tempPath = storage_path('app/tmp/' . Str::random(16) . '_' . $filename);
                    
                    // Ensure temp directory exists
                    if (!is_dir(dirname($tempPath))) {
                        mkdir(dirname($tempPath), 0755, true);
                    }

                    // Save to temp file
                    file_put_contents($tempPath, $contents);

                    // Add to listing media collection
                    $media = $listing->addMedia($tempPath)
                        ->usingName($filename)
                        ->toMediaCollection('images');

                    $output[] = [
                        'id' => $media->id,
                        'original' => $media->getUrl(),
                        'conversions' => [
                            'thumb' => $media->getUrl('thumb'),
                            'medium' => $media->getUrl('medium'),
                            'large' => $media->getUrl('large'),
                        ],
                    ];

                    // Set first image as profile avatar
                    if ($isFirst) {
                        $profile->clearMediaCollection('avatar');
                        $profile->addMediaFromUrl($media->getUrl())
                            ->toMediaCollection('avatar');
                        $isFirst = false;
                    }

                    // Clean up temp file
                    if (file_exists($tempPath)) {
                        unlink($tempPath);
                    }

                } catch (\Throwable $e) {
                    Log::error("Error processing image: {$filename}", [
                        'exception' => $e->getMessage(),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Media processing failed', [
                'error' => $e->getMessage(),
            ]);
        }

        return $output;
    }
}