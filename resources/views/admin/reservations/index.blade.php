@extends('layouts.sidebar')
@section('page-title', 'Reservations')

@section('content')
<div class="bg-white p-6 rounded shadow">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Reservations</h1>

    <!-- Status Filter -->
    <form method="GET" action="{{ route('admin.reservations') }}" class="flex items-center gap-2">
      <label class="text-sm text-gray-600">Filter:</label>
      <select name="status" class="border rounded p-2" onchange="this.form.submit()">
        @php
          $pending  = data_get($counts, 'pending', 0);
          $approved = data_get($counts, 'approved', 0);
          $declined = data_get($counts, 'declined', 0);
        @endphp
        <option value="" {{ $status === null ? 'selected' : '' }}>All</option>
        <option value="pending"  {{ $status === 'pending'  ? 'selected' : '' }}>Pending {{ $pending  ? "($pending)"  : '' }}</option>
        <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved {{ $approved ? "($approved)" : '' }}</option>
        <option value="declined" {{ $status === 'declined' ? 'selected' : '' }}>Declined {{ $declined ? "($declined)" : '' }}</option>
      </select>

      @if(request('status'))
        <a href="{{ route('admin.reservations') }}" class="text-sm text-blue-700 underline">Clear</a>
      @endif
    </form>
  </div>

  <table class="w-full border-collapse border">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-2 text-left">ID</th>
        <th class="text-left">Customer</th>
        <th class="text-left">Status</th>
        <th class="text-left">Bundles</th>
        <th class="text-left">Created</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
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

        $badgeClass = match ($label) {
          'Approved' => 'bg-green-100 text-green-800',
          'Declined' => 'bg-red-100 text-red-800',
          default    => 'bg-amber-100 text-amber-800', // Pending / unknown
        };
      @endphp

{{-- inside your <tbody> loop --}}
<tr class="border-t hover:bg-gray-50">
  <td class="p-2">
    <a href="{{ route('admin.reservations.show', $r) }}" class="text-blue-700 underline">#{{ $r->id }}</a>
  </td>
  <td>{{ optional($r->user)->name ?? '—' }}</td>
  <td>
    <span class="px-2 py-1 rounded text-xs font-semibold {{ $badgeClass }}">{{ $label }}</span>
  </td>
  <td>
    @foreach($r->items as $it)
      <div>{{ $it->menu->name ?? '—' }} × {{ $it->quantity }}</div>
    @endforeach
  </td>
  <td>{{ $r->created_at->format('Y-m-d H:i') }}</td>
  <td class="text-center">
    <a href="{{ route('admin.reservations.show',$r) }}" class="px-2 py-1 border rounded text-sm">View</a>
    @if ($label === 'Pending')
      <form action="{{ route('admin.reservations.approve', $r) }}" method="POST" class="inline ml-1">
        @csrf @method('PATCH')
        <button class="bg-green-600 text-white px-3 py-1 rounded text-sm">Approve</button>
      </form>
      <a href="{{ route('admin.reservations.show',$r) }}#decline"
         class="ml-1 bg-rose-600 text-white px-3 py-1 rounded text-sm">Decline</a>
    @endif
  </td>
</tr>

    @empty
      <tr><td colspan="6" class="text-center text-gray-500 p-4">No reservations found.</td></tr>
    @endforelse
    </tbody>
  </table>

  <div class="mt-4">
    {{ $reservations->links() }} {{-- keeps ?status=... thanks to withQueryString() --}}
  </div>
</div>
@endsection
