<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:superadmin']);
    }

    /**
     * Show all users for the superadmin.
     */
    public function index()
    {
        $users = User::all();
        return view('superadmin.users', compact('users'));
    }

    /**
     * Update user role.
     */
    public function update(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|in:superadmin,admin,customer',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->syncRoles([$request->role]);

        return back()->with('success', 'User role updated successfully.');
    }
}
