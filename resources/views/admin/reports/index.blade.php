@extends('layouts.sidebar')

@section('page-title', 'Reports')

@section('content')
<div class="container mx-auto bg-white rounded-xl shadow-lg border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
        </div>
    </div>

    <div class="mb-8">
        <p class="text-gray-600">Generate and export various reports for approved reservations within a specific date range.</p>
    </div>

    <form action="{{ route('admin.reports.generate') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Report Type Dropdown -->
        <div>
            <label for="report_type" class="block text-sm font-medium text-gray-700 mb-2">
                Report Type
            </label>
            <select id="report_type"
                    name="report_type"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all duration-200 @error('report_type') border-red-500 @enderror"
                    required>
                <option value="">Select Report Type</option>
                <option value="reservation" {{ old('report_type') == 'reservation' ? 'selected' : '' }}>Reservation Report</option>
                <option value="sales" {{ old('report_type') == 'sales' ? 'selected' : '' }}>Cafeteria Sales Report</option>
                <option value="inventory" {{ old('report_type') == 'inventory' ? 'selected' : '' }}>Inventory Usage Report</option>
                <option value="crm" {{ old('report_type') == 'crm' ? 'selected' : '' }}>CRM Report</option>
            </select>
            @error('report_type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Start Date -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Start Date
                </label>
                <input type="date"
                       id="start_date"
                       name="start_date"
                       value="{{ old('start_date', now()->startOfMonth()->format('Y-m-d')) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all duration-200 @error('start_date') border-red-500 @enderror"
                       required>
                @error('start_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- End Date -->
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                    End Date
                </label>
                <input type="date"
                       id="end_date"
                       name="end_date"
                       value="{{ old('end_date', now()->format('Y-m-d')) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all duration-200 @error('end_date') border-red-500 @enderror"
                       required>
                @error('end_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Generate Report Button -->
        <div class="flex justify-center pt-4">
            <button type="submit"
                    class="inline-flex items-center px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Generate Report
            </button>
        </div>
    </form>

    <!-- Quick Date Range Buttons -->
    <div class="mt-8 pt-6 border-t border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Date Ranges</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <button type="button"
                    onclick="setDateRange('today')"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200 text-sm font-medium">
                Today
            </button>
            <button type="button"
                    onclick="setDateRange('week')"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200 text-sm font-medium">
                This Week
            </button>
            <button type="button"
                    onclick="setDateRange('month')"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200 text-sm font-medium">
                This Month
            </button>
            <button type="button"
                    onclick="setDateRange('year')"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200 text-sm font-medium">
                This Year
            </button>
        </div>
    </div>
</div>

<script>
function setDateRange(range) {
    const today = new Date();
    let startDate, endDate;

    switch(range) {
        case 'today':
            startDate = endDate = today;
            break;
        case 'week':
            // Monday of current week
            const monday = new Date(today);
            monday.setDate(today.getDate() - today.getDay() + 1);
            startDate = monday;
            // Sunday of current week
            const sunday = new Date(monday);
            sunday.setDate(monday.getDate() + 6);
            endDate = sunday;
            break;
        case 'month':
            startDate = new Date(today.getFullYear(), today.getMonth(), 1);
            endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            break;
        case 'year':
            startDate = new Date(today.getFullYear(), 0, 1);
            endDate = new Date(today.getFullYear(), 11, 31);
            break;
    }

    document.getElementById('start_date').value = startDate.toISOString().split('T')[0];
    document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
}
</script>
@endsection
