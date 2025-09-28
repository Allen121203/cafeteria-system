

@extends('layouts.sidebar')

@section('content')
<div class="space-y-8">
    <!-- Available Menus -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center mb-6">
            <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h1 class="text-2xl font-bold text-gray-900">Available Menus</h1>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($menus as $menu)
                <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-md border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-start justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-900">{{ $menu->name }}</h2>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">₱{{ $menu->price }}</span>
                    </div>
                    <p class="text-gray-600 mb-4">{{ $menu->description ?? 'Delicious meal prepared with fresh ingredients' }}</p>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ ucfirst($menu->meal_time) }}
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            {{ $menu->items->pluck('name')->join(', ') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">No menus available at the moment.</p>
                    <p class="text-gray-400 mt-1">Please check back later.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Make a Reservation -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center mb-6">
            <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h2 class="text-2xl font-bold text-gray-900">Make a Reservation</h2>
        </div>

        <form method="POST" action="{{ route('reservations.store') }}" class="grid gap-6 max-w-lg">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Menu</label>
                <select name="menu_id" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    <option value="">Choose a menu</option>
                    @foreach ($menus as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->name }} - ₱{{ $menu->price }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <input type="number" name="quantity" min="1" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Guests</label>
                    <input type="number" name="guests" min="1" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                    <input type="time" name="time" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>
            </div>

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-colors duration-200 shadow-lg flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Reserve Now
            </button>
        </form>
    </div>
</div>
@endsection
