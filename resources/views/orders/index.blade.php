<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Toko Roti') }} - Pesanan Saya</title>
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
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .order-card {
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
                        @else
                            <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900">Beranda</a>
                        @endif
                        <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-gray-900 font-semibold">Pesanan Saya</a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-gray-900">Kelola Produk</a>
                            <a href="{{ route('admin.orders.index') }}" class="text-gray-700 hover:text-gray-900">Kelola Pesanan</a>
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
    <section class="hero-bg text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4 fade-in">Riwayat Pesanan Saya</h1>
            <p class="text-lg md:text-xl mb-2 slide-up">Lihat status dan detail semua pesanan Anda</p>
        </div>
    </section>

    <!-- Orders Section -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 fade-in">
                    {{ session('success') }}
                </div>
            @endif

            @if($orders->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-6">
                    @foreach($orders as $order)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden order-card">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800">#{{ $order->order_number }}</h3>
                                        <p class="text-gray-600 text-sm">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase
                                        @if($order->status == 'pending') status-pending
                                        @elseif($order->status == 'confirmed') status-confirmed
                                        @elseif($order->status == 'completed') status-completed
                                        @else status-cancelled @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                                
                                <div class="flex justify-between items-center mb-4">
                                    <div>
                                        <p class="text-gray-600 text-sm">Total Pembayaran</p>
                                        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-gray-600 text-sm">Metode</p>
                                        <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                                    </div>
                                </div>
                                
                                @if($order->address)
                                    <div class="mb-4">
                                        <p class="text-gray-600 text-sm">Alamat Pengiriman</p>
                                        <p class="text-gray-800">{{ $order->address }}</p>
                                    </div>
                                @endif
                                
                                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                                    <a href="{{ route('orders.show', $order) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors duration-200">
                                        Lihat Detail
                                    </a>
                                    
                                    @if($order->status == 'pending')
                                        <span class="text-yellow-600 text-sm font-medium">Menunggu Konfirmasi</span>
                                    @elseif($order->status == 'confirmed')
                                        <span class="text-blue-600 text-sm font-medium">Diproses</span>
                                    @elseif($order->status == 'completed')
                                        <span class="text-green-600 text-sm font-medium">Selesai</span>
                                    @else
                                        <span class="text-red-600 text-sm font-medium">Dibatalkan</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-6xl mb-6">📦</div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">Belum Ada Pesanan</h3>
                    <p class="text-gray-600 mb-8">Anda belum memiliki pesanan. Mulai belanja sekarang!</p>
                    <a href="{{ route('home') }}" class="bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200">
                        Belanja Sekarang
                    </a>
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
