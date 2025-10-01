<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MenuController extends Controller
{
    private const TYPES = ['standard' => 'Standard Menu', 'special' => 'Special Menu'];
    private const MEALS = [
        'breakfast'  => 'Breakfast',
        'am_snacks'  => 'AM Snacks',
        'lunch'      => 'Lunch',
        'pm_snacks'  => 'PM Snacks',
        'dinner'     => 'Dinner',
    ];
    private const PRICE = [
        'standard' => ['breakfast'=>150, 'am_snacks'=>150, 'lunch'=>300, 'pm_snacks'=>100, 'dinner'=>300],
        'special'  => ['breakfast'=>170, 'am_snacks'=>100, 'lunch'=>350, 'pm_snacks'=>150, 'dinner'=>350],
    ];

    public function index(Request $request): View
    {
        // Defaults: standard + breakfast
        $type = $request->query('type', 'standard');
        $meal = $request->query('meal', 'breakfast');

        // Sanitize type (only allow our keys)
        if (!array_key_exists($type, self::TYPES)) {
            $type = 'standard';
        }
        // Sanitize meal: allow 'all' or a valid key
        if ($meal !== 'all' && !array_key_exists($meal, self::MEALS)) {
            $meal = 'breakfast';
        }

        // Base query with relations
        $base = Menu::with('items');

        // Apply type filter if column exists
        if (Schema::hasColumn('menus', 'type')) {
            $base->where('type', $type);
        }

        // Build current list depending on meal
        $currentQuery = clone $base;
        if ($meal !== 'all' && Schema::hasColumn('menus', 'meal_time')) {
            $currentQuery->where('meal_time', $meal);
        }
        $currentMenus = $currentQuery->orderBy('created_at', 'desc')->get();

        // Menus by day (for convenience; 'all' = all meals for this type)
        $menusByDay = [
            'all'        => (clone $base)->orderBy('created_at', 'desc')->get(),
            'breakfast'  => (clone $base)->where('meal_time', 'breakfast')->orderBy('created_at', 'desc')->get(),
            'am_snacks'  => (clone $base)->where('meal_time', 'am_snacks')->orderBy('created_at', 'desc')->get(),
            'lunch'      => (clone $base)->where('meal_time', 'lunch')->orderBy('created_at', 'desc')->get(),
            'pm_snacks'  => (clone $base)->where('meal_time', 'pm_snacks')->orderBy('created_at', 'desc')->get(),
            'dinner'     => (clone $base)->where('meal_time', 'dinner')->orderBy('created_at', 'desc')->get(),
        ];

        // Counts per meal (for current type)
        if (Schema::hasColumn('menus', 'meal_time')) {
            $counts = Menu::selectRaw('meal_time, COUNT(*) as total')
                ->when(Schema::hasColumn('menus', 'type'), fn($qq) => $qq->where('type', $type))
                ->groupBy('meal_time')
                ->pluck('total', 'meal_time');
        } else {
            $counts = collect();
        }
        $totalCount = (int) ($counts->sum() ?? 0);

        // Active price only when a single meal is chosen
        $activePrice = ($meal !== 'all' && isset(self::PRICE[$type][$meal])) ? self::PRICE[$type][$meal] : null;

        return view('admin.menus.index', [
            'type'         => $type,
            'meal'         => $meal,
            'types'        => self::TYPES,
            'meals'        => self::MEALS,
            'activePrice'  => $activePrice,
            'menusByDay'   => $menusByDay,
            'currentMenus' => $currentMenus,
            'counts'       => $counts,
            'totalCount'   => $totalCount,
            'priceMap'     => self::PRICE,
        ]);
    }

    public function create(Request $request): View
    {
        $type = $request->query('type', 'standard');
        $meal = $request->query('meal', 'breakfast');

        if (!array_key_exists($type, self::TYPES)) $type = 'standard';
        if (!array_key_exists($meal, self::MEALS)) $meal = 'breakfast';

        $has = [
            'type'        => Schema::hasColumn('menus', 'type'),
            'meal_time'   => Schema::hasColumn('menus', 'meal_time'),
            'name'        => Schema::hasColumn('menus', 'name'),
            'description' => Schema::hasColumn('menus', 'description'),
            'price'       => Schema::hasColumn('menus', 'price'),
        ];

        $activePrice = ($has['price'] && isset(self::PRICE[$type][$meal])) ? self::PRICE[$type][$meal] : null;

        // Reuse index view to keep UX consistent
        return view('admin.menus.index', [
            'type'        => $type,
            'meal'        => $meal,
            'types'       => self::TYPES,
            'meals'       => self::MEALS,
            'activePrice' => $activePrice,
            'has'         => $has,
            'priceMap'    => self::PRICE,
            'menusByDay'  => ['all' => collect()],
            'currentMenus'=> collect(),
            'counts'      => collect(),
            'totalCount'  => 0,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $hasType   = Schema::hasColumn('menus', 'type');
        $hasMeal   = Schema::hasColumn('menus', 'meal_time');
        $hasName   = Schema::hasColumn('menus', 'name');
        $hasDesc   = Schema::hasColumn('menus', 'description');
        $hasPrice  = Schema::hasColumn('menus', 'price');

        $rules = [];
        if ($hasType) $rules['type'] = 'required|in:standard,special';
        if ($hasMeal) $rules['meal_time'] = 'required|in:breakfast,am_snacks,lunch,pm_snacks,dinner';
        if ($hasName) $rules['name']      = 'nullable|string|max:255';
        if ($hasDesc) $rules['description']= 'nullable|string';
        $rules['items'] = 'array';
        $rules['items.*.name'] = 'required|string|max:255';
        $rules['items.*.type'] = 'required|in:food,drink,dessert';

        $data = $request->validate($rules);

        if ($hasPrice && $hasType && $hasMeal) {
            $type = $data['type'] ?? 'standard';
            $meal = $data['meal_time'] ?? 'breakfast';
            $data['price'] = self::PRICE[$type][$meal] ?? 0;
        }

        $payload = [];
        foreach (['type','meal_time','name','description','price'] as $f) {
            if (isset($data[$f])) $payload[$f] = $data[$f];
        }

        $menu = Menu::create($payload);

        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $item) {
                $menu->items()->create($item);
            }
        }

        return redirect()->route('admin.menus.index', ['type' => $payload['type'] ?? 'standard', 'meal' => $payload['meal_time'] ?? 'breakfast'])
            ->with('success', 'Menu created. Add at least 5 foods to complete the bundle.');
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $hasType   = Schema::hasColumn('menus', 'type');
        $hasMeal   = Schema::hasColumn('menus', 'meal_time');
        $hasName   = Schema::hasColumn('menus', 'name');
        $hasDesc   = Schema::hasColumn('menus', 'description');
        $hasPrice  = Schema::hasColumn('menus', 'price');

        $rules = [];
        if ($hasType) $rules['type'] = 'required|in:standard,special';
        if ($hasMeal) $rules['meal_time'] = 'required|in:breakfast,am_snacks,lunch,pm_snacks,dinner';
        if ($hasName) $rules['name']      = 'nullable|string|max:255';
        if ($hasDesc) $rules['description']= 'nullable|string';
        $rules['items'] = 'array';
        $rules['items.*.name'] = 'required|string|max:255';
        $rules['items.*.type'] = 'required|in:food,drink,dessert';

        $data = $request->validate($rules);

        if ($hasPrice && $hasType && $hasMeal) {
            $type = $data['type'] ?? 'standard';
            $meal = $data['meal_time'] ?? 'breakfast';
            $data['price'] = self::PRICE[$type][$meal] ?? 0;
        }

        $payload = [];
        foreach (['type','meal_time','name','description','price'] as $f) {
            if (isset($data[$f])) $payload[$f] = $data[$f];
        }

        $menu->update($payload);

        $menu->items()->delete();
        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $item) {
                $menu->items()->create($item);
            }
        }

        return back()->with('success', 'Menu updated.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted.');
    }

    public function addItem(Request $request, Menu $menu): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:food,drink,dessert',
        ]);

        $menu->items()->create([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return back()->with('success', 'Menu item added.');
    }

    public function customerIndex(): View
    {
        $allMenus = Menu::with('items')->get();

        $menus = $allMenus->groupBy('meal_time')->map(function ($mealsByMeal) {
            return $mealsByMeal->groupBy('type');
        });

        return view('customer.menu', compact('menus'));
    }
}
