@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
@php
    $hasFilters = request('categories') || request('min_price') || request('max_price') || request('availability') || request('sizes') || request('types');
    $hasSort = request('sort') && request('sort') !== 'latest';
@endphp

<style>
    .refrens-sheet{display:none;position:fixed;inset:0;z-index:1000}
    .refrens-sheet:target{display:block}
    .refrens-sheet__backdrop{position:fixed;inset:0;background:rgba(0,0,0,.45);backdrop-filter:blur(2px)}
    .refrens-sheet__panel{position:fixed;left:0;right:0;bottom:0;max-height:86vh;background:#fff;border-top-left-radius:22px;border-top-right-radius:22px;overflow:auto}
    .refrens-sheet__handle{width:56px;height:6px;border-radius:999px;background:#e5efff;margin:10px auto}
    .refrens-accordion{border-bottom:1px solid rgba(0,0,0,.06);padding:12px 0}
    .refrens-accordion summary{list-style:none;cursor:pointer;display:flex;align-items:center;justify-content:space-between;font-weight:800}
    .refrens-accordion summary::-webkit-details-marker{display:none}
    .refrens-pill{display:inline-flex;align-items:center;justify-content:center;padding:.5rem .9rem;border:1px solid rgba(0,0,0,.12);border-radius:12px;font-weight:800;font-size:.85rem;background:#fff}
    .refrens-pill input{display:none}
    .refrens-pill--active{background:#eef2ff;border-color:#4f46e5;color:#1e3a8a}
    .refrens-applybar{position:sticky;bottom:0;background:#fff;padding:14px;border-top:1px solid rgba(0,0,0,.06)}
</style>

<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        
        <!-- Filter & Sort Buttons -->
        <div class="flex gap-4 mb-6 sticky top-16 z-30 bg-white py-2 -mx-4 px-4 shadow-sm sm:static sm:bg-transparent sm:shadow-none sm:py-0 sm:mx-0 sm:px-0">
            <a href="#filter" class="flex-1 flex items-center justify-center px-4 py-2.5 border {{ $hasFilters ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-blue-600 border-blue-600 hover:bg-blue-50' }} rounded-full font-medium text-sm transition-all shadow-sm active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                Filter {{ $hasFilters ? '(Aktif)' : '' }}
            </a>
            <a href="#sort" class="flex-1 flex items-center justify-center px-4 py-2.5 border {{ $hasSort ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-blue-600 border-blue-600 hover:bg-blue-50' }} rounded-full font-medium text-sm transition-all shadow-sm active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                Urutan
            </a>
        </div>

        <div id="filter" class="refrens-sheet" role="dialog" aria-modal="true">
            <a class="refrens-sheet__backdrop" href="{{ request()->fullUrl() }}" aria-label="Close"></a>
            <div class="refrens-sheet__panel">
                <div class="px-4 pt-2 pb-3">
                    <div class="refrens-sheet__handle"></div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-bold fs-5">Filter</div>
                        <a href="{{ request()->fullUrl() }}" class="btn btn-sm btn-light rounded-circle" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                </div>

                <form action="{{ route('shop.index') }}" method="GET">
                    <div class="px-4 pb-3">
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif

                        <details class="refrens-accordion" open>
                            <summary>
                                <span>Kategori</span>
                                <i class="bi bi-chevron-down"></i>
                            </summary>
                            <div class="pt-3">
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($categories as $category)
                                        @php $checked = in_array($category, request('categories', [])); @endphp
                                        <label class="refrens-pill {{ $checked ? 'refrens-pill--active' : '' }}">
                                            <input type="checkbox" name="categories[]" value="{{ $category }}" {{ $checked ? 'checked' : '' }}>
                                            {{ $category }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </details>

                        <details class="refrens-accordion">
                            <summary>
                                <span>Tipe Produk</span>
                                <i class="bi bi-chevron-down"></i>
                            </summary>
                            <div class="pt-3">
                                @php $types = request('types', []); @endphp
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach(['Top','Bottom','Accessories'] as $t)
                                        @php $checked = in_array($t, $types); @endphp
                                        <label class="refrens-pill {{ $checked ? 'refrens-pill--active' : '' }}">
                                            <input type="checkbox" name="types[]" value="{{ $t }}" {{ $checked ? 'checked' : '' }}>
                                            {{ $t }}
                                        </label>
                                    @endforeach
                                </div>
                                <div class="text-muted small mt-2">Tipe produk belum dipakai untuk filter di data saat ini.</div>
                            </div>
                        </details>

                        <details class="refrens-accordion">
                            <summary>
                                <span>Ketersediaan</span>
                                <i class="bi bi-chevron-down"></i>
                            </summary>
                            <div class="pt-3">
                                @php $availability = request('availability', 'all'); @endphp
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach([['all','Semua'],['in','Tersedia'],['out','Habis']] as [$val,$label])
                                        @php $checked = $availability === $val; @endphp
                                        <label class="refrens-pill {{ $checked ? 'refrens-pill--active' : '' }}">
                                            <input type="radio" name="availability" value="{{ $val }}" {{ $checked ? 'checked' : '' }}>
                                            {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </details>

                        <details class="refrens-accordion">
                            <summary>
                                <span>Harga</span>
                                <i class="bi bi-chevron-down"></i>
                            </summary>
                            <div class="pt-3">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="form-control form-control-lg rounded-4">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="form-control form-control-lg rounded-4">
                                    </div>
                                </div>
                            </div>
                        </details>

                        <details class="refrens-accordion" open>
                            <summary>
                                <span>Size</span>
                                <i class="bi bi-chevron-down"></i>
                            </summary>
                            <div class="pt-3">
                                @php $sizes = request('sizes', []); @endphp
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach(['S','M','L','XL'] as $s)
                                        @php $checked = in_array($s, $sizes); @endphp
                                        <label class="refrens-pill {{ $checked ? 'refrens-pill--active' : '' }}">
                                            <input type="checkbox" name="sizes[]" value="{{ $s }}" {{ $checked ? 'checked' : '' }}>
                                            {{ $s }}
                                        </label>
                                    @endforeach
                                </div>
                                <div class="text-muted small mt-2">Size belum dipakai untuk filter di data saat ini.</div>
                            </div>
                        </details>
                    </div>

                    <div class="refrens-applybar">
                        <button type="submit" class="btn btn-danger w-100 btn-lg rounded-pill fw-bold" style="background:#ef4444;border-color:#ef4444;">
                            Aplikasikan
                        </button>
                        <a href="{{ route('shop.index', request()->only('q','sort')) }}" class="btn btn-outline-secondary w-100 mt-2 btn-lg rounded-pill fw-bold">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div id="sort" class="refrens-sheet" role="dialog" aria-modal="true">
            <a class="refrens-sheet__backdrop" href="{{ request()->fullUrl() }}" aria-label="Close"></a>
            <div class="refrens-sheet__panel">
                <div class="px-4 pt-2 pb-3">
                    <div class="refrens-sheet__handle"></div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-bold fs-5">Urutan</div>
                        <a href="{{ request()->fullUrl() }}" class="btn btn-sm btn-light rounded-circle" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                </div>

                <form action="{{ route('shop.index') }}" method="GET">
                    <div class="px-4 pb-3">
                        @foreach(request()->except('sort') as $k => $v)
                            @if(is_array($v))
                                @foreach($v as $vv)
                                    <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endif
                        @endforeach

                        @php $sort = request('sort', 'latest'); @endphp
                        <div class="list-group list-group-flush rounded-4 overflow-hidden border">
                            @foreach([['latest','Terbaru'],['popular','Terpopuler'],['price_asc','Harga Terendah'],['price_desc','Harga Tertinggi']] as [$val,$label])
                                <label class="list-group-item d-flex align-items-center justify-content-between py-3">
                                    <div class="fw-semibold">{{ $label }}</div>
                                    <input class="form-check-input m-0" type="radio" name="sort" value="{{ $val }}" {{ $sort === $val ? 'checked' : '' }}>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="refrens-applybar">
                        <button type="submit" class="btn btn-primary w-100 btn-lg rounded-pill fw-bold">Terapkan</button>
                        <a href="{{ route('shop.index', request()->except('sort')) }}" class="btn btn-outline-secondary w-100 mt-2 btn-lg rounded-pill fw-bold">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
            @forelse($products as $product)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4">
                        <div class="position-relative">
                            @if($product->stock <= 0)
                                <span class="badge text-bg-dark position-absolute top-0 end-0 m-2 rounded-pill">Sold Out</span>
                            @elseif($product->created_at->diffInDays(now()) < 7)
                                <span class="badge text-bg-primary position-absolute top-0 start-0 m-2 rounded-pill">New</span>
                            @endif
                            <a href="{{ route('shop.show', $product->slug) }}" class="d-block">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="card-img-top" style="aspect-ratio: 4/5; object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light text-muted" style="aspect-ratio: 4/5;">
                                        <i class="bi bi-image fs-3"></i>
                                    </div>
                                @endif
                            </a>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title fs-6 fw-bold text-truncate">
                                <a href="{{ route('shop.show', $product->slug) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                            </h3>
                            <div class="mt-1">
                                <div class="fw-black text-primary" data-money-idr="{{ (float) $product->price }}">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <div class="text-muted mb-2"><i class="bi bi-box-seam fs-1"></i></div>
                        <div class="fw-bold fs-5">Produk tidak ditemukan</div>
                        <div class="text-muted">Silakan kembali lagi untuk produk baru.</div>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
