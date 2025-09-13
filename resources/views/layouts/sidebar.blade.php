<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Smart Cafeteria') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind / Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex flex-col" x-data="{ confirmLogout: false }">
            <!-- Logo -->
            <div class="p-4 text-2xl font-bold border-b border-gray-700">
                <img src="{{ asset('images/caf-logo.png') }}" alt="Logo" class="h-12 mx-auto">
            </div>

            <!-- Menu -->
            <nav class="flex-1 p-4 space-y-2">
                @if(Auth::user()->hasRole('superadmin'))
                    <a href="{{ route('superadmin.users') }}"
                    class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('superadmin.users') ? 'bg-green-600' : '' }}">
                    👥 Manage Users
                    </a>
                @endif

                <!-- Account Settings -->
                <a href="{{ route('profile.edit') }}"
                class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('profile.edit') ? 'bg-blue-600' : '' }}">
                ⚙️ Account Settings
                </a>

                <!-- Logout with confirmation -->
                <button @click="confirmLogout = true"
                    class="w-full text-left block px-3 py-2 rounded bg-red-600 hover:bg-red-700">
                    🚪 Logout
                </button>
            </nav>

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
        </aside>

        <!-- Main content -->
        <div class="flex-1 bg-gray-100">
            <!-- Topbar -->
            <div class="flex justify-between items-center bg-white shadow px-6 py-4">
                <h1 class="text-xl font-bold">
                    @yield('page-title', 'Dashboard')
                </h1>
                <div class="flex items-center space-x-4">
                    <input type="text" placeholder="Search" class="border rounded px-3 py-1 focus:ring focus:ring-green-400">
                    <button class="p-2 bg-gray-200 rounded-full">🔔</button>
                </div>
            </div>

            <!-- Page content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
