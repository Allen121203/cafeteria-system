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
        $type = $request->query('type', 'standard');
        $meal = $request->query('meal', 'breakfast');

        $q = Menu::with('items');

        // Guarded filters – only apply if the columns exist
        if (Schema::hasColumn('menus', 'menu_type') && isset(self::TYPES[$type])) {
            $q->where('menu_type', $type);
        }
        if (Schema::hasColumn('menus', 'meal_time') && isset(self::MEALS[$meal])) {
            $q->where('meal_time', $meal);
        }

        // Day grouping only if column exists; otherwise just list all
        $hasDayNo = Schema::hasColumn('menus', 'day_no');
        if ($hasDayNo) {
            $menusByDay = $q->orderBy('day_no')->get()->groupBy('day_no');
        } else {
            $menusByDay = collect(['all' => $q->orderBy('created_at', 'desc')->get()]);
        }

        // Meal counts for the dropdown (guarded)
        if (Schema::hasColumn('menus', 'meal_time')) {
            $counts = Menu::selectRaw('meal_time, COUNT(*) as total')
                ->when(Schema::hasColumn('menus','menu_type') && isset(self::TYPES[$type]),
                    fn($qq) => $qq->where('menu_type', $type))
                ->groupBy('meal_time')
                ->pluck('total', 'meal_time');
        } else {
            $counts = collect();
        }

        $activePrice = self::PRICE[$type][$meal] ?? null;

        return view('admin.menus.index', [
            'type'        => $type,
            'meal'        => $meal,
            'types'       => self::TYPES,
            'meals'       => self::MEALS,
            'activePrice' => $activePrice,
            'menusByDay'  => $menusByDay,
            'hasDayNo'    => $hasDayNo,
            'counts'      => $counts,
        ]);
    }
    public function create(Request $request): View
    {
        $type = $request->query('type', 'standard');
        $meal = $request->query('meal', 'breakfast');
        $day  = (int) $request->query('day', 1);

        // What columns exist right now?
        $has = [
            'menu_type'   => Schema::hasColumn('menus', 'menu_type'),
            'meal_time'   => Schema::hasColumn('menus', 'meal_time'),
            'day_no'      => Schema::hasColumn('menus', 'day_no'),
            'name'        => Schema::hasColumn('menus', 'name'),
            'description' => Schema::hasColumn('menus', 'description'),
            'price'       => Schema::hasColumn('menus', 'price'),
        ];

        $activePrice = ($has['price'] && isset(self::PRICE[$type][$meal])) ? self::PRICE[$type][$meal] : null;

        return view('admin.menus.create', [
            'type'        => $type,
            'meal'        => $meal,
            'day'         => $day,
            'types'       => self::TYPES,
            'meals'       => self::MEALS,
            'activePrice' => $activePrice,
            'has'         => $has,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Detect columns to validate only what exists
        $hasType   = Schema::hasColumn('menus', 'menu_type');
        $hasMeal   = Schema::hasColumn('menus', 'meal_time');
        $hasDay    = Schema::hasColumn('menus', 'day_no');
        $hasName   = Schema::hasColumn('menus', 'name');
        $hasDesc   = Schema::hasColumn('menus', 'description');
        $hasPrice  = Schema::hasColumn('menus', 'price');
        $hasNumber = Schema::hasColumn('menus', 'number');

        $rules = [];
        if ($hasType) $rules['menu_type'] = 'required|in:standard,special';
        if ($hasMeal) $rules['meal_time'] = 'required|in:breakfast,am_snacks,lunch,pm_snacks,dinner';
        if ($hasDay)  $rules['day_no']    = 'required|integer|min:1|max:4';
        if ($hasName) $rules['name']      = 'nullable|string|max:255';
        if ($hasDesc) $rules['description']= 'nullable|string';
        $rules['items'] = 'array';
        $rules['items.*.name'] = 'required|string|max:255';
        $rules['items.*.type'] = 'required|in:food,drink,dessert';

        $data = $request->validate($rules);

        // If number column exists, auto-increment based on existing menus
        if ($hasNumber) {
            $data['number'] = Menu::max('number') + 1;
        }

        // If price column exists, auto-set based on type+meal
        if ($hasPrice && $hasType && $hasMeal) {
            $type = $data['menu_type'] ?? 'standard';
            $meal = $data['meal_time'] ?? 'breakfast';
            $data['price'] = self::PRICE[$type][$meal] ?? 0;
        }

        // Build only fields that exist in DB
        $payload = [];
        foreach (['menu_type','meal_time','day_no','number','name','description','price'] as $f) {
            if (isset($data[$f])) $payload[$f] = $data[$f];
        }

        $menu = Menu::create($payload);

        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $item) {
                $menu->items()->create($item);
            }
        }

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu created. Add at least 5 foods to complete the bundle.');
    }

    public function edit(Menu $menu): View
    {
        return view('admin.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        // Similar to store, but for update
        $hasType   = Schema::hasColumn('menus', 'menu_type');
        $hasMeal   = Schema::hasColumn('menus', 'meal_time');
        $hasDay    = Schema::hasColumn('menus', 'day_no');
        $hasName   = Schema::hasColumn('menus', 'name');
        $hasDesc   = Schema::hasColumn('menus', 'description');
        $hasPrice  = Schema::hasColumn('menus', 'price');
        $hasNumber = Schema::hasColumn('menus', 'number');

        $rules = [];
        if ($hasType) $rules['menu_type'] = 'required|in:standard,special';
        if ($hasMeal) $rules['meal_time'] = 'required|in:breakfast,am_snacks,lunch,pm_snacks,dinner';
        if ($hasDay)  $rules['day_no']    = 'required|integer|min:1|max:4';
        if ($hasName) $rules['name']      = 'nullable|string|max:255';
        if ($hasDesc) $rules['description']= 'nullable|string';
        $rules['items'] = 'array';
        $rules['items.*.name'] = 'required|string|max:255';
        $rules['items.*.type'] = 'required|in:food,drink,dessert';

        $data = $request->validate($rules);

        // If number column exists, auto-increment based on existing menus
        if ($hasNumber) {
            $data['number'] = Menu::max('number') + 1;
        }

        // If price column exists, auto-set based on type+meal
        if ($hasPrice && $hasType && $hasMeal) {
            $type = $data['menu_type'] ?? 'standard';
            $meal = $data['meal_time'] ?? 'breakfast';
            $data['price'] = self::PRICE[$type][$meal] ?? 0;
        }

        // Build only fields that exist in DB
        $payload = [];
        foreach (['menu_type','meal_time','day_no','number','name','description','price'] as $f) {
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
}
