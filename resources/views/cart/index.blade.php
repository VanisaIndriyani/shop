@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto pt-28 pb-24 px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-10 font-sans">      </h1>
       

        @if(session('success'))
            <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-4" role="alert">
                <p class="font-bold text-green-700">Success</p>
                <p class="text-green-600">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4" role="alert">
                <p class="font-bold text-red-700">Error</p>
                <p class="text-red-600">{{ session('error') }}</p>
            </div>
        @endif

        @if($cartItems->isEmpty())
            <div class="text-center py-24 bg-gray-50 rounded-xl">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Tas belanja Anda kosong</h3>
                <p class="mt-1 text-gray-500">Ayo mulai belanja dan temukan gaya favoritmu.</p>
                <div class="mt-8">
                    <a href="{{ route('shop.index') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-all shadow-lg hover:shadow-xl">
                        Mulai Belanja
                    </a>
                </div>
            </div>
        @else
            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    @foreach($cartItems as $item)
                        <div class="card border-0 shadow-sm rounded-4 mb-3">
                            <div class="card-body d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="rounded-3" style="width:96px;height:120px;object-fit:cover;">
                                    @else
                                        <div class="rounded-3 bg-light d-flex align-items-center justify-content-center text-muted" style="width:96px;height:120px;">
                                            <i class="bi bi-image fs-4"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="fs-6 fw-bold mb-1">
                                            <a href="{{ route('shop.show', $item->product->slug) }}" class="text-decoration-none text-dark">{{ $item->product->name }}</a>
                                        </h3>
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="text-muted small">
                                        Size: <span class="fw-semibold text-dark">{{ $item->size ?? 'All Size' }}</span>
                                        <span class="mx-2">•</span>
                                        Qty: <span class="fw-semibold text-dark">{{ $item->quantity }}</span>
                                    </div>
                                    <div class="mt-2 fw-bold text-primary" data-money-idr="{{ (float) ($item->product->price * $item->quantity) }}">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 96px;">
                        <div class="card-body">
                            <h2 class="fs-5 fw-bold mb-3">Ringkasan Pesanan</h2>
                            @php
                                $subtotal = $cartItems->sum(function($item) { return $item->product->price * $item->quantity; });
                            @endphp
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-semibold" data-money-idr="{{ (float) $subtotal }}">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">Total</span>
                                <span class="fw-bold" data-money-idr="{{ (float) $subtotal }}">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 rounded-pill mt-3">Lanjut Pembayaran</a>
                            <p class="text-muted small text-center mt-2">Pajak dan biaya pengiriman dihitung saat checkout.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
