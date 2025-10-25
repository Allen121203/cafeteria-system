

@extends('layouts.sidebar')
@section('page-title', 'Manage Users')

@section('content')
<div class="bg-white p-6 rounded shadow mx-auto max-w-full md:max-w-none md:ml-0 md:mr-0" style="max-width: calc(100vw - 12rem);">
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">User Management</h1>
        <div class="flex space-x-2">
            <button onclick="openRecentActivitiesModal()"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                View Recent Activities
            </button>
            <button onclick="document.getElementById('addAdminModal').classList.remove('hidden')"
                    class="bg-green-600 text-white px-4 py-2 rounded">
                + Add Admin
            </button>
        </div>
    </div>

    <div class="overflow-auto max-h-96 mt-6">
        <table class="w-full min-w-max border-collapse border">
            <thead class="bg-gray-200 sticky top-0">
                <tr>
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

{{-- Modal: Recent Activities --}}
<div id="recentActivitiesModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow w-full max-w-4xl max-h-[80vh] overflow-auto">
        <h2 class="text-xl font-bold mb-4">Recent Activities</h2>

        <div id="activitiesTableContainer">
            <p class="text-gray-500">Loading...</p>
        </div>

        <div class="flex justify-end space-x-2 mt-4">
            <button type="button" onclick="document.getElementById('recentActivitiesModal').classList.add('hidden')"
                    class="bg-gray-400 px-4 py-2 rounded">Close</button>
        </div>
    </div>
</div>

<script>
function openEditModal(id, name, email) {
    document.getElementById('editUserModal').classList.remove('hidden');
    document.getElementById('editName').value = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editUserForm').action = `/cafeteria-system/public/superadmin/users/${id}`;
}

let allAudits = [];
let currentSortBy = 'created_at';
let currentSortDirection = 'desc';

async function openRecentActivitiesModal() {
    document.getElementById('recentActivitiesModal').classList.remove('hidden');
    await loadActivities();
}

async function loadActivities() {
    const container = document.getElementById('activitiesTableContainer');
    container.innerHTML = '<p class="text-gray-500">Loading...</p>';

    try {
        const response = await fetch('/cafeteria-system/public/superadmin/recent-audits');
        allAudits = await response.json();

        if (allAudits.length === 0) {
            container.innerHTML = '<p class="text-gray-500">No recent activities found.</p>';
            return;
        }

        renderTable();
    } catch (error) {
        container.innerHTML = '<p class="text-red-500">Error loading activities.</p>';
        console.error('Error fetching audits:', error);
    }
}

function renderTable() {
    // Sort the audits client-side
    const sortedAudits = [...allAudits].sort((a, b) => {
        let aVal, bVal;

        if (currentSortBy === 'created_at') {
            aVal = new Date(a.created_at);
            bVal = new Date(b.created_at);
        } else {
            aVal = a[currentSortBy].toLowerCase();
            bVal = b[currentSortBy].toLowerCase();
        }

        if (currentSortDirection === 'asc') {
            return aVal < bVal ? -1 : aVal > bVal ? 1 : 0;
        } else {
            return aVal > bVal ? -1 : aVal < bVal ? 1 : 0;
        }
    });

    let html = `
        <div class="overflow-auto max-h-96">
            <table class="w-full min-w-max border-collapse border">
                <thead class="bg-gray-200 sticky top-0">
                    <tr>
                        <th class="border px-4 py-2 text-left">User</th>
                        <th class="border px-4 py-2 text-left cursor-pointer hover:bg-gray-300" onclick="sortBy('action')">Action ${getSortIcon('action')}</th>
                        <th class="border px-4 py-2 text-left cursor-pointer hover:bg-gray-300" onclick="sortBy('module')">Module ${getSortIcon('module')}</th>
                        <th class="border px-4 py-2 text-left cursor-pointer hover:bg-gray-300" onclick="sortBy('description')">Description ${getSortIcon('description')}</th>
                        <th class="border px-4 py-2 text-left cursor-pointer hover:bg-gray-300" onclick="sortBy('created_at')">Date ${getSortIcon('created_at')}</th>
                    </tr>
                </thead>
                <tbody>
    `;

    sortedAudits.forEach(audit => {
        const date = new Date(audit.created_at).toLocaleString();
        html += `
            <tr>
                <td class="border px-4 py-2">${audit.user ? audit.user.name : 'Unknown'}</td>
                <td class="border px-4 py-2">${audit.action}</td>
                <td class="border px-4 py-2">${audit.module}</td>
                <td class="border px-4 py-2">${audit.description}</td>
                <td class="border px-4 py-2">${date}</td>
            </tr>
        `;
    });

    html += `
                </tbody>
            </table>
        </div>
    `;

    document.getElementById('activitiesTableContainer').innerHTML = html;
}

function sortBy(column) {
    if (currentSortBy === column) {
        currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        currentSortBy = column;
        currentSortDirection = 'asc'; // Default to ascending for new column
    }
    renderTable();
}

function getSortIcon(column) {
    if (currentSortBy !== column) return '';
    return currentSortDirection === 'asc' ? '▲' : '▼';
}
</script>
@endsection
