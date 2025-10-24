@extends('layouts.sidebar')

@section('page-title', isset($reportType) ? ucfirst($reportType) . ' Report Results' : 'Report Results')

@section('content')
<div class="w-full min-h-screen overflow-hidden">
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mx-auto max-w-full md:max-w-none md:ml-0 md:mr-0" style="max-width: calc(100vw - 12rem);">
        <!-- Header with Back Arrow -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <a href="{{ route('admin.reports.index') }}" 
                   class="mr-4 text-gray-600 hover:text-gray-900 transition-colors duration-200"
                   title="Back to Reports">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        @if(isset($reportType))
                            @switch($reportType)
                                @case('reservation')
                                    Reservation Report
                                    @break
                                @case('sales')
                                    Cafeteria Sales Report
                                    @break
                                @case('inventory')
                                    Inventory Usage Report
                                    @break
                                @case('crm')
                                    CRM Report
                                    @break
                                @default
                                    Report
                            @endswitch
                        @else
                            Sales Report
                        @endif
                    </h1>
                    <p class="text-gray-600 mt-1">
                        Period: {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <form action="{{ route('admin.reports.export.pdf') }}" method="POST" class="inline">
                    @csrf
                    @if(isset($reportType))
                        <input type="hidden" name="report_type" value="{{ $reportType }}">
                    @endif
                    <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                    <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export PDF
                    </button>
                </form>
                <form action="{{ route('admin.reports.export.excel') }}" method="POST" class="inline">
                    @csrf
                    @if(isset($reportType))
                        <input type="hidden" name="report_type" value="{{ $reportType }}">
                    @endif
                    <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                    <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Excel
                    </button>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        @if(isset($reportType) && $reportType == 'sales')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-blue-100 text-sm font-medium">Total Reservations</p>
                        <p class="text-2xl font-bold">{{ $totalReservations }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-green-100 text-sm font-medium">Total Sales</p>
                        <p class="text-2xl font-bold">₱{{ number_format($totalRevenue, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Detailed Report Section -->
        <div class="mt-6">
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-900">
                    @if(isset($reportType))
                        @switch($reportType)
                            @case('reservation')
                                Detailed Reservation Report
                                @break
                            @case('sales')
                                Detailed Sales Report
                                @break
                            @case('inventory')
                                Detailed Inventory Usage Report
                                @break
                            @case('crm')
                                Detailed CRM Report
                                @break
                            @default
                                Detailed Report
                        @endswitch
                    @else
                        Detailed Sales Report
                    @endif
                </h2>
            </div>

            <div class="overflow-auto bg-white rounded-xl shadow-lg border border-gray-200 max-h-96">
                @if(isset($reportType) && $reportType == 'reservation')
                    @if($reservationData->isEmpty())
                        <div class="p-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-lg font-medium">No reservation data found for the selected period.</p>
                            <p class="text-sm">Try selecting a different date range.</p>
                        </div>
                    @else
                        <div class="overflow-auto">
                            <table class="w-full min-w-max">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Date</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persons</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($reservationData as $reservation)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $reservation['id'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $reservation['event_name'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reservation['event_date'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $reservation['customer_name'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reservation['department'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reservation['number_of_persons'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($reservation['status'] == 'approved') bg-green-100 text-green-800
                                                @elseif($reservation['status'] == 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $reservation['status'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reservation['created_at'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @elseif(isset($reportType) && $reportType == 'inventory')
                    @if($inventoryData->isEmpty())
                        <div class="p-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-lg font-medium">No inventory usage data found for the selected period.</p>
                            <p class="text-sm">Try selecting a different date range.</p>
                        </div>
                    @else
                        <div class="overflow-auto">
                            <table class="w-full min-w-max">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inventory Item</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Used</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reservations Count</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($inventoryData as $item)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item['name'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item['unit'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($item['total_used'], 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item['reservations_count'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @elseif(isset($reportType) && $reportType == 'crm')
                    @if($crmData->isEmpty())
                        <div class="p-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-lg font-medium">No CRM data found for the selected period.</p>
                            <p class="text-sm">Try selecting a different date range.</p>
                        </div>
                    @else
                        <div class="overflow-auto">
                            <table class="w-full min-w-max">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Reservations</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Spent</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Reservation</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($crmData as $customer)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $customer['name'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer['email'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer['total_reservations'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer['approved_reservations'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱{{ number_format($customer['total_spent'], 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer['last_reservation'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @else
                    @if($salesData->isEmpty())
                        <div class="p-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-lg font-medium">No sales data found for the selected period.</p>
                            <p class="text-sm">Try selecting a different date range.</p>
                        </div>
                    @else
                        <div class="overflow-auto">
                            <table class="w-full min-w-max">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reservation</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Details</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu Items</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($salesData as $reservation)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">#{{ $reservation['reservation_id'] }}</div>
                                            <div class="text-sm text-gray-500">{{ $reservation['customer_name'] }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $reservation['event_name'] }}</div>
                                            <div class="text-sm text-gray-500">{{ $reservation['event_date'] }}</div>
                                            <div class="text-sm text-gray-500">{{ $reservation['number_of_persons'] }} persons</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="space-y-2">
                                                @foreach($reservation['items'] as $item)
                                                <div class="text-sm">
                                                    <span class="font-medium">{{ $item['menu_name'] }}</span>
                                                    <span class="text-gray-500">({{ $item['type'] }} - {{ $item['meal_time'] }})</span>
                                                    <br>
                                                    <span class="text-gray-600">Qty: {{ $item['quantity'] }} × ₱{{ number_format($item['unit_price'], 2) }} = ₱{{ number_format($item['total'], 2) }}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            ₱{{ number_format($reservation['reservation_total'], 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
