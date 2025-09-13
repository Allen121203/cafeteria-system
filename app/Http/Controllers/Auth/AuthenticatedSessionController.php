<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
public function create()
{
    // Only redirect logged-in users
    if (Auth::check()) {
        if (Auth::user()->hasRole('superadmin')) {
            return redirect()->route('superadmin.users');
        } elseif (Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->hasRole('customer')) {
            return redirect()->route('customer.home');
        } else {
            // force logout if user has no role
            Auth::logout();
            return redirect()->route('login');
        }
    }

    return view('auth.login'); // login page
}


    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // regenerate session securely
        request()->session()->regenerate();

        // Redirect based on role
        if (Auth::user()->hasRole('superadmin')) {
            return redirect()->route('superadmin.users');
        } elseif (Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('customer.home');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // After logout, always go to login page
        return redirect()->route('login');
    }
}
