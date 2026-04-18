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
    .refrens-sheet{position:fixed;inset:0;z-index:20000;opacity:0;pointer-events:none;transition:opacity .2s ease}
    .refrens-sheet:target{opacity:1;pointer-events:auto}
    .refrens-sheet__backdrop{position:absolute;inset:0;background:rgba(0,0,0,.35);backdrop-filter:blur(2px)}
    .refrens-sheet__panel{position:absolute;left:0;right:0;bottom:0;max-height:86vh;background:#fff;border-top-left-radius:22px;border-top-right-radius:22px;overflow:hidden;display:flex;flex-direction:column;transform:translateY(100%);transition:transform .22s cubic-bezier(.22,.61,.36,1)}
    .refrens-sheet:target .refrens-sheet__panel{transform:translateY(0)}
    .refrens-sheet__panel form{flex:1;min-height:0;overflow:auto;-webkit-overflow-scrolling:touch}
    .refrens-sheet__header{display:flex;align-items:center;justify-content:space-between;padding:18px 16px;border-bottom:1px solid rgba(0,0,0,.08);background:#fff}
    .refrens-sheet__title{font-weight:900;font-size:24px;line-height:1.1;color:#111827}
    .refrens-sheet__title--sort{font-size:16px}
    .refrens-sheet__close{width:44px;height:44px;border-radius:999px;display:flex;align-items:center;justify-content:center;background:transparent;color:#111827;text-decoration:none !important}
    .refrens-sheet__close:hover{background:rgba(0,0,0,.04)}
    .refrens-sheet__body{flex:1;overflow:auto;-webkit-overflow-scrolling:touch;padding:0 16px}
    .refrens-topbtn{display:flex;align-items:center;justify-content:center;gap:8px;padding:10px 16px;border:1px solid #2563eb;border-radius:12px;background:#fff;color:#2563eb;font-weight:700;font-size:14px;transition:transform .15s ease,background-color .15s ease,color .15s ease;text-decoration:none !important}
    .refrens-topbtn *{text-decoration:none !important}
    .refrens-topbtn:hover{background:rgba(37,99,235,.08);text-decoration:none !important}
    .refrens-topbtn:active{transform:scale(.98)}
    .refrens-topbtn--active{background:#2563eb;color:#fff;text-decoration:none !important}
    .refrens-accordion{border-bottom:1px solid rgba(0,0,0,.08);padding:14px 0}
    .refrens-accordion summary{list-style:none;cursor:pointer;display:flex;align-items:center;justify-content:space-between;font-weight:800;font-size:14px;color:#111827;padding:10px 0}
    .refrens-accordion summary::-webkit-details-marker{display:none}
    .refrens-accordion summary i{transition:transform .15s ease}
    .refrens-accordion[open] summary i{transform:rotate(180deg)}
    .refrens-pill{position:relative;display:inline-flex;align-items:center;justify-content:center;padding:.42rem .8rem;border:1px solid rgba(37,99,235,.35);border-radius:10px;font-weight:800;font-size:.82rem;background:#fff;cursor:pointer;user-select:none;-webkit-tap-highlight-color:transparent}
    .refrens-pill input{display:none}
    .refrens-pill__label{display:inline-flex;align-items:center;justify-content:center}
    .refrens-pill--active{background:rgba(37,99,235,.10);border-color:rgba(37,99,235,.75);color:#1d4ed8}
    .refrens-applybar{position:sticky;bottom:0;background:#fff;padding:14px 16px;border-top:1px solid rgba(0,0,0,.08)}
    .refrens-check{display:flex;align-items:center;gap:12px;padding:12px 0}
    .refrens-check input{appearance:none !important;-webkit-appearance:none !important;width:18px;height:18px;border:2px solid rgba(15,23,42,.22);border-radius:4px;background:#fff;position:relative;flex:0 0 auto;background-image:none !important;box-shadow:none !important;outline:none !important}
    .refrens-check input:focus{box-shadow:0 0 0 3px rgba(37,99,235,.18) !important}
    .refrens-check input:checked{background:#fff;border-color:#2563eb}
    .refrens-check input:checked::after{content:'';position:absolute;left:5px;top:1px;width:5px;height:10px;border:solid #2563eb;border-width:0 2px 2px 0;transform:rotate(45deg)}
    .refrens-check input[type="radio"]{border-radius:999px}
    .refrens-check input[type="radio"]:checked::after{left:50%;top:50%;width:8px;height:8px;border-radius:999px;border:0;background:#2563eb;transform:translate(-50%,-50%)}
    .refrens-check input[type="radio"]:checked + .refrens-check__label{color:#2563eb;font-weight:900}
    .refrens-check input[type="checkbox"]{border-color:rgba(37,99,235,.55)}
    .refrens-check input[type="checkbox"]:checked{background:#2563eb;border-color:#2563eb}
    .refrens-check input[type="checkbox"]:checked::after{border-color:#fff}
    .refrens-check input[type="checkbox"]:checked + .refrens-check__label{color:#2563eb;font-weight:900}
    .refrens-check__label{font-weight:700;color:#111827;font-size:14px}
    .refrens-more{display:flex;align-items:center;justify-content:space-between;width:100%;padding:14px 0;border:0;background:transparent;color:#111827;font-weight:700;font-size:13px}
    .refrens-more i{transition:transform .15s ease}
    .refrens-more.is-open i{transform:rotate(180deg)}
    .refrens-cat-extra{display:none}
    .refrens-catwrap.is-open .refrens-cat-extra{display:flex}
    .refrens-sortitem{display:flex;align-items:center;justify-content:space-between;padding:14px 0;border-bottom:1px solid rgba(0,0,0,.08)}
    .refrens-sortitem:last-child{border-bottom:0}
    .refrens-sortitem__label{font-weight:700;font-size:14px;color:#111827}
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
        top: calc(var(--nav-height) + 12px);
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
        .refrens-topbtn i {
    font-size: 18px;
}
.refrens-topbtn i {
    font-size: 16px;
    line-height: 1;
    display: flex;
    align-items: center;
}

.refrens-topbtn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px; /* biar lebih rapet kayak gambar */
}
    }
</style>

<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        
       <div class="grid grid-cols-2 gap-3 mb-8 shop-sticky-filter">
    
    <a id="shopFilterBtn" href="#filter" class="refrens-topbtn">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <line x1="6" y1="4" x2="6" y2="20"></line>
            <circle cx="6" cy="10" r="2"></circle>

            <line x1="12" y1="4" x2="12" y2="20"></line>
            <circle cx="12" cy="6" r="2"></circle>

            <line x1="18" y1="4" x2="18" y2="20"></line>
            <circle cx="18" cy="14" r="2"></circle>
        </svg>
        <span>Filter</span>
    </a>

    <!-- URUTAN -->
    <a id="shopSortBtn" href="#sort" class="refrens-topbtn">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            
            <!-- garis list -->
            <line x1="10" y1="6" x2="20" y2="6"></line>
            <line x1="10" y1="12" x2="20" y2="12"></line>
            <line x1="10" y1="18" x2="20" y2="18"></line>

           

            <!-- panah bawah -->
            <line x1="3" y1="4" x2="3" y2="18"></line>
            <polyline points="1,16 3,18 5,16"></polyline>

        </svg>
        <span>Urutan</span>
    </a>

</div>

        <div id="filter" class="refrens-sheet" role="dialog" aria-modal="true">
            <a class="refrens-sheet__backdrop" href="{{ request()->fullUrl() }}" aria-label="Close"></a>
            <div class="refrens-sheet__panel">
                <div class="refrens-sheet__header">
                    <div class="refrens-sheet__title">Filter</div>
                    <a href="{{ request()->fullUrl() }}" class="refrens-sheet__close" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
                <form action="{{ route('shop.index') }}" method="GET">
                    <div class="refrens-sheet__body">
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
                                    $selectedCategories = [];
                                    if (request()->filled('category')) {
                                        $selectedCategories[] = (string) request('category');
                                    }
                                    if (request()->has('categories') && is_array(request('categories'))) {
                                        $selectedCategories = array_values(array_filter(array_map('strval', (array) request('categories'))));
                                    }
                                @endphp
                                <div class="border-top pt-2 refrens-catwrap" data-catwrap>
                                    @foreach($categoryList as $category)
                                        @php
                                            $cat = (string) $category;
                                            $isChecked = in_array($cat, $selectedCategories, true);
                                            $isExtra = $loop->index >= 5;
                                        @endphp
                                        <label class="refrens-check {{ $isExtra ? 'refrens-cat-extra' : '' }}">
                                            <input type="checkbox" name="categories[]" value="{{ $cat }}" {{ $isChecked ? 'checked' : '' }}>
                                            <span class="refrens-check__label">{{ $cat }}</span>
                                        </label>
                                    @endforeach
                                    @if($categoryList->count() > 5)
                                        <button type="button" class="refrens-more" data-catmore>
                                            <span>Lihat lainnya</span>
                                            <i class="bi bi-chevron-down"></i>
                                        </button>
                                    @endif
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
                                    <label class="refrens-check">
                                        <input type="radio" name="type" value="" {{ $currentType === '' ? 'checked' : '' }}>
                                        <span class="refrens-check__label">Semua Produk</span>
                                    </label>
                                    <label class="refrens-check">
                                        <input type="radio" name="type" value="featured" {{ $currentType === 'featured' ? 'checked' : '' }}>
                                        <span class="refrens-check__label">Produk Unggulan</span>
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
                                    <label class="refrens-check">
                                        <input type="radio" name="availability" value="all" {{ $availability === 'all' ? 'checked' : '' }}>
                                        <span class="refrens-check__label">Semua</span>
                                    </label>
                                    <label class="refrens-check">
                                        <input type="radio" name="availability" value="in" {{ $availability === 'in' ? 'checked' : '' }}>
                                        <span class="refrens-check__label">Ada Stok</span>
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
                                    $priceSelected = $hasPriceRange ? $priceRange : '';
                                @endphp
                                <div class="border-top pt-2">
                                    <label class="refrens-check">
                                        <input type="radio" name="price_range" value="" {{ $priceSelected === '' ? 'checked' : '' }}>
                                        <span class="refrens-check__label">Semua Harga</span>
                                    </label>
                                    <label class="refrens-check">
                                        <input type="radio" name="price_range" value="under_75000" {{ $priceSelected === 'under_75000' ? 'checked' : '' }}>
                                        <span class="refrens-check__label">Di bawah Rp 75,000</span>
                                    </label>
                                    <label class="refrens-check">
                                        <input type="radio" name="price_range" value="75000_150000" {{ $priceSelected === '75000_150000' ? 'checked' : '' }}>
                                        <span class="refrens-check__label">Rp 75,000 - Rp 150,000</span>
                                    </label>
                                    <label class="refrens-check">
                                        <input type="radio" name="price_range" value="150000_220000" {{ $priceSelected === '150000_220000' ? 'checked' : '' }}>
                                        <span class="refrens-check__label">Rp 150,000 - Rp 220,000</span>
                                    </label>
                                    <label class="refrens-check">
                                        <input type="radio" name="price_range" value="220000_plus" {{ $priceSelected === '220000_plus' ? 'checked' : '' }}>
                                        <span class="refrens-check__label">Rp 220,000+</span>
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
                <div class="refrens-sheet__header">
                    <div class="refrens-sheet__title refrens-sheet__title--sort">Urutkan produk berdasarkan</div>
                    <a href="{{ request()->fullUrl() }}" class="refrens-sheet__close" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
                <form action="{{ route('shop.index') }}" method="GET">
                    <div class="refrens-sheet__body">
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
        let lockedScrollY = 0;
        function lockScroll() {
            if (document.body.classList.contains('refrens-lockscroll')) return;
            lockedScrollY = window.scrollY || window.pageYOffset || 0;
            document.body.classList.add('refrens-lockscroll');
            document.body.style.position = 'fixed';
            document.body.style.top = '-' + lockedScrollY + 'px';
            document.body.style.left = '0';
            document.body.style.right = '0';
            document.body.style.width = '100%';
        }
        function unlockScroll() {
            if (!document.body.classList.contains('refrens-lockscroll')) return;
            document.body.classList.remove('refrens-lockscroll');
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.left = '';
            document.body.style.right = '';
            document.body.style.width = '';
            window.scrollTo(0, lockedScrollY);
        }
        function syncScrollLock() {
            const hash = window.location.hash || '';
            if (hash === '#filter' || hash === '#sort') {
                lockScroll();
                return;
            }
            unlockScroll();
        }
        function initCategoryMore() {
            const wrap = document.querySelector('[data-catwrap]');
            const btn = document.querySelector('[data-catmore]');
            if (!wrap || !btn) return;
            btn.addEventListener('click', function () {
                const isOpen = wrap.classList.toggle('is-open');
                btn.classList.toggle('is-open', isOpen);
                const label = btn.querySelector('span');
                if (label) label.textContent = isOpen ? 'Lihat lebih sedikit' : 'Lihat lainnya';
            });
        }
        syncTopButtons();
        syncScrollLock();
        initCategoryMore();
        window.addEventListener('hashchange', function () {
            syncTopButtons();
            syncScrollLock();
        });
    })();
</script>
@endpush
@endsection
