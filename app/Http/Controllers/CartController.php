<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cartId = Cookie::get('cart_id');
        if (!$cartId) {
            return view('index', ['cartItems' => [], 'totalAmount' => 0]);
        }
        $cartItems = DB::select("
            SELECT ci.id, ci.product_id, ci.quantity, p.name, p.price, p.imageurl, p.stock_quantity
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.cart_id = ?
        ", [$cartId]);
        $totalAmount = collect($cartItems)->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        return view('index', ['cartItems' => $cartItems, 'totalAmount' => $totalAmount]);
    }

    public function addToCart(Request $request, $productId)
    {
        $cartId = Cookie::get('cart_id') ?: Str::random(40);
        $existingCart = DB::selectOne("SELECT * FROM cart WHERE id = ?", [$cartId]);
        if (!$existingCart) {
            DB::insert("INSERT INTO cart (id) VALUES (?)", [$cartId]);
        }
        Cookie::queue('cart_id', $cartId, 60 * 24 * 30); // 30 days
        $product = DB::selectOne("SELECT * FROM products WHERE id = ?", [$productId]);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }
        $cartItem = DB::selectOne("
            SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ?
        ", [$cartId, $productId]);
        if ($cartItem) {    
            DB::update("
                UPDATE cart_items SET quantity = quantity + 1 WHERE id = ?
            ", [$cartItem->id]);
        } else {   
            DB::insert("
                INSERT INTO cart_items (cart_id, product_id, quantity, created_at, updated_at)
                VALUES (?, ?, 1, NOW(), NOW())
            ", [$cartId, $productId]);
        }   
        return redirect()->route('cart.index')->with('success', "{$product->name} has been added to your cart!");
    }
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);  
        DB::update("
            UPDATE cart_items SET quantity = ?, updated_at = NOW() WHERE id = ?
        ", [$request->input('quantity'), $itemId]);  
        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }
    public function removeFromCart($itemId)
    {
        DB::delete("DELETE FROM cart_items WHERE id = ?", [$itemId]);
        return redirect()->route('cart.index')->with('success', 'Item removed from the cart');
    }
    public function checkout(Request $request)
{
    $cartId = Cookie::get('cart_id');
    if (!$cartId) {
        return redirect()->back()->with('error', 'No cart found.');
    }
    $cartItems = DB::select("
        SELECT ci.product_id, ci.quantity, p.name, p.price, p.stock_quantity
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.cart_id = ?
    ", [$cartId]);
    if (empty($cartItems)) {
        return redirect()->back()->with('error', 'Your cart is empty.');
    }

    $totalAmount = collect($cartItems)->sum(function ($item) {
        return $item->price * $item->quantity;
    });

    $itemsArray = array_map(function ($item) {
        return [
            'product_id' => $item->product_id,
            'name' => $item->name,
            'quantity' => $item->quantity,
            'price' => $item->price,
        ];
    }, $cartItems);

    DB::beginTransaction();

    try {
        DB::insert("
            INSERT INTO completed_orders (cart_id, full_name, address, city, items, total, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
        ", [
            $cartId,
            $request->input('fullname'),
            $request->input('address'),
            $request->input('city'),
            json_encode($itemsArray),
            $totalAmount
        ]);

        foreach ($cartItems as $item) {
            $newStockQuantity = $item->stock_quantity - $item->quantity;

            if ($newStockQuantity < 0) {
                throw new \Exception("Insufficient stock for product: " . $item->name);
            }

            DB::update("
                UPDATE products
                SET stock_quantity = ?
                WHERE id = ?
            ", [
                $newStockQuantity,
                $item->product_id
            ]);
        }

        DB::delete("DELETE FROM cart_items WHERE cart_id = ?", [$cartId]);
        DB::delete("DELETE FROM cart WHERE id = ?", [$cartId]);
        DB::commit();

        return redirect()->route('orders')->with('success', 'Order placed successfully!');
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'Error placing the order: ' . $e->getMessage());
    }
}
}
