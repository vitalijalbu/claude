<?php

namespace App\Services\Api;

use App\Models\Listing;
use App\Repositories\ListingRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ListingService
{
    protected ListingRepository $repository;

    public function __construct(ListingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findAll(array $filters = []): LengthAwarePaginator
    {
        return $this->repository->findAll($filters);
    }

    public function findBySlug(string $slug): Listing
    {
        return $this->repository->findBySlug($slug);
    }

    public function getSimilar(Listing $listing, int $limit = 12): Collection
    {
        return $this->repository->getSimilarListings($listing, $limit);
    }

    /**
     * Find or create a listing using raw data which gets converted to a DTO internally
     *
     * @param  array  $data  The listing data
     * @param  int  $profileId  The profile ID
     */
    public function updateOrCreate(array $data): Listing
    {
        if (empty($data['title']) || empty($data['profile_id'])) {
            throw new \Exception("Missing repository fields: 'title' and 'profile_id'");
        }

        // Usa i primi 60 caratteri del titolo solo per lo slug iniziale
        $slugBase = Str::slug(Str::limit($data['title'], 60, ''));

        // Usa slug base per il match
        $data['slug'] = $slugBase;

        // Crea o aggiorna il listing
        $listing = $this->repository->updateOrCreate(
            [
                'title' => $data['title'],
                'ref_site' => $data['ref_site'],
            ],
            $data
        );

        // Aggiungi hash dell'ID allo slug e aggiorna se necessario
        $hashedSlug = $slugBase.'-'.substr(md5($listing->id), 0, 8);
        if ($listing->slug !== $hashedSlug) {
            $listing->slug = $hashedSlug;
            $listing->save();
        }

        return $listing;
    }

    public function attachTaxonomies(Listing $listing, array $taxonomyData): void
    {
        $this->repository->attachTaxonomies($listing, $taxonomyData);
    }
}
