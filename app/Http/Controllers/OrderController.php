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
        $statusMap = [
            'unpaid' => 'pending',
            'paid' => 'paid',
            'processing' => 'processing',
            'shipped' => 'shipped',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
        ];

        $query = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc');
        if ($statusParam && isset($statusMap[$statusParam])) {
            $query->where('status', $statusMap[$statusParam]);
        }
        $orders = $query->get();

        $counts = Order::where('user_id', Auth::id())
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return view('orders.index', compact('orders', 'statusParam', 'counts'));
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
