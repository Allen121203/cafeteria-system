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
        <aside class="w-64 bg-gray-900 text-white flex flex-col">
            <div class="p-4 text-2xl font-bold border-b  border-gray-700">
                <img src="{{ asset('images/caf-logo-2.png') }}" alt="Logo" class="h-30 mx-auto">
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-green-600' : '' }}">
                    Dashboard
                </a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-gray-700">Reservations</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-gray-700">Reports</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-gray-700">Inventory</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-gray-700">Menus</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-gray-700">Calendars</a>
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded hover:bg-gray-700">Account Settings</a>
            </nav>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-3 py-2 bg-red-600 text-center">
                Logout
            </a>
            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>
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
                    <button class="p-2 bg-gray-200 rounded-full">👤</button>
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
