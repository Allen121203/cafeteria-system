<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:admin']);
    }

    public function index(): View
    {
        // TODO: load reservations/inventory as needed
        return view('admin.dashboard');
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
