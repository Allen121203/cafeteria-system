<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:admin']);
    }

    public function index()
    {
        // TODO: load reservations/inventory as needed
        return view('admin.dashboard');
    }

    public function approve(Request $request)
    {
        // TODO: approval logic
        return back()->with('success', 'Reservation approved.');
    }
}
