<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

/**
 * A controller that manage cart items with session.
 */
class CartController extends Controller
{
    public function add(Request $request)
    {
        // 1. Find the product or fail
        $product = Product::findOrFail($request->product_id);

        // 2. Get the current cart from the session, or start with an empty array
        $cart = session()->get('cart', []);

        // 3. If the product is already in the cart, just add to the quantity
        if (isset($cart[$product->id])) {
            $cart[$product->id]['qty'] += $request->qty;
        } else {
            // If it's new, add it to the array with its details
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->selling_price,
                'qty' => $request->qty,
            ];
        }

        // 4. Save it back to the session
        session()->put('cart', $cart);

        return response()->json([
            'message' => 'Item added to cart',
            'cart' => $cart
        ]);
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);

        // Remove the specific item by its array key (product ID)
        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
        }

        return response()->json(['message' => 'Item removed', 'cart' => $cart]);
    }

    public function clear()
    {
        // Completely wipe the basket
        session()->forget('cart');

        return response()->json(['message' => 'Cart cleared', 'cart' => []]);
    }
}
