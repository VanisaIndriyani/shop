@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

@php
    $statusUi = [
        'pending' => ['label' => 'BELUM BAYAR', 'color' => 'warning', 'step' => 0],
        'paid' => ['label' => 'DIBAYAR', 'color' => 'primary', 'step' => 1],
        'processing' => ['label' => 'DIPROSES', 'color' => 'primary', 'step' => 2],
        'shipped' => ['label' => 'DIKIRIM', 'color' => 'info', 'step' => 3],
        'completed' => ['label' => 'SELESAI', 'color' => 'success', 'step' => 4],
        'cancelled' => ['label' => 'DIBATALKAN', 'color' => 'danger', 'step' => -1],
    ];
    $ui = $statusUi[$order->status] ?? ['label' => strtoupper($order->status), 'color' => 'secondary', 'step' => -1];
    $steps = [
        ['key' => 'pending', 'title' => 'Dibuat'],
        ['key' => 'paid', 'title' => 'Dibayar'],
        ['key' => 'processing', 'title' => 'Diproses'],
        ['key' => 'shipped', 'title' => 'Dikirim'],
        ['key' => 'completed', 'title' => 'Selesai'],
    ];
    $activeStep = $ui['step'];
    $progressPercent = $activeStep < 0 ? 0 : (int)round(($activeStep / 4) * 100);
@endphp

