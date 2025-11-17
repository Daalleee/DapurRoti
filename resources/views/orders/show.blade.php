<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Toko Roti') }} - Detail Pesanan #{{ $order->order_number }}</title>
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
    <section class="hero-bg text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-2 fade-in">Detail Pesanan #{{ $order->order_number }}</h1>
            <p class="text-lg md:text-xl">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
    </section>

    <!-- Order Details Section -->
    <section class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Informasi Pesanan</h2>
                            <p class="text-gray-600">Order ID: #{{ $order->order_number }}</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <span class="px-4 py-2 rounded-full text-sm font-semibold uppercase
                                @if($order->status == 'pending') status-pending
                                @elseif($order->status == 'confirmed') status-confirmed
                                @elseif($order->status == 'completed') status-completed
                                @else status-cancelled @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Pesanan</h3>
                        <div class="space-y-3">
                            <p><span class="font-semibold">Tanggal:</span> {{ $order->created_at->format('d M Y, H:i') }}</p>
                            <p><span class="font-semibold">Status:</span> <span class="capitalize">{{ $order->status }}</span></p>
                            <p><span class="font-semibold">Total:</span> <span class="text-green-600 font-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</span></p>
                            <p><span class="font-semibold">Metode Pembayaran:</span> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                            @if($order->notes)
                                <p><span class="font-semibold">Catatan:</span> {{ $order->notes }}</p>
                            @endif
                        </div>
                    </div>
                    
                    @if($order->address)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Alamat Pengiriman</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-700">{{ $order->address }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if(auth()->user()->isAdmin())
                        <div class="md:col-span-2 mt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Status</h3>
                            <form method="POST" action="{{ route('orders.update-status', $order) }}" class="bg-gray-50 p-4 rounded-lg">
                                @csrf
                                @method('PATCH')

                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>

                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                                    Update Status
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800">Produk dalam Pesanan</h2>
                </div>
                
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produk</th>
                                    <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
                                    <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga Satuan</th>
                                    <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($order->orderItems as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4 px-4 text-gray-800">
                                            {{ $item->product->name }}
                                        </td>
                                        <td class="py-4 px-4">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="py-4 px-4 text-green-600">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </td>
                                        <td class="py-4 px-4 font-semibold text-green-600">
                                            Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end">
                        <div class="text-right">
                            <p class="text-lg font-semibold">Total: <span class="text-green-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <a href="{{ route('orders.index') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200">
                    Kembali ke Riwayat Pesanan
                </a>
            </div>
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
