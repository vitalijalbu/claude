<?php

namespace App\Actions\Profile;

use App\DTO\Profile\ProfileDTO;
use App\Models\Profile;

class StoreProfile
{
    public function handle(ProfileDTO $dto): Profile
    {
        return Profile::updateOrCreate(
            ['phone_number' => $dto->phone_number],
            $dto->toArray()
        );
    }
}
