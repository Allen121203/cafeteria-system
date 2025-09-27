<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\AuditTrail;

class PasswordController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        if ($user !== null) {
            $user->password = Hash::make($data['password']);
            $user->save();

            // Audit: record password update
            AuditTrail::create([
                'user_id' => $user->id,
                'action' => 'Updated password',
                'module' => 'users',
                'description' => "User {$user->email} updated their password.",
            ]);
        }

        return redirect()->route('profile.edit')->with('status', 'password-updated');
    }
}
