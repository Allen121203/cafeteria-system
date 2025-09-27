@extends('layouts.sidebar')
@section('page-title','Menu Bundles')

@section('content')
@php
    $menuPrices = $priceMap ?? $prices ?? [
        'standard' => ['breakfast' => 150, 'am_snacks' => 150, 'lunch' => 300, 'pm_snacks' => 100, 'dinner' => 300],
        'special'  => ['breakfast' => 170, 'am_snacks' => 100, 'lunch' => 350, 'pm_snacks' => 150, 'dinner' => 350],
    ];
@endphp

<style>[x-cloak]{ display:none !important; }</style>

<div x-data='menuCreateModal({
        defaultType: @json($type),
        defaultMeal: @json($meal),
        prices: @json($menuPrices)
     })'
     class="bg-white p-6 rounded shadow w-full">

  {{-- Header --}}
  <div class="flex items-center justify-between gap-2 flex-wrap w-full">
    <h1 class="text-2xl font-bold">Menu Bundles</h1>

    <button type="button"
            @click="openCreate()"
            class="bg-blue-600 text-white px-4 py-2 rounded">
      + Add Menu
    </button>
  </div>

  {{-- Type Tabs --}}
  <div class="mt-4 flex gap-2">
    @foreach($types as $key => $label)
      <a href="{{ route('admin.menus.index', ['type'=>$key,'meal'=>$meal]) }}"
         class="px-4 py-2 rounded-full border
                {{ $type === $key ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border-transparent' }}">
        {{ $label }}
      </a>
    @endforeach
  </div>

  {{-- Meal Filter --}}
  <form method="GET" action="{{ route('admin.menus.index') }}" class="mt-4 flex items-center gap-3">
    <input type="hidden" name="type" value="{{ $type }}">
    <label class="text-sm text-gray-600">Meal time:</label>
    <select name="meal" class="border rounded p-2" onchange="this.form.submit()">
      @foreach($meals as $key => $label)
        @php $count = data_get($counts, $key, 0); @endphp
        <option value="{{ $key }}" {{ $meal === $key ? 'selected' : '' }}>
          {{ $label }} {{ $count ? "($count)" : '' }}
        </option>
      @endforeach
    </select>
    @if(request('type') || request('meal'))
      <a href="{{ route('admin.menus.index') }}" class="text-sm text-blue-700 underline">Clear</a>
    @endif
  </form>

  {{-- Fixed price pill --}}
  <div class="mt-3 text-sm">
    <span class="px-2 py-1 rounded bg-gray-100 text-gray-700">
      <strong>{{ ucfirst($type) }}</strong> •
      <strong>{{ data_get($meals, $meal, data_get($meals, 'breakfast', '')) }}</strong> •
      Fixed: <strong>₱{{ number_format($activePrice,2) }}</strong> / head
    </span>
  </div>

  {{-- Grid: 4 columns if we have day_no; else free grid --}}
  @if($hasDayNo)
    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      @for($d=1; $d<=4; $d++)
        @php $list = data_get($menusByDay, $d, collect()); @endphp
        <div class="border rounded-xl p-4">
          <div class="flex items-center justify-between">
            <div class="text-xs uppercase tracking-wide text-slate-500">Day {{ $d }}</div>
            @if($list->isEmpty())
              <button type="button"
                      @click='openCreate(@json($type), @json($meal), {{ $d }})'
                      class="text-blue-600 text-sm">
                Create
              </button>
            @endif
          </div>

          @forelse($list as $menu)
            <div class="mt-3 rounded-lg border p-3">
              <div class="flex items-start justify-between gap-3">
                <div>
                  <h2 class="font-semibold text-lg">{{ $menu->name ?? 'Menu #'.$menu->id }}</h2>
                  <div class="text-xs text-gray-500 mt-0.5">
                    <span class="px-2 py-0.5 rounded bg-gray-100">{{ strtoupper(str_replace('_',' ', $menu->meal_time ?? $meal)) }}</span>
                    <span class="ml-2">₱{{ number_format($menu->price ?? $activePrice, 2) }} / head</span>
                  </div>
                  @if(!empty($menu->description))
                    <p class="text-gray-600 text-sm mt-2">{{ $menu->description }}</p>
                  @endif
                </div>
                <button type="button"
                        @click='openEdit({{ $menu->id }}, @json($menu->name), @json($menu->description), @json($menu->menu_type), @json($menu->meal_time), {{ $menu->day_no ?? 1 }}, @json($menu->items->map(function($i) { return ["name" => $i->name, "type" => $i->type]; })->toArray()))'
                        class="text-blue-600 text-sm">Edit</button>
              </div>

              {{-- Foods --}}
              <div class="mt-3">
                <div class="text-xs text-slate-500 mb-1">Foods ({{ $menu->items->count() }})</div>
                @if($menu->items->count())
                  <ul class="space-y-1 text-sm">
                    @foreach($menu->items as $food)
                      <li class="flex items-center justify-between">
                        <span>{{ $food->name }} <span class="text-xs text-gray-500">({{ $food->type }})</span></span>
                        <a href="{{ route('admin.recipes.index', $food) }}" class="text-green-700 text-xs underline">Recipe</a>
                      </li>
                    @endforeach
                  </ul>
                @else
                  <div class="text-sm text-slate-500">No items yet.</div>
                @endif
              </div>
            </div>
          @empty
            <p class="text-sm text-slate-500 mt-3">No bundle yet for Day {{ $d }}.</p>
          @endforelse
        </div>
      @endfor
    </div>
  @else
    {{-- No day_no column: show all in a responsive grid --}}
    <div class="mt-5 grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      @foreach(data_get($menusByDay, 'all', collect()) as $menu)
        <div class="border rounded-lg p-4 h-full">
          <div class="flex items-start justify-between">
            <div>
              <div class="text-xs uppercase tracking-wide text-slate-500">
                {{ strtoupper(str_replace('_',' ', $menu->meal_time ?? $meal)) }}
              </div>
              <h2 class="text-lg font-semibold">{{ $menu->name ?? 'Menu #'.$menu->id }}</h2>
              <div class="text-slate-600 text-sm">
                ₱{{ number_format($menu->price ?? $activePrice, 2) }} / head
              </div>
            </div>
            <button type="button"
                    @click='openEdit({{ $menu->id }}, @json($menu->name), @json($menu->description), @json($menu->menu_type), @json($menu->meal_time), {{ $menu->day_no ?? 1 }}, @json($menu->items->map(function($i) { return ["name" => $i->name, "type" => $i->type]; })->toArray()))'
                    class="text-blue-600 text-sm">
              Edit
            </button>
          </div>

          <div class="mt-3">
            <div class="text-xs text-slate-500 mb-1">Foods ({{ $menu->items->count() }})</div>
            @if($menu->items->count())
              <ul class="space-y-1">
                @foreach($menu->items as $food)
                  <li class="flex items-center justify-between text-sm">
                    <span>{{ $food->name }} <span class="text-xs text-gray-500">({{ $food->type }})</span></span>
                    <a href="{{ route('admin.recipes.index', $food) }}" class="text-green-700 text-xs underline">Recipe</a>
                  </li>
                @endforeach
              </ul>
            @else
              <div class="text-sm text-slate-500">No items yet.</div>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  @endif

  {{-- CREATE MENU MODAL --}}
  <div x-cloak x-show="isCreateOpen" x-transition
       class="fixed inset-0 z-50 flex items-center justify-center">
    <div @click="close()" class="absolute inset-0 bg-black/40"></div>

    <div class="relative bg-white w-full max-w-xl rounded-xl shadow-lg p-6">
      <button class="absolute top-2 right-3 text-gray-500 hover:text-black" @click="close()">✕</button>

      <h2 class="text-xl font-semibold mb-4">Create Menu</h2>

      <form x-ref="createForm" method="POST" action="{{ route('admin.menus.store') }}" class="space-y-4">
        @csrf

        <div>
          <label class="block text-sm font-medium">Menu type</label>
          <select name="menu_type" class="border rounded p-2 w-full mt-1" x-model="form.type">
            <option value="standard">Standard Menu</option>
            <option value="special">Special Menu</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium">Meal time</label>
          <select name="meal_time" class="border rounded p-2 w-full mt-1" x-model="form.meal">
            <option value="breakfast">Breakfast</option>
            <option value="am_snacks">AM Snacks</option>
            <option value="lunch">Lunch</option>
            <option value="pm_snacks">PM Snacks</option>
            <option value="dinner">Dinner</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium">Day</label>
          <select name="day_no" class="border rounded p-2 w-full mt-1" x-model="form.day">
            <template x-for="d in [1,2,3,4]" :key="d">
              <option :value="d" x-text="'Day ' + d"></option>
            </template>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium">Display name (optional)</label>
          <input name="name" class="border rounded p-2 w-full mt-1" placeholder="e.g., Breakfast Day 1" x-model="form.name">
        </div>

        <div>
          <label class="block text-sm font-medium">Description (optional)</label>
          <textarea name="description" class="border rounded p-2 w-full mt-1" rows="3"
                    placeholder="Short description..." x-model="form.description"></textarea>
        </div>

        {{-- Menu Items --}}
        <div class="border rounded p-3">
          <h3 class="font-medium mb-2">Menu Items (Foods)</h3>
          <div class="space-y-2">
            <template x-for="(item, index) in form.items" :key="index">
              <div class="flex gap-2 items-end">
                <input type="text" :name="'items[' + index + '][name]'" x-model="item.name" placeholder="Food name" class="flex-1 border rounded p-2" required>
                <select :name="'items[' + index + '][type]'" x-model="item.type" class="border rounded p-2">
                  <option value="food">Food/Main Dish</option>
                  <option value="drink">Drink</option>
                  <option value="dessert">Dessert</option>
                </select>
                <button type="button" @click="form.items.splice(index, 1)" class="text-red-600">Remove</button>
              </div>
            </template>
            <button type="button" @click="form.items.push({name: '', type: 'food'})" class="text-blue-600 underline text-sm">+ Add Item</button>
          </div>
        </div>

        <div class="text-xs text-gray-600">
          Fixed price per head:
          <span class="font-semibold" x-text="priceText()"></span>
          <span class="text-gray-500">(auto-applied on save)</span>
        </div>

        <div class="flex justify-end gap-2 pt-2">
          <button type="button" @click="close()" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Create</button>
        </div>
      </form>
    </div>
  </div>

  {{-- EDIT MENU MODAL --}}
  <div x-cloak x-show="isEditOpen" x-transition
       class="fixed inset-0 z-50 flex items-center justify-center">
    <div @click="closeEdit()" class="absolute inset-0 bg-black/40"></div>

    <div class="relative bg-white w-full max-w-xl rounded-xl shadow-lg p-6">
      <button class="absolute top-2 right-3 text-gray-500 hover:text-black" @click="closeEdit()">✕</button>

      <h2 class="text-xl font-semibold mb-4">Edit Menu</h2>

      <form method="POST" action="" x-ref="editForm" class="space-y-4">
        @csrf @method('PATCH')

        <div>
          <label class="block text-sm font-medium">Menu type</label>
          <select name="menu_type" class="border rounded p-2 w-full mt-1" x-model="editForm.type">
            <option value="standard">Standard Menu</option>
            <option value="special">Special Menu</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium">Meal time</label>
          <select name="meal_time" class="border rounded p-2 w-full mt-1" x-model="editForm.meal">
            <option value="breakfast">Breakfast</option>
            <option value="am_snacks">AM Snacks</option>
            <option value="lunch">Lunch</option>
            <option value="pm_snacks">PM Snacks</option>
            <option value="dinner">Dinner</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium">Day</label>
          <select name="day_no" class="border rounded p-2 w-full mt-1" x-model="editForm.day">
            <template x-for="d in [1,2,3,4]" :key="d">
              <option :value="d" x-text="'Day ' + d"></option>
            </template>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium">Display name (optional)</label>
          <input name="name" class="border rounded p-2 w-full mt-1" placeholder="e.g., Breakfast Day 1" x-model="editForm.name">
        </div>

        <div>
          <label class="block text-sm font-medium">Description (optional)</label>
          <textarea name="description" class="border rounded p-2 w-full mt-1" rows="3"
                    placeholder="Short description..." x-model="editForm.description"></textarea>
        </div>

        {{-- Menu Items --}}
        <div class="border rounded p-3">
          <h3 class="font-medium mb-2">Menu Items (Foods)</h3>
          <div class="space-y-2">
            <template x-for="(item, index) in editForm.items" :key="index">
              <div class="flex gap-2 items-end">
                <input type="text" :name="'items[' + index + '][name]'" x-model="item.name" placeholder="Food name" class="flex-1 border rounded p-2" required>
                <select :name="'items[' + index + '][type]'" x-model="item.type" class="border rounded p-2">
                  <option value="food">Food/Main Dish</option>
                  <option value="drink">Drink</option>
                  <option value="dessert">Dessert</option>
                </select>
                <button type="button" @click="editForm.items.splice(index, 1)" class="text-red-600">Remove</button>
              </div>
            </template>
            <button type="button" @click="editForm.items.push({name: '', type: 'food'})" class="text-blue-600 underline text-sm">+ Add Item</button>
          </div>
        </div>

        <div class="text-xs text-gray-600">
          Fixed price per head:
          <span class="font-semibold" x-text="editPriceText()"></span>
          <span class="text-gray-500">(auto-applied on save)</span>
        </div>

        <div class="flex justify-end gap-2 pt-2">
          <button type="button" @click="closeEdit()" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
        </div>
      </form>
    </div>
  </div>

</div>

{{-- Alpine component --}}
<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('menuCreateModal', (opts = {}) => ({
      isCreateOpen: false,
      isEditOpen: false,
      prices: opts.prices || {
        standard: { breakfast:150, am_snacks:150, lunch:300, pm_snacks:100, dinner:300 },
        special:  { breakfast:170, am_snacks:100, lunch:350, pm_snacks:150, dinner:350 },
      },
      form: {
        type:  opts.defaultType || 'standard',
        meal:  opts.defaultMeal || 'breakfast',
        day:   1,
        name:  '',
        description: '',
        items: []
      },
      editForm: {
        id: null,
        type: 'standard',
        meal: 'breakfast',
        day: 1,
        name: '',
        description: '',
        items: []
      },

      openCreate(type = null, meal = null, day = 1) {
        if (type) this.form.type = type;
        if (meal) this.form.meal = meal;
        this.form.day  = day || 1;
        this.form.items = [];
        this.isCreateOpen = true;
      },
      close(){ this.isCreateOpen = false; },

      openEdit(id, name, description, type, meal, day, items = []) {
        this.editForm.id = id;
        this.editForm.name = name || '';
        this.editForm.description = description || '';
        this.editForm.type = type || 'standard';
        this.editForm.meal = meal || 'breakfast';
        this.editForm.day = day || 1;
        this.editForm.items = items.map(item => ({ name: item.name, type: item.type }));

        // ✅ Safe JS string: no broken quotes
        this.$refs.editForm.action = `{{ url('/admin/menus') }}/${id}`;

        this.isEditOpen = true;
      },
      closeEdit(){ this.isEditOpen = false; },

      priceText() {
        const t = this.form.type, m = this.form.meal;
        const v = (this.prices[t] && this.prices[t][m]) ? this.prices[t][m] : 0;
        return '₱' + Number(v).toFixed(2) + ' / head';
      },
      editPriceText() {
        const t = this.editForm.type, m = this.editForm.meal;
        const v = (this.prices[t] && this.prices[t][m]) ? this.prices[t][m] : 0;
        return '₱' + Number(v).toFixed(2) + ' / head';
      }
    }));
  });
</script>
@endsection
