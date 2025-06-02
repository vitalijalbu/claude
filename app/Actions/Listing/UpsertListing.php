<?php

declare(strict_types=1);

namespace App\Actions\Listing;

use App\Models\Listing;

class UpsertListing
{
    public function handle(array $data): Listing
    {
        return Listing::updateOrCreate(
            [
                'phone_number' => $data['phone_number'],
                'ref_site' => $data['ref_site'] ?? null,
            ],
            $data
        );
    }
}
