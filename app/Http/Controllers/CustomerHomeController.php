<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerHomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:customer']);
    }

    // Invokable so Route::get('/home', CustomerHomeController::class) works
    public function __invoke()
    {
        return view('customer.home');
    }

    public function store(Request $request)
    {
        // TODO: create reservation
        return back()->with('success', 'Reservation submitted.');
    }
}
