<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Menampilkan daftar pesanan pengguna
    public function index(Request $request)
    {
        $statusParam = $request->get('status');
        $params = ['tab' => 'orders'];
        if ($statusParam) {
            $params['status'] = $statusParam;
        }

        return redirect()->route('account.index', $params);
    }

    // Menampilkan detail pesanan
    public function show(Order $order)
    {
        // Pastikan pesanan milik pengguna yang sedang login
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }
}
