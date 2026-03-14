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
            'category' => 'T-Shirt Boxy',
            'stock' => 100,
        ]);

        Product::create([
            'name' => 'Boxy Tee - Cloud White',
            'slug' => Str::slug('Boxy Tee Cloud White'),
            'description' => 'The essential white tee, elevated. Features a structured boxy fit that holds its shape wash after wash. Premium combed cotton.',
            'price' => 189000,
            'sale_price' => null,
            'image' => null,
            'category' => 'T-Shirt Boxy',
            'stock' => 100,
        ]);

        Product::create([
            'name' => 'Boxy Tee - Charcoal Grey',
            'slug' => Str::slug('Boxy Tee Charcoal Grey'),
            'description' => 'A versatile charcoal grey boxy tee. Perfect for layering or wearing on its own. Soft, breathable, and durable.',
            'price' => 189000,
            'sale_price' => 169000,
            'image' => null,
            'category' => 'T-Shirt Boxy',
            'stock' => 75,
        ]);

        Product::create([
            'name' => 'Boxy Tee - Navy Blue',
            'slug' => Str::slug('Boxy Tee Navy Blue'),
            'description' => 'Deep navy blue boxy fit t-shirt. The relaxed cut provides ultimate comfort without sacrificing style.',
            'price' => 189000,
            'sale_price' => null,
            'image' => null,
            'category' => 'T-Shirt Boxy',
            'stock' => 60,
        ]);
        
        Product::create([
            'name' => 'Boxy Tee - Forest Green',
            'slug' => Str::slug('Boxy Tee Forest Green'),
            'description' => 'Rich forest green colorway. This boxy tee adds a subtle pop of color to your outfit while maintaining a minimalist aesthetic.',
            'price' => 189000,
            'sale_price' => null,
            'image' => null,
            'category' => 'T-Shirt Boxy',
            'stock' => 45,
        ]);
        
        Product::create([
            'name' => 'Boxy Tee - Earth Brown',
            'slug' => Str::slug('Boxy Tee Earth Brown'),
            'description' => 'Earthy tones for a grounded look. Our Earth Brown boxy tee is made from 100% organic cotton.',
            'price' => 199000,
            'sale_price' => null,
            'image' => null,
            'category' => 'T-Shirt Boxy',
            'stock' => 50,
        ]);
    }
}
