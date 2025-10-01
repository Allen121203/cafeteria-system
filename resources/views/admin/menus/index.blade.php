@extends('layouts.sidebar')
@section('page-title','Menu Bundles')

@section('content')
@php
    // Price map (from controller or local fallback)
    $menuPrices = $priceMap ?? $prices ?? [
        'standard' => ['breakfast' => 150, 'am_snacks' => 150, 'lunch' => 300, 'pm_snacks' => 100, 'dinner' => 300],
        'special'  => ['breakfast' => 170, 'am_snacks' => 100, 'lunch' => 350, 'pm_snacks' => 150, 'dinner' => 350],
    ];
    $type = $type ?? request('type', 'standard');
    $meal = $meal ?? request('meal', 'breakfast');
@endphp

<style>[x-cloak]{ display:none !important; }</style>

<div x-data='menuCreateModal({
        defaultType: @json($type),
        defaultMeal: @json($meal === "all" ? "breakfast" : $meal),
        prices: @json($menuPrices)
     })'
     class="space-y-6">

  {{-- Header --}}
  <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
    <div class="flex items-center justify-between gap-2 flex-wrap w-full">
      <div class="flex items-center">
        <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
        </svg>
        <h1 class="text-2xl font-bold text-gray-900">Menu Bundles</h1>
      </div>

      <button type="button"
              @click="openCreate()"
              class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 shadow-lg flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Add Menu
      </button>
    </div>



