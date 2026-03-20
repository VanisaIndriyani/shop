@extends('layouts.app')

@section('content')
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
    body{background:#f6f7fb}
    .order-wrap{padding:14px 12px 22px}
    .order-card{max-width:520px;margin:0 auto;background:#fff;border:1px solid rgba(0,0,0,.06);border-radius:16px;overflow:hidden}
    .order-top{display:flex;align-items:center;gap:10px;padding:12px 12px;border-bottom:1px solid rgba(0,0,0,.06)}
    .order-back{width:40px;height:40px;border:0;background:transparent;border-radius:999px;display:flex;align-items:center;justify-content:center;color:#111827;text-decoration:none}
    .order-body{padding:14px 14px 18px}
    .order-h1{font-size:18px;font-weight:900;margin:0;color:#111827}
    .order-sub{font-size:12px;color:#6b7280;font-weight:700;margin-top:6px}
    .order-badge{display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:999px;font-size:12px;font-weight:900;border:1px solid rgba(0,0,0,.10)}
    .order-badge.primary{background:rgba(37,99,235,.10);color:#2563eb}
    .order-badge.warning{background:rgba(245,158,11,.12);color:#b45309}
    .order-badge.info{background:rgba(14,165,233,.12);color:#0369a1}
    .order-badge.success{background:rgba(34,197,94,.12);color:#15803d}
    .order-badge.danger{background:rgba(239,68,68,.12);color:#b91c1c}
    .order-badge.secondary{background:rgba(107,114,128,.12);color:#374151}
    .order-section{margin-top:16px}
    .order-title{font-size:14px;font-weight:900;color:#111827;margin:0 0 10px}
    .order-stepbar{height:6px;border-radius:999px;background:rgba(37,99,235,.15);overflow:hidden}
    .order-stepbar > div{height:100%;background:#2563eb;border-radius:999px}
    .order-steps{display:flex;justify-content:space-between;margin-top:10px}
    .order-step{width:20%;text-align:center}
    .order-dot{width:28px;height:28px;border-radius:999px;margin:0 auto;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:12px;border:1px solid rgba(0,0,0,.10);background:#fff;color:#6b7280}
    .order-dot.done{background:#2563eb;border-color:#2563eb;color:#fff}
    .order-step .lbl{margin-top:6px;font-size:11px;font-weight:800;color:#6b7280}
    .order-step .lbl.done{color:#111827}
    .order-alert{border:1px solid rgba(239,68,68,.18);background:rgba(239,68,68,.10);color:#991b1b;border-radius:12px;padding:10px 12px;font-size:12px;font-weight:800}
    .order-item{display:flex;gap:12px;padding:12px 0;border-top:1px solid rgba(0,0,0,.06)}
    .order-item:first-child{border-top:0;padding-top:0}
    .order-thumb{width:62px;height:78px;border-radius:14px;overflow:hidden;background:#f3f4f6;border:1px solid rgba(0,0,0,.06);flex:0 0 auto}
    .order-thumb img{width:100%;height:100%;object-fit:cover}
    .order-meta{flex:1 1 auto;min-width:0}
    .order-name{font-size:12px;font-weight:900;color:#111827;line-height:1.2}
    .order-mini{font-size:11px;font-weight:700;color:#6b7280;margin-top:6px}
    .order-price{font-size:12px;font-weight:900;color:#111827;white-space:nowrap}
    .order-total{display:flex;align-items:center;justify-content:space-between;margin-top:12px;padding-top:12px;border-top:1px solid rgba(0,0,0,.10)}
    .order-total .l{font-size:12px;font-weight:900;color:#111827}
    .order-total .v{font-size:15px;font-weight:900;color:#111827}
    .order-box{border:1px solid rgba(0,0,0,.10);border-radius:14px;background:#fff;padding:12px}
    .order-row{display:flex;align-items:flex-start;justify-content:space-between;gap:10px}
    .order-row + .order-row{margin-top:10px}
    .order-k{font-size:11px;font-weight:900;color:#6b7280}
    .order-val{font-size:12px;font-weight:900;color:#111827;text-align:right}
    .order-copy{border:1px solid rgba(0,0,0,.10);background:#fff;border-radius:999px;padding:7px 10px;font-size:11px;font-weight:900;color:#111827}
    .order-copy:active{transform:scale(.98)}
    .order-feedback{margin-top:8px;font-size:11px;font-weight:900;color:#15803d}
    .order-qris{margin-top:10px;border:1px solid rgba(0,0,0,.10);border-radius:14px;background:#fff;padding:12px;display:flex;justify-content:center}
    .order-qris img{width:220px;height:220px;object-fit:contain}
    .order-proof img{width:100%;border-radius:14px;border:1px solid rgba(0,0,0,.10);max-height:320px;object-fit:cover}
</style>

<div class="order-wrap">
    <div class="order-card" x-data>
        <div class="order-top">
            <a class="order-back" href="{{ route('orders.index') }}" aria-label="Back">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div style="font-weight:900;color:#111827">Pesanan</div>
        </div>
        <div class="order-body">
            <div class="order-row">
                <div>
                    <div class="order-h1">Order #{{ $order->id }}</div>
                    <div class="order-sub">{{ $order->created_at->format('d M Y, H:i') }}</div>
                </div>
                <div class="order-badge {{ $ui['color'] }}">{{ $ui['label'] }}</div>
            </div>

            <div class="order-section">
                <div class="order-title">Status Pesanan</div>
                @if($order->status === 'cancelled')
                    <div class="order-alert">Pesanan dibatalkan.</div>
                @else
                    <div class="order-stepbar"><div style="width: {{ $progressPercent }}%"></div></div>
                    <div class="order-steps">
                        @foreach($steps as $index => $step)
                            @php $isDone = $activeStep >= $index; @endphp
                            <div class="order-step">
                                <div class="order-dot {{ $isDone ? 'done' : '' }}">
                                    @if($isDone)
                                        <i class="bi bi-check2"></i>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>
                                <div class="lbl {{ $isDone ? 'done' : '' }}">{{ $step['title'] }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="order-section">
                <div class="order-title">Daftar Produk</div>
                @foreach($order->items as $item)
                    <div class="order-item">
                        <div class="order-thumb">
                            @if($item->product?->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"><i class="bi bi-image"></i></div>
                            @endif
                        </div>
                        <div class="order-meta">
                            <div class="order-name">{{ $item->product?->name ?? 'Produk dihapus' }}</div>
                            <div class="order-mini">Qty: {{ (int) $item->quantity }} • <span data-money-idr="{{ (float) $item->price }}">Rp {{ number_format($item->price, 0, ',', '.') }}</span></div>
                        </div>
                        <div class="order-price" data-money-idr="{{ (float) ($item->price * $item->quantity) }}">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                    </div>
                @endforeach
                <div class="order-total">
                    <div class="l">Total Pembayaran</div>
                    <div class="v" data-money-idr="{{ (float) $order->total_price }}">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="order-section">
                <div class="order-title">Pengiriman</div>
                <div class="order-box">
                    <div class="order-row">
                        <div class="order-k">Alamat</div>
                        <div class="order-val" style="text-align:right">{{ $order->shipping_address }}</div>
                    </div>
                    @if($order->shipping_courier || $order->tracking_number)
                        <div class="order-row">
                            <div class="order-k">Kurir</div>
                            <div class="order-val">{{ $order->shipping_courier ? strtoupper($order->shipping_courier) : '-' }}</div>
                        </div>
                        <div class="order-row">
                            <div class="order-k">No Resi</div>
                            <div class="order-val" style="display:flex;align-items:center;gap:10px;justify-content:flex-end">
                                <span style="color:#111827">{{ $order->tracking_number ?: '-' }}</span>
                                @if($order->tracking_number)
                                    <button type="button" class="order-copy" data-copy-resi="{{ $order->tracking_number }}">Copy</button>
                                @endif
                            </div>
                        </div>
                        @if($order->tracking_number)
                            <div class="order-feedback d-none" id="copyResiFeedback">Resi tersalin</div>
                        @endif
                    @endif
                    @if($order->shipping_note)
                        <div class="order-row">
                            <div class="order-k">Catatan</div>
                            <div class="order-val">{{ $order->shipping_note }}</div>
                        </div>
                    @endif
                    <div class="order-row">
                        <div class="order-k">Tanggal Pesanan</div>
                        <div class="order-val">{{ $order->created_at->format('d F Y, H:i') }}</div>
                    </div>
                    @if($order->shipped_at)
                        <div class="order-row">
                            <div class="order-k">Tanggal Dikirim</div>
                            <div class="order-val">{{ $order->shipped_at->format('d F Y, H:i') }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="order-section">
                <div class="order-title">Pembayaran</div>
                <div class="order-box">
                    <div class="order-row">
                        <div class="order-k">Metode</div>
                        <div class="order-val">{{ $order->payment_method == 'bank_transfer' ? 'TRANSFER / QRIS' : strtoupper($order->payment_method) }}</div>
                    </div>

                    @if($order->payment_method == 'bank_transfer' && !$order->payment_proof)
                        <div class="order-qris">
                            <img src="{{ asset('img/qr.jpeg') }}" alt="QRIS">
                        </div>
                        <div class="order-sub" style="margin-top:10px">Upload bukti pembayaran supaya admin bisa verifikasi.</div>
                    @endif

                    @if($order->payment_proof)
                        <div class="order-proof" style="margin-top:10px">
                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="d-block">
                                <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Pembayaran">
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
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
