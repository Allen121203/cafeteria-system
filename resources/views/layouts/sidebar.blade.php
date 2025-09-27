<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Smart Cafeteria') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="font-sans antialiased"
      x-data="{ openSidebar: false, confirmLogout: false }">

<div class="min-h-screen flex">

    <!-- Sidebar -->
    <aside class="bg-gray-900 text-white w-64 fixed inset-y-0 left-0 z-40 transform md:translate-x-0 transition-transform duration-200"
           :class="openSidebar ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

        <!-- Logo -->
        <div class="p-4 text-2xl font-bold border-b border-gray-700">
            <img src="{{ asset('images/caf-logo.png') }}" alt="Logo" class="h-12 mx-auto">
        </div>

        <!-- Menu -->
        <nav class="p-4 space-y-2">
            @if(Auth::user()->role === 'superadmin')
                <a href="{{ route('superadmin.users') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('superadmin.users') ? 'bg-green-600' : '' }}">
                    👥 Manage Users
                </a>
            @endif

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-green-600' : '' }}">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.reservations') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.reservations') ? 'bg-green-600' : '' }}">
                    📅 Reservations
                </a>
                
                <a href=""
                   class="block px-3 py-2 rounded hover:bg-gray-700 ">
                    📑 Reports
                </a>
                <a href="{{ route('admin.inventory.index') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.inventory') ? 'bg-green-600' : '' }}">
                    📦 Inventory
                </a>
                <a href="{{ route('admin.menus.index') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.menus.index') ? 'bg-green-600' : '' }}">
                    🍽 Menus
                </a>
                <a href="{{ route('admin.calendar') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.calendar') ? 'bg-green-600' : '' }}">
                    📆 Calendar
                </a>
            @endif

            <!-- Account Settings -->
            <a href="{{ route('profile.edit') }}"
               class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('profile.edit') ? 'bg-green-600' : '' }}">
                ⚙️ Account Settings
            </a>

            <!-- Logout -->
            <button @click="confirmLogout = true"
                    class="w-full text-left block px-3 py-2 rounded bg-red-600 hover:bg-red-700">
                🚪 Logout
            </button>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col md:ml-64 bg-gray-100">
        <!-- Topbar (fixed) -->
        <div class="flex justify-between items-center bg-white shadow px-6 py-4 fixed top-0 left-0 right-0 md:left-64 z-30">
            <div class="flex items-center space-x-3">
                <!-- Mobile burger -->
                <button @click="openSidebar = !openSidebar"
                        class="md:hidden p-2 rounded bg-gray-200">
                    ☰
                </button>
                <h1 class="text-xl font-bold">
                    @yield('page-title', 'Dashboard')
                </h1>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Search -->
                <input type="text" placeholder="Search"
                       class="border rounded px-3 py-1 focus:ring focus:ring-green-400"
                       onkeyup="filterTable(this.value)">

                <!-- Notifications -->
                <div class="relative" x-data="{ openNotif: false }">
                    <button @click="openNotif = !openNotif" class="p-2 bg-gray-200 rounded-full">🔔</button>
                    <div x-show="openNotif"
                         @click.away="openNotif = false"
                         class="absolute right-0 mt-2 w-64 bg-white border rounded shadow-lg z-50"
                         x-cloak>
                        <div class="p-3 border-b font-bold">Notifications</div>
                        <ul class="max-h-60 overflow-y-auto">
                            <li class="px-4 py-2 hover:bg-gray-100">No new notifications</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <main class="p-6 overflow-y-auto flex-1 mt-16">
            @yield('content')
        </main>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div x-show="confirmLogout"
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
     x-cloak>
    <div class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6 text-black">
        <h2 class="text-lg font-bold mb-4 text-black">Confirm Logout</h2>
        <p class="mb-6 text-black">Are you sure you want to log out?</p>

        <div class="flex justify-end gap-2">
            <button @click="confirmLogout = false"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                Cancel
            </button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Yes, Logout
                </button>
            </form>
        </div>
    </div>
</div>

<!-- JS for search filter -->
<script>
function filterTable(query) {
    let rows = document.querySelectorAll("table tbody tr");
    query = query.toLowerCase();
    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(query) ? "" : "none";
    });
}
</script>
</body>
</html>
