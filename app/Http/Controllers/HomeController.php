// HomeController.php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the homepage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $cartId = $request->cookie('cart_id'); // Retrieve the cookie value
        return view('home', compact('cartId')); // Use compact() to pass the variable
    }
}

