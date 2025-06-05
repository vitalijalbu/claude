<?php

declare(strict_types=1);

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

        // Attach tags if provided
        if ($dto->tags) {
            $attachTags = new AttachTags;
            $attachTags->handle($listing, $dto->tags);
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
