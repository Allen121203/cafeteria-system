@extends('layouts.app')
@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold">Customer Home</h1>
    @if(session('success'))<div class="bg-green-100 text-green-700 p-2 my-3 rounded">{{ session('success') }}</div>@endif
    <form method="POST" action="{{ route('reservations.store') }}" class="mt-4 space-y-3">@csrf
        <input class="border p-2 w-full" name="date" type="date" required>
        <input class="border p-2 w-full" name="time" type="time" required>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Reserve</button>
    </form>
</div>
@endsection
