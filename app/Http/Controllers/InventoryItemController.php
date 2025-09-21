<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    public function index()
    {
        // Sorting options: name, qty, expiry_date
        $sort = request('sort', 'name');
        $direction = request('direction', 'asc');

        $items = InventoryItem::orderBy($sort, $direction)->get();

        return view('admin.inventory.index', compact('items', 'sort', 'direction'));
    }

    public function create()
    {
        return view('admin.inventory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'qty'   => 'required|numeric|min:0',
            'unit'  => 'required|string|max:50',
            'expiry_date' => 'nullable|date',
            'category' => 'required|string|max:100'
        ]);

        InventoryItem::create($request->all());

        return redirect()->route('admin.inventory.index')->with('success', 'Item added successfully.');
    }

    public function edit(InventoryItem $inventory)
    {
        return view('admin.inventory.edit', compact('inventory'));
    }

    public function update(Request $request, InventoryItem $inventory)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'qty'   => 'required|numeric|min:0',
            'unit'  => 'required|string|max:50',
            'expiry_date' => 'nullable|date',
            'category' => 'required|string|max:100'
        ]);

        $inventory->update($request->all());

        return redirect()->route('admin.inventory.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(InventoryItem $inventory)
    {
        $inventory->delete();
        return back()->with('success', 'Item deleted.');
    }
}
