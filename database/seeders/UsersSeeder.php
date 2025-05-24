<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (UserType::cases() as $userType) {
            Role::firstOrCreate(['name' => $userType->value]);
        }

        // Crea un admin
        $admin = User::factory()->create([
            'first_name' => 'admin',
            'email' => 'admin@localhost',
            'password' => Hash::make('Password1'),
        ]);
        $admin->assignRole(UserType::ADMIN->value);

        // Crea un demo user
        $demoUser = User::factory()->create([
            'first_name' => 'user',
            'email' => 'user@localhost',
            'password' => Hash::make('Password1'),
        ]);
        $demoUser->assignRole(UserType::USER->value);

        // Crea un advertiser user
        $advertiser = User::factory()->create([
            'first_name' => 'advertiser',
            'email' => 'advertiser@localhost',
            'password' => Hash::make('Password1'),
        ]);
        $advertiser->assignRole(UserType::ADVERTISER->value);

        // Crea un super admin
        $superAdmin = User::factory()->create([
            'first_name' => 'super',
            'email' => 'super@localhost',
            'password' => Hash::make('Password1'),
        ]);
        $superAdmin->assignRole(UserType::SUPER_ADMIN->value);
    }
}
