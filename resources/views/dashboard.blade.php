<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'DapurRoti') }} - Dashboard</title>
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
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }

            50% {
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .dashboard-card {
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('dashboard') }}"
                                class="text-gray-700 hover:text-gray-900 font-semibold">Dashboard</a>
                            <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-gray-900">Kelola
                                Produk</a>
                            <a href="{{ route('admin.orders.index') }}" class="text-gray-700 hover:text-gray-900">Kelola
                                Pesanan</a>
                        @else
                            <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900">Beranda</a>
                            <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-gray-900">Pesanan Saya</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-gray-900">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-gray-700 hover:text-gray-900 transition-colors duration-200">Login</a>
                        <a href="{{ route('register') }}"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors duration-200">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-2 fade-in">Dashboard
                {{ auth()->user()->isAdmin() ? 'Admin' : 'Pelanggan' }}</h1>
            <p class="text-lg md:text-xl">Kelola toko dan pesanan Anda dengan mudah</p>
        </div>
    </section>

    <!-- Dashboard Content -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (auth()->user()->isAdmin())
                <!-- Admin Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Total Produk Card -->
                    <div class="bg-white rounded-lg shadow-md p-6 dashboard-card">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Produk</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Product::count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Pesanan Card -->
                    <div class="bg-white rounded-lg shadow-md p-6 dashboard-card">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Pesanan</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Order::count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Pendapatan Card -->
                    <div class="bg-white rounded-lg shadow-md p-6 dashboard-card">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Pendapatan</p>
                                <p class="text-2xl font-semibold text-gray-900">Rp
                                    {{ number_format(\App\Models\Order::sum('total'), 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Pesanan Terbaru -->
                    <div class="bg-white rounded-lg shadow-md p-6 dashboard-card">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Pesanan Terbaru</h3>
                        <div class="space-y-3">
                            @foreach (\App\Models\Order::with('user')->latest()->limit(5)->get() as $order)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <div>
                                        <p class="font-medium text-gray-800">#{{ $order->order_number }} -
                                            {{ $order->user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span
                                        class="px-2.5 py-0.5 text-xs rounded-full font-semibold
                                        @if ($order->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'completed') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                            <a href="{{ route('admin.orders.index') }}"
                                class="text-indigo-600 hover:text-indigo-800 font-medium">Lihat Semua Pesanan →</a>
                        </div>
                    </div>

                    <!-- Produk Terbaru -->
                    <div class="bg-white rounded-lg shadow-md p-6 dashboard-card">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Produk Terbaru</h3>
                        <div class="space-y-3">
                            @foreach (\App\Models\Product::latest()->limit(5)->get() as $product)
                                <div class="flex items-center py-2 border-b border-gray-100">
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                            alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded mr-3">
                                    @else
                                        <div
                                            class="w-12 h-12 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                            <span class="text-xs text-gray-500">No Img</span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-600">Rp
                                            {{ number_format($product->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                            <a href="{{ route('products.index') }}"
                                class="text-indigo-600 hover:text-indigo-800 font-medium">Lihat Semua Produk →</a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Customer Dashboard -->
                <div class="bg-white rounded-lg shadow-md p-8 text-center dashboard-card">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Selamat Datang, {{ auth()->user()->name }}!</h3>
                    <p class="text-gray-600 mb-8 max-w-2xl mx-auto">Dari dashboard ini Anda dapat mengelola pesanan
                        Anda dan mulai belanja produk-produk terbaik kami.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto">
                        <a href="{{ route('orders.index') }}"
                            class="bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold py-4 px-6 rounded-lg transition-all duration-200 transform hover:-translate-y-1">
                            <div class="text-3xl mb-2">📦</div>
                            <div>Lihat Pesanan Saya</div>
                        </a>
                        <a href="{{ route('home') }}"
                            class="bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-bold py-4 px-6 rounded-lg transition-all duration-200 transform hover:-translate-y-1">
                            <div class="text-3xl mb-2">🛒</div>
                            <div>Belanja Sekarang</div>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; 2024 Toko Roti. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
