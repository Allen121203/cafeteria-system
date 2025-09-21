<?php
namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|numeric|min:0.1',
            'unit' => 'required|string|max:50'
        ]);

        Recipe::create($request->only('menu_item_id', 'inventory_item_id', 'quantity', 'unit'));

        return back()->with('success', 'Ingredient added to recipe!');
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return back()->with('success', 'Ingredient removed.');
    }
}
