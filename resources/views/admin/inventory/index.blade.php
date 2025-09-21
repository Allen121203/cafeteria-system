@extends('layouts.sidebar')
@section('page-title', 'Inventory Management')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Inventory</h1>

<!-- Add Item Button -->
<button onclick="document.getElementById('addItemModal').classList.remove('hidden')"
    class="bg-green-600 text-white px-4 py-2 rounded">
    + Add Item
</button>


    <table class="w-full mt-6 border-collapse border">
        <thead>
            <tr class="bg-gray-200">
                <th><a href="?sort=name">Item</a></th>
                <th><a href="?sort=qty">Quantity</a></th>
                <th>Unit</th>
                <th><a href="?sort=expiry_date">Expiry Date</a></th>
                <th>Category</th>
                <th>Last Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->unit }}</td>
                    <td>{{ $item->expiry_date ?? 'N/A' }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ $item->updated_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('admin.inventory.edit', $item) }}" class="text-blue-600">Edit</a>
                        <form action="{{ route('admin.inventory.destroy', $item) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-600 ml-2">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-gray-500">No inventory items found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
@include('admin.inventory.create')
