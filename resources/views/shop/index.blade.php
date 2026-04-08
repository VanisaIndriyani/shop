@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
@php
    $hasFilters = request('category') || request('categories') || request('price_range') || request('min_price') || request('max_price') || request('availability') || request('size') || request('sizes') || request('types') || request('type');
    $sortParam = request('sort');
    $hasSort = request()->has('sort') && $sortParam && $sortParam !== 'latest';
@endphp

<style>
    .refrens-sheet{display:none;position:fixed;inset:0;z-index:1000}
    .refrens-sheet:target{display:block}
    .refrens-sheet__backdrop{position:fixed;inset:0;background:rgba(0,0,0,.45);backdrop-filter:blur(2px)}
    .refrens-sheet__panel{position:fixed;left:0;right:0;bottom:0;max-height:86vh;background:#fff;border-top-left-radius:22px;border-top-right-radius:22px;overflow:auto}
    .refrens-sheet__handle{width:56px;height:6px;border-radius:999px;background:#e5efff;margin:10px auto}
    .refrens-topbtn{display:flex;align-items:center;justify-content:center;gap:8px;padding:10px 16px;border:1px solid #2563eb;border-radius:12px;background:#fff;color:#2563eb;font-weight:700;font-size:14px;transition:transform .15s ease,background-color .15s ease,color .15s ease;text-decoration:none !important}
    .refrens-topbtn *{text-decoration:none !important}
    .refrens-topbtn:hover{background:rgba(37,99,235,.08);text-decoration:none !important}
    .refrens-topbtn:active{transform:scale(.98)}
    .refrens-topbtn--active{background:#2563eb;color:#fff;text-decoration:none !important}
    .refrens-accordion{border-bottom:1px solid rgba(0,0,0,.06);padding:12px 0}
    .refrens-accordion summary{list-style:none;cursor:pointer;display:flex;align-items:center;justify-content:space-between;font-weight:800}
    .refrens-accordion summary::-webkit-details-marker{display:none}
    .refrens-pill{position:relative;display:inline-flex;align-items:center;justify-content:center;padding:.42rem .8rem;border:1px solid rgba(37,99,235,.35);border-radius:10px;font-weight:800;font-size:.82rem;background:#fff;cursor:pointer;user-select:none;-webkit-tap-highlight-color:transparent}
    .refrens-pill input{display:none}
    .refrens-pill__label{display:inline-flex;align-items:center;justify-content:center}
    .refrens-pill--active{background:rgba(37,99,235,.10);border-color:rgba(37,99,235,.75);color:#1d4ed8}
    .refrens-applybar{position:sticky;bottom:0;background:#fff;padding:14px;border-top:1px solid rgba(0,0,0,.06)}
    .refrens-radio{display:flex;align-items:center;gap:12px;padding:10px 0}
    .refrens-radio .form-check-input{margin:0;accent-color:#2563eb}
    .refrens-radio .refrens-radio__label{font-weight:700;color:#111827}
    .refrens-radio .form-check-input:checked + .refrens-radio__label{color:#2563eb}
    .refrens-sortitem{display:flex;align-items:center;justify-content:space-between;padding:14px 14px;border-bottom:1px solid rgba(0,0,0,.06)}
    .refrens-sortitem:last-child{border-bottom:0}
    .refrens-sortitem__label{font-weight:700}
    .refrens-sortitem__check{opacity:0;color:#2563eb}
    .refrens-sortitem input:checked ~ .refrens-sortitem__check{opacity:1}
    .refrens-sizegrid{display:flex;flex-wrap:wrap;gap:10px}

    :root{
        --nav-height: 64px;
    }

    @media (max-width: 640px){
        :root{
            --nav-height: 72px;
        }
    }

    .shop-sticky-filter{
        position: sticky;
        top: var(--nav-height);
        z-index: 30;
        background: white;
        padding-top: 4px;
        padding-bottom: 8px;
        margin-left: -1rem;
        margin-right: -1rem;
        padding-left: 1rem;
        padding-right: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,.08);
        border-bottom: 1px solid #f3f4f6;
    }

    .shop-sticky-filter a, .shop-sticky-filter a *, .shop-sticky-filter button, .shop-sticky-filter button * {
        text-decoration: none !important;
    }

    @media (min-width: 640px){
        .shop-sticky-filter{
            position: static;
            margin: 0;
            padding: 0;
            box-shadow: none;
            border-bottom: 0;
            background: transparent;
        }
    }
</style>

<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        
        <div class="grid grid-cols-2 gap-3 mb-8 shop-sticky-filter">
            <a id="shopFilterBtn" href="#filter" class="refrens-topbtn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                <span>Filter</span>
            </a>
            <a id="shopSortBtn" href="#sort" class="refrens-topbtn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                <span>Urutan</span>
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
                                @php
                                    $categoryList = $categories instanceof \Illuminate\Support\Collection ? $categories->values() : collect($categories)->values();
                                    $hasCategoryParam = request()->has('category');
                                    $categorySelected = (string) request('category', '');
                                @endphp
                                <div class="border-top pt-2">
                                    <label class="refrens-radio">
                                        <input class="form-check-input" type="radio" name="category" value="" {{ $hasCategoryParam && $categorySelected === '' ? 'checked' : '' }}>
                                        <span class="refrens-radio__label">Semua</span>
                                    </label>
                                    @foreach($categoryList as $category)
                                        <label class="refrens-radio">
                                            <input class="form-check-input" type="radio" name="category" value="{{ $category }}" {{ $hasCategoryParam && $categorySelected === $category ? 'checked' : '' }}>
                                            <span class="refrens-radio__label">{{ $category }}</span>
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
                                @php
                                    $currentType = request('type') === 'featured' ? 'featured' : '';
                                @endphp
                                <div class="border-top pt-2">
                                    <label class="refrens-radio">
                                        <input class="form-check-input" type="radio" name="type" value="" {{ $currentType === '' ? 'checked' : '' }}>
                                        <span class="refrens-radio__label">Semua Produk</span>
                                    </label>
                                    <label class="refrens-radio">
                                        <input class="form-check-input" type="radio" name="type" value="featured" {{ $currentType === 'featured' ? 'checked' : '' }}>
                                        <span class="refrens-radio__label">Produk Unggulan</span>
                                    </label>
                                </div>
                            </div>
                        </details>

                        <details class="refrens-accordion">
                            <summary>
                                <span>Ketersediaan</span>
                                <i class="bi bi-chevron-down"></i>
                            </summary>
                            <div class="pt-3">
                                @php $availability = request('availability', 'all'); @endphp
                                <div class="border-top pt-2">
                                    <label class="refrens-radio">
                                        <input class="form-check-input" type="radio" name="availability" value="all" {{ $availability === 'all' ? 'checked' : '' }}>
                                        <span class="refrens-radio__label">Semua</span>
                                    </label>
                                    <label class="refrens-radio">
                                        <input class="form-check-input" type="radio" name="availability" value="in" {{ $availability === 'in' ? 'checked' : '' }}>
                                        <span class="refrens-radio__label">Ada Stok</span>
                                    </label>
                                </div>
                            </div>
                        </details>

                        <details class="refrens-accordion">
                            <summary>
                                <span>Harga</span>
                                <i class="bi bi-chevron-down"></i>
                            </summary>
                            <div class="pt-3">
                                @php
                                    $hasPriceRange = request()->has('price_range');
                                    $priceRange = (string) request('price_range', '');
                                @endphp
                                <div class="border-top pt-2">
                                    <label class="refrens-radio">
                                        <input class="form-check-input" type="radio" name="price_range" value="" {{ $hasPriceRange && $priceRange === '' ? 'checked' : '' }}>
                                        <span class="refrens-radio__label">Semua Harga</span>
                                    </label>
                                    <label class="refrens-radio">
                                        <input class="form-check-input" type="radio" name="price_range" value="under_75000" {{ $hasPriceRange && $priceRange === 'under_75000' ? 'checked' : '' }}>
                                        <span class="refrens-radio__label">Di bawah Rp 75,000</span>
                                    </label>
                                    <label class="refrens-radio">
                                        <input class="form-check-input" type="radio" name="price_range" value="75000_150000" {{ $hasPriceRange && $priceRange === '75000_150000' ? 'checked' : '' }}>
                                        <span class="refrens-radio__label">Rp 75,000 - Rp 150,000</span>
                                    </label>
                                    <label class="refrens-radio">
                                        <input class="form-check-input" type="radio" name="price_range" value="150000_220000" {{ $hasPriceRange && $priceRange === '150000_220000' ? 'checked' : '' }}>
                                        <span class="refrens-radio__label">Rp 150,000 - Rp 220,000</span>
                                    </label>
                                    <label class="refrens-radio">
                                        <input class="form-check-input" type="radio" name="price_range" value="220000_plus" {{ $hasPriceRange && $priceRange === '220000_plus' ? 'checked' : '' }}>
                                        <span class="refrens-radio__label">Rp 220,000+</span>
                                    </label>
                                </div>
                            </div>
                        </details>

                        <details class="refrens-accordion" open>
                            <summary>
                                <span>Size</span>
                                <i class="bi bi-chevron-down"></i>
                            </summary>
                            <div class="pt-3">
                                @php
                                    $hasSizeParam = request()->has('size');
                                    $size = (string) request('size', '');
                                    $letterSizes = ['S','M','L','XL'];
                                    $numberSizes = ['39','40','41','42','43'];
                                @endphp
                                <div class="refrens-sizegrid mb-2">
                                    @php $checkedAll = $hasSizeParam && $size === ''; @endphp
                                    <label class="refrens-pill {{ $checkedAll ? 'refrens-pill--active' : '' }}">
                                        <input type="radio" name="size" value="" {{ $checkedAll ? 'checked' : '' }}>
                                        <span class="refrens-pill__label">Semua</span>
                                    </label>
                                    @foreach($letterSizes as $s)
                                        @php $checked = $hasSizeParam && $size === (string) $s; @endphp
                                        <label class="refrens-pill {{ $checked ? 'refrens-pill--active' : '' }}">
                                            <input type="radio" name="size" value="{{ $s }}" {{ $checked ? 'checked' : '' }}>
                                            <span class="refrens-pill__label">{{ $s }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="refrens-sizegrid">
                                    @foreach($numberSizes as $s)
                                        @php $checked = $hasSizeParam && $size === (string) $s; @endphp
                                        <label class="refrens-pill {{ $checked ? 'refrens-pill--active' : '' }}">
                                            <input type="radio" name="size" value="{{ $s }}" {{ $checked ? 'checked' : '' }}>
                                            <span class="refrens-pill__label">{{ $s }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </details>
                    </div>

                    <div class="refrens-applybar">
                        <button type="submit" class="btn btn-primary w-100 btn-lg rounded-pill fw-bold">
                            Aplikasikan
                        </button>
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
                        <div class="fw-bold fs-5">Urutkan produk berdasarkan</div>
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
                        <div class="bg-white rounded-4 overflow-hidden border">
                            @foreach([
                                ['featured','Unggulan'],
                                ['latest','Terbaru'],
                                ['oldest','Terlama'],
                                ['popular','Terpopuler'],
                                ['rating_desc','Rating Tertinggi'],
                                ['price_asc','Harga Terendah'],
                                ['price_desc','Harga Tertinggi'],
                                ['name_asc','Nama Produk (A-Z)'],
                                ['name_desc','Nama Produk (Z-A)'],
                            ] as [$val, $label])
                                <label class="refrens-sortitem">
                                    <div class="refrens-sortitem__label">{{ $label }}</div>
                                    <input class="visually-hidden" type="radio" name="sort" value="{{ $val }}" {{ $sort === $val ? 'checked' : '' }} onchange="this.form.submit()">
                                    <i class="bi bi-check2 refrens-sortitem__check"></i>
                                </label>
                            @endforeach
                        </div>
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
@push('scripts')
<script>
    (function () {
        const pills = document.querySelectorAll('.refrens-pill');
        function syncPills() {
            pills.forEach((pill) => {
                const input = pill.querySelector('input[type="radio"], input[type="checkbox"]');
                if (!input) return;
                pill.classList.toggle('refrens-pill--active', !!input.checked);
            });
        }
        pills.forEach((pill) => {
            const input = pill.querySelector('input[type="radio"], input[type="checkbox"]');
            if (!input) return;
            input.addEventListener('change', syncPills);
        });
        syncPills();

        const btnFilter = document.getElementById('shopFilterBtn');
        const btnSort = document.getElementById('shopSortBtn');
        function syncTopButtons() {
            const hash = window.location.hash || '';
            if (btnFilter) btnFilter.classList.toggle('refrens-topbtn--active', hash === '#filter');
            if (btnSort) btnSort.classList.toggle('refrens-topbtn--active', hash === '#sort');
        }
        syncTopButtons();
        window.addEventListener('hashchange', syncTopButtons);
    })();
</script>
@endpush
@endsection
