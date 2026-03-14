@extends('layouts.admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <div class="fw-bold fs-4">Laporan</div>
        <div class="text-muted">Rekap pesanan dan pendapatan, bisa ekspor PDF.</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports.pdf', request()->query()) }}" class="btn btn-primary">
            <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
        </a>
    </div>
</div>

<div class="card content-card mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3">
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Dari</label>
                <input type="date" name="from" value="{{ $from }}" class="form-control">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Sampai</label>
                <input type="date" name="to" value="{{ $to }}" class="form-control">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Status</label>
                <select name="status[]" class="form-select" multiple>
                    @php
                        $allStatuses = [
                            'pending' => 'PENDING',
                            'paid' => 'PAID',
                            'processing' => 'PROCESSING',
                            'shipped' => 'SHIPPED',
                            'completed' => 'COMPLETED',
                            'cancelled' => 'CANCELLED',
                        ];
                    @endphp
                    @foreach($allStatuses as $key => $label)
                        <option value="{{ $key }}" {{ in_array($key, $statuses ?? [], true) ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="form-text">Kalau kosong, berarti semua status.</div>
            </div>

            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">Reset</a>
                <button type="submit" class="btn btn-primary">Terapkan</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-md-4">
        <div class="card content-card">
            <div class="card-body p-4">
                <div class="text-muted small fw-semibold">Total Pesanan</div>
                <div class="fs-3 fw-bold">{{ number_format($stats['total_orders'] ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card content-card">
            <div class="card-body p-4">
                <div class="text-muted small fw-semibold">Total Pendapatan</div>
                <div class="fs-3 fw-bold text-primary">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card content-card">
            <div class="card-body p-4">
                <div class="text-muted small fw-semibold">Rata-rata / Order</div>
                <div class="fs-3 fw-bold">Rp {{ number_format($stats['avg_order_value'] ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-lg-5">
        <div class="card content-card h-100">
            <div class="card-body p-4">
                <div class="fw-bold mb-3">Rekap Status</div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Status</th>
                                <th class="text-end">Jumlah</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($byStatus as $row)
                                <tr>
                                    <td class="fw-semibold">{{ strtoupper($row['status']) }}</td>
                                    <td class="text-end">{{ number_format($row['count'], 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Tidak ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-7">
        <div class="card content-card h-100">
            <div class="card-body p-4">
                <div class="fw-bold mb-3">Top Produk</div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $row)
                                <tr>
                                    <td class="fw-semibold">{{ $row['name'] }}</td>
                                    <td class="text-end">{{ number_format($row['qty'], 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Tidak ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card content-card">
    <div class="card-body p-4">
        <div class="fw-bold mb-3">Daftar Order</div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 72px;">No</th>
                        <th>Order</th>
                        <th>Pelanggan</th>
                        <th class="text-end">Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="text-muted fw-semibold">{{ $orders->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="fw-bold">#{{ $order->id }}</div>
                                <div class="text-muted small">{{ $order->created_at?->format('d M Y, H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $order->user?->name }}</div>
                                <div class="text-muted small">{{ $order->user?->email }}</div>
                            </td>
                            <td class="text-end fw-bold text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td><span class="badge rounded-pill text-bg-secondary">{{ strtoupper($order->status) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">Tidak ada order.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection

