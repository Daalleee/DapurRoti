<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Product::create([
            'name' => 'Croissant',
            'slug' => 'croissant',
            'description' => 'Buttery, flaky pastry perfect for breakfast.',
            'price' => 25000,
            'stock' => 50,
            'image' => null,
            'is_active' => true,
        ]);

        \App\Models\Product::create([
            'name' => 'Chocolate Cake',
            'slug' => 'chocolate-cake',
            'description' => 'Rich chocolate cake with creamy frosting.',
            'price' => 50000,
            'stock' => 20,
            'image' => null,
            'is_active' => true,
        ]);

        \App\Models\Product::create([
            'name' => 'Blueberry Muffin',
            'slug' => 'blueberry-muffin',
            'description' => 'Soft muffin packed with fresh blueberries.',
            'price' => 15000,
            'stock' => 30,
            'image' => null,
            'is_active' => true,
        ]);

        \App\Models\Product::create([
            'name' => 'Bread Loaf',
            'slug' => 'bread-loaf',
            'description' => 'Freshly baked whole wheat bread loaf.',
            'price' => 30000,
            'stock' => 15,
            'image' => null,
            'is_active' => true,
        ]);

        \App\Models\Product::create([
            'name' => 'Donut',
            'slug' => 'donut',
            'description' => 'Sweet glazed donut with sprinkles.',
            'price' => 10000,
            'stock' => 40,
            'image' => null,
            'is_active' => true,
        ]);
    }
}