{{-- Type Tabs (original active color) --}}
<div class="flex gap-2 flex-wrap">
  @foreach($types as $key => $label)
    <a href="{{ route('admin.menus.index', ['type'=>$key,'meal'=>$meal]) }}"
       class="px-4 py-2 rounded-full border transition-colors duration-200
              {{ $type === $key
                  ? 'bg-slate-900 text-white border-slate-900'
                  : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border-transparent' }}">
      {{ $label }}
    </a>
  @endforeach
</div>


    {{-- Meal Filter --}}
    <form method="GET" action="{{ route('admin.menus.index') }}" class="mt-4 flex items-center gap-3 flex-wrap">
      <input type="hidden" name="type" value="{{ $type }}">
      <label class="text-sm text-gray-600">Meal time:</label>
      <select name="meal"
              class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
              onchange="this.form.submit()">
        <option value="all" {{ $meal === 'all' ? 'selected' : '' }}>
          All {{ !empty($totalCount) ? "($totalCount)" : '' }}
        </option>
        @foreach($meals as $key => $label)
          @php $count = data_get($counts ?? [], $key, 0); @endphp
          <option value="{{ $key }}" {{ $meal === $key ? 'selected' : '' }}>
            {{ $label }} {{ $count ? "($count)" : '' }}
          </option>
        @endforeach
      </select>

      @if(request('type') || request('meal'))
        <a href="{{ route('admin.menus.index', ['type'=>'standard','meal'=>'breakfast']) }}" class="text-sm text-blue-700 underline">Clear</a>
      @endif
    </form>

    {{-- Fixed price pill (hide on "All") --}}
    @if($meal !== 'all' && !is_null($activePrice))
      <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-medium">
        <strong>{{ ucfirst($type) }}</strong> •
        <strong>{{ data_get($meals, $meal, 'Meal') }}</strong> •
        Fixed: <strong>₱{{ number_format($activePrice,2) }}</strong> / head
      </div>
    @endif

    {{-- Menus grid --}}
    @php
      // Prefer controller-provided $currentMenus; fallback to $menusByDay
      $list = isset($currentMenus)
                ? $currentMenus
                : ($meal === 'all'
                    ? data_get($menusByDay ?? [], 'all', collect())
                    : data_get($menusByDay ?? [], $meal, collect()));
    @endphp

    <div class="mt-5 grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      @forelse($list as $menu)
        <div id="menu-card-{{ $menu->id }}"
             class="border border-gray-200 rounded-xl shadow-sm p-4 h-full hover:shadow-md transition-shadow">
          <div class="flex items-start justify-between">
            <div>
              <div class="text-xs uppercase tracking-wide text-slate-500">
                {{ strtoupper(str_replace('_',' ', $menu->meal_time)) }}
              </div>
              <h2 class="text-lg font-semibold">{{ $menu->name ?? 'Menu #'.$menu->id }}</h2>
              {{-- <div class="text-slate-600 text-sm">
                ₱{{ number_format($menu->price ?? ($menuPrices[$menu->type][$menu->meal_time] ?? 0), 2) }} / head
              </div> --}}
              @if(!empty($menu->description))
                <p class="text-gray-600 text-sm mt-2">{{ $menu->description }}</p>
              @endif
            </div>
            <div class="flex gap-2">
              <button type="button"
                      @click='openEdit({{ $menu->id }}, @json($menu->name), @json($menu->description), @json($menu->type), @json($menu->meal_time), @json($menu->items->map(fn($i)=>["name"=>$i->name,"type"=>$i->type])->toArray()))'
                      class="text-blue-600 text-sm">
                Edit
              </button>
              <button type="button"
                      @click='openDelete({{ $menu->id }}, @json($menu->name ?? ("Menu #".$menu->id)))'
                      class="text-red-600 text-sm">
                Delete
              </button>
            </div>
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
      @empty
        <div class="col-span-full text-center text-gray-500 py-8">No menus found.</div>
      @endforelse
    </div>
  </div>

  {{-- CREATE MENU MODAL --}}
  <div x-cloak x-show="isCreateOpen" x-transition
       class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div @click="close()" class="absolute inset-0"></div>

    <div class="relative bg-white w-full max-w-2xl rounded-2xl shadow-2xl p-8 transform transition-all duration-300 scale-95"
         x-transition:enter="scale-100"
         x-transition:enter-start="scale-95">
      <button class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 transition-colors duration-200" @click="close()">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>

      <div class="flex items-center mb-6">
        <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
        </svg>
        <h2 class="text-2xl font-bold text-gray-900">Create New Menu</h2>
      </div>

      <form x-ref="createForm" method="POST" action="{{ route('admin.menus.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Menu Type</label>
            <select name="type" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200" x-model="form.type" required>
              <option value="standard">Standard Menu</option>
              <option value="special">Special Menu</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Meal Time</label>
            <select name="meal_time" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200" x-model="form.meal" required>
              <option value="breakfast">Breakfast</option>
              <option value="am_snacks">AM Snacks</option>
              <option value="lunch">Lunch</option>
              <option value="pm_snacks">PM Snacks</option>
              <option value="dinner">Dinner</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Display Name (Optional)</label>
          <input name="name" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200" placeholder="e.g., Breakfast Menu" x-model="form.name">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
          <textarea name="description" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200" rows="3"
                    placeholder="Short description of the menu..."></textarea>
        </div>

        {{-- Menu Items --}}
        <div class="border border-gray-200 rounded-xl p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            Menu Items (Foods)
          </h3>
          <div class="space-y-3">
            <template x-for="(item, index) in form.items" :key="index">
              <div class="flex gap-3 items-end bg-gray-50 p-3 rounded-lg">
                <input type="text" :name="'items[' + index + '][name]'" x-model="item.name" placeholder="Food name" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200" required>
                <select :name="'items[' + index + '][type]'" x-model="item.type" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                  <option value="food">Food/Main Dish</option>
                  <option value="drink">Drink</option>
                  <option value="dessert">Dessert</option>
                </select>
                <button type="button" @click="form.items.splice(index, 1)" class="p-2 text-red-600 hover:text-red-800 transition-colors duration-200 rounded-lg hover:bg-red-50">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
              </div>
            </template>
            <button type="button" @click="form.items.push({name: '', type: 'food'})" class="text-green-600 hover:text-green-800 font-medium transition-colors duration-200 flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Add Item
            </button>
          </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
          <div class="text-sm text-green-800">
            Fixed price per head:
            <span class="font-semibold" x-text="priceText"></span>
            <span class="text-green-600">(auto-applied on save)</span>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
          <button type="button" @click="close()" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-medium">Cancel</button>
          <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 font-medium shadow-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Create Menu
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- EDIT MENU MODAL --}}
  <div x-cloak x-show="isEditOpen" x-transition
       class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div @click="closeEdit()" class="absolute inset-0"></div>

    <div class="relative bg-white w-full max-w-2xl rounded-2xl shadow-2xl p-8 transform transition-all duration-300 scale-95"
         x-transition:enter="scale-100"
         x-transition:enter-start="scale-95">
      <button class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 transition-colors duration-200" @click="closeEdit()">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>

      <div class="flex items-center mb-6">
        <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        <h2 class="text-2xl font-bold text-gray-900">Edit Menu</h2>
      </div>

      <form method="POST" action="" x-ref="editForm" class="space-y-6">
        @csrf @method('PATCH')

        <div class="grid grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Menu Type</label>
            <select name="type" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" x-model="editForm.type" required>
              <option value="standard">Standard Menu</option>
              <option value="special">Special Menu</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Meal Time</label>
            <select name="meal_time" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" x-model="editForm.meal" required>
              <option value="breakfast">Breakfast</option>
              <option value="am_snacks">AM Snacks</option>
              <option value="lunch">Lunch</option>
              <option value="pm_snacks">PM Snacks</option>
              <option value="dinner">Dinner</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Display Name (Optional)</label>
          <input name="name" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="e.g., Breakfast Menu" x-model="editForm.name">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
          <textarea name="description" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" rows="3"
                    placeholder="Short description..."></textarea>
        </div>

        {{-- Menu Items --}}
        <div class="border border-gray-200 rounded-xl p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            Menu Items (Foods)
          </h3>
          <div class="space-y-3">
            <template x-for="(item, index) in editForm.items" :key="index">
              <div class="flex gap-3 items-end bg-gray-50 p-3 rounded-lg">
                <input type="text" :name="'items[' + index + '][name]'" x-model="item.name" placeholder="Food name" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" required>
                <select :name="'items[' + index + '][type]'" x-model="item.type" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                  <option value="food">Food/Main Dish</option>
                  <option value="drink">Drink</option>
                  <option value="dessert">Dessert</option>
                </select>
                <button type="button" @click="editForm.items.splice(index, 1)" class="p-2 text-red-600 hover:text-red-800 transition-colors duration-200 rounded-lg hover:bg-red-50">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
              </div>
            </template>
            <button type="button" @click="editForm.items.push({name: '', type: 'food'})" class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200 flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Add Item
            </button>
          </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
          <div class="text-sm text-blue-800">
            Fixed price per head:
            <span class="font-semibold" x-text="editPriceText"></span>
            <span class="text-blue-600">(auto-applied on save)</span>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
          <button type="button" @click="closeEdit()" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-medium">Cancel</button>
          <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium shadow-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Update Menu
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- DELETE MENU MODAL (teleported) --}}
  <template x-teleport="body">
    <div x-cloak x-show="isDeleteOpen"
         @keydown.escape.window="closeDelete()"
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-50">
      <div class="absolute inset-0" @click="closeDelete()"></div>

      <div x-transition
           class="relative bg-white w-full max-w-md rounded-2xl shadow-2xl p-8 transform transition-all duration-300 scale-95"
           role="dialog" aria-modal="true"
           aria-labelledby="delete-title" aria-describedby="delete-desc"
           x-transition:enter="scale-100"
           x-transition:enter-start="scale-95">

        <button class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 transition-colors duration-200"
                @click="closeDelete()" aria-label="Close">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>

        <div class="flex items-center mb-6">
          <svg class="w-8 h-8 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
          </svg>
          <h2 id="delete-title" class="text-xl font-bold text-gray-900">Delete Menu</h2>
        </div>

        <p id="delete-desc" class="text-gray-600 mb-6">
          Are you sure you want to delete
          <span class="font-semibold text-gray-900" x-text="deleteName || 'this menu'"></span>?
          This action cannot be undone.
        </p>

        <form @submit.prevent="confirmDelete" class="flex justify-end gap-3">
          @csrf
          @method('DELETE')

          <button type="button" @click="closeDelete()"
                  class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-medium">
            Cancel
          </button>

          <button type="submit"
                  class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 font-medium shadow-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            Delete Menu
          </button>
        </form>
      </div>
    </div>
  </template>

  </div>
{{-- Alpine component --}}
<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('menuCreateModal', (opts = {}) => ({
      // State
      isCreateOpen: false,
      isEditOpen: false,
      isDeleteOpen: false,

      deleteId: null,
      deleteName: '',

      prices: opts.prices || {
        standard: { breakfast:150, am_snacks:150, lunch:300, pm_snacks:100, dinner:300 },
        special:  { breakfast:170, am_snacks:100, lunch:350, pm_snacks:150, dinner:350 },
      },

      form: {
        type:  opts.defaultType || 'standard',
        meal:  opts.defaultMeal || 'breakfast',
        name:  '',
        description: '',
        items: []
      },

      editForm: {
        id: null,
        type: 'standard',
        meal: 'breakfast',
        name: '',
        description: '',
        items: []
      },

      // Methods
      openCreate(type = null, meal = null) {
        if (type) this.form.type = type;
        if (meal) this.form.meal = meal;
        this.form.items = [];
        this.isCreateOpen = true;
      },
      close(){ this.isCreateOpen = false; },

      openEdit(id, name, description, type, meal, items = []) {
        this.editForm.id = id;
        this.editForm.name = name || '';
        this.editForm.description = description || '';
        this.editForm.type = type || 'standard';
        this.editForm.meal = meal || 'breakfast';
        this.editForm.items = (items || []).map(i => ({ name: i.name, type: i.type }));

        // set form action safely
        this.$refs.editForm.action = `{{ url('/admin/menus') }}/${id}`;

        this.isEditOpen = true;
      },
      closeEdit(){ this.isEditOpen = false; },

      openDelete(id, name = 'this menu') {
        this.deleteId = id;
        this.deleteName = name || 'this menu';
        this.isDeleteOpen = true;
        document.body.style.overflow = 'hidden';
      },
      closeDelete() {
        this.isDeleteOpen = false;
        this.deleteId = null;
        this.deleteName = '';
        document.body.style.overflow = '';
      },

      // AJAX delete, stay on current page and remove the card
      async confirmDelete() {
        if (!this.deleteId) return;

        const url = `{{ url('/admin/menus') }}/${this.deleteId}`;
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        try {
          const res = await fetch(url, {
            method: 'POST', // Laravel DELETE via method spoofing
            headers: {
              'X-CSRF-TOKEN': token,
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json, text/plain, */*',
              'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: new URLSearchParams({ _method: 'DELETE' })
          });

          if (!res.ok && res.status !== 204) {
            console.warn('Delete failed', await res.text());
          }

          const card = document.getElementById('menu-card-' + this.deleteId);
          if (card) card.remove();

          this.closeDelete(); // close modal, stay on page
        } catch (e) {
          console.error('Delete error', e);
          this.closeDelete();
        }
      },

      // Price helpers
      get priceText() {
        const t = this.form.type, m = this.form.meal;
        if (m === 'all') return 'varies by meal';
        const v = (this.prices[t] && this.prices[t][m]) ? this.prices[t][m] : 0;
        return '₱' + Number(v).toFixed(2) + ' / head';
      },
      get editPriceText() {
        const t = this.editForm.type, m = this.editForm.meal;
        if (m === 'all') return 'varies by meal';
        const v = (this.prices[t] && this.prices[t][m]) ? this.prices[t][m] : 0;
        return '₱' + Number(v).toFixed(2) + ' / head';
      },
    }));
  });
</script>
@endsection
