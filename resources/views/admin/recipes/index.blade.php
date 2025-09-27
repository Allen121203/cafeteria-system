@extends('layouts.sidebar')
@section('page-title','Recipe: '.$menuItem->name)

@section('content')
<div class="bg-white p-6 rounded shadow">
  <h1 class="text-2xl font-bold mb-2">Recipe for: {{ $menuItem->name }}</h1>
  <p class="text-gray-500">Bundle: {{ $menuItem->menu->name }}</p>

  <form action="{{ route('admin.recipes.store', $menuItem) }}" method="POST" class="mt-4 flex gap-3">
    @csrf
    <select name="inventory_item_id" class="border rounded p-2" required>
      <option value="">-- Select Ingredient --</option>
      @foreach($inventory as $inv)
        <option value="{{ $inv->id }}">{{ $inv->name }} ({{ $inv->qty }} {{ $inv->unit }} left)</option>
      @endforeach
    </select>
    <input type="number" step="0.001" name="quantity_needed" class="border rounded p-2 w-36" placeholder="Qty per serving" required>
    <button class="bg-green-600 text-white px-4 py-2 rounded">Add/Update</button>
  </form>

  <table class="w-full mt-5 border-collapse border">
    <thead class="bg-gray-100">
      <tr><th class="p-2 text-left">Ingredient</th><th>Qty per serving</th><th>Action</th></tr>
    </thead>
    <tbody>
      @forelse($menuItem->recipes as $r)
        <tr class="border-t">
          <td class="p-2">{{ $r->inventoryItem->name }}</td>
          <td>{{ $r->quantity_needed }} {{ $r->inventoryItem->unit }}</td>
          <td class="text-center">
            <form action="{{ route('admin.recipes.destroy', [$menuItem,$r]) }}" method="POST">
              @csrf @method('DELETE')
              <button class="text-red-600">Remove</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="3" class="text-center text-gray-500">No ingredients yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
