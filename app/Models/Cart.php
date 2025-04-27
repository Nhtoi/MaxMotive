<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Cart
{
    public static function findCart($cartId)
    {
        return DB::selectOne("SELECT * FROM cart WHERE id = ?", [$cartId]);
    }

    public static function createCart($cartId)
    {
        DB::insert("INSERT INTO cart (id) VALUES (?)", [$cartId]);
    }

    public static function findProduct($productId)
    {
        return DB::selectOne("SELECT * FROM products WHERE id = ?", [$productId]);
    }

    public static function findCartItem($cartId, $productId)
    {
        return DB::selectOne("SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ?", [$cartId, $productId]);
    }

    public static function addCartItem($cartId, $productId)
    {
        DB::insert("
            INSERT INTO cart_items (cart_id, product_id, quantity, created_at, updated_at)
            VALUES (?, ?, 1, NOW(), NOW())
        ", [$cartId, $productId]);
    }

    public static function updateCartItemQuantity($itemId, $quantity)
    {
        DB::update("
            UPDATE cart_items SET quantity = ?, updated_at = NOW() WHERE id = ?
        ", [$quantity, $itemId]);
    }

    public static function incrementCartItemQuantity($itemId)
    {
        DB::update("
            UPDATE cart_items SET quantity = quantity + 1 WHERE id = ?
        ", [$itemId]);
    }

    public static function deleteCartItem($itemId)
    {
        DB::delete("DELETE FROM cart_items WHERE id = ?", [$itemId]);
    }

    public static function getCartItems($cartId)
    {
        return DB::select("
            SELECT ci.id, ci.product_id, ci.quantity, p.name, p.price, p.imageurl, p.stock_quantity
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.cart_id = ?
        ", [$cartId]);
    }

    public static function completeOrder($cartId, $fullname, $address, $city, $items, $total)
    {
        DB::insert("
            INSERT INTO completed_orders (cart_id, full_name, address, city, items, total, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
        ", [$cartId, $fullname, $address, $city, json_encode($items), $total]);
    }

    public static function updateProductStock($productId, $newStock)
    {
        DB::update("
            UPDATE products SET stock_quantity = ? WHERE id = ?
        ", [$newStock, $productId]);
    }

    public static function clearCart($cartId)
    {
        DB::delete("DELETE FROM cart_items WHERE cart_id = ?", [$cartId]);
        DB::delete("DELETE FROM cart WHERE id = ?", [$cartId]);
    }
}
