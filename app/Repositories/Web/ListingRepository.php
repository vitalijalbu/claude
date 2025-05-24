<?php

namespace App\Repositories\Web;

use App\Models\Listing;
use App\Models\Taxonomy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ListingRepository
{
    protected array $with = [
        'city',
        'category',
        'profile',
        'taxonomies',
    ];

    public function findAll(array $params = []): LengthAwarePaginator
    {
        $params['per_page'] = $params['per_page'] ?? 50;

        return QueryBuilder::for(Listing::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('slug'),
                AllowedFilter::exact('title'),
                AllowedFilter::exact('phone_number'),
                AllowedFilter::exact('whatsapp_number'),
                AllowedFilter::exact('category.slug'),
                AllowedFilter::exact('city.slug'),
                AllowedFilter::exact('profile.id'),
                AllowedFilter::exact('profile.phone_number'),
            ])
            ->allowedSorts(['id', 'title', 'price', 'created_at', 'updated_at'])
            ->defaultSort('-created_at')
            ->with($this->with)
            ->paginate($params['per_page'] ?? 50)
            ->appends(request()->query());
    }

    // Find listing by slug
    public function findBySlug(string $slug): ?Listing
    {
        return Listing::where(['slug' => $slug])
            ->with($this->with)
            ->firstOrFail();
    }

    public function getSimilarListings(Listing $listing, int $limit = 10): Collection
    {
        return Listing::where('category_id', $listing->category_id)
            ->where('id', '!=', $listing->id)
            ->with($this->with)
            ->limit($limit)
            ->get();
    }

    public function updateOrCreate(array $match, array $data)
    {
        // dd($match, $data);
        return Listing::updateOrCreate($match, $data);
    }

    public function search(?string $query)
    {
        return QueryBuilder::for(Listing::class)
            ->allowedFilters(['title', 'description'])
            ->when($query, fn ($q) => $q->where('title', 'LIKE', "%{$query}%"))
            ->limit(10)
            ->get()
            ->each->setAttribute('type', 'listing');
    }

    // Attach taxonomies to a listing
    public function attachTaxonomies(Listing $listing, array $taxonomies): void
    {
        $taxonomyIds = [];

        // Collect all taxonomy values (slugs) to search for in one go
        $taxonomySlugs = array_column($taxonomies, 'value');

        // Fetch all taxonomies that match the slugs in the request
        $taxonomiesFromDb = Taxonomy::whereIn('slug', $taxonomySlugs)->get();

        // Map taxonomies to their ids for easy reference
        $taxonomiesMap = $taxonomiesFromDb->keyBy('slug');

        foreach ($taxonomies as $item) {
            $taxonomyValue = $item['value'];

            // If taxonomy exists in the database, add its ID to the list
            if (isset($taxonomiesMap[$taxonomyValue])) {
                $taxonomyIds[] = $taxonomiesMap[$taxonomyValue]->id;
            } else {
                // Log if the taxonomy is not found in the database
                Log::warning("Taxonomy not found: {$taxonomyValue}");
            }
        }

        // Debugging: log the taxonomy IDs before sync
        // Log::info('Taxonomy IDs: '.implode(', ', $taxonomyIds));

        if (! empty($taxonomyIds)) {
            // Sync the taxonomies for the listing
            $listing->taxonomies()->sync($taxonomyIds);
        } else {
            // Log if no valid taxonomies were found to sync
            Log::warning('No taxonomies found to sync.');
        }
    }
}
