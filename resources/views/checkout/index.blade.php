@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

@php
    $subtotal = $cartItems->sum(function($item) { return $item->product->price * $item->quantity; });
@endphp

<style>
    footer{display:none !important}
    .refrens-chat-fab{display:none !important}
    body{background:#f6f7fb}
    .checkout-wrap{padding:14px 12px 22px}
    .checkout-card{max-width:420px;margin:0 auto;background:#fff;border:1px solid rgba(0,0,0,.06);border-radius:16px;overflow:hidden}
    .checkout-body{padding:14px 14px 18px}
    .checkout-h1{font-size:18px;font-weight:900;margin:0 0 6px;color:#111827}
    .checkout-sub{font-size:12px;color:#6b7280;font-weight:600;margin:0 0 12px}
    .checkout-sub a{color:#dc2626;font-weight:800;text-decoration:none}
    .checkout-sub a:hover{text-decoration:underline}
    .checkout-label{font-size:12px;font-weight:800;color:#111827;margin-bottom:6px}
    .checkout-input{border-radius:12px;border:1px solid rgba(0,0,0,.10);padding:12px 12px;font-size:12px;font-weight:600}
    .checkout-input:focus{border-color:rgba(37,99,235,.6);box-shadow:0 0 0 4px rgba(37,99,235,.12)}
    .checkout-row{display:flex;flex-direction:column;gap:10px}
    .checkout-box{border:1px solid rgba(0,0,0,.06);border-radius:12px;background:rgba(0,0,0,.02);padding:12px;font-size:12px;font-weight:700;color:#6b7280}
    .checkout-section{margin-top:18px}
    .checkout-section__title{font-size:15px;font-weight:900;color:#111827;margin:0 0 10px}
    .checkout-payrow{border:1px solid rgba(0,0,0,.10);border-radius:12px;background:#fff;padding:12px;display:flex;align-items:center;justify-content:space-between;gap:12px}
    .checkout-payleft{display:flex;align-items:center;gap:10px}
    .checkout-payleft .tag{font-size:12px;font-weight:900;color:#111827}
    .checkout-payright{display:flex;align-items:center;gap:8px;font-size:12px;font-weight:900;color:#111827}
    .checkout-payright i{color:rgba(17,24,39,.55)}
    .checkout-qris{margin-top:10px;border:1px solid rgba(0,0,0,.10);border-radius:14px;background:#fff;padding:12px;display:flex;align-items:center;justify-content:center}
    .checkout-qris img{width:220px;height:220px;object-fit:contain}

    .checkout-sheet{position:fixed;inset:0;z-index:1300;display:none}
    .checkout-sheet.is-open{display:block}
    .checkout-sheet__backdrop{position:fixed;inset:0;background:rgba(0,0,0,.45);backdrop-filter:blur(2px)}
    .checkout-sheet__panel{position:fixed;left:0;right:0;bottom:0;max-height:70svh;background:#fff;border-top-left-radius:22px;border-top-right-radius:22px;overflow:auto;box-shadow:0 -18px 48px rgba(0,0,0,.18)}
    .checkout-sheet__handle{width:56px;height:6px;border-radius:999px;background:#e5efff;margin:10px auto}
    .checkout-sheet__title{font-size:14px;font-weight:900;color:#111827}
    .checkout-sheet__item{border:1px solid rgba(0,0,0,.10);border-radius:14px;padding:12px;display:flex;align-items:center;justify-content:space-between;gap:12px;background:#fff}
    .checkout-sheet__item + .checkout-sheet__item{margin-top:10px}
    .checkout-sheet__item .l{font-size:12px;font-weight:900;color:#111827}
    .checkout-sheet__item .s{font-size:11px;font-weight:700;color:#6b7280;margin-top:4px}

    .checkout-summary{margin-top:18px}
    .checkout-item{display:flex;align-items:center;gap:12px}
    .checkout-item + .checkout-item{margin-top:12px}
    .checkout-item__thumb{width:42px;height:42px;border-radius:12px;overflow:hidden;background:#f3f4f6;border:1px solid rgba(0,0,0,.06);flex:0 0 auto}
    .checkout-item__thumb img{width:100%;height:100%;object-fit:cover}
    .checkout-item__meta{flex:1 1 auto;min-width:0}
    .checkout-item__name{font-size:12px;font-weight:900;color:#111827;line-height:1.2}
    .checkout-item__qty{font-size:11px;font-weight:700;color:#6b7280;margin-top:4px}
    .checkout-item__price{font-size:12px;font-weight:900;color:#dc2626;white-space:nowrap}
    .checkout-rowbtn{border:1px solid rgba(0,0,0,.10);border-radius:12px;background:#fff;padding:12px;display:flex;align-items:center;justify-content:space-between;gap:10px;font-size:12px;font-weight:800;color:#6b7280}
    .checkout-rowbtn .l{display:flex;align-items:center;gap:10px}
    .checkout-rowbtn i{color:rgba(17,24,39,.55)}
    .checkout-msgsheet{position:fixed;inset:0;z-index:1400;display:none}
    .checkout-msgsheet.is-open{display:block}
    .checkout-msgsheet__backdrop{position:fixed;inset:0;background:rgba(0,0,0,.45);backdrop-filter:blur(2px)}
    .checkout-msgsheet__panel{position:fixed;left:0;right:0;bottom:0;max-height:70svh;background:#fff;border-top-left-radius:22px;border-top-right-radius:22px;overflow:auto;box-shadow:0 -18px 48px rgba(0,0,0,.18)}
    .checkout-msgsheet__handle{width:56px;height:6px;border-radius:999px;background:#e5efff;margin:10px auto}
    .checkout-msgsheet__title{font-size:14px;font-weight:900;color:#111827}
    .checkout-msgopt{border:1px solid rgba(0,0,0,.10);border-radius:14px;padding:12px;display:flex;align-items:flex-start;gap:10px;background:#fff}
    .checkout-msgopt + .checkout-msgopt{margin-top:10px}
    .checkout-msgopt input{margin-top:2px}
    .checkout-msgopt .t{font-size:12px;font-weight:900;color:#111827}
    .checkout-msgopt .d{font-size:11px;font-weight:700;color:#6b7280;margin-top:4px}
    .checkout-msginput{margin-top:10px}
    .checkout-msginput input{width:100%;border:1px solid rgba(0,0,0,.10);border-radius:12px;padding:12px 12px;font-size:12px;font-weight:700}
    .checkout-msgbtn{width:100%;height:44px;border-radius:12px;border:0;background:#2563eb;color:#fff;font-weight:900;margin-top:14px}
    .checkout-lines{margin-top:14px}
    .checkout-line{display:flex;align-items:center;justify-content:space-between;font-size:12px;font-weight:800;color:#111827}
    .checkout-line + .checkout-line{margin-top:10px}
    .checkout-line .muted{color:#6b7280}
    .checkout-total{display:flex;align-items:center;justify-content:space-between;margin-top:12px;padding-top:12px;border-top:1px solid rgba(0,0,0,.10);font-size:12px;font-weight:900;color:#111827}
    .checkout-total .price{font-size:14px}
    .checkout-safe{display:flex;align-items:center;justify-content:center;gap:8px;margin-top:10px;font-size:11px;font-weight:800;color:#6b7280}
    .checkout-safe i{color:rgba(17,24,39,.55)}
    .checkout-note{margin-top:12px;border-radius:12px;background:rgba(37,99,235,.10);padding:10px 12px;font-size:11px;font-weight:800;color:#374151}
    .checkout-orderbtn{width:100%;height:46px;border-radius:12px;border:0;background:#2563eb;color:#fff;font-weight:900;margin-top:14px}
    .checkout-terms{margin-top:10px;text-align:center;font-size:11px;font-weight:700;color:#6b7280}
    .checkout-terms a{color:#dc2626;font-weight:900;text-decoration:none}
</style>

<div class="checkout-wrap" x-data="{ paymentMethod: 'bank_transfer', msgOpen: false, msgMode: 'front', msgText: '' }">
    <div class="checkout-card">
        <div class="checkout-body">
            <div class="checkout-h1">Detail Alamat</div>

            <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="checkout-row">
                    <div>
                        <div class="checkout-label">Alamat Lengkap</div>
                        <textarea class="form-control checkout-input @error('address') is-invalid @enderror" name="address" rows="6" placeholder="Nama penerima, No HP, Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos" required>{{ old('address', Auth::user()->address ?? '') }}</textarea>
                        @error('address') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="checkout-section">
                    <div class="checkout-section__title">Metode Pengiriman</div>
                    <div class="checkout-box">Lengkapi rincian alamat untuk melihat metode pengiriman yang tersedia.</div>
                </div>

                <div class="checkout-section">
                    <div class="checkout-section__title">Metode Pembayaran</div>
                    <input type="hidden" name="payment_method" x-model="paymentMethod">
                    <div class="w-100 checkout-payrow">
                        <div class="checkout-payleft">
                            <div class="tag">Transfer / QRIS</div>
                        </div>
                        <div class="checkout-payright">
                            <span>Transfer / QRIS</span>
                        </div>
                    </div>
                    @error('payment_method') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                    <div class="mt-3" x-show="paymentMethod === 'bank_transfer'">
                        <div class="checkout-label">QRIS</div>
                        <div class="checkout-qris">
                            <img src="{{ asset('img/qr.jpeg') }}" alt="QRIS">
                        </div>
                        <div class="checkout-label">Upload Bukti Pembayaran</div>
                        <input type="file" name="payment_proof" class="form-control checkout-input @error('payment_proof') is-invalid @enderror" accept="image/*">
                        @error('payment_proof') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="checkout-summary">
                    <div class="checkout-section__title">Ringkasan Pesanan</div>
                    <div>
                        @foreach($cartItems as $item)
                            <div class="checkout-item">
                                <div class="checkout-item__thumb">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"><i class="bi bi-image"></i></div>
                                    @endif
                                </div>
                                <div class="checkout-item__meta">
                                    <div class="checkout-item__name">{{ $item->product->name }}</div>
                                    <div class="checkout-item__qty">Jumlah: {{ $item->quantity }}</div>
                                </div>
                                <div class="checkout-item__price" data-money-idr="{{ (float) ($item->product->price * $item->quantity) }}">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3 d-flex flex-column gap-2">
                        <button type="button" class="checkout-rowbtn" @click="msgOpen = true">
                            <div class="l">Tinggalkan pesan pengiriman (opsional)</div>
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>

                    @php $qtyAll = (int) $cartItems->sum('quantity'); @endphp
                    <div class="checkout-lines">
                        <div class="checkout-line">
                            <div class="muted">Subtotal • {{ $qtyAll }} barang</div>
                            <div class="checkout-item__price" data-money-idr="{{ (float) $subtotal }}">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                        </div>
                        <div class="checkout-line">
                            <div class="muted">Pengiriman</div>
                            <div class="muted">-</div>
                        </div>
                    </div>
                    <div class="checkout-total">
                        <div>Total Pembayaran</div>
                        <div class="price" data-money-idr="{{ (float) $subtotal }}">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                    </div>
                    <div class="checkout-safe">
                        <i class="bi bi-lock"></i>
                        <div>Transaksi Aman | Pembayaran telah terenkripsi.</div>
                    </div>
                    <div class="checkout-note">
                        Bea atau pajak impor mungkin dikenakan tergantung negara tujuan pengiriman.
                    </div>

                    <button type="submit" class="checkout-orderbtn">Order Sekarang</button>
                    <div class="checkout-terms">
                        Dengan melakukan pesanan, telah setuju dengan <a href="javascript:void(0)">Syarat &amp; Ketentuan</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

<div class="checkout-msgsheet" :class="msgOpen ? 'is-open' : ''" x-cloak>
    <div class="checkout-msgsheet__backdrop" @click="msgOpen = false"></div>
    <div class="checkout-msgsheet__panel">
        <div class="px-3">
            <div class="checkout-msgsheet__handle"></div>
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="checkout-msgsheet__title">Pesan Permintaan Pengiriman</div>
                <button type="button" class="btn btn-sm btn-light rounded-circle" @click="msgOpen = false"><i class="bi bi-x-lg"></i></button>
            </div>

            <label class="checkout-msgopt w-100">
                <input type="radio" name="shipping_note_mode" value="front" x-model="msgMode">
                <div>
                    <div class="t">Tinggalkan paket di depan rumah</div>
                </div>
            </label>
            <label class="checkout-msgopt w-100">
                <input type="radio" name="shipping_note_mode" value="lobby" x-model="msgMode">
                <div>
                    <div class="t">Tinggalkan paket di lobby/satpam</div>
                </div>
            </label>
            <label class="checkout-msgopt w-100">
                <input type="radio" name="shipping_note_mode" value="custom" x-model="msgMode">
                <div>
                    <div class="t">Sesuaikan pesan</div>
                </div>
            </label>

            <div class="checkout-msginput" x-show="msgMode === 'custom'">
                <input type="text" name="shipping_note" placeholder="Tinggalkan pesan pengiriman" x-model="msgText">
            </div>

            <button type="button" class="checkout-msgbtn" @click="msgOpen = false">Konfirmasi</button>
            <div class="pb-3"></div>
        </div>
    </div>
</div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
@endsection
