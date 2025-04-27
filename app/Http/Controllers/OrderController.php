<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function showUserOrders(Request $request)
    {
        $cartId = $request->cookie('cart_id');

        if (!$cartId) {
            return view('orders', ['orders' => collect()]);
        }

        $orders = Order::getUserOrders($cartId);

        foreach ($orders as $order) {
            $order->items = json_decode($order->items);
        }

        return view('orders', compact('orders'));
    }

    public function adminOrders()
    {
        $orders = Order::getAllOrders();

        foreach ($orders as $order) {
            $order->items = json_decode($order->items);
        }

        return view('admin-orders', compact('orders'));
    }
}
