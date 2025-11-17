<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Toko Roti') }} - Checkout Pesanan</title>
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
        .product-card {
            transition: all 0.3s ease;
        }
        .status-pending { background-color: #fef3c7; color: #d97706; }
        .status-confirmed { background-color: #dbeafe; color: #2563eb; }
        .status-completed { background-color: #d1fae5; color: #059669; }
        .status-cancelled { background-color: #fee2e2; color: #dc2626; }
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
    @if($cart && $cart->cartItems->count() > 0)
    <section class="hero-bg text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-2 fade-in">Checkout Pesanan</h1>
            <p class="text-lg md:text-xl">Selesaikan pesanan Anda dengan cepat dan aman</p>
        </div>
    </section>

    <!-- Checkout Section -->
    <section class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Summary -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-2xl font-bold text-gray-800">Ringkasan Pesanan</h2>
                        </div>
                        
                        <div class="p-6">
                            @foreach($cart->cartItems as $item)
                                <div class="flex items-center justify-between py-4 border-b border-gray-200">
                                    <div class="flex items-center">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded-lg mr-4">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                                <span class="text-gray-500 text-xs">No Image</span>
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="font-semibold text-gray-800">{{ $item->product->name }}</h4>
                                            <p class="text-gray-600">Rp {{ number_format($item->product->price, 0, ',', '.') }} x {{ $item->quantity }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-green-600">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Checkout Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden sticky top-6">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-2xl font-bold text-gray-800">Detail Pesanan</h2>
                        </div>
                        
                        <div class="p-6">
                            <form method="POST" action="{{ route('orders.store') }}" class="space-y-6">
                                @csrf

                                <!-- Delivery Address -->
                                <div>
                                    <label for="address" class="block text-lg font-semibold text-gray-700 mb-2">Alamat Pengiriman</label>
                                    <textarea name="address" id="address" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none" placeholder="Masukkan alamat lengkap pengiriman Anda..." required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Payment Method -->
                                <div>
                                    <label for="payment_method" class="block text-lg font-semibold text-gray-700 mb-2">Metode Pembayaran</label>
                                    <div class="space-y-3">
                                        <label class="flex items-center space-x-3 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                            <input type="radio" name="payment_method" value="cash" class="text-indigo-600 focus:ring-indigo-500" checked>
                                            <span>Berbayar Tunai</span>
                                        </label>
                                        <label class="flex items-center space-x-3 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                            <input type="radio" name="payment_method" value="transfer" class="text-indigo-600 focus:ring-indigo-500">
                                            <span>Transfer Bank</span>
                                        </label>
                                        <label class="flex items-center space-x-3 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                            <input type="radio" name="payment_method" value="qris" class="text-indigo-600 focus:ring-indigo-500">
                                            <span>QRIS</span>
                                        </label>
                                    </div>
                                    @error('payment_method')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Notes Input -->
                                <div>
                                    <label for="notes" class="block text-lg font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                                    <textarea name="notes" id="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none" placeholder="Tambahkan catatan khusus...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Order Summary -->
                                <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-4 border border-green-200">
                                    <h3 class="text-lg font-bold mb-3 text-gray-800">Total Pembayaran</h3>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span>Subtotal:</span>
                                            <span>Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Biaya Pengiriman:</span>
                                            <span class="text-green-600">Gratis</span>
                                        </div>
                                        <hr class="my-2 border-t border-gray-300">
                                        <div class="flex justify-between text-lg font-bold">
                                            <span>Total:</span>
                                            <span class="text-green-600">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="space-y-3">
                                    <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm2.5 3a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm6.207.293a1 1 0 00-1.414 0l-6 6a1 1 0 101.414 1.414l6-6a1 1 0 000-1.414zM12.5 10a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" clip-rule="evenodd" />
                                        </svg>
                                        Buat Pesanan
                                    </button>
                                    <a href="{{ route('cart.index') }}" class="w-full block text-center bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200">
                                        Kembali ke Keranjang
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @else
    <section class="hero-bg text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4 fade-in">Checkout Pesanan</h1>
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🛒</div>
                <h3 class="text-2xl font-bold text-white mb-2">Keranjang Kosong</h3>
                <p class="text-lg text-white mb-6">Tidak ada produk dalam keranjang Anda</p>
                <a href="{{ route('home') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors duration-200">
                    Lihat Produk
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
