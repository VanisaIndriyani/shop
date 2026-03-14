@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <!-- Header Section -->
            <div class="mb-12" data-aos="fade-down">
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-gray-500">
                        <li><a href="{{ route('shop.index') }}" class="hover:text-blue-600 transition-colors">Home</a></li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" /></svg></li>
                        <li class="text-gray-900 font-bold">Riwayat Pesanan</li>
                    </ol>
                </nav>
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tighter uppercase mb-2">Riwayat Pesanan</h1>
                <p class="text-lg text-gray-500 font-medium border-l-4 border-blue-600 pl-4">Pantau status dan detail semua pesanan Anda di sini.</p>
            </div>

            <!-- Tabs Shopee-style -->
            @php
                $tabs = [
                    ['key' => null, 'label' => 'Semua'],
                    ['key' => 'unpaid', 'label' => 'Belum Bayar'],
                    ['key' => 'processing', 'label' => 'Diproses'],
                    ['key' => 'shipped', 'label' => 'Dikirim'],
                    ['key' => 'completed', 'label' => 'Selesai'],
                    ['key' => 'cancelled', 'label' => 'Dibatalkan'],
                ];
                $statusMapCounts = [
                    'pending' => 'unpaid',
                    'paid' => 'paid',
                    'processing' => 'processing',
                    'shipped' => 'shipped',
                    'completed' => 'completed',
                    'cancelled' => 'cancelled',
                ];
                $totalAll = array_sum($counts ?? []);
                $countsByKey = [
                    'unpaid' => $counts['pending'] ?? 0,
                    'processing' => $counts['processing'] ?? 0,
                    'shipped' => $counts['shipped'] ?? 0,
                    'completed' => $counts['completed'] ?? 0,
                    'cancelled' => $counts['cancelled'] ?? 0,
                ];
            @endphp

            <div class="mb-4">
                <div class="d-flex flex-wrap gap-2">
                    @foreach($tabs as $tab)
                        @php
                            $isActive = ($statusParam ?? null) === ($tab['key'] ?? null);
                            $badgeCount = $tab['key'] ? ($countsByKey[$tab['key']] ?? 0) : $totalAll;
                            $url = $tab['key'] ? route('orders.index', ['status' => $tab['key']]) : route('orders.index');
                        @endphp
                        <a href="{{ $url }}" class="btn btn-sm rounded-pill {{ $isActive ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ $tab['label'] }}
                            <span class="badge text-bg-light ms-2">{{ $badgeCount }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            @if($orders->isEmpty())
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-16 text-center" data-aos="zoom-in">
                    <div class="mx-auto h-32 w-32 bg-blue-50 rounded-full flex items-center justify-center mb-8">
                        <svg class="h-16 w-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-4 uppercase tracking-tight">Belum Ada Pesanan</h3>
                    <p class="text-gray-500 mb-10 max-w-sm mx-auto font-medium">Sepertinya Anda belum melakukan pembelian apapun. Yuk, cari produk favorit Anda sekarang!</p>
                    <a href="{{ route('shop.index') }}" class="inline-flex items-center px-8 py-4 border border-transparent rounded-2xl shadow-lg text-lg font-black text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:-translate-y-1">
                        Mulai Belanja Sekarang
                    </a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($orders as $order)
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                                <div class="card-header bg-white border-0 px-4 py-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex flex-wrap align-items-center gap-3">
                                        <div>
                                            <div class="text-muted small fw-semibold">Nomor Pesanan</div>
                                            <div class="fw-bold">#{{ $order->id }}</div>
                                        </div>
                                        <div class="vr"></div>
                                        <div>
                                            <div class="text-muted small fw-semibold">Tanggal</div>
                                            <div class="fw-semibold">{{ $order->created_at->format('d M Y') }}</div>
                                        </div>
                                        <div class="vr"></div>
                                        <div>
                                            <div class="text-muted small fw-semibold">Total</div>
                                            <div class="fw-bold text-primary" data-money-idr="{{ (float) $order->total_price }}">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                    <div>
                                        @php
                                            $statusMap = [
                                                'pending' => 'warning',
                                                'processing' => 'primary',
                                                'completed' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge rounded-pill text-bg-{{ $statusMap[$order->status] ?? 'secondary' }}">{{ strtoupper($order->status) }}</span>
                                    </div>
                                </div>
                                <div class="card-body px-4 py-4">
                                    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-3">
                                        <div class="d-flex align-items-center gap-2 overflow-auto" style="max-width: 100%">
                                            @foreach($order->items->take(4) as $item)
                                                <div class="position-relative">
                                                    <div class="rounded-3 border bg-light overflow-hidden" style="width:72px;height:72px;">
                                                        @if($item->product && $item->product->image)
                                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" style="width:100%;height:100%;object-fit:cover;">
                                                        @else
                                                            <div class="d-flex align-items-center justify-content-center text-muted" style="width:100%;height:100%;">
                                                                <i class="bi bi-image"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <span class="position-absolute top-0 end-0 translate-middle badge rounded-pill text-bg-primary">
                                                        {{ $item->quantity }}
                                                    </span>
                                                </div>
                                            @endforeach
                                            @if($order->items->count() > 4)
                                                <div class="rounded-3 border bg-light d-flex align-items-center justify-content-center text-muted fw-bold" style="width:72px;height:72px;">
                                                    +{{ $order->items->count() - 4 }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="w-100 w-lg-auto d-flex gap-2">
                                            <a href="{{ route('orders.show', $order) }}" class="btn btn-primary rounded-pill px-4">Detail Pesanan</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection
