<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CustomerHomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:customer']);
    }

    public function index(): View
    {
        $menus = \App\Models\Menu::with('items')->get();
        return view('customer.home', compact('menus'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'date' => 'required|date|after:today',
            'time' => 'required',
            'guests' => 'required|integer|min:1',
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $reservation = \App\Models\Reservation::create([
            'user_id' => auth()->id(),
            'date' => $request->date,
            'time' => $request->time,
            'guests' => $request->guests,
            'status' => 'pending',
        ]);

        \App\Models\ReservationItem::create([
            'reservation_id' => $reservation->id,
            'menu_id' => $request->menu_id,
            'quantity' => $request->quantity,
        ]);

        return back()->with('success', 'Reservation submitted.');
    }
}
