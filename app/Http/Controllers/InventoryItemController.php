<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class InventoryItemController extends Controller
{
    public function index(): View
    {
        // Sorting options: name, qty, expiry_date
        $sort = request('sort', 'name');
        $direction = request('direction', 'asc');
        $category = request('category');

        $query = InventoryItem::query();

        if ($category) {
            $query->where('category', $category);
        }

        $items = $query->orderBy($sort, $direction)->get();

        // Get distinct categories for the dropdown
        $categories = InventoryItem::distinct()->pluck('category')->sort();

        return view('admin.inventory.index', compact('items', 'sort', 'direction', 'category', 'categories'));
    }

    public function create(): View
    {
        return view('admin.inventory.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'qty'   => 'required|numeric|min:0',
            'unit'  => 'required|string|max:50',
            'expiry_date' => 'nullable|date',
            'category' => 'required|string|max:100'
        ]);

        InventoryItem::create($data);

        return redirect()->route('admin.inventory.index')->with('success', 'Item added successfully.');
    }

    public function edit(InventoryItem $inventory): View
    {
        return view('admin.inventory.edit', compact('inventory'));
    }

    public function update(Request $request, InventoryItem $inventory): RedirectResponse
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'qty'   => 'required|numeric|min:0',
            'unit'  => 'required|string|max:50',
            'expiry_date' => 'nullable|date',
            'category' => 'required|string|max:100'
        ]);

        $inventory->update($data);

        return redirect()->route('admin.inventory.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(InventoryItem $inventory): RedirectResponse
    {
        $inventory->delete();
        return back()->with('success', 'Item deleted.');
    }
}
