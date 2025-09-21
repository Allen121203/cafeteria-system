@extends('layouts.sidebar')
@section('page-title', 'Calendars')

@section('content')
<div class="bg-white p-6 rounded shadow">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Calendars</h1>

        <!-- Month Picker -->
        <form method="GET" action="{{ route('admin.calendar') }}">
            <input type="month" name="month" value="{{ $month }}"
                   class="border rounded px-2 py-1 cursor-pointer"
                   onchange="this.form.submit()">
        </form>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <!-- Sidebar List of ALL Approved Events -->
        <div>
            <h2 class="font-bold mb-3">List of Events</h2>
            <ul class="space-y-2">
                @forelse($allApproved as $event)
                    <li class="border p-2 rounded bg-green-100">
                        <strong>{{ $event->user->name }}</strong>  
                        <div>{{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }} - {{ $event->guests }} guests</div>
                    </li>
                @empty
                    <li class="text-gray-500">No approved reservations yet.</li>
                @endforelse
            </ul>
        </div>

        <!-- Calendar Grid -->
        <div class="col-span-2">
            <div class="bg-red-600 text-white px-4 py-2 font-bold">
                {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
            </div>

            @php
                $monthStart = \Carbon\Carbon::parse($month . '-01')->startOfMonth();
                $daysInMonth = $monthStart->daysInMonth;
                $startDay = $monthStart->dayOfWeek;
            @endphp

            <div class="grid grid-cols-7 border-t border-l">
                <!-- Weekday headers -->
                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                    <div class="border-r border-b p-2 font-bold text-center bg-gray-100">{{ $day }}</div>
                @endforeach

                <!-- Empty slots before first day -->
                @for($i = 0; $i < $startDay; $i++)
                    <div class="border-r border-b p-4"></div>
                @endfor

                <!-- Days with events -->
                @for($d = 1; $d <= $daysInMonth; $d++)
                    @php
                        $currentDate = \Carbon\Carbon::parse($month . '-' . str_pad($d, 2, '0', STR_PAD_LEFT));
                        $eventsForDay = $monthlyApproved->filter(fn($res) => \Carbon\Carbon::parse($res->date)->isSameDay($currentDate));
                    @endphp
                    <div class="border-r border-b p-2 h-24 relative">
                        <div class="font-bold">{{ $d }}</div>
                        @foreach($eventsForDay as $ev)
                            <div class="bg-green-500 text-white text-xs rounded px-1 mt-1 truncate">
                                {{ $ev->user->name }} ({{ $ev->guests }} guests)
                            </div>
                        @endforeach
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>
@endsection
