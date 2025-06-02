<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Category\ShowCategory;
use App\Actions\Geo\ShowCity;
use App\Actions\Listing\AttachTaxonomies;
use App\Actions\Listing\UpdateOrCreateListing;
use App\Actions\Listing\UpsertListing;
use App\Actions\Profile\UpdateOrCreateProfile;
use App\Actions\Profile\UpsertProfile;
use App\Http\Requests\StoreGrabber;
use App\Services\OptimizedMediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class GrabberController extends ApiController
{
    public function store(
        StoreGrabber $request,
        ShowCity $findCity,
        ShowCategory $findCategory,
        UpsertProfile $updateOrCreateProfile,
        UpsertListing $updateOrCreateListing,
        AttachTaxonomies $attachTaxonomies,
        OptimizedMediaService $mediaService
    ): JsonResponse {
        $validated = $request->validated();

        if ($validated['is_verified'] === false) {
            return response()->json([
                'error' => 'Listing not verified',
            ], 403);
        }

        return DB::transaction(function () use (
            $validated,
            $findCity,
            $findCategory,
            $updateOrCreateProfile,
            $updateOrCreateListing,
            $attachTaxonomies,
            $mediaService
        ) {
            try {
                // Find city and category
                $city = $findCity->handle($validated['city']);
                $category = $findCategory->handle($validated['category']);

                if (! $city || ! $category) {
                    return response()->json([
                        'error' => ! $city ? 'City not found' : 'Category not found',
                    ], 404);
                }

                // Create or update profile
                $profile = $updateOrCreateProfile->handle([
                    'name' => $validated['phone_number'],
                    'email' => $validated['email'] ?? null,
                    'phone_number' => $validated['phone_number'],
                    'whatsapp_number' => $validated['whatsapp_number'],
                    'rating' => $validated['rating'] ?? null,
                    'bio' => $this->cleanText($validated['description']),
                    'date_birth' => $validated['date_birth'] ?? null,
                    'city_id' => $city->id,
                ]);

                // Create or update listing
                $listing = $updateOrCreateListing->handle([
                    'title' => $this->cleanText($validated['title'], 60),
                    'description' => $this->cleanText($validated['description']),
                    'category_id' => $category->id,
                    'city_id' => $city->id,
                    'date_birth' => $validated['date_birth'] ?? null,
                    'slug' => Str::slug($this->cleanText($validated['title'], 60)),
                    'profile_id' => $profile->id,
                    'phone_number' => $validated['phone_number'],
                    'whatsapp_number' => $validated['whatsapp_number'],
                    'location' => $validated['location'] ?? null,
                    'ref_site' => $validated['ref_site'] ?? null,
                    'is_verified' => $validated['is_verified'] ?? false,
                    'lon' => $this->addRandomOffset($validated['lon'] ?? null),
                    'lat' => $this->addRandomOffset($validated['lat'] ?? null),
                ]);

                // Attach taxonomies
                if (! empty($validated['taxonomies'])) {
                    $attachTaxonomies->handle($listing, $validated['taxonomies']);
                }

                // Process media
                if (! empty($validated['media'])) {
                    $mediaService->attachImagesFromLocalRaw($profile, $listing, $validated['media']);
                }

                Log::info('Listing processed successfully', [
                    'listing_id' => $listing->id,
                    'action' => $listing->wasRecentlyCreated ? 'created' : 'updated',
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => $listing->wasRecentlyCreated ? 'Listing created successfully' : 'Listing updated successfully',
                    'data' => $listing->load(['profile', 'category', 'city', 'taxonomies']),
                ]);

            } catch (\Throwable $e) {
                Log::error('Error storing listing', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return response()->json([
                    'error' => "Internal server error: {$e->getMessage()}",
                ], 500);
            }
        });
    }

    private function cleanText(string $text, ?int $limit = null): string
    {
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
}
