<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class SuperAdminController extends Controller
{
    public function index()
    {
        // Get all users except the superadmin
        $users = User::whereDoesntHave('roles', function($q) {
            $q->where('name', 'superadmin');
        })->get();

        return view('superadmin.users', compact('users'));
    }

    // Create new admin
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // ✅ Assign role as admin automatically
        $user->assignRole('admin');

        return back()->with('success', 'Admin created successfully.');
    }

    // Edit admin info
    public function edit(User $user)
    {
        return view('superadmin.edit-admin', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->route('superadmin.users')->with('success', 'Admin updated successfully.');
    }

    // Delete user
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    // Track audit trails (dummy for now)
    public function audit(User $user)
    {
        // Here you can fetch user activity logs if using a package like spatie/laravel-activitylog
        return view('superadmin.audit', compact('user'));
    }
}
