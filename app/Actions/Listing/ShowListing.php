<?php

declare(strict_types=1);

namespace App\Actions\Listing;

use App\Models\Listing;

class ShowListing
{
    protected array $includes = [
        'city',
        'category',
        'profile',
        'province',
        'tags.group',
    ];

    public function handle(string $slug): Listing
    {
        return Listing::where('slug', $slug)
            ->with($this->includes)
            ->firstOrFail();
    }
}
