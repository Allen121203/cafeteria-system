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
    <aside class="bg-gradient-to-b from-gray-800 to-gray-900 text-white w-64 fixed inset-y-0 left-0 z-40 transform md:translate-x-0 transition-all duration-300 shadow-xl"
           :class="openSidebar ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

        <!-- Logo -->
        <div class="p-6 border-b border-gray-700/50">
            <img src="{{ asset('images/caf-logo.png') }}" alt="Logo" class="h-12 mx-auto">
        </div>

        <!-- Menu -->
        <nav class="p-4 space-y-1">
            @if(Auth::user()->role === 'superadmin')
                <a href="{{ route('superadmin.users') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700/50 transition-all duration-200 {{ request()->routeIs('superadmin.users') ? 'bg-green-600 shadow-lg' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    Manage Users
                </a>
            @endif

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700/50 transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-green-600 shadow-lg' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.reservations') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700/50 transition-all duration-200 {{ request()->routeIs('admin.reservations') ? 'bg-green-600 shadow-lg' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Reservations
                </a>

                <a href=""
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700/50 transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Reports
                </a>
                <a href="{{ route('admin.inventory.index') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700/50 transition-all duration-200 {{ request()->routeIs('admin.inventory.index') ? 'bg-green-600 shadow-lg' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Inventory
                </a>
                <div class="relative" x-data="{ openMenus: {{ (request()->routeIs('admin.menus.*') || request()->routeIs('admin.recipes.index')) ? 'true' : 'false' }}, isOnMenusPage: {{ (request()->routeIs('admin.menus.*') || request()->routeIs('admin.recipes.index')) ? 'true' : 'false' }} }">
                    <button @click="if (!isOnMenusPage) openMenus = !openMenus"
                            class="flex items-center justify-between w-full px-4 py-3 rounded-lg hover:bg-gray-700/50 transition-all duration-200 {{ (request()->routeIs('admin.menus.*') || request()->routeIs('admin.recipes.index')) ? 'bg-green-600 shadow-lg' : '' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Menus
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="openMenus ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openMenus"
                         @click.away="if (!isOnMenusPage) openMenus = false"
                         class="ml-8 mt-1 space-y-1"
                         @if(!(request()->routeIs('admin.menus.*') || request()->routeIs('admin.recipes.index'))) x-cloak @endif>
                        <a href="{{ route('admin.menus.index', ['type' => 'standard', 'meal' => 'breakfast']) }}"
                           @click.stop
                           class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700/50 transition-all duration-200 {{ request()->routeIs('admin.menus.index') ? 'bg-green-700' : '' }}">
                            Manage Menus
                        </a>
                        <a href="{{ route('admin.menus.prices') }}"
                           @click.stop
                           class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700/50 transition-all duration-200 {{ request()->routeIs('admin.menus.prices') ? 'bg-green-700' : '' }}">
                            Manage Prices
                        </a>
                    </div>
                </div>
                <a href="{{ route('admin.calendar') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700/50 transition-all duration-200 {{ request()->routeIs('admin.calendar') ? 'bg-green-600 shadow-lg' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Calendar
                </a>
            @endif

            <!-- Account Settings -->
            <a href="{{ route('profile.edit') }}"
               class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700/50 transition-all duration-200 {{ request()->routeIs('profile.edit') ? 'bg-green-600 shadow-lg' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Account Settings
            </a>

            <!-- Logout -->
            <button @click="confirmLogout = true"
                    class="w-full text-left flex items-center px-4 py-3 rounded-lg bg-red-600 hover:bg-red-700 transition-all duration-200 shadow-lg">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Logout
            </button>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col md:ml-64 bg-gray-100">
        <!-- Topbar (fixed) -->
        <div class="flex justify-between items-center bg-white shadow-lg px-6 py-4 fixed top-0 left-0 right-0 md:left-64 z-30 border-b border-gray-200">
            <div class="flex items-center space-x-4">
                <!-- Mobile burger -->
                <button @click="openSidebar = !openSidebar"
                        class="md:hidden p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-xl font-bold text-gray-800">
                    @yield('page-title', 'Dashboard')
                </h1>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Search -->
                <div class="relative">
                    <input type="text" placeholder="Search..."
                           class="border border-gray-300 rounded-lg px-4 py-2 pl-10 focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all duration-200"
                           onkeyup="filterTable(this.value)">
                    <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <!-- Notifications -->
                <div class="relative" x-data="{ openNotif: false }">
                    <button @click="openNotif = !openNotif"
                            class="p-2 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors duration-200 relative">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM15 7v5H9v6H5V7h10z"></path>
                        </svg>
                    </button>
                    <div x-show="openNotif"
                         @click.away="openNotif = false"
                         class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-xl z-50"
                         x-cloak>
                        <div class="p-4 border-b border-gray-200 font-semibold text-gray-800">Notifications</div>
                        <ul class="max-h-60 overflow-y-auto">
                            <li class="px-4 py-3 hover:bg-gray-50 text-gray-600">No new notifications</li>
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
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-8 text-black transform transition-all duration-300 scale-95"
         x-transition:enter="scale-100"
         x-transition:enter-start="scale-95">
        <div class="flex items-center mb-6">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-xl font-bold text-gray-900">Confirm Logout</h2>
            </div>
        </div>
        <p class="mb-8 text-gray-600">Are you sure you want to log out?</p>

        <div class="flex justify-end gap-3">
            <button @click="confirmLogout = false"
                    class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium">
                Cancel
            </button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 font-medium shadow-lg">
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
