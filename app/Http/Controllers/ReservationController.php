<?php
// app/Http/Controllers/ReservationController.php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use App\Notifications\ReservationStatusChanged;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $q = Reservation::with(['user','items.menu.items']); // items -> menu -> foods
        if (in_array($status, ['pending','approved','declined'], true)) {
            $q->where('status',$status);
        }

        $reservations = $q->latest()->paginate(10)->withQueryString();

        $counts = Reservation::selectRaw('status, COUNT(*) total')
            ->groupBy('status')->pluck('total','status');

        return view('admin.reservations.index', compact('reservations','status','counts'));
    }

    public function show(Reservation $reservation)
    {
        $reservation->load(['user','items.menu.items']);
        return view('admin.reservations.show', ['r' => $reservation]);
    }

    public function approve(Request $request, Reservation $reservation)
    {
        DB::transaction(function () use ($reservation) {
            $reservation->status = 'approved';
            $reservation->save();

            // Deduct inventory based on recipes (guard every relation)
            $guests = $reservation->guests ?? $reservation->attendees ?? 1;

            foreach ($reservation->items as $resItem) {
                $menu = $resItem->menu;
                $bundleQty = $resItem->quantity ?? 1;
                if (!$menu) continue;

                foreach ($menu->items as $food) {
                    foreach ($food->recipes as $recipe) {
                        $ingredient = $recipe->inventoryItem;
                        if (!$ingredient) continue;
                        $deduct = (float)($recipe->quantity_needed ?? 0) * $bundleQty * $guests;
                        if ($deduct <= 0) continue;
                        $ingredient->qty = max(0, ($ingredient->qty ?? 0) - $deduct);
                        $ingredient->save();
                    }
                }
            }
        });

        $this->notifyCustomer($reservation, 'approved');

        return redirect()
            ->route('admin.reservations.show', $reservation)
            ->with('accepted', true)
            ->with('success', 'Reservation approved and inventory updated.');
    }

    public function decline(Request $request, Reservation $reservation)
    {
        $data = $request->validate(['reason' => 'required|string|max:1000']);

        $reservation->status = 'declined';
        if (Schema::hasColumn('reservations','decline_reason')) {
            $reservation->decline_reason = $data['reason'];
        }
        $reservation->save();

        $this->notifyCustomer($reservation, 'declined', $data['reason']);

        return redirect()
            ->route('admin.reservations.show', $reservation)
            ->with('declined', true)
            ->with('success', 'Reservation declined and customer notified.');
    }

    /** Email + SMS with graceful fallbacks (no crash if not configured locally) */
    protected function notifyCustomer(Reservation $reservation, string $status, ?string $reason = null): void
    {
        $notification = new ReservationStatusChanged($reservation, $status, $reason);

        // Email target
        if ($reservation->relationLoaded('user') ? $reservation->user : $reservation->user()->exists()) {
            optional($reservation->user)->notify($notification);
        } elseif (!empty($reservation->email)) {
            Notification::route('mail', $reservation->email)->notify($notification);
        }

        // SMS (Vonage) only if configured
        $hasVonage = (bool) (config('services.vonage.key') && config('services.vonage.secret'));
        if ($hasVonage) {
            $phone = $reservation->contact_number
                ?? optional($reservation->user)->phone
                ?? optional($reservation->user)->mobile
                ?? null;
            if ($phone) {
                Notification::route('vonage', $phone)->notify($notification);
            }
        }
    }

    public function create()
    {
        // Fetch all menus grouped by meal_time and type, eager load 'items' relationship
        $menus = \App\Models\Menu::with('items')->get()->groupBy(['meal_time', 'type']);

        // Pass the menus to the view
        return view('customer.reservation_form_menu', compact('menus'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
            'reservations' => 'required|array',
            'reservations.*.category' => 'required|string',
            'reservations.*.menu' => 'required|string',
            'reservations.*.qty' => 'required|integer|min:0',
        ]);

        // Calculate total number of persons from menu selections
        $totalPersons = 0;
        foreach ($validated['reservations'] as $meal => $data) {
            $totalPersons += $data['qty'];
        }

        // Create the reservation
        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'event_name' => 'Catering Reservation', // Default event name, could be made dynamic
            'event_date' => $validated['reservation_date'],
            'event_time' => $validated['reservation_time'],
            'number_of_persons' => $totalPersons,
            'special_requests' => $validated['notes'],
            'status' => 'pending',
        ]);

        // Save reservation items (menu selections)
        foreach ($validated['reservations'] as $meal => $data) {
            if ($data['qty'] > 0) {
                // Find menu by name and meal_time
                $menu = \App\Models\Menu::where('name', $data['menu'])
                    ->where('meal_time', $meal)
                    ->first();

                if (!$menu) {
                    // If menu not found, skip or handle error
                    continue;
                }

                \App\Models\ReservationItem::create([
                    'reservation_id' => $reservation->id,
                    'menu_id' => $menu->id,
                    'quantity' => $data['qty'],
                ]);
            }
        }

        return redirect()->route('reservation_details')->with('success', 'Reservation placed successfully!');
    }
}
