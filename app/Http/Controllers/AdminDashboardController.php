<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\InventoryItem;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:admin']);
    }

    public function index(): View
    {
        $totalReservations = Reservation::count();
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $menusSold = ReservationItem::sum('quantity');
        $lowStocks = InventoryItem::where('qty', '<', 5)->get();
        $outOfStocks = InventoryItem::where('qty', 0)->get();
        $expiringSoon = InventoryItem::where('expiry_date', '<=', Carbon::now()->addDays(7))
            ->where('expiry_date', '>=', Carbon::now())
            ->get();

        return view('admin.dashboard', compact(
            'totalReservations',
            'pendingReservations',
            'menusSold',
            'lowStocks',
            'outOfStocks',
            'expiringSoon'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('admin');

        return redirect()->back()->with('success', 'Admin created successfully');
    }

    public function approve(Request $request)
    {
        // TODO: approval logic
        return back()->with('success', 'Reservation approved.');
    }
}
