<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'user@refrens.com'],
            [
                'name' => 'User Demo',
                'phone' => '089900112233',
                'address' => 'Jl. Contoh No. 1, Indonesia',
                'password' => Hash::make('123456'),
                'role' => 'customer',
            ]
        );

        if (Product::count() === 0) {
            $fallbackProducts = [
                ['Demo Tee - Black', 189000, 'T-Shirt', 50],
                ['Demo Hoodie - Grey', 329000, 'Hoodie', 30],
                ['Demo Pants - Khaki', 349000, 'Pants', 25],
                ['Demo Jacket - Olive', 389000, 'Jacket', 20],
            ];

            foreach ($fallbackProducts as [$name, $price, $category, $stock]) {
                $slug = Str::slug($name);
                Product::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $name,
                        'description' => $name,
                        'price' => $price,
                        'sale_price' => null,
                        'image' => null,
                        'category' => $category,
                        'stock' => $stock,
                    ]
                );
            }
        }

        $seedTag = 'seed:customer-demo';

        Order::where('user_id', $user->id)
            ->where('shipping_note', $seedTag)
            ->delete();

        $products = Product::orderBy('id')->take(8)->get();
        if ($products->isEmpty()) {
            return;
        }

        $makeItems = function (array $pairs) use ($products) {
            $items = [];
            foreach ($pairs as [$idx, $qty]) {
                $p = $products->get($idx % $products->count());
                if (!$p) {
                    continue;
                }
                $items[] = [
                    'product_id' => $p->id,
                    'quantity' => $qty,
                    'price' => (float) $p->price,
                ];
            }
            return $items;
        };

        $ordersToSeed = [
            [
                'status' => 'pending',
                'payment_method' => 'bank_transfer',
                'shipping_courier' => null,
                'tracking_number' => null,
                'shipped_at' => null,
                'days_ago' => 1,
                'items' => $makeItems([[0, 1], [1, 1]]),
            ],
            [
                'status' => 'paid',
                'payment_method' => 'bank_transfer',
                'shipping_courier' => null,
                'tracking_number' => null,
                'shipped_at' => null,
                'days_ago' => 3,
                'items' => $makeItems([[2, 1]]),
            ],
            [
                'status' => 'processing',
                'payment_method' => 'bank_transfer',
                'shipping_courier' => null,
                'tracking_number' => null,
                'shipped_at' => null,
                'days_ago' => 4,
                'items' => $makeItems([[3, 2]]),
            ],
            [
                'status' => 'shipped',
                'payment_method' => 'bank_transfer',
                'shipping_courier' => 'JNE',
                'tracking_number' => 'JNE' . now()->format('ymd') . '0001',
                'shipped_at' => now()->subDays(2),
                'days_ago' => 6,
                'items' => $makeItems([[4, 1], [5, 1]]),
            ],
            [
                'status' => 'completed',
                'payment_method' => 'bank_transfer',
                'shipping_courier' => 'J&T',
                'tracking_number' => 'JT' . now()->format('ymd') . '0002',
                'shipped_at' => now()->subDays(10),
                'days_ago' => 12,
                'items' => $makeItems([[6, 1]]),
            ],
            [
                'status' => 'cancelled',
                'payment_method' => 'bank_transfer',
                'shipping_courier' => null,
                'tracking_number' => null,
                'shipped_at' => null,
                'days_ago' => 15,
                'items' => $makeItems([[7, 1]]),
            ],
        ];

        foreach ($ordersToSeed as $data) {
            $items = $data['items'] ?? [];
            if (count($items) === 0) {
                continue;
            }

            $total = 0;
            foreach ($items as $it) {
                $total += ((float) $it['price']) * ((int) $it['quantity']);
            }

            $createdAt = now()->subDays((int) ($data['days_ago'] ?? 0));

            $order = Order::unguarded(function () use ($user, $data, $seedTag, $total, $createdAt) {
                return Order::create([
                    'user_id' => $user->id,
                    'status' => $data['status'],
                    'total_price' => $total,
                    'shipping_address' => $user->address,
                    'payment_method' => $data['payment_method'],
                    'payment_proof' => null,
                    'shipping_courier' => $data['shipping_courier'],
                    'tracking_number' => $data['tracking_number'],
                    'shipping_note' => $seedTag,
                    'shipped_at' => $data['shipped_at'],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            });

            foreach ($items as $it) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $it['product_id'],
                    'quantity' => $it['quantity'],
                    'price' => $it['price'],
                ]);
            }
        }
    }
}
