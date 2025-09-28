{{-- resources/views/admin/reservations/show.blade.php --}}
@extends('layouts.sidebar')
@section('page-title','Reservation #'.$r->id)

@section('content')
<style>[x-cloak]{display:none!important}</style>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div x-data="reservationShow({accepted:@js(session('accepted',false)),declined:@js(session('declined',false))})"
     class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 w-full">

  <div class="flex items-center justify-between mb-6">
    <div class="flex items-center">
        <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
        </svg>
        <h1 class="text-2xl font-bold text-gray-900">Reservation #{{ $r->id }}</h1>
    </div>
    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ring-1 ring-inset
      {{ $r->status==='approved' ? 'bg-green-100 text-green-800 ring-green-600/20' : ($r->status==='declined' ? 'bg-red-100 text-red-800 ring-red-600/20' : 'bg-amber-100 text-amber-800 ring-amber-600/20') }}">
        @if($r->status === 'approved')
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
        @elseif($r->status === 'declined')
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        @else
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
            </svg>
        @endif
        {{ ucfirst($r->status) }}
    </span>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Event Details Card -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-gray-50 rounded-xl p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h2 class="text-lg font-semibold text-gray-900">Event Details</h2>
            </div>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-gray-500 font-medium">Date(s)</dt>
                    <dd class="mt-1 text-gray-900">{{ $r->start_date && $r->end_date ? "$r->start_date to $r->end_date" : ($r->event_date ?? '—') }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 font-medium">Days</dt>
                    <dd class="mt-1 text-gray-900">{{ $r->days ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 font-medium">Attendees</dt>
                    <dd class="mt-1 text-gray-900 font-medium text-green-600">{{ $r->guests ?? $r->attendees ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 font-medium">Location</dt>
                    <dd class="mt-1 text-gray-900">{{ $r->location ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        <!-- Menus Ordered Card -->
        <div class="bg-gray-50 rounded-xl p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h2 class="text-lg font-semibold text-gray-900">Menus Ordered</h2>
            </div>
            @forelse($r->items as $it)
                <div class="mb-4 bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <div class="font-medium text-gray-900">{{ $it->menu->name ?? '—' }}</div>
                            <div class="text-sm text-gray-600">Quantity: {{ $it->quantity }}</div>
                        </div>
                        <a class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200" href="{{ route('admin.menus.edit', $it->menu_id) }}">
                            View Bundle
                        </a>
                    </div>
                    @if(optional($it->menu)->items && $it->menu->items->count())
                        <ul class="space-y-1 text-sm">
                            @foreach($it->menu->items as $food)
                                <li class="flex items-center text-gray-700">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2H5a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.586l-1.293-1.293a1 1 0 00-1.414 0L10 16.414 7.707 14.121a1 1 0 00-1.414 0L5.586 16H3a2 2 0 00-2 2v4a2 2 0 002 2h12a2 2 0 002-2v-2h-4a4 4 0 01-4-4v-1.586l1.293-1.293a1 1 0 001.414 0L15 11.414l2.293 2.293A1 1 0 0019 14v4a2 2 0 01-2 2H7z"></path>
                                    </svg>
                                    {{ $food->name }} <span class="text-xs text-gray-500 ml-1">({{ $food->type }})</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500">No items in this bundle.</p>
                    @endif
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p>No menus linked to this reservation.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Customer and Actions Sidebar -->
    <div class="space-y-6">
        <!-- Customer Card -->
        <div class="bg-gray-50 rounded-xl p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <h2 class="text-lg font-semibold text-gray-900">Customer Information</h2>
            </div>
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500 font-medium">Name</dt>
                    <dd class="text-gray-900 font-medium">{{ optional($r->user)->name ?? $r->customer_name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 font-medium">Email</dt>
                    <dd class="text-gray-900">{{ optional($r->user)->email ?? $r->email ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 font-medium">Phone</dt>
                    <dd class="text-gray-900">{{ optional($r->user)->phone ?? $r->contact_number ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        @if($r->status !== 'approved' && $r->status !== 'declined')
        <!-- Actions Card -->
        <div class="bg-gray-50 rounded-xl p-6" id="decline">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <h2 class="text-lg font-semibold text-gray-900">Reservation Actions</h2>
            </div>

            <form method="POST" action="{{ route('admin.reservations.approve', $r) }}" class="mb-4">
                @csrf @method('PATCH')
                <button class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 font-medium shadow-lg flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Accept Reservation
                </button>
            </form>

            <button type="button" @click="openDecline()" class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 font-medium shadow-lg flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                </svg>
                Decline Reservation
            </button>
        </div>
        @endif

        @if($r->status === 'declined' && !empty($r->decline_reason))
        <!-- Decline Reason Card -->
        <div class="bg-gray-50 rounded-xl p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h2 class="text-lg font-semibold text-gray-900">Decline Reason</h2>
            </div>
            <p class="text-sm text-gray-700">{{ $r->decline_reason }}</p>
        </div>
        @endif
    </div>
  </div>

  {{-- Accepted popup --}}
  <div x-cloak x-show="acceptedOpen" x-transition
       class="fixed inset-0 z-50 flex items-center justify-center">
    <div @click="acceptedOpen=false" class="absolute inset-0 bg-black/40"></div>
    <div class="relative bg-white rounded-xl shadow-lg p-6 w-full max-w-sm text-center">
      <div class="flex items-center justify-center mb-4">
        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
      </div>
      <h3 class="text-lg font-semibold mb-2">Reservation Accepted</h3>
      <p class="text-sm text-gray-600">Inventory was updated and the customer was notified.</p>
      <button class="mt-4 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 font-medium shadow-lg" @click="acceptedOpen=false">OK</button>
    </div>
  </div>

  {{-- Decline modal --}}
  <div x-cloak x-show="declineOpen" x-transition
       class="fixed inset-0 z-50 flex items-center justify-center">
    <div @click="declineOpen=false" class="absolute inset-0 bg-black/40"></div>
    <div class="relative bg-white rounded-xl shadow-lg p-6 w-full max-w-lg">
      <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition-colors duration-200" @click="declineOpen=false">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
      <h3 class="text-lg font-semibold mb-3">Decline Reservation</h3>
      <p class="text-sm text-gray-600 mb-6">Please provide a reason. The customer will receive this via email and SMS.</p>

      <form method="POST" action="{{ route('admin.reservations.decline', $r) }}" class="space-y-4">
        @csrf @method('PATCH')
        <div class="space-y-2">
          <label for="reason" class="block text-sm font-medium text-gray-700">Reason for declining</label>
          <textarea name="reason" id="reason" rows="4" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 resize-none" placeholder="Please provide a detailed reason for declining this reservation..."></textarea>
          @error('reason') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-4">
          <button type="button" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-medium" @click="declineOpen=false">Cancel</button>
          <button class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 font-medium shadow-lg">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('reservationShow', (opts) => ({
      acceptedOpen: false,
      declineOpen: false,
      openDecline(){ this.declineOpen = true; },
      init(){
        if (opts.accepted) this.acceptedOpen = true;
      }
    }));
  });
</script>
@endsection
