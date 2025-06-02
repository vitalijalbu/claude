<?php

declare(strict_types=1);

namespace App\Actions\Home;

use App\Actions\Category\IndexCategories;
use App\Actions\Listing\IndexListings;
use App\Actions\Province\IndexProvince;
use App\Models\Geo\City;
use App\Models\Listing;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class IndexHome
{
    private const RECENT_LISTINGS_LIMIT = 12;

    private const TOP_CITIES_LIMIT = 12;

    private const MIN_CITY_LISTINGS = 10;

    private const CACHE_TTL = 300;

    public function __construct(
        private readonly IndexCategories $indexCategories,
        private readonly IndexListings $indexListings,
        private readonly IndexProvince $indexProvinces
    ) {}

    public function handle(): array
    {
        return [
            'categories' => $this->getCategories(),
            'regions' => $this->getProvinces(),
            'listings' => $this->getRecentListings(),
            'cities' => $this->getTopCities(),
        ];
    }

    private function getCategories(): Collection
    {
        return Cache::remember(
            'home.categories',
            self::CACHE_TTL,
            fn () => $this->indexCategories->handle()
        );
    }

    private function getProvinces(): Collection
    {
        return Cache::remember(
            'home.provinces',
            self::CACHE_TTL,
            fn () => $this->indexProvinces->handle()
        );
    }

    private function getRecentListings(): Collection
    {
        return Cache::remember(
            'home.recent_listings',
            self::CACHE_TTL,
            fn () => Listing::query()
                ->with(['media', 'category', 'city', 'profile'])
                ->latest('created_at')
                ->limit(self::RECENT_LISTINGS_LIMIT)
                ->get()
        );
    }

    private function getTopCities(): Collection
    {
        return Cache::remember(
            'home.top_cities',
            self::CACHE_TTL,
            fn () => City::query()
                ->withCount('listings')
                ->having('listings_count', '>', self::MIN_CITY_LISTINGS)
                ->orderBy('listings_count', 'desc')
                ->limit(self::TOP_CITIES_LIMIT)
                ->get()
        );
    }
}
