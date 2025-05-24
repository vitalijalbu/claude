<?php

namespace App\Services\Api;

use App\Repositories\GeoRepository;
use App\Repositories\ListingRepository;
use App\Repositories\ProfileRepository;
use Illuminate\Support\Collection;

class ExploreService
{
    public function __construct(
        protected ListingRepository $listingRepo,
        protected GeoRepository $cityRepo,
        protected ProfileRepository $profileRepo
    ) {}

    public function searchAll(?string $query): Collection
    {
        $listings = $this->listingRepo->search($query);
        $cities = $this->cityRepo->search($query);
        $profiles = $this->profileRepo->search($query);

        return collect()
            ->merge($listings)
            ->merge($cities)
            ->merge($profiles);
    }
}
