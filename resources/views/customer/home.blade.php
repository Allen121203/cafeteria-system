

@extends('layouts.sidebar') {{-- Use your sidebar layout --}}

@section('content')
<div class="bg-white rounded shadow p-6">
    <h1 class="text-2xl font-bold mb-4">Available Menus</h1>

    <div class="grid sm:grid-cols-2 gap-4">
        @forelse ($menus as $menu)
            <div class="border rounded p-4">
                <h2 class="font-semibold">{{ $menu->name }}</h2>
                <p class="text-sm text-gray-600">{{ $menu->description ?? 'No description' }}</p>
                <p class="mt-1 font-bold">₱{{ $menu->price }}</p>
                <p class="text-sm">Meal Time: {{ ucfirst($menu->meal_time) }}</p>
                <p class="text-sm">Items: {{ $menu->items->pluck('name')->join(', ') }}</p>
            </div>
        @empty
            <p>No menus available.</p>
        @endforelse
    </div>

    <h2 class="text-xl font-bold mt-8">Make a Reservation</h2>
    <form method="POST" action="{{ route('reservations.store') }}" class="mt-4 grid gap-3 max-w-md">
        @csrf
        <label class="block">
            <span class="text-sm">Select Menu</span>
            <select name="menu_id" required class="mt-1 w-full border rounded p-2">
                <option value="">Choose a menu</option>
                @foreach ($menus as $menu)
                    <option value="{{ $menu->id }}">{{ $menu->name }} - ₱{{ $menu->price }}</option>
                @endforeach
            </select>
        </label>
        <label class="block">
            <span class="text-sm">Quantity</span>
            <input type="number" name="quantity" min="1" required class="mt-1 w-full border rounded p-2">
        </label>
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
