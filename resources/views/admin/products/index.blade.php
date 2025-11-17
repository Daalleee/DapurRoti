<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Toko Roti') }} - Kelola Produk</title>
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
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .product-card {
            transition: all 0.3s ease;
        }
        .product-card img {
            transition: all 0.3s ease;
        }
        .product-card:hover img {
            transform: scale(1.05);
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
                            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900 font-semibold">Dashboard</a>
                            <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-gray-900 font-semibold">Kelola Produk</a>
                            <a href="{{ route('admin.orders.index') }}" class="text-gray-700 hover:text-gray-900">Kelola Pesanan</a>
                        @else
                            <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900">Beranda</a>
                            <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-gray-900">Pesanan Saya</a>
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-center md:text-left mb-4 md:mb-0">
                    <h1 class="text-3xl md:text-4xl font-bold mb-2 fade-in">Kelola Produk</h1>
                    <p class="text-lg md:text-xl">Tambah, edit, atau hapus produk roti Anda</p>
                </div>
                <a href="{{ route('products.create') }}" class="bg-white text-indigo-600 px-6 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors duration-200 shadow-lg">
                    Tambah Produk Baru
                </a>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 fade-in">
                    {{ session('success') }}
                </div>
            @endif

            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden product-card">
                            <div class="h-48 overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500 text-xl">No Image</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $product->name }}</h3>
                                <p class="text-gray-600 mb-4 text-sm">{{ Str::limit($product->description, 80) }}</p>
                                
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-2xl font-bold text-green-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-2.5 py-0.5 rounded">
                                        Stok: {{ $product->stock }}
                                    </span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                        @if($product->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                        @if($product->is_active) Aktif @else Tidak Aktif @endif
                                    </span>
                                    
                                    <div class="space-x-2">
                                        <a href="{{ route('products.edit', $product) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm transition-colors duration-200">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition-colors duration-200">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Table view for small screens -->
                <div class="md:hidden mt-8 overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Produk</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Harga</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Stok</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($products as $product)
                                <tr>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded mr-3">
                                            @else
                                                <div class="w-12 h-12 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                                    <span class="text-gray-500 text-xs">No Img</span>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-medium text-gray-800">{{ $product->name }}</div>
                                                <div class="text-xs text-gray-500">{{ Str::limit($product->description, 30) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-green-600 font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="py-4 px-4">{{ $product->stock }}</td>
                                    <td class="py-4 px-4">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('products.edit', $product) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-2 py-1 rounded text-xs">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-2 py-1 rounded text-xs">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-6xl mb-6">🍞</div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">Tidak Ada Produk</h3>
                    <p class="text-gray-600 mb-8">Mulai tambah produk roti Anda untuk ditampilkan di toko.</p>
                    <a href="{{ route('products.create') }}" class="bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200">
                        Tambah Produk Pertama
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
