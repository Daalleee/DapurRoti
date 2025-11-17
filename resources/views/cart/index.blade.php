<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Toko Roti') }} - Keranjang Belanja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .fade-in {
            animation: fadeIn 1s ease-in;
        }
        .slide-up {
            animation: slideUp 0.8s ease-out;
        }
        .bounce-in {
            animation: bounceIn 1s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }
        .cart-item {
            transition: all 0.3s ease;
        }
        .cart-item:hover {
            background-color: #f9fafb;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-gray-800">Toko Roti</h1>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                            <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-gray-900">Kelola Produk</a>
                            <a href="{{ route('admin.orders.index') }}" class="text-gray-700 hover:text-gray-900">Kelola Pesanan</a>
                        @else
                            <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900">Beranda</a>
                            <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-gray-900 font-semibold">Pesanan Saya</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-gray-900">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 transition-colors duration-200">Login</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors duration-200">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    @if($cart->cartItems->count() > 0)
    <section class="hero-bg text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-2 fade-in">Keranjang Belanja</h1>
            <p class="text-lg md:text-xl">Periksa dan atur pesanan Anda sebelum checkout</p>
        </div>
    </section>

    <!-- Cart Section -->
    <section class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800">Produk dalam Keranjang</h2>
                </div>
                
                <div class="p-6">
                    <div class="space-y-6">
                        @foreach($cart->cartItems as $item)
                            <div class="flex items-center justify-between border-b border-gray-200 pb-6 cart-item">
                                <div class="flex items-center space-x-4">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-20 h-20 object-cover rounded-lg">
                                    @else
                                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <span class="text-gray-500 text-xs">No Image</span>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800">{{ $item->product->name }}</h3>
                                        <p class="text-gray-600">Rp {{ number_format($item->price_snapshot, 0, ',', '.') }} per item</p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-6">
                                    <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-lg text-sm">Update</button>
                                    </form>

                                    <div class="text-lg font-bold text-green-600">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </div>

                                    <form method="POST" action="{{ route('cart.remove', $item) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-between items-center pt-8 mt-8 border-t border-gray-200">
                        <div>
                            <p class="text-xl font-bold">Total: <span class="text-green-600">Rp {{ number_format($cart->total, 0, ',', '.') }}</span></p>
                        </div>
                        <div class="flex space-x-4">
                            <form method="POST" action="{{ route('cart.clear') }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-3 rounded-lg transition-colors duration-200">Kosongkan Keranjang</button>
                            </form>
                            <a href="{{ route('orders.create') }}" class="bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200">
                                Lanjutkan ke Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @else
    <section class="hero-bg text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4 fade-in">Keranjang Belanja</h1>
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🛒</div>
                <h3 class="text-2xl font-bold text-white mb-2">Keranjang Anda Kosong</h3>
                <p class="text-lg text-white mb-6">Mulailah belanja untuk menambahkan produk ke keranjang Anda</p>
                <a href="{{ route('home') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors duration-200">
                    Mulai Belanja
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; 2024 Toko Roti. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
