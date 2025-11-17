<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $orders = Order::with('user')->get();
            return view('admin.orders.index', compact('orders'));
        } else {
            $orders = auth()->user()->orders()->with('orderItems.product')->get();
            return view('orders.index', compact('orders'));
        }
    }

    public function create(Request $request)
    {
        $cart = auth()->user()->cart()->with('cartItems.product')->first();
        
        if (!$cart) {
            $cart = auth()->user()->cart()->create();
        }

        // Check if there's an intended product from guest flow
        if (session('intended_product')) {
            $product = Product::find(session('intended_product'));
            session()->forget('intended_product'); // Clear the session
        } elseif ($request->product_id) {
            $product = Product::findOrFail($request->product_id);
        } else {
            $product = null;
        }
        
        // If there's a product to add directly, add it to cart
        if ($product && $cart) {
            $existingItem = $cart->cartItems()->where('product_id', $product->id)->first();
            
            if ($existingItem) {
                $existingItem->increment('quantity');
            } else {
                $cart->cartItems()->create([
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price_snapshot' => $product->price,
                ]);
            }
        }

        // Reload cart with updated items
        $cart = auth()->user()->cart()->with('cartItems.product')->first();

        return view('orders.create', compact('cart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:500',
            'payment_method' => 'required|in:cash,transfer,qris',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = auth()->user()->cart()->with('cartItems.product')->first();
        
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty.');
        }

        // Validate stock for all items in cart
        foreach ($cart->cartItems as $item) {
            if (!$item->product->isInStock($item->quantity)) {
                return redirect()->route('cart.index')->with('error', 
                    "Insufficient stock for {$item->product->name}. Only {$item->product->stock} available.");
            }
        }

        $total = $cart->total; // Using the accessor from Cart model

        DB::transaction(function () use ($request, $total, $cart) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $total,
                'status' => 'pending', // Start with pending as per APK.md
                'address' => $request->address,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            foreach ($cart->cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price_snapshot,
                ]);

                // Decrement stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Clear the cart after successful order
            $cart->cartItems()->delete();
            
            // Send notification to user about the new order
            $order->user->notify(new OrderCreatedNotification($order));
            
            // Send notification to admin users about the new order
            $adminUsers = \App\Models\User::where('role', 'admin')->get();
            foreach ($adminUsers as $admin) {
                $admin->notify(new OrderCreatedNotification($order));
            }
        });

        return redirect()->route('orders.index')->with('success', 'Order placed successfully.');
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('orderItems.product');
        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Order status updated.');
    }
    
    /**
     * Display order reports
     */
    public function reports(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $status = $request->query('status');
        
        $query = Order::with('user');
        
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->get();
        
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total');
        $statusCounts = $orders->groupBy('status')->map->count();
        
        return view('admin.reports.orders', compact('orders', 'totalOrders', 'totalRevenue', 'statusCounts', 'startDate', 'endDate', 'status'));
    }
}
