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

        // Attach tags if provided
        if ($dto->tags) {
            $attachTags = new AttachTags;
            $attachTags->handle($listing, $dto->tags);
        }

        return $listing->fresh()->load(['city', 'category', 'profile']);
    }
}
