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

        Product::create([
            'name' => 'Boxy Tee - Cloud White',
            'slug' => Str::slug('Boxy Tee Cloud White'),
            'description' => 'The essential white tee, elevated. Features a structured boxy fit that holds its shape wash after wash. Premium combed cotton.',
            'price' => 189000,
            'sale_price' => null,
            'image' => null,
            'category' => 'T-Shirt',
            'stock' => 100,
        ]);

        Product::create([
            'name' => 'Boxy Tee - Charcoal Grey',
            'slug' => Str::slug('Boxy Tee Charcoal Grey'),
            'description' => 'A versatile charcoal grey boxy tee. Perfect for layering or wearing on its own. Soft, breathable, and durable.',
            'price' => 189000,
            'sale_price' => 169000,
            'image' => null,
            'category' => 'Polo',
            'stock' => 75,
        ]);

        Product::create([
            'name' => 'Boxy Tee - Navy Blue',
            'slug' => Str::slug('Boxy Tee Navy Blue'),
            'description' => 'Deep navy blue boxy fit t-shirt. The relaxed cut provides ultimate comfort without sacrificing style.',
            'price' => 189000,
            'sale_price' => null,
            'image' => null,
            'category' => 'Hoodie',
            'stock' => 60,
        ]);
        
        Product::create([
            'name' => 'Boxy Tee - Forest Green',
            'slug' => Str::slug('Boxy Tee Forest Green'),
            'description' => 'Rich forest green colorway. This boxy tee adds a subtle pop of color to your outfit while maintaining a minimalist aesthetic.',
            'price' => 189000,
            'sale_price' => null,
            'image' => null,
            'category' => 'Jacket',
            'stock' => 45,
        ]);
        
        Product::create([
            'name' => 'Boxy Tee - Earth Brown',
            'slug' => Str::slug('Boxy Tee Earth Brown'),
            'description' => 'Earthy tones for a grounded look. Our Earth Brown boxy tee is made from 100% organic cotton.',
            'price' => 199000,
            'sale_price' => null,
            'image' => null,
            'category' => 'Pants',
            'stock' => 50,
        ]);

        Product::create([
            'name' => 'Polo Shirt - Classic Black',
            'slug' => Str::slug('Polo Shirt Classic Black'),
            'description' => 'Classic polo shirt with a clean collar and a comfortable regular fit. Easy to dress up or down.',
            'price' => 219000,
            'sale_price' => null,
            'image' => null,
            'category' => 'Polo',
            'stock' => 40,
        ]);

        Product::create([
            'name' => 'Hoodie - Grey Melange',
            'slug' => Str::slug('Hoodie Grey Melange'),
            'description' => 'Soft fleece hoodie with a roomy hood and kangaroo pocket. Perfect for daily wear.',
            'price' => 329000,
            'sale_price' => 299000,
            'image' => null,
            'category' => 'Hoodie',
            'stock' => 35,
        ]);

        Product::create([
            'name' => 'Jacket - Utility Olive',
            'slug' => Str::slug('Jacket Utility Olive'),
            'description' => 'Lightweight utility jacket with multiple pockets and a clean silhouette. Great for layering.',
            'price' => 389000,
            'sale_price' => null,
            'image' => null,
            'category' => 'Jacket',
            'stock' => 25,
        ]);

        Product::create([
            'name' => 'Pants - Cargo Khaki',
            'slug' => Str::slug('Pants Cargo Khaki'),
            'description' => 'Relaxed cargo pants with functional pockets and durable fabric. Built for comfort.',
            'price' => 349000,
            'sale_price' => null,
            'image' => null,
            'category' => 'Pants',
            'stock' => 30,
        ]);
    }
}
