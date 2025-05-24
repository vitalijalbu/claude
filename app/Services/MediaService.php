<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\Profile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class MediaService
{
    protected $imageManager;

    public function __construct()
    {
        // Initialize Intervention Image with the best available driver
        $this->imageManager = $this->createImageManager();
    }

    /**
     * Create ImageManager with the best available driver
     */
    protected function createImageManager(): ImageManager
    {
        $hasImageMagick = extension_loaded('imagick');
        $hasGD = extension_loaded('gd');

        Log::info("Image processing capabilities", [
            'imagick' => $hasImageMagick,
            'gd' => $hasGD,
            'using' => $hasImageMagick ? 'imagick' : 'gd'
        ]);

        if (!$hasImageMagick && !$hasGD) {
            throw new \Exception("No image processing library available. Install either ImageMagick or GD extension.");
        }

        if ($hasImageMagick) {
            return new ImageManager(new ImagickDriver());
        } else {
            Log::warning("ImageMagick not available, using GD. Consider installing ImageMagick for better image quality.");
            return new ImageManager(new GdDriver());
        }
    }

    /**
     * Process and attach images to a listing from raw files
     *
     * @param  Profile  $profile  Owner profile
     * @param  Listing  $listing  Target listing
     * @param  array  $filenames  Array of filenames to process
     * @return array Processed image data with original and conversion URLs
     */
    public function attachImagesFromLocalRaw(Profile $profile, Listing $listing, array $filenames): array
    {
        // Source is S3, target is local according to your config
        $sourceDisk = Storage::disk(config('glide.disk_source', 's3'));
        $targetDisk = Storage::disk(config('glide.disk_target', 'local'));

        $output = [];
        $sizes = config('glide.sizes');
        $isFirst = true;

        // Create temporary directory for image processing
        $tmpBaseDir = $this->ensureDirectoryExists(config('glide.temp_dir', storage_path('app/tmp')));

        // Create a session-specific working directory to avoid conflicts
        $sessionDir = $this->ensureDirectoryExists("{$tmpBaseDir}/".Str::random(16));

        try {
            foreach ($filenames as $filename) {
                $rawPath = config('glide.s3.raw_path', 'raw')."/{$profile->phone_number}/{$filename}";

                // Skip if file doesn't exist
                if (! $sourceDisk->exists($rawPath)) {
                    Log::warning("Raw image not found: {$rawPath}");
                    continue;
                }

                try {
                    // Get file info
                    $contents = $sourceDisk->get($rawPath);
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);
                    $fileBaseName = pathinfo($filename, PATHINFO_FILENAME);

                    // Define target paths for local storage
                    $mediaBasePath = config('glide.s3.media_path', 'media');
                    $listingDir = "{$mediaBasePath}/{$profile->phone_number}/listings/{$listing->id}";
                    $targetBasePath = "{$listingDir}/{$filename}";
                    $conversionDir = "{$listingDir}/conversions";

                    // Ensure target directories exist in local storage
                    $this->ensureLocalDirectoryExists($targetDisk, $listingDir);
                    $this->ensureLocalDirectoryExists($targetDisk, $conversionDir);

                    // Create a file-specific working directory
                    $workingDir = $this->ensureDirectoryExists("{$sessionDir}/".Str::random(8));

                    // Step 1: Save original image to target local location
                    if (! $targetDisk->exists($targetBasePath)) {
                        $result = $targetDisk->put($targetBasePath, $contents);
                        if (! $result) {
                            Log::error("Failed to save original image locally: {$targetBasePath}");
                            continue;
                        }
                    }

                    // Step 2: Save a local copy for processing (temporary)
                    $tempOriginal = "{$workingDir}/{$filename}";
                    file_put_contents($tempOriginal, $contents);

                    // Validate the image
                    if (! $this->isValidImage($tempOriginal)) {
                        Log::warning("Invalid image file: {$filename}");
                        continue;
                    }

                    // Step 3: Process image conversions
                    $converted = $this->processImageConversions(
                        $targetDisk,
                        $tempOriginal,
                        $fileBaseName,
                        $extension,
                        $conversionDir,
                        $sizes,
                        $workingDir
                    );

                    // Add to output - use local URLs for local disk
                    $output[] = [
                        'original' => $this->getLocalUrl($targetDisk, $targetBasePath),
                        'conversions' => $converted,
                    ];

                    // Set avatar image for the first processed image
                    if ($isFirst) {
                        $avatarPath = "{$mediaBasePath}/{$profile->phone_number}/avatar.{$extension}";
                        $avatarDir = dirname($avatarPath);

                        // Ensure the directory exists
                        $this->ensureLocalDirectoryExists($targetDisk, $avatarDir);

                        if (! $targetDisk->exists($avatarPath)) {
                            $targetDisk->put($avatarPath, $contents);
                        }
                        $isFirst = false;
                    }
                } catch (\Throwable $e) {
                    Log::error("Error processing image: {$filename}", [
                        'exception' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                } finally {
                    // Clean up any file-specific working directory
                    if (isset($workingDir) && is_dir($workingDir)) {
                        $this->cleanupTempDirectory($workingDir);
                    }
                }
            }
        } finally {
            // Clean up the session directory when all processing is done
            if (isset($sessionDir) && is_dir($sessionDir)) {
                $this->cleanupTempDirectory($sessionDir);
            }
        }

        return $output;
    }

    /**
     * Process all image conversions for a single image
     *
     * @param  \Illuminate\Contracts\Filesystem\Filesystem  $targetDisk  Target disk instance
     * @param  string  $tempOriginal  Path to temporary original file
     * @param  string  $fileBaseName  Original filename without extension
     * @param  string  $extension  File extension
     * @param  string  $conversionDir  Directory for conversions in local storage
     * @param  array  $sizes  Configuration for image sizes
     * @param  string  $workingDir  Temporary working directory
     * @return array Converted image URLs
     */
    protected function processImageConversions($targetDisk, string $tempOriginal, string $fileBaseName, string $extension, string $conversionDir, array $sizes, string $workingDir): array
    {
        $converted = [];
        $tmpConversionDir = $this->ensureDirectoryExists("{$workingDir}/conversions");

        // Get format settings from config
        $format = strtolower(config('glide.format', 'jpg'));
        $quality = config('glide.quality', 90);
        $fit = config('glide.fit', 'crop');

        foreach ($sizes as $size => $params) {
            try {
                // Use consistent file naming for the converted images
                $convertedFilename = "{$size}_{$fileBaseName}.{$format}";
                $localConversionPath = "{$tmpConversionDir}/{$convertedFilename}";
                $targetConversionPath = "{$conversionDir}/{$convertedFilename}";

                // Check if conversion already exists in local storage
                if ($targetDisk->exists($targetConversionPath)) {
                    $converted[$size] = $this->getLocalUrl($targetDisk, $targetConversionPath);
                    continue;
                }

                // Create the conversion using Intervention Image
                $this->createImageConversion($tempOriginal, $localConversionPath, [
                    'width' => $params['w'] ?? null,
                    'height' => $params['h'] ?? null,
                    'fit' => $fit,
                    'quality' => $quality,
                    'format' => $format,
                ]);

                // Verify the conversion was successful
                if (! file_exists($localConversionPath) || filesize($localConversionPath) === 0) {
                    throw new \Exception("Failed to generate conversion: {$convertedFilename}");
                }

                // Additional validation of the converted image
                $imageInfo = @getimagesize($localConversionPath);
                if ($imageInfo === false) {
                    throw new \Exception("Generated image is not valid: {$convertedFilename}");
                }

                // Save to local target disk
                $fileContents = file_get_contents($localConversionPath);
                $result = $targetDisk->put($targetConversionPath, $fileContents);

                if (! $result) {
                    throw new \Exception("Failed to save conversion locally: {$targetConversionPath}");
                }

                // Store the URL
                $converted[$size] = $this->getLocalUrl($targetDisk, $targetConversionPath);

                Log::info("Successfully created conversion: {$size}_{$fileBaseName}.{$format}", [
                    'size' => filesize($localConversionPath),
                    'dimensions' => "{$imageInfo[0]}x{$imageInfo[1]}"
                ]);
            } catch (\Throwable $e) {
                Log::error("Failed to convert image {$size}: {$e->getMessage()}", [
                    'size' => $size,
                    'filename' => $fileBaseName,
                    'params' => $params,
                ]);
            }
        }

        return $converted;
    }

    /**
     * Create image conversion using Intervention Image
     *
     * @param  string  $sourcePath  Source image path
     * @param  string  $outputPath  Output path for converted image
     * @param  array  $params  Conversion parameters
     */
    protected function createImageConversion(string $sourcePath, string $outputPath, array $params): void
    {
        if (! file_exists($sourcePath)) {
            throw new \Exception("Source file does not exist: {$sourcePath}");
        }

        // Validate source image before processing
        $sourceImageInfo = @getimagesize($sourcePath);
        if ($sourceImageInfo === false) {
            throw new \Exception("Source file is not a valid image: {$sourcePath}");
        }

        Log::debug("Converting image with Intervention Image", [
            'source' => basename($sourcePath),
            'source_size' => filesize($sourcePath),
            'dimensions' => "{$sourceImageInfo[0]}x{$sourceImageInfo[1]}",
            'params' => $params,
        ]);

        try {
            // Load the image
            $image = $this->imageManager->read($sourcePath);

            $width = $params['width'] ?? null;
            $height = $params['height'] ?? null;
            $fit = $params['fit'] ?? 'crop';

            // Apply transformations
            if ($width && $height) {
                switch ($fit) {
                    case 'crop':
                        // Crop to exact dimensions
                        $image->cover($width, $height);
                        break;
                    case 'contain':
                        // Resize maintaining aspect ratio, fit within bounds
                        $image->scale($width, $height);
                        break;
                    case 'fill':
                        // Resize to exact dimensions (may distort)
                        $image->resize($width, $height);
                        break;
                    default:
                        // Default to crop
                        $image->cover($width, $height);
                        break;
                }
            } elseif ($width) {
                // Resize by width, maintain aspect ratio
                $image->scaleDown($width);
            } elseif ($height) {
                // Resize by height, maintain aspect ratio
                $image->scaleDown(height: $height);
            }

            // Get format and quality
            $format = $params['format'] ?? 'jpg';
            $quality = $params['quality'] ?? 90;

            // Save the image
            $encodedImage = match($format) {
                'jpg', 'jpeg' => $image->toJpeg($quality),
                'png' => $image->toPng(),
                'webp' => $image->toWebp($quality),
                'gif' => $image->toGif(),
                default => $image->toJpeg($quality),
            };

            // Write to file
            file_put_contents($outputPath, $encodedImage);

            // Verify the file was created successfully
            if (! file_exists($outputPath) || filesize($outputPath) === 0) {
                throw new \Exception('Failed to save converted image: empty or missing file');
            }

            $outputSize = filesize($outputPath);
            $convertedImageInfo = @getimagesize($outputPath);

            Log::debug('Image conversion complete', [
                'output' => basename($outputPath),
                'size' => $outputSize,
                'dimensions' => $convertedImageInfo ? "{$convertedImageInfo[0]}x{$convertedImageInfo[1]}" : 'unknown',
            ]);

        } catch (\Throwable $e) {
            Log::error("Intervention Image conversion error: {$e->getMessage()}", [
                'source' => $sourcePath,
                'target' => $outputPath,
                'params' => $params,
                'source_exists' => file_exists($sourcePath),
                'source_size' => file_exists($sourcePath) ? filesize($sourcePath) : 0,
            ]);
            
            // Clean up partial file if it exists
            if (file_exists($outputPath)) {
                @unlink($outputPath);
            }
            
            throw $e;
        }
    }

    /**
     * Ensure a directory exists, creating it if necessary
     *
     * @param  string  $path  Directory path
     * @return string The directory path
     */
    protected function ensureDirectoryExists(string $path): string
    {
        if (! is_dir($path)) {
            if (! mkdir($path, 0755, true) && ! is_dir($path)) {
                throw new \Exception("Failed to create directory: {$path}");
            }
        }

        return $path;
    }

    /**
     * Ensure a directory exists in local storage
     *
     * @param  \Illuminate\Contracts\Filesystem\Filesystem  $disk  Local disk instance
     * @param  string  $directory  Directory path
     */
    protected function ensureLocalDirectoryExists($disk, string $directory): void
    {
        try {
            // For local disk, we need to create the actual directory structure
            $fullPath = $disk->path($directory);
            $this->ensureDirectoryExists(dirname($fullPath));
        } catch (\Throwable $e) {
            Log::warning("Failed to create directory locally: {$directory}", [
                'exception' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get URL for local disk files
     *
     * @param  \Illuminate\Contracts\Filesystem\Filesystem  $disk
     * @param  string  $path
     * @return string
     */
    protected function getLocalUrl($disk, string $path): string
    {
        // For local disk, we need to generate proper URLs
        // This assumes you have a route or public symlink set up for local storage
        try {
            if (method_exists($disk, 'url')) {
                return $disk->url($path);
            }
            
            // Fallback: construct URL manually
            $baseUrl = config('app.url');
            return "{$baseUrl}/storage/{$path}";
        } catch (\Throwable $e) {
            Log::warning("Failed to generate URL for local file: {$path}", [
                'exception' => $e->getMessage(),
            ]);
            return $path; // Return path as fallback
        }
    }

    /**
     * Helper method to clean up a temporary directory
     *
     * @param  string  $directory  Path to directory
     */
    protected function cleanupTempDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            return;
        }

        try {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($files as $fileinfo) {
                $action = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                @$action($fileinfo->getRealPath());
            }

            @rmdir($directory);
        } catch (\Throwable $e) {
            Log::warning("Failed to clean up directory {$directory}: {$e->getMessage()}");
        }
    }

    /**
     * Check if a file is a valid image
     *
     * @param  string  $path  Path to the image file
     * @return bool Whether the file is a valid image
     */
    protected function isValidImage(string $path): bool
    {
        if (! file_exists($path) || filesize($path) === 0) {
            return false;
        }

        try {
            // Use getimagesize to validate image
            $imageInfo = @getimagesize($path);

            // If getimagesize returns false or doesn't return expected array
            if ($imageInfo === false || ! isset($imageInfo[0]) || ! isset($imageInfo[1])) {
                return false;
            }

            // Ensure the image has reasonable dimensions
            if ($imageInfo[0] <= 0 || $imageInfo[1] <= 0) {
                return false;
            }

            // Check for supported image types
            $supportedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP];
            if (! in_array($imageInfo[2], $supportedTypes)) {
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning("Invalid image file: {$path} - {$e->getMessage()}");
            return false;
        }
    }
}