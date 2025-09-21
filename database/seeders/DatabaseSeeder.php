<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
{
    $this->call([
        UserSeeder::class, // if you already have one
        ReservationSeeder::class,
    ]);
}

}
