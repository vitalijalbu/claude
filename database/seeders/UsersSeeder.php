<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Crea i ruoli
        foreach (UserType::cases() as $userType) {
            Role::firstOrCreate(['name' => $userType->value]);
        }

        $users = [
            [
                'first_name' => 'admin',
                'email' => 'admin@localhost',
                'role' => UserType::ADMIN->value,
            ],
            [
                'first_name' => 'user',
                'email' => 'user@localhost',
                'role' => UserType::USER->value,
            ],
            [
                'first_name' => 'advertiser',
                'email' => 'advertiser@localhost',
                'role' => UserType::ADVERTISER->value,
            ],
            [
                'first_name' => 'super',
                'email' => 'super@localhost',
                'role' => UserType::SUPER_ADMIN->value,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'first_name' => $userData['first_name'],
                    'password' => Hash::make('Password1'),
                ]
            );

            if (! $user->hasRole($userData['role'])) {
                $user->assignRole($userData['role']);
            }
        }
    }
}
