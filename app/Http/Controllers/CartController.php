<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cartId = Cookie::get('cart_id');

        if (!$cartId) {
            return view('index', ['cartItems' => [], 'totalAmount' => 0]);
        }

        $cartItems = Cart::getCartItems($cartId);

        $totalAmount = collect($cartItems)->sum(fn($item) => $item->price * $item->quantity);

        return view('index', ['cartItems' => $cartItems, 'totalAmount' => $totalAmount]);
    }

    public function addToCart(Request $request, $productId)
    {
        $cartId = Cookie::get('cart_id') ?: Str::random(40);

        if (!Cart::findCart($cartId)) {
            Cart::createCart($cartId);
        }

        Cookie::queue('cart_id', $cartId, 60 * 24 * 30); // 30 days

        $product = Cart::findProduct($productId);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $cartItem = Cart::findCartItem($cartId, $productId);

        if ($cartItem) {
            Cart::incrementCartItemQuantity($cartItem->id);
        } else {
            Cart::addCartItem($cartId, $productId);
        }

        return redirect()->route('cart.index')->with('success', "{$product->name} has been added to your cart!");
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        Cart::updateCartItemQuantity($itemId, $request->input('quantity'));

        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    public function removeFromCart($itemId)
    {
        Cart::deleteCartItem($itemId);

        return redirect()->route('cart.index')->with('success', 'Item removed from the cart');
    }

    public function checkout(Request $request)
    {
        $cartId = Cookie::get('cart_id');

        if (!$cartId) {
            return redirect()->back()->with('error', 'No cart found.');
        }

        $cartItems = Cart::getCartItems($cartId);

        if (empty($cartItems)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $totalAmount = collect($cartItems)->sum(fn($item) => $item->price * $item->quantity);

        $itemsArray = array_map(fn($item) => [
            'product_id' => $item->product_id,
            'name' => $item->name,
            'quantity' => $item->quantity,
            'price' => $item->price,
        ], $cartItems);

        DB::beginTransaction();

        try {
            Cart::completeOrder(
                $cartId,
                $request->input('fullname'),
                $request->input('address'),
                $request->input('city'),
                $itemsArray,
                $totalAmount
            );

            foreach ($cartItems as $item) {
                $newStock = $item->stock_quantity - $item->quantity;

                if ($newStock < 0) {
                    throw new \Exception("Insufficient stock for product: " . $item->name);
                }

                Cart::updateProductStock($item->product_id, $newStock);
            }

            Cart::clearCart($cartId);

            DB::commit();

            return redirect()->route('orders')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error placing the order: ' . $e->getMessage());
        }
    }
}
