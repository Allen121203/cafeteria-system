<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
public function store(Request $request): RedirectResponse
{
    $data = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'address' => ['nullable', 'string', 'max:255'],
        'contact_no' => ['nullable', 'string', 'max:20'],
        'department' => ['nullable', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'name'       => $data['name'],
        'email'      => $data['email'],
        'password'   => Hash::make($data['password']),
        'address'    => $data['address'] ?? null,
        'contact_no' => $data['contact_no'] ?? null,
        'department' => $data['department'] ?? null,
        'role'       => 'customer', // default
    ]);

    Auth::login($user);

    return redirect()->route('customer.home');
}


}
