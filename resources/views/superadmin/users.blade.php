@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold mb-4">Manage Users</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

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
            @foreach($users as $user)
                <tr>
                    <td class="border px-4 py-2">{{ $user->name }}</td>
                    <td class="border px-4 py-2">{{ $user->email }}</td>
                    <td class="border px-4 py-2">{{ $user->getRoleNames()->implode(', ') }}</td>
                    <td class="border px-4 py-2">
                        <form method="POST" action="{{ route('superadmin.users.update') }}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <select name="role" class="border p-1">
                                <option value="superadmin" @if($user->hasRole('superadmin')) selected @endif>Superadmin</option>
                                <option value="admin" @if($user->hasRole('admin')) selected @endif>Admin</option>
                                <option value="customer" @if($user->hasRole('customer')) selected @endif>Customer</option>
                            </select>
                            <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded">Update</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
