@extends('layouts.sidebar') {{-- Use your sidebar layout --}}
@section('page-title', 'Manage Users')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">User Management</h1>

    <!-- Add Admin Button -->
    <button onclick="document.getElementById('addAdminModal').classList.remove('hidden')"
        class="bg-green-600 text-white px-4 py-2 rounded">
        + Add Admin
    </button>

    <!-- Users Table -->
    <table class="w-full mt-6 border-collapse border">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Email</th>
                <th class="border px-4 py-2">Role</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr class="text-center">
                    <td class="border px-4 py-2">{{ $user->name }}</td>
                    <td class="border px-4 py-2">{{ $user->email }}</td>
                    <td class="border px-4 py-2">{{ ucfirst($user->role) }}</td>

                    <td class="border px-4 py-2 space-x-2">
                        @if($user->role === 'admin')
                            <!-- Edit -->
                            <button onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')" 
                                    class="px-2 py-1 bg-blue-600 text-white rounded">
                                Edit
                            </button>
                            <!-- Audit -->
                            <a href="{{ route('superadmin.users.audit', $user) }}"
                               class="bg-yellow-500 text-white px-2 py-1 rounded">Audit</a>
                            <!-- Delete -->
                            <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 text-white px-2 py-1 rounded"
                                    onclick="return confirm('Delete this admin?')">Delete</button>
                            </form>
                        @elseif($user->role === 'customer')
                            <!-- Audit -->
                            <a href="{{ route('superadmin.users.audit', $user) }}"
                               class="bg-yellow-500 text-white px-2 py-1 rounded">Audit</a>
                            <!-- Delete -->
                            <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 text-white px-2 py-1 rounded"
                                    onclick="return confirm('Delete this customer?')">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal: Add Admin -->
<div id="addAdminModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow w-96">
        <h2 class="text-xl font-bold mb-4">Add New Admin</h2>
        <form method="POST" action="{{ route('superadmin.users.store') }}">
            @csrf
            <input type="text" name="name" placeholder="Name" class="border p-2 w-full mb-2" required>
            <input type="email" name="email" placeholder="Email" class="border p-2 w-full mb-2" required>
            <input type="password" name="password" placeholder="Password" class="border p-2 w-full mb-2" required>
            <input type="password" name="password_confirmation" placeholder="Confirm Password" class="border p-2 w-full mb-2" required>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="document.getElementById('addAdminModal').classList.add('hidden')"
                        class="bg-gray-400 px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Create</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Admin -->
<div id="editAdminModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow w-96">
        <h2 class="text-xl font-bold mb-4">Edit Admin</h2>
        <form id="editAdminForm" method="POST">
            @csrf @method('PATCH')
            <input type="text" id="editName" name="name" placeholder="Name" class="border p-2 w-full mb-2" required>
            <input type="email" id="editEmail" name="email" placeholder="Email" class="border p-2 w-full mb-2" required>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="document.getElementById('editAdminModal').classList.add('hidden')"
                        class="bg-gray-400 px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- JS -->
<script>
function openEditModal(id, name, email) {
    document.getElementById('editAdminForm').action = `/superadmin/users/${id}`;
    document.getElementById('editName').value = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editAdminModal').classList.remove('hidden');
}
</script>
@endsection
