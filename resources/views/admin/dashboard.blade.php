@extends('layouts.sidebar')
@section('page-title', 'Admin')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <!-- Greeting -->
    <h1 class="text-2xl font-bold mb-2">Hello <span class="text-green-600">{{ Auth::user()->name }}</span>,</h1>
    <p class="text-gray-600 mb-6">Have a good day :)</p>

    <!-- Top Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-green-600 text-white p-4 rounded shadow text-center">
            <h2 class="text-lg font-semibold">Total Reservations</h2>
            <p class="text-2xl font-bold mt-2">{{ $totalReservations }}</p>
        </div>
        <div class="bg-yellow-500 text-white p-4 rounded shadow text-center">
            <h2 class="text-lg font-semibold">Pending Reservations</h2>
            <p class="text-2xl font-bold mt-2">{{ $pendingReservations }}</p>
        </div>
        <div class="bg-orange-500 text-white p-4 rounded shadow text-center">
            <h2 class="text-lg font-semibold">Menus Sold</h2>
            <p class="text-2xl font-bold mt-2">{{ $menusSold }}</p>
        </div>
    </div>

    <!-- Inventory Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Low Stocks -->
        <div class="bg-yellow-200 rounded shadow">
            <h2 class="bg-yellow-400 p-3 font-bold rounded-t">Low Stocks Items</h2>
            <ul class="divide-y divide-gray-300">
                @forelse($lowStocks as $item)
                <li class="flex justify-between p-3"><span>{{ $item->name }}</span><span class="bg-red-500 text-white px-2 py-1 text-xs rounded">{{ $item->qty }} {{ $item->unit }}</span></li>
                @empty
                <li class="p-3">No low stock items</li>
                @endforelse
            </ul>
            <div class="p-3 text-right">
                <a href="#" class="text-blue-600 hover:underline">See More</a>
            </div>
        </div>

        <!-- Out of Stocks -->
        <div class="bg-red-200 rounded shadow">
            <h2 class="bg-red-500 text-white p-3 font-bold rounded-t">Out of Stocks Items</h2>
            <ul class="divide-y divide-gray-300">
                @forelse($outOfStocks as $item)
                <li class="p-3">{{ $item->name }}</li>
                @empty
                <li class="p-3">No out of stock items</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Expiring Soon -->
    <div class="bg-white shadow rounded">
        <h2 class="bg-red-500 text-white p-3 font-bold rounded-t">⚠️ Expiring Soon</h2>
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">Items</th>
                    <th class="p-3 border">Quantity</th>
                    <th class="p-3 border">Expiry Date</th>
                    <th class="p-3 border">Days Left</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expiringSoon as $item)
                <tr>
                    <td class="p-3 border">{{ $item->name }}</td>
                    <td class="p-3 border">{{ $item->qty }} {{ $item->unit }}</td>
                    <td class="p-3 border">{{ $item->expiry_date }}</td>
                    <td class="p-3 border text-red-600 font-bold">{{ (int) \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($item->expiry_date)) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-3 border text-center">No items expiring soon</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
