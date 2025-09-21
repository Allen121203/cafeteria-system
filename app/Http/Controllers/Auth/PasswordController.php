<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\AuditTrail;

AuditTrail::create([
    'user_id' => $request->user()->id,
    'action' => 'Updated password',
]);

class PasswordController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'password-updated');
    }
}
