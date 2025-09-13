

@extends('layouts.sidebar') {{-- Use your sidebar layout --}}

@section('content')
<div class="bg-white rounded shadow p-6">
    <h1 class="text-2xl font-bold mb-4">Menu</h1>

    {{-- Show sample menu items for now if none passed --}}
    @php
        $menu = $menuItems ?? [
            ['name' => 'Chicken Meal', 'description' => 'Rice + chicken + drink', 'price' => 120],
            ['name' => 'Pasta Plate', 'description' => 'Creamy pasta + bread', 'price' => 95],
        ];
    @endphp

    <div class="grid sm:grid-cols-2 gap-4">
        @foreach ($menu as $item)
            <div class="border rounded p-4">
                <h2 class="font-semibold">{{ $item['name'] }}</h2>
                <p class="text-sm text-gray-600">{{ $item['description'] }}</p>
                <p class="mt-1 font-bold">₱{{ $item['price'] }}</p>
            </div>
        @endforeach
    </div>

    <h2 class="text-xl font-bold mt-8">Reservation Form</h2>
    <form method="POST" action="{{ route('reservations.store') }}" class="mt-4 grid gap-3 max-w-md">
        @csrf
        <label class="block">
            <span class="text-sm">Date</span>
            <input type="date" name="date" required class="mt-1 w-full border rounded p-2">
        </label>
        <label class="block">
            <span class="text-sm">Time</span>
            <input type="time" name="time" required class="mt-1 w-full border rounded p-2">
        </label>
        <label class="block">
            <span class="text-sm">Number of Guests</span>
            <input type="number" name="guests" min="1" required class="mt-1 w-full border rounded p-2">
        </label>
        <button class="bg-blue-600 text-white px-4 py-2 rounded w-fit">Reserve</button>
    </form>
</div>
@endsection
