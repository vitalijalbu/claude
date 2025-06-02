<?php

declare(strict_types=1);

namespace App\Actions\Home;

use App\Actions\Category\IndexCategories;
use App\Actions\Listing\IndexListings;
use App\Actions\Province\IndexProvince;
use App\DTO\Listing\ListingFilterDTO;
use App\Models\Geo\City;

class IndexHome
{
    public function handle(): array
    {
        $indexCategories = new IndexCategories();
        $indexListings = new IndexListings();
        $indexProvinces = new IndexProvince();

        $categories = $indexCategories->handle();
        
        $featuredListingsFilter = new ListingFilterDTO(
            is_featured: true,
            per_page: 12
        );
        $listings = $indexListings->handle($featuredListingsFilter);
        
        $cities = City::withCount('listings')
            ->having('listings_count', '>', 10)
            ->orderBy('listings_count', 'desc')
            ->limit(12)
            ->get();
            
        $provinces = $indexProvinces->handle();

        return [
            'categories' => $categories,
            'regions' => $provinces,
            'listings' => $listings,
            'cities' => $cities,
        ];
    }
}