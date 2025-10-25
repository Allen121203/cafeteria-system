

@extends('layouts.sidebar')
@section('page-title', 'Manage Users')

@section('content')
<div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mx-auto max-w-full md:max-w-none md:ml-0 md:mr-0" style="max-width: calc(100vw - 12rem);">
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

    <!-- Table wrapper -->
    <div class="overflow-auto max-h-96">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50 sticky top-0">
                <tr>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($user->role) }}</td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex flex-col sm:flex-row space-y-1 sm:space-y-0 sm:space-x-2">
                            @if($user->role === 'admin')
                                <button
                                    onclick="openEditModal({{ $user->id }}, '{{ e($user->name) }}', '{{ e($user->email) }}')"
                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-200 flex items-center px-2 py-1 rounded text-xs sm:text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                <a href="{{ route('superadmin.users.audit', $user) }}"
                                   class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200 flex items-center px-2 py-1 rounded text-xs sm:text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Audit
                                </a>
                            @else
                                <a href="{{ route('superadmin.users.audit', $user) }}"
                                   class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200 flex items-center px-2 py-1 rounded text-xs sm:text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Audit
                                </a>
                            @endif

                            <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Delete this user?')"
                                        class="text-red-600 hover:text-red-900 transition-colors duration-200 flex items-center px-2 py-1 rounded text-xs sm:text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 sm:px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <p class="text-lg">No users found.</p>
                            <p class="text-sm text-gray-400 mt-1">Start by adding your first user.</p>
                        </td>
                    </tr>
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

        <div id="activitiesTableContainer" class="overflow-auto max-h-96">
            <table class="w-full min-w-max">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="px-4 sm:px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-4 sm:px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-300" onclick="sortBy('action')">Action</th>
                        <th class="px-4 sm:px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-300" onclick="sortBy('module')">Module</th>
                        <th class="px-4 sm:px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-300" onclick="sortBy('description')">Description</th>
                        <th class="px-4 sm:px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-300" onclick="sortBy('created_at')">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="5" class="px-4 sm:px-6 py-12 text-center text-gray-500">
                            <p class="text-gray-500">Loading...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
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

    let tbodyHtml = '';

    if (sortedAudits.length === 0) {
        tbodyHtml = `
            <tr>
                <td colspan="5" class="px-4 sm:px-6 py-12 text-center text-gray-500">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-lg">No recent activities found.</p>
                    <p class="text-sm text-gray-400 mt-1">Activities will appear here as users interact with the system.</p>
                </td>
            </tr>
        `;
    } else {
        sortedAudits.forEach(audit => {
            const date = new Date(audit.created_at).toLocaleString();
            tbodyHtml += `
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${audit.user ? audit.user.name : 'Unknown'}</td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">${audit.action}</td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">${audit.module}</td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">${audit.description}</td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">${date}</td>
                </tr>
            `;
        });
    }

    document.querySelector('#activitiesTableContainer tbody').innerHTML = tbodyHtml;
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
