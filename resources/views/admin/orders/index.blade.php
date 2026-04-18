@extends('layouts.admin')

@section('content')
@php
    $statusColor = [
        'pending' => 'warning',
        'paid' => 'primary',
        'processing' => 'info',
        'shipped' => 'secondary',
        'completed' => 'success',
        'cancelled' => 'danger',
    ];
    $pendingCount = $orders->getCollection()->where('status', 'pending')->count();
    $processingCount = $orders->getCollection()->where('status', 'processing')->count();
@endphp

<style>
    .orders-hero{border:0;border-radius:18px;box-shadow:0 12px 30px rgba(16,24,40,.06);background:linear-gradient(135deg,#2563eb,#4f46e5)}
    .orders-hero__inner{padding:18px;color:#fff;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap}
    .orders-chip{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.22);border-radius:999px;padding:8px 12px;font-size:12px;font-weight:800}
    .orders-search{border-radius:12px}
    .orders-table td,.orders-table th{white-space:nowrap}
    .orders-mobile-card{border:1px solid #e5e7eb;border-radius:14px;padding:12px;background:#fff}
</style>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div class="w-100">
        <div class="orders-hero">
            <div class="orders-hero__inner">
                <div>
                    <div class="fw-bold fs-4">Pesanan</div>
                    <div class="opacity-75">Pantau pesanan yang masuk.</div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="orders-chip"><i class="bi bi-receipt"></i> Total: {{ number_format($orders->total(), 0, ',', '.') }}</span>
                    <span class="orders-chip"><i class="bi bi-hourglass-split"></i> Pending: {{ $pendingCount }}</span>
                    <span class="orders-chip"><i class="bi bi-box-seam"></i> Proses: {{ $processingCount }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card content-card">
    <div class="card-body border-bottom bg-white">
        <input id="orderSearch" type="search" class="form-control orders-search" placeholder="Cari nomor order, nama, atau email customer...">
    </div>

    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle mb-0 orders-table">
            <thead class="table-light">
                <tr>
                    <th class="ps-4" style="width: 72px;">No</th>
                    <th>Order</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Resi</th>
                    <th class="pe-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr data-order-row data-search="{{ strtolower('#'.$order->id.' '.$order->user?->name.' '.$order->user?->email) }}">
                        <td class="ps-4 fw-semibold text-muted">{{ $orders->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-bold">#{{ $order->id }}</div>
                            <div class="text-muted small">{{ $order->created_at?->format('d M Y, H:i') }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $order->user?->name }}</div>
                            <div class="text-muted small">{{ $order->user?->email }}</div>
                        </td>
                        <td class="fw-bold text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge rounded-pill text-bg-{{ $statusColor[$order->status] ?? 'dark' }}">
                                {{ strtoupper($order->status) }}
                            </span>
                        </td>
                        <td>
                            @if($order->tracking_number)
                                <div class="fw-semibold">{{ $order->tracking_number }}</div>
                                <div class="text-muted small">{{ $order->shipping_courier ? strtoupper($order->shipping_courier) : 'KURIR' }}</div>
                            @else
                                <div class="text-muted small">-</div>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                <i class="bi bi-eye me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">Belum ada pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-md-none p-3" id="orderMobileList">
        <div class="d-grid gap-3">
            @forelse($orders as $order)
                <div class="orders-mobile-card" data-order-row data-search="{{ strtolower('#'.$order->id.' '.$order->user?->name.' '.$order->user?->email) }}">
                    <div class="d-flex align-items-start justify-content-between gap-2">
                        <div>
                            <div class="fw-bold">#{{ $order->id }}</div>
                            <div class="text-muted small">{{ $order->created_at?->format('d M Y, H:i') }}</div>
                        </div>
                        <span class="badge rounded-pill text-bg-{{ $statusColor[$order->status] ?? 'dark' }}">{{ strtoupper($order->status) }}</span>
                    </div>
                    <div class="mt-2">
                        <div class="fw-semibold">{{ $order->user?->name }}</div>
                        <div class="text-muted small">{{ $order->user?->email }}</div>
                    </div>
                    <div class="mt-2 d-flex align-items-center justify-content-between gap-2">
                        <div class="fw-bold text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                            <i class="bi bi-eye me-1"></i> Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">Belum ada pesanan.</div>
            @endforelse
        </div>
    </div>

    <div class="card-body">
        {{ $orders->links() }}
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const input = document.getElementById('orderSearch');
        if (!input) return;
        const rows = document.querySelectorAll('[data-order-row]');
        function applyFilter() {
            const q = String(input.value || '').trim().toLowerCase();
            rows.forEach((row) => {
                const hay = row.getAttribute('data-search') || '';
                row.style.display = q === '' || hay.includes(q) ? '' : 'none';
            });
        }
        input.addEventListener('input', applyFilter);
    })();
</script>
@endpush
@endsection
