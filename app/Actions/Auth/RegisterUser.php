<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DTO\Auth\RegisterDTO;
use App\Enums\UserType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterUser
{
    public function handle(RegisterDTO $dto): User
    {
        $data = $dto->toArray();
        $data['password'] = Hash::make($dto->password);
        $data['type'] = UserType::USER;

        return User::create($data);
    }
}
