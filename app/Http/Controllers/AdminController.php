<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:superadmin']);
    }

    public function index()
    {
        $admins = User::role('admin')->get();
        return view('superadmin.admins', compact('admins'));
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
}
