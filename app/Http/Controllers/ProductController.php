<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%' . $q . '%')
                    ->orWhere('category', 'like', '%' . $q . '%')
                    ->orWhere('description', 'like', '%' . $q . '%');
            });
        }

        // Filter by Category
        if ($request->has('categories')) {
            $categories = $request->categories;
            if (is_array($categories)) {
                $query->whereIn('category', $categories);
            }
        }

        if ($request->has('types')) {
            $types = $request->types;
            if (is_array($types) && count($types)) {
                $query->whereIn('product_type', $types);
            }
        }

        if ($request->filled('type')) {
            $type = (string) $request->type;
            if (in_array($type, ['featured', 'unggulan', 'Produk Unggulan'], true)) {
                $query->where('is_featured', true);
            } else {
                $query->where('product_type', $type);
            }
        }

        if ($request->has('sizes')) {
            $sizes = $request->sizes;
            if (is_array($sizes) && count($sizes)) {
                $query->where(function ($q) use ($sizes) {
                    foreach ($sizes as $s) {
                        $q->orWhereJsonContains('sizes', $s);
                    }
                });
            }
        }

        // Filter by Price Range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('availability')) {
            if ($request->availability === 'in') {
                $query->where('stock', '>', 0);
            } elseif ($request->availability === 'out') {
                $query->where('stock', '<=', 0);
            }
        }

        // Sort
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'popular':
                    // Assuming 'popular' means most viewed or ordered, 
                    // but for now let's just stick to latest or random if no tracking exists.
                    // Or maybe check if there is a 'views' column.
                    // Defaulting to latest for now.
                    $query->latest(); 
                    break;
                case 'latest':
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $categories = Product::select('category')->distinct()->whereNotNull('category')->pluck('category');
        $productTypes = Product::select('product_type')->distinct()->whereNotNull('product_type')->pluck('product_type');
        
        $products = $query->paginate(12)->withQueryString();
        return view('shop.index', compact('products', 'categories', 'productTypes'));
    }

    public function show(Product $product)
    {
        return view('shop.show', compact('product'));
    }

    public function search(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        if ($q === '') {
            return response()->json(['results' => []]);
        }

        $results = Product::query()
            ->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%' . $q . '%')
                    ->orWhere('category', 'like', '%' . $q . '%');
            })
            ->orderBy('stock', 'desc')
            ->latest()
            ->limit(8)
            ->get(['id', 'name', 'slug', 'price', 'image']);

        return response()->json(['results' => $results]);
    }
}
