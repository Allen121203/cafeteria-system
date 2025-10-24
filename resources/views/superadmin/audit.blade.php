@extends('layouts.sidebar')

@section('page-title', 'Audit Trail')

@section('content')
<div class="bg-white p-6 rounded shadow mx-auto max-w-full md:max-w-none md:ml-0 md:mr-0" style="max-width: calc(100vw - 12rem);">
        <h1 class="text-2xl font-bold mb-4">Audit Trail for {{ $user->name }}</h1>

        @if(!empty($audits) && $audits->isNotEmpty())
            <div class="overflow-auto max-h-96">
                <table class="w-full min-w-max border-collapse border">
                    <thead class="bg-gray-200 sticky top-0">
                        <tr>
                            <th class="border px-4 py-2">Action</th>
                            <th class="border px-4 py-2">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                @forelse($audits as $log)
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
            </div>
        @else
            <p>No audit records available.</p>
        @endif

        <div class="mt-4">
            <a href="{{ route('superadmin.users') }}" class="bg-gray-500 text-white px-4 py-2 rounded">← Back</a>
        </div>
    </div>
</div>
@endsection
