<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

class CustomerHomeController extends Controller
{
    public function __invoke()
    {
        $menuItems = [
            ['name' => 'Chicken Meal', 'description' => 'Rice + chicken + drink', 'price' => 120],
            ['name' => 'Pasta Plate', 'description' => 'Creamy pasta + bread', 'price' => 95],
        ];

        return view('customer.home', compact('menuItems'));
    }
}
