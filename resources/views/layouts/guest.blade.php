<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Smart Cafeteria') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex">
            
            <!-- Left side: Image -->
            <div class="hidden md:flex w-1/2 items-center justify-center bg-gray-100">
                <img src="{{ asset('images/caf-logo.png') }}" 
                     alt="Smart Cafeteria Logo" 
                     class="max-h-96">
            </div>

            <!-- Right side: Form -->
            <div class="w-full md:w-1/2 flex items-center justify-center bg-white">
                <div class="w-full max-w-md p-6">
                    {{ $slot }}
                </div>
            </div>

        </div>
    </body>
</html>
