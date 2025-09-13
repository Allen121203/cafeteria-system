@extends('layouts.sidebar')

@section('page-title', 'Audit Trail')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Audit Trail for {{ $user->name }}</h1>

    @if(count($logs) > 0)
        <table class="w-full border-collapse border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">Action</th>
                    <th class="border px-4 py-2">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="border px-4 py-2">{{ $log->action }}</td>
                        <td class="border px-4 py-2">{{ $log->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center py-4">No audit records found.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    @else
        <p>No audit records available.</p>
    @endif

    <div class="mt-4">
        <a href="{{ route('superadmin.users') }}" class="bg-gray-500 text-white px-4 py-2 rounded">← Back</a>
    </div>
</div>
@endsection
