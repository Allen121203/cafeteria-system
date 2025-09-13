<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:superadmin']);
    }

    public function index()
    {
        $users = User::with('roles')->get();
        return view('superadmin.users', compact('users'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required','exists:users,id'],
            'role'    => ['required','in:superadmin,admin,customer'],
        ]);

        $user = User::findOrFail($data['user_id']);
        $user->syncRoles([$data['role']]);

        return back()->with('success', 'User role updated successfully.');
    }
    public function store(Request $request)
{
    $data = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $user = \App\Models\User::create([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'password' => bcrypt($data['password']),
    ]);

    $user->assignRole('admin'); // 👈 automatically admin

    return back()->with('success', 'Admin account created successfully!');
}

}