<style>
    .order-card { border: 0; border-radius: 18px; box-shadow: 0 12px 30px rgba(16, 24, 40, 0.08); }
    .thumb { width: 72px; height: 72px; border-radius: 16px; overflow: hidden; background: #f8f9fa; border: 1px solid rgba(0,0,0,.06); flex: 0 0 auto; }
    .thumb img { width: 100%; height: 100%; object-fit: cover; }
    .step-dot { width: 34px; height: 34px; border-radius: 999px; display: flex; align-items: center; justify-content: center; font-weight: 800; }
    .step-line { height: 4px; border-radius: 999px; background: rgba(13,110,253,.15); overflow: hidden; }
    .step-line > div { height: 100%; background: #0d6efd; border-radius: 999px; }
</style>

<div class="container py-5">
    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-decoration-none">Shop</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none">Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">#{{ $order->id }}</li>
                </ol>
            </nav>
            <div class="fw-bold fs-3">Detail Pesanan</div>
            <div class="text-muted">Order #{{ $order->id }} • {{ $order->created_at->format('d M Y, H:i') }}</div>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary rounded-pill">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success order-card px-4 py-3 mb-4">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card order-card mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="fw-bold fs-5"><i class="bi bi-receipt-cutoff me-2 text-primary"></i>Status Pesanan</div>
                        <span class="badge rounded-pill text-bg-{{ $ui['color'] }} px-3 py-2">{{ $ui['label'] }}</span>
                    </div>

                    @if($order->status === 'cancelled')
                        <div class="alert alert-danger border-0 rounded-4 mt-3 mb-0" style="background: rgba(220,53,69,.10);">
                            Pesanan dibatalkan.
                        </div>
                    @else
                        <div class="mt-3">
                            <div class="step-line">
                                <div style="width: {{ $progressPercent }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                @foreach($steps as $index => $step)
                                    @php
                                        $isDone = $activeStep >= $index;
                                        $isActive = $activeStep === $index;
                                    @endphp
                                    <div class="text-center" style="width: 20%;">
                                        <div class="mx-auto step-dot {{ $isDone ? 'bg-primary text-white' : 'bg-light text-muted' }}" style="{{ $isActive ? 'box-shadow: 0 10px 24px rgba(13,110,253,.25);' : '' }}">
                                            @if($isDone)
                                                <i class="bi bi-check2"></i>
                                            @else
                                                <span class="small">{{ $index + 1 }}</span>
                                            @endif
                                        </div>
                                        <div class="small fw-semibold mt-2 {{ $isDone ? 'text-dark' : 'text-muted' }}">{{ $step['title'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card order-card">
                <div class="card-body p-4">
                    <div class="fw-bold fs-5 mb-3"><i class="bi bi-bag me-2 text-primary"></i>Daftar Produk</div>
                    <div class="d-flex flex-column gap-3">
                        @foreach($order->items as $item)
                            <div class="d-flex align-items-center gap-3">
                                <div class="thumb">
                                    @if($item->product?->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-truncate">{{ $item->product?->name }}</div>
                                    <div class="text-muted small">Qty: {{ $item->quantity }} • <span data-money-idr="{{ (float) $item->price }}">Rp {{ number_format($item->price, 0, ',', '.') }}</span></div>
                                </div>
                                <div class="fw-bold text-primary text-nowrap" data-money-idr="{{ (float) ($item->price * $item->quantity) }}">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>

                    <hr class="my-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="text-muted fw-semibold">Total Pembayaran</div>
                        <div class="fw-bold fs-4 text-primary" data-money-idr="{{ (float) $order->total_price }}">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card order-card mb-4">
                <div class="card-body p-4">
                    <div class="fw-bold fs-5 mb-3"><i class="bi bi-geo-alt me-2 text-primary"></i>Pengiriman</div>
                    <div class="text-muted small mb-1">Alamat</div>
                    <div class="fw-semibold">{{ $order->shipping_address }}</div>
                    <hr>
                    @if($order->shipping_courier || $order->tracking_number || $order->shipping_note)
                        <div class="text-muted small mb-1">Kurir</div>
                        <div class="fw-semibold">{{ $order->shipping_courier ? strtoupper($order->shipping_courier) : '-' }}</div>
                        <div class="mt-2 text-muted small mb-1">No Resi</div>
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <div class="fw-bold text-primary text-truncate">{{ $order->tracking_number ?: '-' }}</div>
                            @if($order->tracking_number)
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill flex-shrink-0" data-copy-resi="{{ $order->tracking_number }}">
                                    <i class="bi bi-clipboard me-1"></i>Copy
                                </button>
                            @endif
                        </div>
                        @if($order->tracking_number)
                            <div class="text-success small fw-semibold mt-2 d-none" id="copyResiFeedback">Resi tersalin</div>
                        @endif
                        @if($order->shipping_note)
                            <div class="mt-2 text-muted small mb-1">Catatan</div>
                            <div class="fw-semibold">{{ $order->shipping_note }}</div>
                        @endif
                        <hr>
                    @endif
                    <div class="text-muted small mb-1">Tanggal Pesanan</div>
                    <div class="fw-semibold">{{ $order->created_at->format('d F Y, H:i') }}</div>
                    @if($order->shipped_at)
                        <div class="mt-2 text-muted small mb-1">Tanggal Dikirim</div>
                        <div class="fw-semibold">{{ $order->shipped_at->format('d F Y, H:i') }}</div>
                    @endif
                </div>
            </div>

            <div class="card order-card mb-4">
                <div class="card-body p-4">
                    <div class="fw-bold fs-5 mb-3"><i class="bi bi-credit-card me-2 text-primary"></i>Pembayaran</div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="text-muted small">Metode</div>
                        <span class="badge rounded-pill text-bg-light border">{{ $order->payment_method == 'bank_transfer' ? 'TRANSFER / QRIS' : strtoupper($order->payment_method) }}</span>
                    </div>

                    @if($order->payment_proof)
                        <div class="mt-3">
                            <div class="text-muted small mb-2">Bukti Pembayaran</div>
                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="d-block">
                                <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Pembayaran" class="w-100 rounded-4 border" style="max-height: 280px; object-fit: cover;">
                            </a>
                        </div>
                    @elseif($order->payment_method == 'bank_transfer')
                        <div class="alert alert-warning border-0 rounded-4 mt-3 mb-0" style="background: rgba(255,193,7,.12);">
                            Menunggu verifikasi bukti transfer oleh admin.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card order-card">
                <div class="card-body p-4">
                    <div class="fw-bold fs-5 mb-2"><i class="bi bi-headset me-2 text-primary"></i>Butuh Bantuan?</div>
                    <div class="text-muted small mb-3">Kalau ada kendala pesanan, chat admin atau hubungi WhatsApp.</div>
                    <a href="https://wa.me/628123456789" target="_blank" class="btn btn-dark w-100 rounded-pill">Hubungi WhatsApp</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        const btn = document.querySelector('[data-copy-resi]');
        const feedback = document.getElementById('copyResiFeedback');
        if (!btn) return;

        async function copyText(text) {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(text);
                return;
            }
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.setAttribute('readonly', '');
            textarea.style.position = 'fixed';
            textarea.style.left = '-9999px';
            textarea.style.top = '-9999px';
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
        }

        btn.addEventListener('click', async () => {
            const resi = btn.getAttribute('data-copy-resi') || '';
            if (!resi) return;
            try {
                await copyText(resi);
                if (feedback) {
                    feedback.classList.remove('d-none');
                    window.clearTimeout(window.__resiTimer);
                    window.__resiTimer = window.setTimeout(() => {
                        feedback.classList.add('d-none');
                    }, 1600);
                }
            } catch (e) {}
        });
    })();
</script>
@endpush
@endsection
