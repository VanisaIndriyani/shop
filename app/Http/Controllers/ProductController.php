<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\OrderItem;

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
        if ($request->filled('category')) {
            $query->where('category', (string) $request->category);
        } elseif ($request->has('categories')) {
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
        if ($request->filled('size')) {
            $query->whereJsonContains('sizes', (string) $request->size);
        }

        if ($request->filled('price_range')) {
            switch ((string) $request->price_range) {
                case 'under_75000':
                    $query->where('price', '<=', 75000);
                    break;
                case '75000_150000':
                    $query->whereBetween('price', [75000, 150000]);
                    break;
                case '150000_220000':
                    $query->whereBetween('price', [150000, 220000]);
                    break;
                case '220000_plus':
                    $query->where('price', '>=', 220000);
                    break;
            }
        }

        // Filter by Price Range (manual)
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
                case 'featured':
                    $query->orderBy('is_featured', 'desc')->latest();
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'popular':
                case 'rating_desc':
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
        $recent = session()->get('recently_viewed', []);
        if (!is_array($recent)) {
            $recent = [];
        }
        $recent = array_values(array_filter($recent, fn ($id) => (int) $id !== (int) $product->id));
        array_unshift($recent, (int) $product->id);
        $recent = array_values(array_unique($recent));
        $recent = array_slice($recent, 0, 12);
        session()->put('recently_viewed', $recent);

        $recentIds = array_values(array_filter($recent, fn ($id) => (int) $id !== (int) $product->id));
        $recentIds = array_slice($recentIds, 0, 4);
        $recentMap = Product::query()
            ->whereIn('id', $recentIds)
            ->get(['id', 'name', 'slug', 'price', 'image', 'stock'])
            ->keyBy('id');
        $recentlyViewed = collect($recentIds)->map(fn ($id) => $recentMap->get($id))->filter()->values();

        $recommended = Product::query()
            ->where('id', '!=', $product->id)
            ->when($product->category, fn ($q) => $q->where('category', $product->category))
            ->latest()
            ->limit(8)
            ->get(['id', 'name', 'slug', 'price', 'image', 'stock']);

        if ($recommended->count() < 8) {
            $need = 8 - $recommended->count();
            $extra = Product::query()
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $recommended->pluck('id'))
                ->latest()
                ->limit($need)
                ->get(['id', 'name', 'slug', 'price', 'image', 'stock']);
            $recommended = $recommended->concat($extra);
        }

        $recentOrderedIds = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereNotNull('order_items.product_id')
            ->whereIn('orders.status', ['paid', 'processing', 'shipped', 'completed'])
            ->orderBy('orders.created_at', 'desc')
            ->limit(40)
            ->pluck('order_items.product_id')
            ->unique()
            ->values()
            ->take(6)
            ->all();

        $recentOrderedMap = Product::query()
            ->whereIn('id', $recentOrderedIds)
            ->get(['id', 'name', 'slug', 'price', 'image', 'stock'])
            ->keyBy('id');
        $recentOrdered = collect($recentOrderedIds)->map(fn ($id) => $recentOrderedMap->get($id))->filter()->values();

        return view('shop.show', compact('product', 'recommended', 'recentlyViewed', 'recentOrdered'));
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
