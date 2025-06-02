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
    private const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB

    private const ALLOWED_MIMES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    private const TEMP_DIR = 'tmp/media';

    public function attachImagesFromLocalRaw(Profile $profile, Listing $listing, array $filenames): array
    {
        $sourceDisk = Storage::disk(config('glide.disk_source', 's3'));
        $output = [];
        $processedCount = 0;
        $isFirst = true;

        try {
            $listing->clearMediaCollection('images');

            foreach ($filenames as $index => $filename) {
                if ($processedCount >= 10) {
                    Log::info("Skipping image {$filename}: Maximum limit reached");
                    break;
                }

                $rawPath = config('glide.s3.raw_path', 'raw')."/{$profile->phone_number}/{$filename}";

                if (! $sourceDisk->exists($rawPath)) {
                    Log::warning("Raw image not found: {$rawPath}");

                    continue;
                }

                try {
                    $processedImage = $this->processImage($sourceDisk, $rawPath, $filename, $listing, $index);

                    if ($processedImage) {
                        $output[] = $processedImage;
                        $processedCount++;

                        if ($isFirst && $profile) {
                            $this->setProfileAvatar($profile, $processedImage['media']);
                            $isFirst = false;
                        }
                    }
                } catch (\Throwable $e) {
                    Log::error("Error processing image: {$filename}", [
                        'exception' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }

            $listing->update(['media_count' => $processedCount]);

        } catch (\Throwable $e) {
            Log::error('Media processing failed', [
                'profile_id' => $profile->id,
                'listing_id' => $listing->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $output;
    }

    private function processImage($sourceDisk, string $rawPath, string $filename, Listing $listing, int $order): ?array
    {
        $contents = $sourceDisk->get($rawPath);

        if (strlen($contents) > self::MAX_FILE_SIZE) {
            Log::warning("Image too large: {$filename}");

            return null;
        }

        $tempPath = $this->createTempFile($contents, $filename);

        try {
            if (! $this->isValidImage($tempPath)) {
                Log::warning("Invalid image file: {$filename}");

                return null;
            }

            $media = $listing->addMedia($tempPath)
                ->usingName(pathinfo($filename, PATHINFO_FILENAME))
                ->usingFileName($this->generateSecureFilename($filename))
                ->withCustomProperties([
                    'original_name' => $filename,
                    'order' => $order,
                    'processed_at' => now()->toISOString(),
                ])
                ->toMediaCollection('images');

            return [
                'id' => $media->id,
                'original' => $media->getUrl(),
                'conversions' => [
                    'thumb' => $media->getUrl('thumb'),
                    'medium' => $media->getUrl('medium'),
                    'large' => $media->getUrl('large'),
                ],
                'media' => $media,
            ];

        } finally {
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
        }
    }

    private function setProfileAvatar(Profile $profile, $media): void
    {
        try {
            $profile->clearMediaCollection('avatar');
            $profile->addMediaFromUrl($media->getUrl())
                ->usingName('avatar')
                ->toMediaCollection('avatar');
        } catch (\Throwable $e) {
            Log::warning('Failed to set profile avatar', [
                'profile_id' => $profile->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function createTempFile(string $contents, string $filename): string
    {
        $tempDir = storage_path('app/'.self::TEMP_DIR);

        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $tempPath = $tempDir.'/'.Str::random(32).'.'.$extension;

        file_put_contents($tempPath, $contents);

        return $tempPath;
    }

    private function generateSecureFilename(string $originalFilename): string
    {
        $extension = pathinfo($originalFilename, PATHINFO_EXTENSION);

        return Str::random(40).'.'.strtolower($extension);
    }

    private function isValidImage(string $path): bool
    {
        if (! file_exists($path) || filesize($path) === 0) {
            return false;
        }

        $imageInfo = @getimagesize($path);
        if ($imageInfo === false) {
            return false;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $path);
        finfo_close($finfo);

        if (! in_array($mimeType, self::ALLOWED_MIMES)) {
            return false;
        }

        if ($imageInfo[0] < 100 || $imageInfo[1] < 100) {
            return false;
        }

        return true;
    }
}
