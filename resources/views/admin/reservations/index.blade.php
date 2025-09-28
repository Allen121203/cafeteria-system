@extends('layouts.sidebar')
@section('page-title', 'Reservations')

@section('content')
<div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            <h1 class="text-2xl font-bold text-gray-900">Reservations</h1>
        </div>

        <!-- Status Filter -->
        <form method="GET" action="{{ route('admin.reservations') }}" class="flex items-center gap-3">
            <label class="text-sm font-medium text-gray-700">Filter by Status:</label>
            @php
                $pending  = data_get($counts, 'pending', 0);
                $approved = data_get($counts, 'approved', 0);
                $declined = data_get($counts, 'declined', 0);
            @endphp
            <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200" onchange="this.form.submit()">
                <option value="" {{ $status === null ? 'selected' : '' }}>All Reservations</option>
                <option value="pending"  {{ $status === 'pending'  ? 'selected' : '' }}>Pending ({{ $pending }})</option>
                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved ({{ $approved }})</option>
                <option value="declined" {{ $status === 'declined' ? 'selected' : '' }}>Declined ({{ $declined }})</option>
            </select>

            @if(request('status'))
                <a href="{{ route('admin.reservations') }}" class="flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Clear
                </a>
            @endif
        </form>
    </div>

    @if($reservations->count() > 0)
        <div class="overflow-x-auto bg-white rounded-xl shadow-lg border border-gray-200">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bundles</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($reservations as $r)
                        @php
                            // Pull raw DB value (ignores any getStatusAttribute accessor that might force "approved")
                            $raw = $r->getRawOriginal('status');
                            $key = strtolower((string) $raw);

                            // Map supported forms (string or numeric enums)
                            $map = [
                                'pending'  => 'Pending',
                                'approved' => 'Approved',
                                'declined' => 'Declined',
                                '0' => 'Pending',
                                '1' => 'Approved',
                                '2' => 'Declined',
                            ];
                            $label = data_get($map, $key, ucfirst((string) $raw));

                            $statusConfig = match ($label) {
                                'Approved' => ['bg-green-100 text-green-800', 'bg-green-600', 'check'],
                                'Declined' => ['bg-red-100 text-red-800', 'bg-red-600', 'x'],
                                default    => ['bg-amber-100 text-amber-800', 'bg-amber-600', 'clock'], // Pending / unknown
                            };
                            [$badgeClass, $iconBg, $icon] = $statusConfig;
                        @endphp

                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.reservations.show', $r) }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">#{{ $r->id }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($r->user)->name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $badgeClass }} ring-1 ring-inset {{ $iconBg === 'bg-green-600' ? 'ring-green-600/20' : ($iconBg === 'bg-red-600' ? 'ring-red-600/20' : 'ring-amber-600/20') }}">
                                    @if($icon === 'check')
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @elseif($icon === 'x')
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @forelse($r->items as $it)
                                    <div class="mb-1">{{ $it->menu->name ?? '—' }} × {{ $it->quantity }}</div>
                                @empty
                                    <span class="text-gray-500">No bundles</span>
                                @endforelse
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $r->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-y-2">
                                <a href="{{ route('admin.reservations.show', $r) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View
                                </a>
                                @if ($label === 'Pending')
                                    <form action="{{ route('admin.reservations.approve', $r) }}" method="POST" class="inline-block ml-2">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent rounded-md text-sm text-white bg-green-600 hover:bg-green-700 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Approve
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.reservations.show', $r) }}#decline" class="inline-flex items-center px-3 py-1 border border-transparent rounded-md text-sm text-white bg-red-600 hover:bg-red-700 transition-colors duration-200 ml-2">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                        </svg>
                                        Decline
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                </tbody>
            </table>
        </div>

        <div class="text-center py-12 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No reservations found</h3>
            <p class="text-sm">Try adjusting your filter or create a new reservation.</p>
        </div>
    @endforelse
    @else
        <div class="text-center py-12 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No reservations yet</h3>
            <p class="text-sm">Get started by creating your first reservation.</p>
        </div>
    @endif

    @if($reservations->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $reservations->links() }}
        </div>
    @endif
</div>
@endsection
