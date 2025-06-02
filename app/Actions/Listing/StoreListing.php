<?php

namespace App\Actions\Listing;

use App\DTO\Listing\ListingDTO;
use App\Models\Listing;
use Illuminate\Support\Str;

class StoreListing
{
    public function handle(ListingDTO $dto): Listing
    {
        $data = $dto->toArray();

        // Generate unique slug if needed
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($dto->title);
        }

        $listing = Listing::create($data);

        // Attach taxonomies if provided
        if ($dto->taxonomies) {
            $attachTaxonomies = new AttachTaxonomies;
            $attachTaxonomies->handle($listing, $dto->taxonomies);
        }

        return $listing->load(['city', 'category', 'profile']);
    }

    private function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (Listing::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
