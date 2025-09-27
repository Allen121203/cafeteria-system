<?php
namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Recipe;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RecipeController extends Controller
{
    public function index(MenuItem $menuItem): View
    {
        $menuItem->load('recipes.inventoryItem','menu');
        $inventory = InventoryItem::orderBy('name')->get();
        return view('admin.recipes.index', compact('menuItem','inventory'));
    }

    public function store(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $data = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity_needed'   => 'required|numeric|min:0.001',
        ]);

        $inventoryItem = InventoryItem::find($data['inventory_item_id']);

        $menuItem->recipes()->updateOrCreate(
            ['inventory_item_id' => $data['inventory_item_id']],
            ['quantity_needed'   => $data['quantity_needed'], 'unit' => $inventoryItem->unit]
        );

        return back()->with('success','Ingredient added/updated.');
    }

    public function destroy(MenuItem $menuItem, Recipe $recipe): RedirectResponse
    {
        abort_unless($recipe->menu_item_id === $menuItem->id, 404);
        $recipe->delete();
        return back()->with('success','Ingredient removed.');
    }
}
