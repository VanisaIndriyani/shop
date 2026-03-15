<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'sale_price' => ['nullable', 'numeric'],
            'category' => ['required', 'string', 'max:255'],
            'product_type' => ['nullable', 'string', 'max:50'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'sizes' => ['nullable', 'array'],
            'sizes.*' => ['string', 'in:S,M,L,XL,39,40,41,42,43'],
            'images' => ['required', 'array', 'size:3'],
            'images.0' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'images.1' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'images.2' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $images = [];
        foreach ($request->file('images') as $file) {
            $images[] = $file->store('products', 'public');
        }

        Product::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'price' => $validated['price'],
            'sale_price' => $validated['sale_price'] ?? null,
            'category' => $validated['category'],
            'product_type' => $validated['product_type'] ?? null,
            'sizes' => $validated['sizes'] ?? null,
            'stock' => $validated['stock'],
            'description' => $validated['description'] ?? null,
            'image' => $images[0] ?? null,
            'images' => $images ?: null,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'sale_price' => ['nullable', 'numeric'],
            'category' => ['required', 'string', 'max:255'],
            'product_type' => ['nullable', 'string', 'max:50'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'sizes' => ['nullable', 'array'],
            'sizes.*' => ['string', 'in:S,M,L,XL,39,40,41,42,43'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $images = $product->images ?: ($product->image ? [$product->image] : []);
        $images = array_values($images);
        while (count($images) < 3) {
            $images[] = $images[0] ?? null;
        }
        $images = array_slice($images, 0, 3);

        if ($request->hasFile('images')) {
            $incoming = $request->file('images');
            for ($i = 0; $i < 3; $i++) {
                if (!isset($incoming[$i])) {
                    continue;
                }
                $file = $incoming[$i];
                if (!$file) {
                    continue;
                }
                if (!empty($images[$i])) {
                    Storage::disk('public')->delete($images[$i]);
                }
                $images[$i] = $file->store('products', 'public');
            }
        }

        $product->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'price' => $validated['price'],
            'sale_price' => $validated['sale_price'] ?? null,
            'category' => $validated['category'],
            'product_type' => $validated['product_type'] ?? null,
            'sizes' => $validated['sizes'] ?? null,
            'stock' => $validated['stock'],
            'description' => $validated['description'] ?? null,
            'image' => $images[0] ?? null,
            'images' => $images ?: null,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if (is_array($product->images) && count($product->images)) {
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img);
            }
        } elseif ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
