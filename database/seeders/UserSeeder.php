<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create a default superadmin user
        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'], // change email if you want
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'), // change password if you want
            ]
        );

        // Assign superadmin role
        if (! $user->hasRole('superadmin')) {
            $user->assignRole('superadmin');
        }
    }
}
