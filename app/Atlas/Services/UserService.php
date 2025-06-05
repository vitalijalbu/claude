<?php

declare(strict_types=1);

namespace App\Atlas\Services;

use App\Atlas\DTOs\TokenPayload;
use App\Atlas\Enums\RoleEnum;
use App\Atlas\Exceptions\InvalidTokenAtlasException;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function resolveUserFromToken(TokenPayload $tokenPayload)
    {
        if (empty($tokenPayload->sub)) {
            throw new InvalidTokenAtlasException('Atlas token payload does not contain a sub. Cannot resolve memberId.');
        }

        $user = User::where('atlas_member_id', $tokenPayload->sub)->first();

        return $user;
    }

    /**
     * Create User
     *
     *
     * @return User
     */
    public function createUser(array $data, Organization $organization)
    {
        $user = new User([
            'name' => Arr::get($data, 'name'),
            'email' => Arr::get($data, 'email'),
            'atlas_member_id' => Arr::get($data, 'atlas_member_id'),
        ]);

        $user->organization()->associate($organization);
        $user->save();

        if (Arr::has($data, 'role_codes') && is_array(Arr::get($data, 'role_codes'))) {
            $this->syncUserRoles($user, Arr::get($data, 'role_codes'));
        }

        return $user;
    }

    /**
     * Update User
     */
    public function updateUser(User $user, array $data): User
    {
        $user->update([
            'name' => Arr::get($data, 'name'),
            'email' => Arr::get($data, 'email'),
        ]);

        // QUESTION: Can organization be updated? Maybe...

        if (Arr::has($data, 'role_codes') && is_array(Arr::get($data, 'role_codes'))) {
            $this->syncUserRoles($user, Arr::get($data, 'role_codes'));
        }

        return $user;
    }

    /**
     * Sync User Roles according to Atlas.
     *
     * @param  array  $roles  array of roles for the user
     */
    public function syncUserRoles(User $user, array $roles): void
    {
        $validRolesToSync = [];
        foreach ($roles as $roleCode) {
            if (RoleEnum::tryFrom($roleCode) !== null) {
                $validRolesToSync[] = $roleCode;
            }
        }

        if (empty($validRolesToSync)) {
            Log::debug("No roles can be assigned to User {$user->id}");
        } else {
            $user->syncRoles($validRolesToSync);
            Log::debug("User {$user->id} roles synced to: " . implode(', ', $validRolesToSync));
        }
    }
}
