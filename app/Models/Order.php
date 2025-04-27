<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Order
{
    public static function getUserOrders($cartId)
    {
        return DB::table('completed_orders')->where('cart_id', $cartId)->get();
    }

    public static function getAllOrders()
    {
        return DB::table('completed_orders')->orderBy('created_at', 'desc')->get();
    }
}
