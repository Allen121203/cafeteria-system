@extends('layouts.sidebar')

@section('page-title', 'Manage Users')

@section('content')
<div class="bg-white p-6 rounded shadow" x-data="{ open: false }">
    <!-- Header Row -->
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Manage Users</h1>
        <!-- Add Admin Button -->
        <button @click="open = true"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Add Admin
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Modal -->
    <div x-show="open"
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
         x-cloak>
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <!-- Close button -->
            <button @click="open = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">
                ✕
            </button>

            <h2 class="text-lg font-semibold mb-4">Add Admin</h2>

            <form method="POST" action="{{ route('superadmin.users.store') }}" class="space-y-3">
                @csrf
                <input type="text" name="name" placeholder="Name" class="border p-2 w-full" required>
                <input type="email" name="email" placeholder="Email" class="border p-2 w-full" required>
                <input type="password" name="password" placeholder="Password" class="border p-2 w-full" required>
                <input type="password" name="password_confirmation" placeholder="Confirm Password" class="border p-2 w-full" required>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="open = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Save Admin
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <table class="w-full border-collapse border">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Email</th>
                <th class="border px-4 py-2">Role</th>
                <th class="border px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
                <tr>
                    <td class="border px-4 py-2">{{ $u->name }}</td>
                    <td class="border px-4 py-2">{{ $u->email }}</td>
                    <td class="border px-4 py-2">{{ $u->getRoleNames()->implode(', ') }}</td>
                    <td class="border px-4 py-2">
                        <form method="POST" action="{{ route('superadmin.users.update') }}" class="flex gap-2">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $u->id }}">
                            <select name="role" class="border p-1">
                                <option value="superadmin" @selected($u->hasRole('superadmin'))>Superadmin</option>
                                <option value="admin" @selected($u->hasRole('admin'))>Admin</option>
                                <option value="customer" @selected($u->hasRole('customer'))>Customer</option>
                            </select>
                            <button class="bg-green-600 text-white px-2 py-1 rounded">Update</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
