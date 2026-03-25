<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

class InventoryItemController extends Controller
{
    public function index(): View
    {
        // Sorting options: name, qty, expiry_date
        $allowedSorts = ['name', 'qty', 'expiry_date', 'category', 'updated_at'];
        $sort = request('sort', 'name');
        $direction = strtolower((string) request('direction', 'asc')) === 'desc' ? 'desc' : 'asc';
        $category = request('category');

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'name';
        }

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
        return view('admin.inventory.index');
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'qty'   => 'required|numeric|min:0',
            'unit'  => 'required|string|max:50',
            'expiry_date' => 'nullable|date',
            'category' => 'required|string|max:100'
        ]);

        $item = InventoryItem::create($data);

        AuditTrail::create([
            'user_id'     => Auth::id(),
            'action'      => 'Added Inventory Item',
            'module'      => 'inventory',
            'description' => 'added an inventory item',
        ]);

        // Create notification for admins/superadmin about inventory item addition
        $this->createAdminNotification('inventory_item_added', 'inventory', 'A new inventory item has been added by ' . Auth::user()->name, [
            'item_name' => $item->name,
            'category' => $item->category,
            'quantity' => $item->qty,
            'unit' => $item->unit,
            'updated_by' => Auth::user()->name,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Item added successfully.',
                'item' => $item->fresh(),
            ]);
        }

        return redirect()->route('admin.inventory.index')->with('success', 'Item added successfully.');
    }

    public function edit(InventoryItem $inventory): View
    {
        return view('admin.inventory.index', compact('inventory'));
    }

    public function update(Request $request, InventoryItem $inventory): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'qty'   => 'required|numeric|min:0',
            'unit'  => 'required|string|max:50',
            'expiry_date' => 'nullable|date',
            'category' => 'required|string|max:100'
        ]);

        $oldQty = $inventory->qty;
        $inventory->update($data);

        AuditTrail::create([
            'user_id'     => Auth::id(),
            'action'      => 'Updated Inventory Item',
            'module'      => 'inventory',
            'description' => 'updated an inventory item',
        ]);

        // Create notification for admins/superadmin about inventory item update
        $this->createAdminNotification('inventory_item_updated', 'inventory', 'An inventory item has been updated by ' . Auth::user()->name, [
            'item_name' => $inventory->name,
            'category' => $inventory->category,
            'old_quantity' => $oldQty,
            'new_quantity' => $inventory->qty,
            'unit' => $inventory->unit,
            'updated_by' => Auth::user()->name,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Item updated successfully.',
                'item' => $inventory->fresh(),
            ]);
        }

        return redirect()->route('admin.inventory.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Request $request, InventoryItem $inventory): RedirectResponse|JsonResponse
    {
        $itemName = $inventory->name;
        $inventory->delete();

        AuditTrail::create([
            'user_id'     => Auth::id(),
            'action'      => 'Deleted Inventory Item',
            'module'      => 'inventory',
            'description' => 'deleted an inventory item',
        ]);

        // Create notification for admins/superadmin about inventory item deletion
        $this->createAdminNotification('inventory_item_deleted', 'inventory', 'An inventory item has been deleted by ' . Auth::user()->name, [
            'item_name' => $itemName,
            'updated_by' => Auth::user()->name,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Item deleted.',
            ]);
        }

        return back()->with('success', 'Item deleted.');
    }

    /** Create notification for admins/superadmin */
    protected function createAdminNotification(string $action, string $module, string $description, array $metadata = []): void
    {
        try {
            if (! Schema::hasTable('notifications')) {
                return;
            }

            $admins = User::query()
                ->whereIn('role', ['admin', 'superadmin'])
                ->pluck('id');

            if ($admins->isEmpty()) {
                return;
            }

            $hasTitle = Schema::hasColumn('notifications', 'title');
            $hasType = Schema::hasColumn('notifications', 'type');
            $hasAction = Schema::hasColumn('notifications', 'action');
            $hasModule = Schema::hasColumn('notifications', 'module');
            $hasDescription = Schema::hasColumn('notifications', 'description');
            $hasMetadata = Schema::hasColumn('notifications', 'metadata');
            $hasRead = Schema::hasColumn('notifications', 'read');

            foreach ($admins as $adminId) {
                $payload = ['user_id' => $adminId];

                if ($hasTitle) {
                    $payload['title'] = ucwords(str_replace('_', ' ', $action));
                }

                if ($hasType) {
                    $payload['type'] = $action;
                }

                if ($hasAction) {
                    $payload['action'] = $action;
                }

                if ($hasModule) {
                    $payload['module'] = $module;
                }

                if ($hasDescription) {
                    $payload['description'] = $description;
                }

                if ($hasMetadata) {
                    $payload['metadata'] = $metadata;
                }

                if ($hasRead) {
                    $payload['read'] = false;
                }

                Notification::create($payload);
            }
        } catch (Throwable $e) {
            Log::warning('Inventory admin notification creation failed.', [
                'action' => $action,
                'module' => $module,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
