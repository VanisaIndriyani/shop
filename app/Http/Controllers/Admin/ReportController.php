<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        [$from, $to] = $this->parseDateRange($request);
        $statuses = $this->parseStatuses($request);

        $ordersQuery = Order::query()->with('user')->whereBetween('created_at', [$from, $to]);
        if (!empty($statuses)) {
            $ordersQuery->whereIn('status', $statuses);
        }

        $orders = (clone $ordersQuery)->latest()->paginate(12)->withQueryString();

        $stats = [
            'total_orders' => (clone $ordersQuery)->count(),
            'total_revenue' => (clone $ordersQuery)->sum('total_price'),
            'avg_order_value' => (clone $ordersQuery)->avg('total_price') ?: 0,
        ];

        $byStatus = (clone $ordersQuery)
            ->selectRaw('status, COUNT(*) as cnt, COALESCE(SUM(total_price),0) as total')
            ->groupBy('status')
            ->orderBy('status')
            ->get()
            ->map(fn ($row) => [
                'status' => $row->status,
                'count' => (int) $row->cnt,
                'total' => (float) $row->total,
            ]);

        $topProducts = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->when(!empty($statuses), fn ($q) => $q->whereIn('orders.status', $statuses))
            ->selectRaw('order_items.product_id, COALESCE(products.name, "Produk dihapus") as name, SUM(order_items.quantity) as qty, SUM(order_items.quantity * order_items.price) as total')
            ->groupBy('order_items.product_id', 'products.name')
            ->orderByDesc('qty')
            ->limit(10)
            ->get()
            ->map(fn ($row) => [
                'product_id' => $row->product_id,
                'name' => $row->name,
                'qty' => (int) $row->qty,
                'total' => (float) $row->total,
            ]);

        return view('admin.reports.index', [
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'statuses' => $statuses,
            'orders' => $orders,
            'stats' => $stats,
            'byStatus' => $byStatus,
            'topProducts' => $topProducts,
        ]);
    }

    public function exportPdf(Request $request)
    {
        [$from, $to] = $this->parseDateRange($request);
        $statuses = $this->parseStatuses($request);

        $ordersQuery = Order::query()->with('user')->whereBetween('created_at', [$from, $to]);
        if (!empty($statuses)) {
            $ordersQuery->whereIn('status', $statuses);
        }

        $orders = (clone $ordersQuery)->latest()->get();

        $stats = [
            'total_orders' => (clone $ordersQuery)->count(),
            'total_revenue' => (clone $ordersQuery)->sum('total_price'),
            'avg_order_value' => (clone $ordersQuery)->avg('total_price') ?: 0,
        ];

        $byStatus = (clone $ordersQuery)
            ->selectRaw('status, COUNT(*) as cnt, COALESCE(SUM(total_price),0) as total')
            ->groupBy('status')
            ->orderBy('status')
            ->get()
            ->map(fn ($row) => [
                'status' => $row->status,
                'count' => (int) $row->cnt,
                'total' => (float) $row->total,
            ]);

        $topProducts = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->when(!empty($statuses), fn ($q) => $q->whereIn('orders.status', $statuses))
            ->selectRaw('order_items.product_id, COALESCE(products.name, "Produk dihapus") as name, SUM(order_items.quantity) as qty, SUM(order_items.quantity * order_items.price) as total')
            ->groupBy('order_items.product_id', 'products.name')
            ->orderByDesc('qty')
            ->limit(10)
            ->get()
            ->map(fn ($row) => [
                'product_id' => $row->product_id,
                'name' => $row->name,
                'qty' => (int) $row->qty,
                'total' => (float) $row->total,
            ]);

        $filename = 'laporan-' . $from->format('Ymd') . '-' . $to->format('Ymd') . '.pdf';

        return Pdf::loadView('admin.reports.pdf', [
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'statuses' => $statuses,
            'orders' => $orders,
            'stats' => $stats,
            'byStatus' => $byStatus,
            'topProducts' => $topProducts,
            'generatedAt' => now()->format('d M Y, H:i'),
        ])->setPaper('a4', 'portrait')->download($filename);
    }

    private function parseDateRange(Request $request): array
    {
        $defaultFrom = now()->subDays(30)->toDateString();
        $defaultTo = now()->toDateString();

        $from = Carbon::parse($request->get('from', $defaultFrom))->startOfDay();
        $to = Carbon::parse($request->get('to', $defaultTo))->endOfDay();

        if ($from->greaterThan($to)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        return [$from, $to];
    }

    private function parseStatuses(Request $request): array
    {
        $raw = $request->get('status');
        if ($raw === null || $raw === '' || $raw === 'all') {
            return [];
        }

        $list = is_array($raw) ? $raw : explode(',', (string) $raw);
        $list = array_values(array_filter(array_map(fn ($v) => trim((string) $v), $list)));

        $allowed = ['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'];
        return array_values(array_intersect($allowed, $list));
    }
}

