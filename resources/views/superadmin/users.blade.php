

@extends('layouts.sidebar')
@section('page-title', 'Manage Users')

@section('content')
<div class="bg-white p-6 rounded shadow">
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">User Management</h1>
        <button onclick="document.getElementById('addAdminModal').classList.remove('hidden')"
                class="bg-green-600 text-white px-4 py-2 rounded">
            + Add Admin
        </button>
    </div>

    <table class="w-full mt-6 border-collapse border">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2 text-left">Name</th>
                <th class="border px-4 py-2 text-left">Email</th>
                <th class="border px-4 py-2 text-left">Role</th>
                <th class="border px-4 py-2 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td class="border px-4 py-2">{{ $user->name }}</td>
                    <td class="border px-4 py-2">{{ $user->email }}</td>
                    <td class="border px-4 py-2">{{ ucfirst($user->role) }}</td>
                    <td class="border px-4 py-2 text-center space-x-2">
                        @if($user->role === 'admin')
                            <button
                                onclick="openEditModal({{ $user->id }}, '{{ e($user->name) }}', '{{ e($user->email) }}')"
                                class="px-2 py-1 bg-blue-600 text-white rounded">
                                Edit
                            </button>
                            <a href="{{ route('superadmin.users.audit', $user) }}"
                               class="px-2 py-1 bg-yellow-500 text-white rounded">
                                Audit
                            </a>
                        @else
                            <a href="{{ route('superadmin.users.audit', $user) }}"
                               class="px-2 py-1 bg-yellow-500 text-white rounded">
                                Audit
                            </a>
                        @endif

                        <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Delete this user?')"
                                    class="px-2 py-1 bg-red-600 text-white rounded">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-gray-500 px-4 py-6">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal: Add Admin --}}
<div id="addAdminModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow w-96">
        <h2 class="text-xl font-bold mb-4">Add New Admin</h2>

        <form method="POST" action="{{ route('superadmin.users.store') }}"
              onsubmit="return confirm('Create this admin?')">
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

{{-- Modal: Edit Admin --}}
<div id="editUserModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow w-96">
        <h2 class="text-xl font-bold mb-4">Edit Admin</h2>

        <form id="editUserForm" method="POST" onsubmit="return confirm('Save changes to this admin?')">
            @csrf @method('PUT')
            <input type="text"   name="name"  id="editName"  class="border p-2 w-full mb-2" required>
            <input type="email"  name="email" id="editEmail" class="border p-2 w-full mb-2" required>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="document.getElementById('editUserModal').classList.add('hidden')"
                        class="bg-gray-400 px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, name, email) {
    document.getElementById('editUserModal').classList.remove('hidden');
    document.getElementById('editName').value = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editUserForm').action = `/cafeteria-system/public/superadmin/users/${id}`;
}
</script>
@endsection
