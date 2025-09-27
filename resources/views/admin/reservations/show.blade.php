{{-- resources/views/admin/reservations/show.blade.php --}}
@extends('layouts.sidebar')
@section('page-title','Reservation #'.$r->id)

@section('content')
<style>[x-cloak]{display:none!important}</style>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div x-data="reservationShow({accepted:@js(session('accepted',false)),declined:@js(session('declined',false))})"
     class="bg-white p-6 rounded shadow w-full">

  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">Reservation #{{ $r->id }}</h1>
    <span class="px-2 py-1 rounded text-sm
      {{ $r->status==='approved' ? 'bg-emerald-100 text-emerald-800' : ($r->status==='declined' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
      {{ ucfirst($r->status) }}
    </span>
  </div>

  <div class="grid md:grid-cols-3 gap-6 mt-6">
    <div class="md:col-span-2 space-y-4">
      <div class="border rounded p-4">
        <h2 class="font-semibold mb-2">Event Details</h2>
        <dl class="grid grid-cols-2 gap-2 text-sm">
          <dt class="text-gray-500">Date(s)</dt>
          <dd>{{ $r->start_date && $r->end_date ? "$r->start_date to $r->end_date" : ($r->event_date ?? '—') }}</dd>

          <dt class="text-gray-500">Days</dt>
          <dd>{{ $r->days ?? '—' }}</dd>

          <dt class="text-gray-500">Attendees</dt>
          <dd>{{ $r->guests ?? $r->attendees ?? '—' }}</dd>

          <dt class="text-gray-500">Location</dt>
          <dd>{{ $r->location ?? '—' }}</dd>
        </dl>
      </div>

      <div class="border rounded p-4">
        <h2 class="font-semibold mb-2">Menus Ordered</h2>
        @forelse($r->items as $it)
          <div class="mb-3 border rounded p-3">
            <div class="flex items-center justify-between">
              <div>
                <div class="font-medium">{{ $it->menu->name ?? '—' }}</div>
                <div class="text-sm text-gray-500">Quantity: {{ $it->quantity }}</div>
              </div>
              <a class="text-blue-600 text-sm" href="{{ route('admin.menus.edit', $it->menu_id) }}">View Bundle</a>
            </div>
            @if(optional($it->menu)->items && $it->menu->items->count())
              <ul class="mt-2 text-sm list-disc ml-5">
                @foreach($it->menu->items as $food)
                  <li>{{ $food->name }} <span class="text-xs text-gray-500">({{ $food->type }})</span></li>
                @endforeach
              </ul>
            @endif
          </div>
        @empty
          <p class="text-gray-500 text-sm">No menus linked.</p>
        @endforelse
      </div>
    </div>

    <div class="space-y-4">
      <div class="border rounded p-4">
        <h2 class="font-semibold mb-2">Customer</h2>
        <dl class="text-sm grid grid-cols-3 gap-2">
          <dt class="text-gray-500 col-span-1">Name</dt>
          <dd class="col-span-2">{{ optional($r->user)->name ?? $r->customer_name ?? '—' }}</dd>

          <dt class="text-gray-500 col-span-1">Email</dt>
          <dd class="col-span-2">{{ optional($r->user)->email ?? $r->email ?? '—' }}</dd>

          <dt class="text-gray-500 col-span-1">Phone</dt>
          <dd class="col-span-2">{{ optional($r->user)->phone ?? $r->contact_number ?? '—' }}</dd>
        </dl>
      </div>

      @if($r->status !== 'approved' && $r->status !== 'declined')
      <div class="border rounded p-4" id="decline">
        <h2 class="font-semibold mb-3">Actions</h2>

        <form method="POST" action="{{ route('admin.reservations.approve', $r) }}" class="mb-2">
          @csrf @method('PATCH')
          <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded">
            Accept Reservation
          </button>
        </form>

        <button type="button" @click="openDecline()" class="w-full bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded">
          Decline Reservation
        </button>
      </div>
      @endif

      @if($r->status === 'declined' && !empty($r->decline_reason))
      <div class="border rounded p-4">
        <h2 class="font-semibold mb-2">Decline Reason</h2>
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
      <h3 class="text-lg font-semibold mb-2">Reservation Accepted</h3>
      <p class="text-sm text-gray-600">Inventory was updated and the customer was notified.</p>
      <button class="mt-4 px-4 py-2 bg-emerald-600 text-white rounded" @click="acceptedOpen=false">OK</button>
    </div>
  </div>

  {{-- Decline modal --}}
  <div x-cloak x-show="declineOpen" x-transition
       class="fixed inset-0 z-50 flex items-center justify-center">
    <div @click="declineOpen=false" class="absolute inset-0 bg-black/40"></div>
    <div class="relative bg-white rounded-xl shadow-lg p-6 w-full max-w-lg">
      <button class="absolute top-2 right-3 text-gray-500 hover:text-black" @click="declineOpen=false">✕</button>
      <h3 class="text-lg font-semibold mb-3">Decline Reservation</h3>
      <p class="text-sm text-gray-600 mb-3">Please provide a reason. The customer will receive this via email and SMS.</p>

      <form method="POST" action="{{ route('admin.reservations.decline', $r) }}" class="space-y-3">
        @csrf @method('PATCH')
        <textarea name="reason" rows="4" required class="w-full border rounded p-2" placeholder="Reason for declining..."></textarea>
        @error('reason') <p class="text-sm text-rose-600">{{ $message }}</p> @enderror

        <div class="flex justify-end gap-2">
          <button type="button" class="px-4 py-2 bg-gray-200 rounded" @click="declineOpen=false">Cancel</button>
          <button class="px-4 py-2 bg-rose-600 text-white rounded">Submit</button>
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
