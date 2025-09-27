<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SuperAdminController extends Controller
{
    public function index(): View
    {
        // Show everyone except superadmin
        $users = User::where('role', '!=', 'superadmin')->orderBy('name')->get();

        return view('superadmin.users', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','unique:users,email'],
            'password' => ['required','string','min:6','confirmed'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'admin', // always admin when created by superadmin
        ]);

        AuditTrail::create([
            'user_id'     => Auth::id(),
            'action'      => 'Created Admin',
            'module'      => 'users',
            'description' => "Created admin {$user->email}",
        ]);

        return redirect()->route('superadmin.users')->with('success', 'Admin created successfully.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        if ($user->role !== 'admin') {
            return back()->with('error', 'Only admin accounts can be edited.');
        }

        $data = $request->validate([
            'name'  => ['required','string','max:255'],
            'email' => ['required','email','unique:users,email,' . $user->id],
        ]);

        $user->update($data);

        AuditTrail::create([
            'user_id'     => Auth::id(),
            'action'      => 'Updated Admin',
            'module'      => 'users',
            'description' => "Updated admin {$user->email}",
        ]);

        return redirect()->route('superadmin.users')->with('success', 'Admin updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $role  = $user->role;
        $email = $user->email;

        $user->delete();

        AuditTrail::create([
            'user_id'     => Auth::id(),
            'action'      => 'Deleted User',
            'module'      => 'users',
            'description' => "Deleted {$role} {$email}",
        ]);

        return back()->with('success', 'User deleted successfully.');
    }

    public function audit(User $user): View
    {
        $audits = AuditTrail::where('user_id', $user->id)->latest()->get();
        return view('superadmin.audit', compact('user','audits'));
    }
}
