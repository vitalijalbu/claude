<?php

declare(strict_types=1);

namespace App\Actions\Listing;

use App\DTO\Listing\UpdateListingDTO;
use App\Models\Listing;

class UpdateListing
{
    public function handle(Listing $listing, UpdateListingDTO $dto): Listing
    {
        $listing->update($dto->toArray());

        // Attach taxonomies if provided
        if ($dto->taxonomies) {
            $attachTaxonomies = new AttachTaxonomies;
            $attachTaxonomies->handle($listing, $dto->taxonomies);
        }

        return $listing->fresh()->load(['city', 'category', 'profile']);
    }
}
