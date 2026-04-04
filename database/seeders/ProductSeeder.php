<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing products to avoid duplicates when re-seeding
        Schema::disableForeignKeyConstraints();
        Product::truncate();
        Schema::enableForeignKeyConstraints();

        Product::create([
            'name' => 'Boxy Tee - Midnight Black',
            'slug' => Str::slug('Boxy Tee Midnight Black'),
            'description' => 'Our signature boxy fit t-shirt in deep midnight black. Heavyweight cotton, dropped shoulders, and a cropped length for the perfect modern silhouette.',
            'price' => 189000,
            'sale_price' => null,
            'image' => null,
            'category' => 'T-Shirt',
            'stock' => 100,
        ]);

      
    }
}
