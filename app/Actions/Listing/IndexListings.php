<?php

namespace App\Actions\Listing;

use App\DTO\Listing\ListingFilterDTO;
use App\Models\Listing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

    public function handle(?ListingFilterDTO $filters = null): LengthAwarePaginator
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
            ->with($this->defaultIncludes);

        if ($filters) {
            if ($filters->search) {
                $query->where(function ($q) use ($filters) {
                    $q->where('title', 'LIKE', "%{$filters->search}%")
                        ->orWhere('description', 'LIKE', "%{$filters->search}%");
                });
            }

            if ($filters->category_id) {
                $query->where('category_id', $filters->category_id);
            }

            if ($filters->city_id) {
                $query->where('city_id', $filters->city_id);
            }

            if ($filters->is_verified !== null) {
                $query->where('is_verified', $filters->is_verified);
            }

            if ($filters->is_featured !== null) {
                $query->where('is_featured', $filters->is_featured);
            }

            return $query
                ->orderBy($filters->sort, $filters->direction)
                ->paginate($filters->per_page, ['*'], 'page', $filters->page);
        }

        return $query->defaultSort('-created_at')->paginate(25);
    }
}
