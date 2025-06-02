<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Models\Profile;

class UpsertProfile
{
    public function handle(array $data): Profile
    {
        return Profile::updateOrCreate(
            ['phone_number' => $data['phone_number']],
            $data
        );
    }
}
