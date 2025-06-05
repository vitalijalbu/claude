<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Category\ShowCategory;
use App\Actions\Geo\ShowCity;
use App\Actions\Listing\AttachTags;
use App\Actions\Listing\UpsertListing;
use App\Actions\Profile\UpsertProfile;
use App\Http\Requests\StoreGrabber;
use App\Jobs\ProcessListingImages;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class GrabberController extends ApiController
{
    public function store(
        StoreGrabber $request,
        ShowCity $findCity,
        ShowCategory $findCategory,
        UpsertProfile $updateOrCreateProfile,
        UpsertListing $updateOrCreateListing,
        AttachTags $attachTags,
    ): JsonResponse {
        $validated = $request->validated();

        if (($validated['is_verified'] ?? false) === false) {
            return response()->json([
                'error' => 'Listing not verified',
            ], 403);
        }

        $response = DB::transaction(function () use (
            $validated,
            $findCity,
            $findCategory,
            $updateOrCreateProfile,
            $updateOrCreateListing,
            $attachTags,
        ) {
            try {
                // Find city and category
                $city = $findCity->handle($validated['city']);
                $category = $findCategory->handle($validated['category']);

                if (!$city || !$category) {
                    return response()->json([
                        'error' => !$city ? 'City not found' : 'Category not found',
                    ], 404);
                }

                // Create or update profile
                $profile = $updateOrCreateProfile->handle([
                    'name' => $validated['phone_number'],
                    'email' => $validated['email'] ?? null,
                    'phone_number' => $validated['phone_number'],
                    'whatsapp_number' => $validated['whatsapp_number'] ?? null,
                    'rating_stats' => null,
                    'bio' => $this->cleanText($validated['description'] ?? ''),
                    'date_birth' => $validated['date_birth'] ?? null,
                    'city_id' => $city->id,
                ]);

                // Create or update listing
                $listing = $updateOrCreateListing->handle([
                    'title' => $this->cleanText($validated['title'] ?? '', 60),
                    'description' => $this->cleanText($validated['description'] ?? ''),
                    'category_id' => $category->id,
                    'city_id' => $city->id,
                    'date_birth' => $validated['date_birth'] ?? null,
                    'slug' => Str::slug($this->cleanText($validated['title'] ?? 'listing', 60)),
                    'profile_id' => $profile->id,
                    'phone_number' => $validated['phone_number'],
                    'whatsapp_number' => $validated['whatsapp_number'] ?? null,
                    'location' => $validated['location'] ?? null,
                    'ref_site' => $validated['ref_site'] ?? null,
                    'is_verified' => $validated['is_verified'] ?? false,
                    'lon' => $this->addRandomOffset($validated['lon'] ?? null),
                    'lat' => $this->addRandomOffset($validated['lat'] ?? null),
                ]);

                // Attach tags
                if (!empty($validated['tags'])) {
                    $attachTags->handle($listing, $validated['tags']);
                }

                // Process media
                if (isset($validated['media']) && !empty($validated['media'])) {
                    $validFiles = $this->validateMediaFiles($validated['media'], $validated['phone_number']);
                    
                    if (!empty($validFiles)) {
                        ProcessListingImages::dispatch($listing->id, $validFiles, $validated['phone_number'])
                            ->onQueue('images');

                        Log::info('Images queued for background processing', [
                            'listing_id' => $listing->id,
                            'phone_number' => $validated['phone_number'],
                            'media_count' => count($validFiles),
                        ]);
                    }
                }

                Log::info('Listing processed successfully', [
                    'listing_id' => $listing->id,
                    'action' => $listing->wasRecentlyCreated ? 'created' : 'updated',
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => $listing->wasRecentlyCreated ? 'Listing created successfully' : 'Listing updated successfully',
                    'data' => $listing->load(['profile', 'category', 'city', 'tags']),
                ]);

            } catch (\Throwable $e) {
                Log::error('Error storing listing', [
                    'phone_number' => $validated['phone_number'] ?? 'unknown',
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return response()->json([
                    'error' => "Internal server error: {$e->getMessage()}",
                ], 500);
            }
        });

        // 🚀 DOPO CHE LA RISPOSTA È PRONTA, AVVIA IL WORKER
        if ($response->getStatusCode() === 200) {
            $this->startQueueWorkerInBackground();
        }

        return $response;
    }

    /**
     * 🎯 AVVIA WORKER IN BACKGROUND DOPO RISPOSTA RIUSCITA
     */
    private function startQueueWorkerInBackground(): void
    {
        try {
            $artisanPath = base_path('artisan');
            $command = "php {$artisanPath} queue:work database --queue=images --stop-when-empty --timeout=300";

            if (PHP_OS_FAMILY === 'Windows') {
                // Windows - avvia in background
                pclose(popen("start /B {$command}", "r"));
            } else {
                // Linux/Mac - avvia in background
                exec("{$command} > /dev/null 2>&1 &");
            }

            Log::info('✅ Queue worker avviato automaticamente in background');

        } catch (\Exception $e) {
            Log::error('❌ Errore nell\'avviare queue worker automatico', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Valida che i file media esistano su S3
     */
    private function validateMediaFiles(array $mediaFiles, string $phoneNumber): array
    {
        $validFiles = [];
        $sourceDir = "raw/{$phoneNumber}";
        $disk = Storage::disk('s3');

        foreach ($mediaFiles as $filename) {
            $sourceFile = "{$sourceDir}/{$filename}";
            
            if ($disk->exists($sourceFile)) {
                $validFiles[] = $filename;
            } else {
                Log::warning("File non trovato su S3: {$sourceFile}");
            }
        }

        return $validFiles;
    }

    private function cleanText(string $text, ?int $limit = null): string
    {
        if (empty($text)) {
            return '';
        }

        $cleaned = Str::of($text)
            ->replaceMatches('/[\p{So}\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}]/u', '');

        return $limit ? $cleaned->limit($limit, '')->toString() : $cleaned->toString();
    }

    private function addRandomOffset(?float $coordinate): ?float
    {
        if ($coordinate === null) {
            return null;
        }

        return $coordinate + random_int(-9, 9) / 100000;
    }

    // Debug S3 method
    public function debugS3(string $phoneNumber): JsonResponse
    {
        $disk = Storage::disk('s3');
        $rawDir = "raw/{$phoneNumber}";
        $mediaDir = "media/{$phoneNumber}";

        try {
            $rawFiles = [];
            try {
                $rawFiles = $disk->files($rawDir);
            } catch (\Exception $e) {
                // Directory non esiste
            }
            
            $mediaFiles = [];
            try {
                $mediaFiles = $disk->files($mediaDir);
            } catch (\Exception $e) {
                // Directory non esiste
            }

            return response()->json([
                'phone_number' => $phoneNumber,
                'raw_directory' => [
                    'path' => $rawDir,
                    'files' => $rawFiles,
                    'count' => count($rawFiles)
                ],
                'media_directory' => [
                    'path' => $mediaDir,
                    'files' => $mediaFiles,
                    'count' => count($mediaFiles)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Altri metodi esistenti...
    public function s3(): JsonResponse
    {
        $disk = Storage::disk('s3');
        $allFiles = $disk->allFiles();
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $images = array_filter($allFiles, function ($file) use ($imageExtensions) {
            return in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $imageExtensions);
        });

        $grouped = [];
        foreach ($images as $image) {
            $pathParts = pathinfo($image);
            $folder = $pathParts['dirname'] === '.' ? '/' : $pathParts['dirname'];
            $grouped[$folder][] = $pathParts['basename'];
        }

        return response()->json($grouped);
    }

    public function s3_clone(string $phoneNumber): JsonResponse
    {
        $disk = Storage::disk('s3');
        $sourceDir = "raw/{$phoneNumber}";
        $targetDir = "media/{$phoneNumber}";
        $files = $disk->files($sourceDir);
        $cloned = [];

        foreach ($files as $file) {
            $filename = basename($file);
            $newPath = "{$targetDir}/{$filename}";

            if (!$disk->exists($newPath)) {
                $disk->copy($file, $newPath);
                $cloned[] = $newPath;
            }
        }

        return response()->json([
            'cloned' => $cloned,
            'count' => count($cloned),
        ]);
    }
}