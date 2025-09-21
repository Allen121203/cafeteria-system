@extends('layouts.sidebar')

@section('page-title', 'Reservations')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Reservations</h1>

    <!-- Filter Dropdown -->
    <form method="GET" action="{{ route('admin.reservations') }}" class="mb-4">
        <select name="status" onchange="this.form.submit()" class="border p-2 rounded">
            <option value="">All</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="declined" {{ request('status') === 'declined' ? 'selected' : '' }}>Declined</option>
        </select>   
    </form>

    <!-- Table -->
    @if($reservations->isEmpty())
        <p class="text-gray-600">No reservations made yet</p>
    @else
    <table class="w-full border-collapse border">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="border px-4 py-2">RID</th>
                <th class="border px-4 py-2">Customer</th>
                <th class="border px-4 py-2">Request ID</th>
                <th class="border px-4 py-2"># of Guests</th>
                <th class="border px-4 py-2">Date</th>
                <th class="border px-4 py-2">Status</th>
                <th class="border px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $reservation)
                <tr>
                    <td class="border px-4 py-2">{{ $reservation->id }}</td>
                    <td class="border px-4 py-2">{{ $reservation->user->name }}</td>
                    <td class="border px-4 py-2">{{ $reservation->request_id }}</td>
                    <td class="border px-4 py-2">{{ $reservation->guests }} Pax</td>
                    <td class="border px-4 py-2">{{ $reservation->date}}{{ $reservation->time }}</td>
                    <td class="border px-4 py-2">
                        @if($reservation->status === 'approved')
                            <span class="bg-green-500 text-white px-2 py-1 rounded">Approved</span>
                        @elseif($reservation->status === 'declined')
                            <span class="bg-red-500 text-white px-2 py-1 rounded">Declined</span>
                        @else
                            <span class="bg-yellow-500 text-white px-2 py-1 rounded">Pending</span>
                        @endif
                    </td>
                    <td class="border px-4 py-2">
                        <a href="#" class="text-blue-600 hover:underline">View Details</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
