<?php

namespace App\Http\Controllers\Api;

use App\Actions\Province\IndexProvince;
use App\Http\Resources\Api\CategoryResource;
use App\Http\Resources\Api\CityResource;
use App\Http\Resources\Api\ListingResource;
use App\Repositories\CategoryRepository;
use App\Repositories\CityRepository;
use App\Repositories\ListingRepository;

class HomeController extends ApiController
{
    protected CategoryRepository $categoryRepo;

    protected ListingRepository $listingRepo;

    protected CityRepository $cityRepo;

    public function __construct(CategoryRepository $categoryRepo, ListingRepository $listingRepo, CityRepository $cityRepo)
    {
        $this->categoryRepo = $categoryRepo;
        $this->listingRepo = $listingRepo;
        $this->cityRepo = $cityRepo;

    }

    public function index(IndexProvince $indexProvince)
    {
        $categories = $this->categoryRepo->findAll();
        $listings = $this->listingRepo->findAll(['is_featured' => true], 12);
        $cities = $this->cityRepo->findSpotlight();
        $provinces = $indexProvince->handle(); // oppure featuredProvinces()

        return [
            'categories' => CategoryResource::collection($categories),
            'regions' => $provinces,
            'listings' => ListingResource::collection($listings),
            'cities' => CityResource::collection($cities),
        ];
    }
}
