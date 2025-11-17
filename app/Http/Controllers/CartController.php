<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = auth()->user()->cart()->with('cartItems.product')->first();

        if (!$cart) {
            $cart = auth()->user()->cart()->create();
        }

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if (!$product->is_active || !$product->isInStock($request->quantity)) {
            return back()->with('error', 'Product is not available or insufficient stock.');
        }

        $cart = auth()->user()->cart()->firstOrCreate();

        $cartItem = $cart->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if (!$product->isInStock($newQuantity)) {
                return back()->with('error', 'Insufficient stock for the requested quantity.');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cart->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price_snapshot' => $product->price,
            ]);
        }

        return back()->with('success', 'Product added to cart successfully.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorize('update', $cartItem);

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock,
        ]);

        if (!$cartItem->product->isInStock($request->quantity)) {
            return back()->with('error', 'Insufficient stock.');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated successfully.');
    }

    public function remove(CartItem $cartItem)
    {
        $this->authorize('delete', $cartItem);

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        $cart = auth()->user()->cart()->first();

        if ($cart) {
            $cart->cartItems()->delete();
        }

        return back()->with('success', 'Cart cleared successfully.');
    }
}
