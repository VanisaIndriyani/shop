<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        return view('cart.index', compact('cartItems'));
    }

    public function addToCart(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('account.index', ['login' => 1]);
        }
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string'
        ]);

        $size = $request->size ?? 'M'; // Default to M if not provided

        $cart = Cart::where('user_id', Auth::id())
                    ->where('product_id', $product->id)
                    ->where('size', $size)
                    ->first();

        if ($cart) {
            $cart->quantity += $request->quantity;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'size' => $size
            ]);
        }

        if ($request->has('buy_now')) {
            return redirect()->route('checkout.index');
        }

        if ($request->has('open_drawer')) {
            return redirect()->route('shop.show', $product->slug)->with('cart_drawer', true);
        }
        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->update(['quantity' => $request->quantity]);

        if ($request->has('open_drawer')) {
            return back()->with('cart_drawer', true);
        }
        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        if (request()->has('open_drawer')) {
            return back()->with('cart_drawer', true);
        }
        return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
    }
}
