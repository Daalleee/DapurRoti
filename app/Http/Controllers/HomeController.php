<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::active()->get();
        return view('home', compact('products'));
    }
    
    /**
     * Handle the "Pesan Sekarang" click for a product
     */
    public function pesanSekarang(Request $request, Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }
        
        if (!auth()->check()) {
            // Store intended product in session for redirect after login
            session(['intended_product' => $product->id]);
            
            // Redirect to login with next parameter
            return redirect()->route('login', ['next' => route('product.pesan-sekarang', $product)]);
        }
        
        // If user is already logged in, redirect to the order creation page with the product
        return redirect()->route('orders.create', ['product_id' => $product->id]);
    }
}
