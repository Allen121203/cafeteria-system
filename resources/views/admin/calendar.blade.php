@extends('layouts.sidebar')
@section('page-title', 'Calendars')

@section('content')
<div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h1 class="text-2xl font-bold text-gray-900">Calendar</h1>
        </div>

        <!-- Month Picker -->
        <form method="GET" action="{{ route('admin.calendar') }}" class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-700">Select Month:</label>
            <input type="month" name="month" value="{{ $month }}" id="month-picker"
                   class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 cursor-pointer">
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar List of Approved Events for the Month -->
        <div class="lg:col-span-1">
            <div class="bg-gray-50 rounded-xl p-4">
                <h2 class="font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Approved Events ({{ $monthlyApproved->count() }})
                </h2>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($monthlyApproved as $event)
                        <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                            <div class="font-medium text-gray-900">{{ $event->user->name }}</div>
                            <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }}</div>
                            <div class="text-sm text-green-600 font-medium">{{ $event->guests }} guests</div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-500">
                            <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p>No approved reservations for this month.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="lg:col-span-2">
            <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 rounded-xl shadow-lg mb-4">
                <h2 class="text-xl font-bold">{{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</h2>
            </div>

            @php
                $monthStart = \Carbon\Carbon::parse($month . '-01')->startOfMonth();
                $daysInMonth = $monthStart->daysInMonth;
                $startDay = $monthStart->dayOfWeek;
            @endphp

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="grid grid-cols-7 border-b">
                    <!-- Weekday headers -->
                    @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                        <div class="bg-gray-50 border-r text-center py-3 px-1 font-semibold text-gray-700 text-sm uppercase tracking-wide">
                            {{ $day }}
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-7">
                    <!-- Empty slots before first day -->
                    @for($i = 0; $i < $startDay; $i++)
                        <div class="border-r border-b p-2 bg-gray-50"></div>
                    @endfor

                    <!-- Days with events -->
                    @for($d = 1; $d <= $daysInMonth; $d++)
                        @php
                            $currentDate = \Carbon\Carbon::parse($month . '-' . str_pad($d, 2, '0', STR_PAD_LEFT));
                            $eventsForDay = $monthlyApproved->filter(fn($res) => \Carbon\Carbon::parse($res->date)->isSameDay($currentDate));
                            $hasEvents = $eventsForDay->count() > 0;
                        @endphp
                        <div class="border-r border-b p-3 relative min-h-[80px] hover:bg-gray-50 transition-colors duration-200 {{ $hasEvents ? 'bg-green-50' : '' }}">
                            <div class="font-semibold text-gray-900 text-sm">{{ $d }}</div>
                            @if($hasEvents)
                                <div class="mt-1 space-y-1">
                                    @foreach($eventsForDay as $ev)
                                        <div class="bg-green-600 text-white text-xs rounded px-2 py-1 truncate flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $ev->user->name }} ({{ $ev->guests }})
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('month-picker').addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endsection
