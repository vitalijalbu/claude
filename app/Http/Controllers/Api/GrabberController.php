<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreGrabber;
use App\Services\Api\CategoryService;
use App\Services\Api\GeoService;
use App\Services\Api\ListingService;
use App\Services\Api\ProfileService;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class GrabberController extends ApiController
{
    protected ListingService $listingService;

    protected ProfileService $profileService;

    protected GeoService $geoService;

    protected MediaService $mediaService;

    protected CategoryService $categoryService;

    public function __construct(
        ListingService $listingService,
        ProfileService $profileService,
        GeoService $geoService,
        MediaService $mediaService,
        CategoryService $categoryService,
    ) {
        $this->listingService = $listingService;
        $this->profileService = $profileService;
        $this->geoService = $geoService;
        $this->mediaService = $mediaService;
        $this->categoryService = $categoryService;
    }

    public function store(StoreGrabber $request): JsonResponse
    {
        $validated = $request->validated();

        if ($validated['is_verified'] === false) {
            return response()->json([
                'error' => 'Listing not verified',
            ], 403);
        }

        try {
            // Trova città
            $city = $this->geoService->findCity($validated['city']);
            if (! $city) {
                Log::error('City not found', ['city' => $validated['city']]);

                return response()->json(['error' => 'City not found'], 404);
            }

            // Trova categoria
            $category = $this->categoryService->findOne($validated['category']);
            if (! $category) {
                Log::error('Category not found', ['category' => $validated['category']]);

                return response()->json(['error' => 'Category not found'], 404);
            }

            // Profile Data
            $profileData = [
                'name' => $validated['phone_number'],
                'email' => $validated['email'] ?? null,
                'phone_number' => $validated['phone_number'],
                'whatsapp_number' => $validated['whatsapp_number'],
                'rating' => $validated['rating'] ?? null,
                'bio' => Str::of($validated['description'])
                    ->replaceMatches('/[\p{So}\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}]/u', ''),
                'date_birth' => $validated['date_birth'] ?? null,
                'city_id' => $city->id,
            ];

            $profile = $this->profileService->updateOrCreate($profileData);

            $formattedTitle = Str::of($validated['title'])
                ->replaceMatches('/[\p{So}\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}]/u', '')
                ->limit(60, '');
            // Listing Data
            $listingData = [
                'title' => $formattedTitle,
                'description' => Str::of($validated['description'])
                    ->replaceMatches('/[\p{So}\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}]/u', ''),
                'category_id' => $category->id,
                'city_id' => $city->id,
                'date_birth' => $validated['date_birth'] ?? null,
                'slug' => Str::slug($formattedTitle),
                'profile_id' => $profile->id,
                'phone_number' => $validated['phone_number'],
                'whatsapp_number' => $validated['whatsapp_number'],
                'location' => $validated['location'] ?? null,
                'media' => $validated['media'] ?? null,
                'ref_site' => $validated['ref_site'] ?? null,
                'is_verified' => $validated['is_verified'] ?? false,
                'lon' => isset($validated['lon'])
                    ? $validated['lon'] + random_int(-9, 9) / 100000
                    : null,
                'lat' => isset($validated['lat'])
                ? $validated['lat'] + random_int(-9, 9) / 100000
                : null,
            ];

            if (empty($listingData['title']) || empty($listingData['profile_id'])) {
                return response()->json([
                    'error' => "Missing controller fields: 'title' and 'profile_id'",
                ], 400);
            }

            // Check if listing is new or updated
            $listing = $this->listingService->updateOrCreate($listingData);

            if (! empty($validated['taxonomies'])) {
                $this->listingService->attachTaxonomies($listing, $validated['taxonomies']);
            }

            // Media
            if (! empty($validated['media'])) {
                $this->mediaService->attachImagesFromLocalRaw($profile, $listing, $validated['media']);

                // // Set first image as avatar
                // $firstImage = $validated['media'][0] ?? null;

                // if ($firstImage) {
                //     $profile->clearMediaCollection('avatar');

                //     $avatarPath = storage_path("media/{$profile->phone_number}/{$firstImage}");

                //     if (File::exists($avatarPath)) {
                //         $profile->addMedia($avatarPath)
                //             ->toMediaCollection('avatar');
                //     }
                // }
            }

            // Determine success message based on whether it's a new or updated listing
            $message = $listing->wasRecentlyCreated ? 'Listing created successfully' : 'Listing updated successfully';

            // Log the result
            Log::info('Listing action', [
                'listing_id' => $listing->id,
                'status' => $message,
                'action' => $listing->wasRecentlyCreated ? 'created' : 'updated',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'data' => $listing->load([
                    'profile',
                    'category',
                    'city',
                    'taxonomies',
                ]),
            ]);
        } catch (\Throwable $e) {
            // Log the error
            Log::error('Error storing listing', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => "Internal server error: {$e->getMessage()}",
            ], 500);
        }
    }

    // Grabber S3
    public function s3(): JsonResponse
    {
        $disk = Storage::disk('s3');

        // Recupera ricorsivamente tutti i file
        $allFiles = $disk->allFiles();

        // Filtra solo immagini (opzionale)
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $images = array_filter($allFiles, function ($file) use ($imageExtensions) {
            return in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $imageExtensions);
        });

        // Organizza per cartelle
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

        // Percorsi
        $sourceDir = "raw/{$phoneNumber}";
        $targetDir = "media/{$phoneNumber}";

        // Recupera i file da raw/{phoneNumber}
        $files = $disk->files($sourceDir);

        $cloned = [];

        foreach ($files as $file) {
            $filename = basename($file);
            $newPath = "{$targetDir}/{$filename}";

            // Clona solo se il file non esiste già nella destinazione
            if (! $disk->exists($newPath)) {
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
