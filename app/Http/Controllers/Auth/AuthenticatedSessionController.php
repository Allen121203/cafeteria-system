<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\AuditTrail;

class AuthenticatedSessionController extends Controller
{
    // Show login (or redirect if already logged in)
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'superadmin') return redirect()->route('superadmin.users');
            if (Auth::user()->role === 'admin')      return redirect()->route('admin.dashboard');
            if (Auth::user()->role === 'customer')   return redirect()->route('customer.home');

            // No valid role? Force logout to avoid 403 loop
            Auth::logout();
            return redirect()->route('login');
        }

        return view('auth.login');
    }

    // Login
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // ✅ Log login action
        AuditTrail::create([
            'user_id' => auth()->id(),
            'action'  => 'Logged in',
            'module'  => 'auth',
            'description' => 'User logged in successfully',
        ]);

        if (Auth::user()->role === 'superadmin') return redirect()->route('superadmin.users');
        if (Auth::user()->role === 'admin')      return redirect()->route('admin.dashboard');

        return redirect()->route('customer.home');
    }

    // Logout
    public function destroy(Request $request): RedirectResponse
    {
        // ✅ Log logout action before session ends
        AuditTrail::create([
            'user_id' => auth()->id(),
            'action'  => 'Logged out',
            'module'  => 'auth',
            'description' => 'User logged out successfully',
        ]);

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
