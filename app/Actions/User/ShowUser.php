<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Http\Resources\UserResource;
use App\Models\User;

class ShowUser
{
    public function execute(User $user): UserResource
    {
        $user->load(['organization']);

        return new UserResource($user);
    }
}
