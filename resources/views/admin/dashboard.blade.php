@extends('layouts.app')

@section('content')
<div class="bg-white rounded shadow p-6">
    <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>

    <h2 class="text-xl font-semibold">Pending Reservations</h2>
    @php
        $pending = $reservations ?? [
            ['customer' => 'Juan Dela Cruz', 'date' => '2025-10-15', 'time' => '11:30', 'guests' => 20],
            ['customer' => 'Maria Clara', 'date' => '2025-10-16', 'time' => '12:00', 'guests' => 10],
        ];
    @endphp

    <div class="overflow-auto mt-2">
        <table class="min-w-[600px] w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Customer</th>
                    <th class="p-2 text-left">Date</th>
                    <th class="p-2 text-left">Time</th>
                    <th class="p-2 text-left">Guests</th>
                    <th class="p-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($pending as $r)
                <tr class="border-t">
                    <td class="p-2">{{ $r['customer'] }}</td>
                    <td class="p-2">{{ $r['date'] }}</td>
                    <td class="p-2">{{ $r['time'] }}</td>
                    <td class="p-2">{{ $r['guests'] }}</td>
                    <td class="p-2">
                        <form method="POST" action="{{ route('reservations.approve') }}">
                            @csrf
                            <button class="text-green-700 hover:underline">Approve</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <h2 class="text-xl font-semibold mt-6">Inventory Status</h2>
    @php
        $inventory = $inventory ?? [
            ['name' => 'Chicken Breast', 'qty' => 45],
            ['name' => 'Rice (kg)', 'qty' => 30],
            ['name' => 'Softdrinks (bottles)', 'qty' => 80],
        ];
    @endphp
    <ul class="list-disc pl-6">
        @foreach ($inventory as $i)
            <li>{{ $i['name'] }} – {{ $i['qty'] }}</li>
        @endforeach
    </ul>
</div>
@endsection
