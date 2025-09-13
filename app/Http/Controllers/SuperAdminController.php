<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AuditTrail;
class SuperAdminController extends Controller
{
    public function index()
    {
        // ✅ Get all users except the superadmin
        $users = User::where('role', '!=', 'superadmin')->get();

        return view('superadmin.users', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // ✅ Create admin directly
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => 'admin', // assign admin automatically
        ]);

        return back()->with('success', 'Admin created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->route('superadmin.users')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

// SuperAdminController.php

    public function audit(User $user)
    {
        $logs = AuditTrail::where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('superadmin.audit', compact('user', 'logs'));
    }

}
