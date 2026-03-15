<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
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

        $orders = collect();
        $counts = [];
        $orderCount = 0;
        $wishlistItems = collect();

        if (Auth::check()) {
            $query = Order::with(['items.product'])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc');

            if ($statusParam && isset($statusMap[$statusParam])) {
                $query->where('status', $statusMap[$statusParam]);
            }

            $orders = $query->get();

            $counts = Order::where('user_id', Auth::id())
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $orderCount = array_sum($counts);

            $wishlistItems = Wishlist::with('product')
                ->where('user_id', Auth::id())
                ->latest()
                ->get();
        }

        return view('account.index', compact('orders', 'counts', 'statusParam', 'orderCount', 'wishlistItems'));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($user->id)],
            'address' => ['required', 'string', 'max:2000'],
        ]);

        $user->update($validated);

        return redirect()->route('account.index')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePin(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_pin' => ['required', 'string'],
            'new_pin' => ['required', 'numeric', 'digits:6', 'confirmed'],
        ]);

        if (!Hash::check($validated['current_pin'], (string) $user->password)) {
            return back()->withErrors(['current_pin' => 'PIN lama salah.'])->withInput();
        }

        $user->update(['password' => $validated['new_pin']]);

        return redirect()->route('account.index')->with('success', 'PIN berhasil diperbarui.');
    }
}
