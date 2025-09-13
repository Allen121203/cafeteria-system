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
    // Show login (or redirect if already logged in)
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->hasRole('superadmin')) return redirect()->route('superadmin.users');
            if (Auth::user()->hasRole('admin'))      return redirect()->route('admin.dashboard');
            if (Auth::user()->hasRole('customer'))   return redirect()->route('customer.home');

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
        request()->session()->regenerate();

        if (Auth::user()->hasRole('superadmin')) return redirect()->route('superadmin.users');
        if (Auth::user()->hasRole('admin'))      return redirect()->route('admin.dashboard');
        return redirect()->route('customer.home');
    }

    // Logout
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
