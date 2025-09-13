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
      x-data="{ openSidebar: false, confirmLogout: false, confirmForm: false, formToSubmit: null }">

    <div class="min-h-screen flex">
        <!-- Sidebar (unchanged)... -->
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

    <!-- 🔔 General Form Confirmation Modal -->
    <div x-show="confirmForm"
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
         x-cloak>
        <div class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6 text-black">
            <h2 class="text-lg font-bold mb-4 text-black">Confirm Action</h2>
            <p class="mb-6 text-black">Are you sure you want to continue?</p>

            <div class="flex justify-end gap-2">
                <button @click="confirmForm = false"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Cancel
                </button>
                <button @click="formToSubmit.submit(); confirmForm = false"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Yes, Proceed
                </button>
            </div>
        </div>
    </div>

    <!-- JS for search filter & confirmation -->
    <script>
    // 🔎 Search Filter
    function filterTable(query) {
        let rows = document.querySelectorAll("table tbody tr");
        query = query.toLowerCase();
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? "" : "none";
        });
    }

    // ✅ Form Confirmation Hook
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll("form").forEach(form => {
            if (form.getAttribute("data-confirm") === "true") {
                form.addEventListener("submit", function (e) {
                    e.preventDefault();
                    document.querySelector("body").__x.$data.formToSubmit = this;
                    document.querySelector("body").__x.$data.confirmForm = true;
                });
            }
        });
    });
    </script>
</body>

</html>
