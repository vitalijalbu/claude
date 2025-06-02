<?php

namespace App\Actions\Listing;

use App\Models\Listing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexListings
{
    protected array $defaultIncludes = [
        'city',
        'category',
        'profile',
        'province',
    ];

    public function handle(?Request $filters = null): LengthAwarePaginator
    {
        $query = QueryBuilder::for(Listing::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('slug'),
                AllowedFilter::exact('category_id'),
                AllowedFilter::exact('city_id'),
                AllowedFilter::exact('phone_number'),
                AllowedFilter::exact('is_verified'),
                AllowedFilter::exact('is_featured'),
                'title',
                'description',
                'category.slug',
                'city.slug',
                'province.slug',
            ])
            ->allowedSorts(['id', 'title', 'created_at', 'updated_at'])
            ->allowedIncludes($this->defaultIncludes)
            ->with($this->defaultIncludes)
            ->defaultSort('-created_at'); // Fixed: removed duplicate paginate() and moved defaultSort before paginate

        return $query->paginate(25); // Fixed: single paginate call with correct per_page
    }
}
