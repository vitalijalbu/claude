<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\DTO\Profile\UpdateProfileDTO;
use App\Models\Profile;

class UpdateProfile
{
    public function handle(Profile $profile, UpdateProfileDTO $dto): Profile
    {
        $profile->update($dto->toArray());

        return $profile->fresh();
    }
}
