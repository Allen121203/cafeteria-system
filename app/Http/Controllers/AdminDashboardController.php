<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
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

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
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
