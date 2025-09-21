<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status'); // pending, approved, declined

        $reservations = Reservation::with('user')
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->get();

        return view('admin.reservations', compact('reservations', 'status'));
    }
}
