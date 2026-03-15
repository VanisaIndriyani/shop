@extends('layouts.admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <div class="fw-bold fs-4">Pesanan</div>
        <div class="text-muted">Pantau pesanan yang masuk.</div>
    </div>
</div>

<div class="card content-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
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
                    <tr>
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
                            <span class="badge rounded-pill 
                                @if($order->status === 'pending') text-bg-warning
                                @elseif($order->status === 'paid') text-bg-primary
                                @elseif($order->status === 'processing') text-bg-info
                                @elseif($order->status === 'shipped') text-bg-secondary
                                @elseif($order->status === 'completed') text-bg-success
                                @elseif($order->status === 'cancelled') text-bg-danger
                                @else text-bg-dark
                                @endif">
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
    <div class="card-body">
        {{ $orders->links() }}
    </div>
</div>
@endsection
