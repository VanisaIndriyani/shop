@extends('layouts.admin')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card content-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted fw-semibold small">Total Pesanan</div>
                    <div class="fs-3 fw-bold mt-1">{{ $totalOrders }}</div>
                </div>
                <div class="bg-primary bg-opacity-10 text-primary rounded-4 d-flex align-items-center justify-content-center" style="width:52px;height:52px;">
                    <i class="bi bi-cart3 fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card content-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted fw-semibold small">Pesan Belum Dibaca</div>
                    <div class="fs-3 fw-bold mt-1">{{ $unreadMessages ?? 0 }}</div>
                    <a href="{{ route('admin.messages.index') }}" class="small fw-semibold text-primary text-decoration-none">Lihat pesan</a>
                </div>
                <div class="bg-info bg-opacity-10 text-info rounded-4 d-flex align-items-center justify-content-center" style="width:52px;height:52px;">
                    <i class="bi bi-chat-dots fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card content-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted fw-semibold small">Pendapatan Selesai</div>
                    <div class="fs-5 fw-bold mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
                <div class="bg-success bg-opacity-10 text-success rounded-4 d-flex align-items-center justify-content-center" style="width:52px;height:52px;">
                    <i class="bi bi-currency-dollar fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card content-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted fw-semibold small">Total Customer</div>
                    <div class="fs-3 fw-bold mt-1">{{ $totalCustomers }}</div>
                </div>
                <div class="bg-warning bg-opacity-10 text-warning rounded-4 d-flex align-items-center justify-content-center" style="width:52px;height:52px;">
                    <i class="bi bi-people fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card content-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted fw-semibold small">Total Produk</div>
                    <div class="fs-3 fw-bold mt-1">{{ $totalProducts }}</div>
                </div>
                <div class="bg-danger bg-opacity-10 text-danger rounded-4 d-flex align-items-center justify-content-center" style="width:52px;height:52px;">
                    <i class="bi bi-box-seam fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-xl-8">
        <div class="card content-card">
            <div class="card-header bg-white border-0 px-4 py-3 d-flex align-items-center justify-content-between">
                <div>
                    <div class="fw-bold">Grafik Pesanan (14 Hari)</div>
                    <div class="text-muted small">Jumlah pesanan & pendapatan selesai</div>
                </div>
            </div>
            <div class="card-body px-4 py-3">
                <canvas id="ordersChart" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="card content-card">
            <div class="card-header bg-white border-0 px-4 py-3">
                <div class="fw-bold">Status Pesanan</div>
                <div class="text-muted small">Distribusi status saat ini</div>
            </div>
            <div class="card-body px-4 py-3">
                <canvas id="statusChart" height="140"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card content-card">
    <div class="card-header bg-white border-0 px-4 py-3 d-flex align-items-center justify-content-between">
        <div>
            <div class="fw-bold">Pesanan Terbaru</div>
            <div class="text-muted small">8 pesanan terakhir</div>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
    </div>
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
                    <th class="pe-4 text-end">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td class="ps-4 fw-semibold text-muted">{{ $loop->iteration }}</td>
                        <td class="fw-bold">#{{ $order->id }}</td>
                        <td class="fw-semibold">{{ $order->user?->name }}</td>
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
                        <td class="pe-4 text-end text-muted">{{ $order->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">Belum ada pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        const labels = @json($labels ?? []);
        const ordersSeries = @json($ordersSeries ?? []);
        const revenueSeries = @json($revenueSeries ?? []);
        const statusCounts = @json($statusCounts ?? []);

        const formatRupiah = (value) => {
            try {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value || 0);
            } catch (e) {
                return 'Rp ' + (value || 0);
            }
        };

        const ordersCanvas = document.getElementById('ordersChart');
        if (ordersCanvas) {
            new Chart(ordersCanvas, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Pesanan',
                            data: ordersSeries,
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13,110,253,.12)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 2,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Pendapatan (Selesai)',
                            data: revenueSeries,
                            borderColor: '#198754',
                            backgroundColor: 'rgba(25,135,84,.10)',
                            tension: 0.35,
                            fill: false,
                            pointRadius: 2,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function (ctx) {
                                    if (ctx.dataset.yAxisID === 'y1') return ctx.dataset.label + ': ' + formatRupiah(ctx.parsed.y);
                                    return ctx.dataset.label + ': ' + ctx.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            ticks: {
                                callback: function (val) {
                                    return formatRupiah(val);
                                }
                            }
                        }
                    }
                }
            });
        }

        const statusCanvas = document.getElementById('statusChart');
        if (statusCanvas) {
            const statusLabels = Object.keys(statusCounts);
            const statusData = statusLabels.map(k => statusCounts[k]);
            const colors = {
                pending: '#ffc107',
                processing: '#0d6efd',
                paid: '#0d6efd',
                shipped: '#6f42c1',
                completed: '#198754',
                cancelled: '#dc3545'
            };

            new Chart(statusCanvas, {
                type: 'doughnut',
                data: {
                    labels: statusLabels.map(s => String(s).toUpperCase()),
                    datasets: [{
                        data: statusData,
                        backgroundColor: statusLabels.map(s => colors[s] || '#6c757d'),
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    cutout: '68%'
                }
            });
        }
    })();
</script>
@endpush
@endsection
