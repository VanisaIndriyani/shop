<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function store(Request $request, Product $product)
    {
        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        if ($request->has('open_drawer')) {
            return back()->with('cart_drawer', true);
        }
        return back()->with('success', 'Produk ditambahkan ke wishlist.');
    }

    public function destroy(Product $product)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();

        if (request()->has('open_drawer')) {
            return back()->with('cart_drawer', true);
        }
        return back()->with('success', 'Produk dihapus dari wishlist.');
    }
}
