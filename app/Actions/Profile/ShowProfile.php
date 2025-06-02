<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Models\Profile;

class ShowProfile
{
    public function handle(string $phoneNumber): Profile
    {
        return Profile::where('phone_number', $phoneNumber)
            ->withCount('listings')
            ->with([
                'listings' => function ($query) {
                    $query->latest()->take(5);
                },
                'taxonomies',
                'city',
                'province',
                'category',
            ])
            ->firstOrFail();
    }
}
