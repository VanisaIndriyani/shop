<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();
        $unreadMessages = Message::where('is_from_admin', false)->where('is_read', false)->count();

        $recentOrders = Order::with('user')->latest()->take(8)->get();

        $startDate = Carbon::now()->subDays(13)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $ordersByDayRaw = Order::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $revenueByDayRaw = Order::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $ordersSeries = [];
        $revenueSeries = [];

        for ($i = 0; $i < 14; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();
            $labels[] = Carbon::parse($date)->format('d M');
            $ordersSeries[] = (int)($ordersByDayRaw[$date]->total ?? 0);
            $revenueSeries[] = (float)($revenueByDayRaw[$date]->total ?? 0);
        }

        $statusCounts = Order::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->orderBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalCustomers',
            'totalProducts',
            'unreadMessages',
            'recentOrders',
            'labels',
            'ordersSeries',
            'revenueSeries',
            'statusCounts'
        ));
    }
}
