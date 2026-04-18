@extends('layouts.admin')

@section('content')
@php
    $allStatuses = [
        'pending' => 'PENDING',
        'paid' => 'PAID',
        'processing' => 'PROCESSING',
        'shipped' => 'SHIPPED',
        'completed' => 'COMPLETED',
        'cancelled' => 'CANCELLED',
    ];
    $statusColor = [
        'pending' => 'warning',
        'paid' => 'primary',
        'processing' => 'info',
        'shipped' => 'secondary',
        'completed' => 'success',
        'cancelled' => 'danger',
    ];
@endphp

<style>
    .report-filter{border:0;border-radius:18px;box-shadow:0 12px 30px rgba(16,24,40,.06)}
    .report-filter .form-control,.report-filter .form-select{border-radius:12px}
    .report-chip{display:inline-flex;align-items:center;gap:8px;border:1px solid rgba(37,99,235,.25);border-radius:999px;padding:8px 12px;background:#fff;font-weight:700;font-size:12px}
    .report-stat{border:0;border-radius:18px;box-shadow:0 12px 30px rgba(16,24,40,.06)}
    .report-chartbox{position:relative;height:230px}
    .report-chartbox canvas{width:100% !important;height:100% !important}
    .report-soft{background:#f8fafc}
</style>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <div class="fw-bold fs-4">Laporan</div>
        <div class="text-muted">Rekap pesanan, performa status, dan top produk.</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports.pdf', request()->query()) }}" class="btn btn-primary rounded-pill px-3">
            <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
        </a>
    </div>
</div>

<div class="card report-filter mb-4">
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
                <label class="form-label fw-semibold d-block">Status</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($allStatuses as $key => $label)
                        <label class="report-chip">
                            <input type="checkbox" name="status[]" value="{{ $key }}" class="form-check-input m-0" {{ in_array($key, $statuses ?? [], true) ? 'checked' : '' }}>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="form-text">Kosongkan semua centang untuk menampilkan semua status.</div>
            </div>
            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary rounded-pill px-3">Reset</a>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Terapkan</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-md-4">
        <div class="card report-stat">
            <div class="card-body p-4">
                <div class="text-muted small fw-semibold">Total Pesanan</div>
                <div class="fs-3 fw-bold">{{ number_format($stats['total_orders'] ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card report-stat">
            <div class="card-body p-4">
                <div class="text-muted small fw-semibold">Total Pendapatan</div>
                <div class="fs-3 fw-bold text-primary">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card report-stat">
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
                <div class="report-chartbox mb-3">
                    <canvas id="reportStatusChart"></canvas>
                </div>
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
                                    <td><span class="badge rounded-pill text-bg-{{ $statusColor[$row['status']] ?? 'secondary' }}">{{ strtoupper($row['status']) }}</span></td>
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
                <div class="report-chartbox mb-3">
                    <canvas id="topProductChart"></canvas>
                </div>
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
                            <td><span class="badge rounded-pill text-bg-{{ $statusColor[$order->status] ?? 'secondary' }}">{{ strtoupper($order->status) }}</span></td>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        const byStatus = @json($byStatus ?? []);
        const topProducts = @json($topProducts ?? []);
        const statusCanvas = document.getElementById('reportStatusChart');
        const topCanvas = document.getElementById('topProductChart');

        const statusColor = {
            pending: '#f59e0b',
            paid: '#2563eb',
            processing: '#06b6d4',
            shipped: '#64748b',
            completed: '#22c55e',
            cancelled: '#ef4444'
        };

        if (statusCanvas) {
            const labels = byStatus.map(r => String(r.status || '').toUpperCase());
            const values = byStatus.map(r => Number(r.count || 0));
            const colors = byStatus.map(r => statusColor[r.status] || '#94a3b8');

            new Chart(statusCanvas, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{ data: values, backgroundColor: colors, borderWidth: 0 }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 10, boxHeight: 10, padding: 10, font: { size: 11, weight: '700' } } }
                    },
                    cutout: '65%'
                }
            });
        }

        if (topCanvas) {
            const labels = topProducts.map(r => String(r.name || 'Produk'));
            const values = topProducts.map(r => Number(r.qty || 0));
            new Chart(topCanvas, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Qty',
                        data: values,
                        backgroundColor: 'rgba(37,99,235,.85)',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } },
                        x: { ticks: { maxRotation: 0, minRotation: 0, autoSkip: true, maxTicksLimit: 6 } }
                    }
                }
            });
        }
    })();
</script>
@endpush
@endsection
