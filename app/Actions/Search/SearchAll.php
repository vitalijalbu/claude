<?php

declare(strict_types=1);

namespace App\Actions\Search;

use App\Models\Geo\City;
use App\Models\Listing;
use App\Models\Profile;
use Illuminate\Support\Collection;

class SearchAll
{
    public function handle(?string $query): Collection
    {
        if (!$query) {
            return collect([
                ...$this->getFeaturedListings(),
                ...$this->getPopularCities(),
                ...$this->getTopProfiles(),
            ]);
        }

        return collect([
            ...$this->searchListings($query),
            ...$this->searchCities($query),
            ...$this->searchProfiles($query),
        ]);
    }

    private function getFeaturedListings(): array
    {
        return Listing::where('is_featured', true)
            ->with(['city', 'category'])
            ->limit(10)
            ->get()
            ->map(fn($listing) => [
                'id' => $listing->id,
                'label' => $listing->title,
                'type' => 'listing',
                'slug' => $listing->slug,
            ])
            ->toArray();
    }

    private function getPopularCities(): array
    {
        return City::withCount('listings')
            ->having('listings_count', '>', 0)
            ->orderBy('listings_count', 'desc')
            ->limit(10)
            ->get()
            ->map(fn($city) => [
                'id' => $city->id,
                'label' => $city->name,
                'type' => 'city',
                'slug' => $city->slug ?? $city->id,
            ])
            ->toArray();
    }

    private function getTopProfiles(): array
    {
        return Profile::withCount('listings')
            ->having('listings_count', '>', 0)
            ->orderBy('rating', 'desc')
            ->limit(10)
            ->get()
            ->map(fn($profile) => [
                'id' => $profile->id,
                'label' => $profile->name,
                'type' => 'profile',
                'slug' => $profile->phone_number,
            ])
            ->toArray();
    }

    private function searchListings(string $query): array
    {
        return Listing::where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->with(['city', 'category'])
            ->limit(5)
            ->get()
            ->map(fn($listing) => [
                'id' => $listing->id,
                'label' => $listing->title,
                'type' => 'listing',
                'slug' => $listing->slug,
            ])
            ->toArray();
    }

    private function searchCities(string $query): array
    {
        return City::where('name', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn($city) => [
                'id' => $city->id,
                'label' => $city->name,
                'type' => 'city',
                'slug' => $city->slug ?? $city->id,
            ])
            ->toArray();
    }

    private function searchProfiles(string $query): array
    {
        return Profile::where('name', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn($profile) => [
                'id' => $profile->id,
                'label' => $profile->name,
                'type' => 'profile',
                'slug' => $profile->phone_number,
            ])
            ->toArray();
    }
}