@extends('layouts.sidebar')
@section('page-title', 'Menus Management')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Menus</h1>

    <!-- Add Menu -->
    <a href="{{ route('admin.menus.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">+ Add Menu</a>

    @foreach($menus as $menu)
        <div class="mt-6 border rounded p-4">
            <h2 class="text-xl font-semibold">{{ $menu->name }}</h2>
            <p class="text-gray-600">{{ $menu->description }}</p>
            <h3 class="mt-4 font-bold">Menu Items</h3>
            <ul>
                @forelse($menu->items as $item)
                    <li class="border p-2 mt-2">
                        {{ $item->name }} ({{ ucfirst($item->type) }})
                        
                        <!-- Recipes -->
                        <ul class="ml-6">
                            @forelse($item->recipes as $recipe)
                                <li>
                                    {{ $recipe->inventoryItem->name }} - {{ $recipe->quantity }} {{ $recipe->unit }}
                                    <form action="{{ route('admin.recipes.destroy', $recipe) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 ml-2">Remove</button>
                                    </form>
                                </li>
                            @empty
                                <li class="text-gray-500">No ingredients yet.</li>
                            @endforelse
                        </ul>

                        <!-- Add Ingredient -->
                        <form action="{{ route('admin.recipes.store') }}" method="POST" class="mt-2">
                            @csrf
                            <input type="hidden" name="menu_item_id" value="{{ $item->id }}">
                            <select name="inventory_item_id" required>
                                @foreach(\App\Models\InventoryItem::all() as $inv)
                                    <option value="{{ $inv->id }}">{{ $inv->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" step="0.1" name="quantity" placeholder="Qty" required>
                            <input type="text" name="unit" placeholder="Unit" required>
                            <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded">+ Ingredient</button>
                        </form>
                    </li>
                @empty
                    <li class="text-gray-500">No items yet.</li>
                @endforelse
            </ul>

            <!-- Add Item -->
            <a href="{{ route('admin.menu-items.create', $menu) }}" class="bg-yellow-500 text-white px-2 py-1 rounded mt-3 inline-block">
                + Add Item
            </a>
        </div>
    @endforeach
</div>
@endsection