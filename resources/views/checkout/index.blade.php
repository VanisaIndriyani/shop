@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

@php
    $subtotal = $cartItems->sum(function($item) { return $item->product->price * $item->quantity; });
@endphp

<style>
    .checkout-card { border: 0; border-radius: 18px; box-shadow: 0 12px 30px rgba(16, 24, 40, 0.08); }
    .payment-card { border: 1px solid #eef2ff; border-radius: 18px; padding: 16px; cursor: pointer; transition: all .2s ease; background: #fff; }
    .payment-card:hover { transform: translateY(-1px); box-shadow: 0 12px 30px rgba(16, 24, 40, 0.08); }
    .payment-card.active { border-color: rgba(13,110,253,.35); box-shadow: 0 16px 32px rgba(13, 110, 253, 0.12); background: rgba(13,110,253,.04); }
    .thumb { width: 64px; height: 64px; border-radius: 14px; overflow: hidden; background: #f8f9fa; border: 1px solid rgba(0,0,0,.06); flex: 0 0 auto; }
    .thumb img { width: 100%; height: 100%; object-fit: cover; }
</style>

<div class="container py-5" x-data="{ paymentMethod: '{{ old('payment_method', 'bank_transfer') }}' }">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="fw-bold fs-3">Checkout</div>
            <div class="text-muted">Selesaikan pembayaran untuk memproses pesanan Anda.</div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary rounded-pill">Kembali</a>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2 mb-4">
        <span class="badge rounded-pill text-bg-primary">2</span>
        <span class="fw-semibold">Checkout</span>
        <span class="text-muted">•</span>
        <span class="text-muted small">Langkah 2 dari 3</span>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <div class="col-12 col-lg-7">
                <div class="card checkout-card mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="fw-bold fs-5"><i class="bi bi-geo-alt me-2 text-primary"></i>Informasi Pengiriman</div>
                        </div>

                        <label for="address" class="form-label fw-semibold">Alamat Lengkap</label>
                        <textarea id="address" name="address" rows="4" class="form-control @error('address') is-invalid @enderror" placeholder="Jalan, No Rumah, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos" required>{{ old('address', Auth::user()->address) }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Pastikan alamat sudah benar supaya pengiriman lancar.</div>
                    </div>
                </div>

                <div class="card checkout-card">
                    <div class="card-body p-4">
                        <div class="fw-bold fs-5 mb-3"><i class="bi bi-credit-card me-2 text-primary"></i>Metode Pembayaran</div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="payment-card w-100" :class="paymentMethod === 'bank_transfer' ? 'active' : ''">
                                    <input class="d-none" type="radio" name="payment_method" value="bank_transfer" x-model="paymentMethod">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width:44px;height:44px;background:#0d6efd;">
                                                <i class="bi bi-qr-code-scan fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">Transfer / QRIS</div>
                                                <div class="text-muted small">Upload bukti pembayaran</div>
                                            </div>
                                        </div>
                                        <i class="bi bi-check-circle-fill text-primary" x-show="paymentMethod === 'bank_transfer'"></i>
                                    </div>
                                </label>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="payment-card w-100" :class="paymentMethod === 'cod' ? 'active' : ''">
                                    <input class="d-none" type="radio" name="payment_method" value="cod" x-model="paymentMethod">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width:44px;height:44px;background:#198754;">
                                                <i class="bi bi-cash-coin fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">COD</div>
                                                <div class="text-muted small">Bayar saat barang sampai</div>
                                            </div>
                                        </div>
                                        <i class="bi bi-check-circle-fill text-success" x-show="paymentMethod === 'cod'"></i>
                                    </div>
                                </label>
                            </div>
                        </div>
                        @error('payment_method') <div class="text-danger small mt-2">{{ $message }}</div> @enderror

                        <div class="mt-4" x-show="paymentMethod === 'bank_transfer'">
                            <div class="alert alert-primary border-0 rounded-4 mb-3" style="background: rgba(13,110,253,.08);">
                                <div class="fw-bold mb-1">Instruksi Pembayaran</div>
                                <div class="text-muted small">Scan QRIS atau transfer ke rekening di bawah, lalu upload bukti pembayaran (opsional).</div>
                            </div>

                            <div class="row g-3 align-items-center">
                                <div class="col-12 col-md-7">
                                    <div class="card border-0 rounded-4" style="background:#f8fafc;">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between small text-muted">
                                                <span>Bank</span><span class="fw-bold text-dark">BCA</span>
                                            </div>
                                            <hr class="my-2">
                                            <div class="d-flex justify-content-between small text-muted">
                                                <span>No Rekening</span><span class="fw-bold text-dark">7655 2980 45</span>
                                            </div>
                                            <hr class="my-2">
                                            <div class="d-flex justify-content-between small text-muted">
                                                <span>Nama</span><span class="fw-bold text-dark">YUDHA FEBRIANSYAH</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5 text-center">
                                    <div class="bg-white rounded-4 p-3 shadow-sm border">
                                        <img src="{{ asset('img/qr.jpeg') }}" alt="QRIS Payment" style="width: 180px; height: 180px; object-fit: contain;">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label for="payment_proof" class="form-label fw-semibold">Upload Bukti Pembayaran</label>
                                <input type="file" name="payment_proof" id="payment_proof" class="form-control @error('payment_proof') is-invalid @enderror" accept="image/*">
                                @error('payment_proof') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="form-text">Format: JPG/PNG/JPEG/GIF. Maks 2MB.</div>
                            </div>
                        </div>

                        <div class="mt-4" x-show="paymentMethod === 'cod'">
                            <div class="alert alert-warning border-0 rounded-4 mb-0" style="background: rgba(255,193,7,.12);">
                                <div class="fw-bold mb-1">Info COD</div>
                                <div class="text-muted small">Siapkan uang tunai sesuai total pembayaran. Pastikan nomor HP aktif untuk kurir.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="card checkout-card position-sticky" style="top: 96px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="fw-bold fs-5"><i class="bi bi-bag-check me-2 text-primary"></i>Ringkasan Pesanan</div>
                        </div>

                        <div class="d-flex flex-column gap-3 mb-3" style="max-height: 340px; overflow: auto;">
                            @foreach($cartItems as $item)
                                <div class="d-flex align-items-center gap-3">
                                    <div class="thumb">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-truncate">{{ $item->product->name }}</div>
                                        <div class="text-muted small">Ukuran: {{ $item->size ?? 'M' }} • Qty: {{ $item->quantity }}</div>
                                    </div>
                                    <div class="fw-bold text-primary text-nowrap" data-money-idr="{{ (float) ($item->product->price * $item->quantity) }}">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</div>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-semibold" data-money-idr="{{ (float) $subtotal }}">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Pengiriman</span>
                            <span class="fw-semibold text-success">GRATIS</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold fs-5 text-primary" data-money-idr="{{ (float) $subtotal }}">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill mt-3 py-3 fw-bold">Konfirmasi Pesanan</button>
                        <div class="text-muted small text-center mt-2">Dengan mengklik tombol di atas, kamu setuju dengan syarat & ketentuan.</div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
@endsection
