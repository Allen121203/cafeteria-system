<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->take(5)->get();

        if ($customers->isEmpty()) {
            $this->command->warn('⚠ No customers found. Please seed users first.');
            return;
        }

        $sampleData = [
            ['guests' => 10, 'date' => '2025-09-15', 'time' => '12:00:00', 'status' => 'approved'],
            ['guests' => 20, 'date' => '2025-09-18', 'time' => '14:00:00', 'status' => 'pending'],
            ['guests' => 15, 'date' => '2025-09-20', 'time' => '18:30:00', 'status' => 'declined'],
            ['guests' => 8,  'date' => '2025-09-22', 'time' => '10:15:00', 'status' => 'approved'],
            ['guests' => 25, 'date' => '2025-09-25', 'time' => '20:00:00', 'status' => 'pending'],
        ];

        foreach ($customers as $index => $customer) {
            Reservation::create([
            'user_id' => 2,
            'event_name' => 'Sample Event', // Added missing required field
            'date' => '2025-09-15',
            'time' => '12:00:00',
            'guests' => 10,
            'status' => 'approved',
            'created_at' => '2025-10-22 18:12:47',
            'updated_at' => '2025-10-22 18:12:47'
        ]);
        }
    }
}
