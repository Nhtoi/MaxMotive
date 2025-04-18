<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function showUserOrders(Request $request)
    {
        $cartId = $request->cookie('cart_id'); // Get cart_id from cookie
    
        if (!$cartId) {
            return view('orders', ['orders' => collect()]);
        }
    
        $orders = DB::table('completed_orders')->where('cart_id', $cartId)->get();

        foreach ($orders as $order) {
            $order->items = json_decode($order->items);
        }
    
        return view('orders', compact('orders'));
    }
    
}
