<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(12);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,paid,processing,shipped,completed,cancelled'],
            'shipping_courier' => ['nullable', 'string', 'max:50'],
            'tracking_number' => ['nullable', 'string', 'max:120'],
            'shipping_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $payload = [
            'status' => $validated['status'],
            'shipping_courier' => $validated['shipping_courier'] ?? $order->shipping_courier,
            'tracking_number' => $validated['tracking_number'] ?? $order->tracking_number,
            'shipping_note' => $validated['shipping_note'] ?? $order->shipping_note,
        ];

        if ($validated['status'] === 'shipped' && $order->shipped_at === null) {
            $payload['shipped_at'] = now();
        }

        $order->update($payload);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
