<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'       => 'Super Admin',
            'email'      => 'superadmin@example.com',
            'password'   => Hash::make('password123'), // change later
            'role'       => 'superadmin',
            'address'    => '',
            'contact_no' => '09123456789',
            'department' => '',
        ]);
    }
}
