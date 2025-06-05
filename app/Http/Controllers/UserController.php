<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\User\ShowUser;
use App\Http\Resources\UserResource;
use App\Models\User;

final class UserController extends Controller
{
    /**
     * Display a specified user.
     */
    public function show(User $user, ShowUser $action): UserResource
    {

        return $action->execute($user);
    }
}
