@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
    .refrens-productswiper{border-radius:16px;overflow:hidden;touch-action: pan-y;position:relative}
    .refrens-productswiper .swiper-button-next,
    .refrens-productswiper .swiper-button-prev{width:40px;height:40px;background:rgba(255,255,255,0.9);box-shadow:0 4px 12px rgba(0,0,0,0.15);border-radius:50%;color:#111827;transition:all 0.2s;z-index:10}
    .refrens-productswiper .swiper-button-next:after,
    .refrens-productswiper .swiper-button-prev:after{font-size:16px;font-weight:bold}
    .refrens-productswiper .swiper-button-next:hover,
    .refrens-productswiper .swiper-button-prev:hover{background:#fff;color:#2563eb;transform:scale(1.1)}
    .refrens-productswiper .swiper-button-disabled{opacity:0 !important;pointer-events:none}
    @media (max-width: 767px){
        .refrens-productswiper .swiper-button-next,
        .refrens-productswiper .swiper-button-prev{display:flex !important} /* Munculkan juga di mobile agar terlihat */
        .refrens-productswiper .swiper-button-next{right:10px}
        .refrens-productswiper .swiper-button-prev{left:10px}
    }
    .refrens-productpager{display:flex;justify-content:center;gap:6px;padding:10px 0}
    .refrens-productpager .swiper-pagination-bullet{width:7px;height:7px;border-radius:999px;background:rgba(17,24,39,.28);opacity:1}
    .refrens-productpager .swiper-pagination-bullet-active{background:#111827}
    .refrens-productframe{aspect-ratio:3/4}
    .refrens-productthumbs{display:flex;gap:10px;overflow-x:auto;overflow-y:hidden;-webkit-overflow-scrolling:touch;scrollbar-width:none;padding-bottom:4px}
    .refrens-productthumbs::-webkit-scrollbar{display:none}
    .refrens-productthumb{width:72px;height:72px;border-radius:14px;overflow:hidden;border:1px solid rgba(0,0,0,.08);background:#f3f4f6;flex:0 0 auto}
    .refrens-productthumb img{width:100%;height:100%;object-fit:cover}
    .refrens-productthumb.is-active{border-color:rgba(37,99,235,.8);box-shadow:0 12px 22px rgba(37,99,235,.12)}
    .refrens-sizeguide-sheet{position:fixed;inset:0;z-index:1000;display:none}
    .refrens-sizeguide-sheet.is-open{display:block}
    .refrens-sizeguide-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.45);backdrop-filter:blur(2px)}
    .refrens-sizeguide-panel{position:fixed;left:0;right:0;bottom:0;max-height:86vh;background:#fff;border-top-left-radius:22px;border-top-right-radius:22px;overflow:auto}
    .refrens-sizeguide-handle{width:56px;height:6px;border-radius:999px;background:#e5efff;margin:10px auto}
    .refrens-sizeguide-image{display:block;width:min(360px,100%);max-height:62vh;height:auto;object-fit:contain;margin:0 auto}
    .refrens-areaguide-sheet{position:fixed;inset:0;z-index:1001;display:none}
    .refrens-areaguide-sheet.is-open{display:block}
    .refrens-areaguide-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.45);backdrop-filter:blur(2px)}
    .refrens-areaguide-panel{position:fixed;left:0;right:0;bottom:0;max-height:86vh;background:#fff;border-top-left-radius:22px;border-top-right-radius:22px;overflow:auto}
    .refrens-areaguide-handle{width:56px;height:6px;border-radius:999px;background:#e5efff;margin:10px auto}
    .refrens-areaguide-search{position:relative}
    .refrens-areaguide-search input{width:100%;border:1px solid rgba(0,0,0,.12);border-radius:12px;padding:10px 42px 10px 12px;font-size:12px;font-weight:600}
    .refrens-areaguide-search .bi{position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#111827}
    .refrens-areaguide-item{display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid rgba(0,0,0,.06);font-size:12px;font-weight:700;color:#111827}
    .refrens-areaguide-item:last-child{border-bottom:0}
    .refrens-areaguide-item i{color:rgba(17,24,39,.55)}
    .refrens-areaguide-breadcrumb{font-size:12px;font-weight:700;color:#6b7280;margin-bottom:6px}
    .refrens-sizebtn{min-width:48px;height:40px;border-radius:10px;border:1px solid rgba(0,0,0,.08);background:#fff;font-weight:600;font-size:12px}
    .refrens-sizebtn.is-active{border-color:#2563eb;background:rgba(37,99,235,.08);color:#2563eb}
    .refrens-infocard{border:1px solid rgba(0,0,0,.08);border-radius:14px;background:#fff}
    .refrens-infocard__title{font-size:12px;font-weight:800;color:#111827}
    .refrens-infocard__sub{font-size:11px;color:#6b7280;font-weight:600}
    .refrens-sizeguide-preview{width:100%;max-width:240px;height:auto;object-fit:contain;border:1px solid rgba(0,0,0,.08);border-radius:12px}
    .refrens-textblock{font-size:12px;line-height:1.55;color:#111827}
    .refrens-textblock h4{font-size:12px;font-weight:800;margin:0 0 8px}
    .refrens-textblock .refrens-dash{border-top:1px dashed rgba(17,24,39,.35);margin:10px 0}
    .refrens-textblock ul{padding-left:14px;margin:0}
    .refrens-textblock li{margin:0 0 8px}
    .refrens-textblock li:last-child{margin-bottom:0}
    .refrens-shipcard{border:1px solid rgba(0,0,0,.08);border-radius:14px;background:#fff}
    .refrens-shipcard__title{font-size:12px;font-weight:800;margin-bottom:10px}
    .refrens-shiprow{display:flex;align-items:center;justify-content:space-between;gap:10px;font-size:12px}
    .refrens-shiprow small{display:block;color:#6b7280;font-weight:600}
    .refrens-shipaction{color:#dc2626;font-weight:700;text-decoration:none}
    .refrens-shipaction:hover{text-decoration:underline}
    .refrens-addbar{position:fixed;left:0;right:0;bottom:0;z-index:60;background:#fff;border-top:1px solid rgba(0,0,0,.10);padding:10px 12px calc(10px + env(safe-area-inset-bottom))}
    .refrens-addbar__inner{max-width:520px;margin:0 auto;display:flex;align-items:center;gap:10px}
    .refrens-qty{display:flex;align-items:center;border:1px solid rgba(0,0,0,.10);border-radius:12px;overflow:hidden;height:44px}
    .refrens-qty button{width:44px;height:44px;border:0;background:#fff;color:#111827;font-weight:800}
    .refrens-qty input{width:44px;height:44px;border:0;text-align:center;font-weight:700}
    .refrens-addbtn{height:48px;border-radius:14px;font-weight:900;background:#2563eb;border:0;color:#fff;transition:none;box-shadow:0 14px 28px rgba(37,99,235,.22);display:flex;align-items:center;justify-content:center;gap:10px}
    .refrens-addbtn:disabled{background:#d1d5db;color:#6b7280}
    .refrens-msgbtn{width:48px;height:48px;border-radius:14px;background:#2563eb;border:0;color:#fff;display:flex;align-items:center;justify-content:center;transition:none;flex:0 0 auto;box-shadow:0 14px 28px rgba(37,99,235,.18)}
    .refrens-msgbtn:active{transform:scale(.98)}
    .refrens-strip{display:flex;gap:16px;overflow-x:auto;overflow-y:hidden;-webkit-overflow-scrolling:touch;scrollbar-width:none;padding-bottom:6px}
    .refrens-strip::-webkit-scrollbar{display:none}
    .refrens-mini{flex:0 0 auto;width:132px}
    .refrens-mini__img{position:relative;border-radius:12px;overflow:hidden;background:#f3f4f6;aspect-ratio:3/4}
    .refrens-mini__img img{width:100%;height:100%;object-fit:cover}
    .refrens-mini__act{position:absolute;right:8px;bottom:8px;width:28px;height:28px;border-radius:10px;background:#fff;border:1px solid rgba(0,0,0,.10);display:flex;align-items:center;justify-content:center;color:#111827}
    .refrens-mini__name{font-size:11px;font-weight:700;color:#111827;margin-top:8px;line-height:1.25}
    .refrens-mini__price{font-size:11px;font-weight:700;color:#dc2626;margin-top:4px}
    .refrens-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
    .refrens-grid .refrens-mini{width:auto}
    .refrens-sold{position:absolute;left:8px;bottom:8px;background:rgba(0,0,0,.55);color:#fff;font-size:10px;font-weight:700;padding:2px 8px;border-radius:999px}
    .swiper-wrapper {
        transition-timing-function: ease-out !important; /* Ganti ke ease-out agar lebih natural di akhir gerakan */
    }
    .swiper-slide {
        transform: translate3d(0,0,0);
        background: #f3f4f6;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }
    .swiper-slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    @media (min-width: 768px){
        .refrens-strip{overflow:visible;padding-bottom:0}
        .refrens-mini{width:180px}
        .refrens-grid{grid-template-columns:repeat(4,minmax(0,1fr))}
        .refrens-grid .refrens-mini{width:auto}
    }
    @media (max-width: 767px){
        .refrens-productswiper{border-radius:0}
        .refrens-productframe{aspect-ratio:auto;height:min(52vh,420px)}
        .refrens-detailpad{padding-left:16px;padding-right:16px}
        .refrens-detailwrap{padding-bottom:110px}
        .refrens-chat-fab{display:none !important}
    }
</style>
<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-0 sm:px-6 lg:px-8 py-4 sm:py-10 refrens-detailwrap">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 lg:items-start">
            <div class="flex flex-col gap-4">
                @php
                    $gallery = $product->images ?: [];
                    if (empty($gallery) && $product->image) {
                        $gallery = [$product->image];
                    }
                @endphp
                <div class="position-relative -mx-4 sm:mx-0">
                    @if(!empty($gallery))
                        <div class="swiper refrens-productswiper refrens-productswiper--main">
                            <div class="swiper-wrapper refrens-productframe">
                                @foreach($gallery as $img)
                                    <div class="swiper-slide">
                                        <img src="{{ asset('storage/' . $img) }}" alt="{{ $product->name }}">
                                    </div>
                                @endforeach
                            </div>
                            <!-- Add Arrows -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                    @else
                        <div class="refrens-productframe d-flex align-items-center justify-content-center bg-light text-muted rounded-2">
                            <svg class="h-24 w-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    @if($product->stock <= 0)
                        <span class="badge text-bg-dark position-absolute top-0 start-0 m-2 rounded-pill">Sold Out</span>
                    @endif
                </div>
                @if(!empty($gallery))
                    <div class="refrens-productpager" data-product-pagination></div>
                @endif
                @if(!empty($gallery))
                    <div class="refrens-productthumbs mt-2 hidden md:flex" data-thumbs>
                        @foreach($gallery as $idx => $img)
                            <button type="button" class="p-0 border-0 bg-transparent refrens-productthumb {{ $idx === 0 ? 'is-active' : '' }}" data-thumb-index="{{ $idx }}">
                                <img src="{{ asset('storage/' . $img) }}" alt="thumb">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right: Product Info -->
            <div class="mt-4 sm:mt-16 lg:mt-0 refrens-detailpad" x-data="{
                size: '',
                quantity: 1,
                showSizeGuide: false,
                showLocationPicker: false,
                pickerStep: 1,
                areaQuery: '',
                selectedProvince: '',
                selectedCity: '',
                selectedArea: '',
                cartOpen: {{ session('cart_drawer') ? 'true' : 'false' }},
                provinces: ['Aceh','Bali','Bangka Belitung','Banten','Bengkulu','DI Yogyakarta','DKI Jakarta','Gorontalo','Jambi','Jawa Barat','Jawa Tengah','Jawa Timur','Kalimantan Barat','Kalimantan Selatan','Kalimantan Tengah','Kalimantan Timur','Kalimantan Utara','Kepulauan Riau','Lampung','Maluku','Maluku Utara','Nusa Tenggara Barat','Nusa Tenggara Timur','Papua','Papua Barat','Papua Barat Daya','Papua Pegunungan','Papua Selatan','Papua Tengah','Riau','Sulawesi Barat','Sulawesi Selatan','Sulawesi Tengah','Sulawesi Tenggara','Sulawesi Utara','Sumatera Barat','Sumatera Selatan','Sumatera Utara'],
                cities: {
                    'Bali': ['Kab. Badung','Kab. Bangli','Kab. Buleleng','Kab. Gianyar','Kab. Jembrana','Kab. Karangasem','Kab. Klungkung','Kab. Tabanan','Kota Denpasar']
                },
                areas: {
                    'Kab. Bangli': ['Bangli','Kintamani','Susut','Tembuku']
                },
                defaultCities(province) {
                    return ['Kota/Kab. Pusat','Kota/Kab. Utara','Kota/Kab. Selatan','Kota/Kab. Timur','Kota/Kab. Barat'];
                },
                defaultAreas(city) {
                    return ['Area 1','Area 2','Area 3','Area 4','Area 5'];
                },
                init() {
                    try {
                        const saved = localStorage.getItem('refrens_shipping_area');
                        if (saved) {
                            const parts = String(saved).split(',').map(s => s.trim());
                            this.selectedProvince = parts[0] || '';
                            this.selectedCity = parts[1] || '';
                            this.selectedArea = parts[2] || '';
                        }
                    } catch (e) {}
                },
                startPicker() {
                    this.showLocationPicker = true;
                    this.pickerStep = 1;
                    this.areaQuery = '';
                },
                filteredList() {
                    const q = String(this.areaQuery || '').trim().toLowerCase();
                    let list = [];
                    if (this.pickerStep === 1) list = this.provinces;
                    if (this.pickerStep === 2) list = this.cities[this.selectedProvince] || this.defaultCities(this.selectedProvince);
                    if (this.pickerStep === 3) list = this.areas[this.selectedCity] || this.defaultAreas(this.selectedCity);
                    if (!q) return list;
                    return list.filter(x => String(x).toLowerCase().includes(q));
                },
                pickItem(item) {
                    if (this.pickerStep === 1) {
                        this.selectedProvince = item;
                        this.pickerStep = 2; this.areaQuery = '';
                    } else if (this.pickerStep === 2) {
                        this.selectedCity = item;
                        this.pickerStep = 3; this.areaQuery = '';
                    } else {
                        this.selectedArea = item;
                        const sum = [this.selectedProvince, this.selectedCity, this.selectedArea].filter(Boolean).join(', ');
                        try { localStorage.setItem('refrens_shipping_area', sum); } catch (e) {}
                        this.showLocationPicker = false;
                    }
                },
                backStep() {
                    if (this.pickerStep > 1) { this.pickerStep -= 1; this.areaQuery = ''; }
                    else { this.showLocationPicker = false; }
                }
            }" x-init="init()">
                <div class="border-b border-gray-200 pb-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge rounded-pill {{ $product->stock > 0 ? 'text-bg-primary' : 'text-bg-dark' }}">{{ $product->stock > 0 ? 'Ada Stok' : 'Habis' }}</span>
                        @if($product->category)
                            <span class="badge rounded-pill text-bg-light border">{{ $product->category }}</span>
                        @endif
                    </div>
                    <h1 class="text-xl fw-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <p class="h5 fw-bold text-blue-600 mb-0" data-money-idr="{{ (float) $product->price }}">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <div style="margin-top:-2px;">
                            @auth
                                @php
                                    $inWishlist = \App\Models\Wishlist::where('user_id', Auth::id())->where('product_id', $product->id)->exists();
                                @endphp
                                @if($inWishlist)
                                    <form method="POST" action="{{ route('wishlist.destroy', $product) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link p-0 text-blue-600" aria-label="Remove from wishlist">
                                            <i class="bi bi-heart-fill" style="font-size:16px;"></i>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('wishlist.store', $product) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-link p-0 text-blue-600" aria-label="Add to wishlist">
                                            <i class="bi bi-heart" style="font-size:16px;"></i>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('account.index', ['login' => 1]) }}" class="btn btn-link p-0 text-blue-600" aria-label="Login">
                                    <i class="bi bi-heart" style="font-size:16px;"></i>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <div class="refrens-infocard p-3 mb-4 d-md-none">
                        <div class="d-flex align-items-start justify-content-between gap-3">
                            <div>
                                <div class="refrens-infocard__title">Informasi Jumlah</div>
                                <div class="refrens-infocard__sub">Jumlah Maksimal: {{ max(0, (int) $product->stock) }}</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="refrens-qty" style="height:40px">
                                    <button type="button" style="width:40px;height:40px" @click="quantity > 1 ? quantity-- : null">-</button>
                                    <input type="text" style="width:40px;height:40px" x-model="quantity" readonly>
                                    <button type="button" style="width:40px;height:40px" @click="quantity++">+</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="fw-semibold">Ukuran</div>
                            <button @click="showSizeGuide = true" class="btn btn-link p-0 text-decoration-none text-muted fw-semibold">
                                Size Guide <span class="ml-1">&rsaquo;</span>
                            </button>
                        </div>
                        @php
                            $sizeOrder = ['S','M','L','XL','39','40','41','42','43'];
                            $sizeValues = is_array($product->sizes) ? $product->sizes : [];
                            $sizeValues = array_values(array_filter($sizeValues, fn ($v) => $v !== null && $v !== ''));
                            if (count($sizeValues) === 0) {
                                $sizeValues = ['M', 'L', 'XL'];
                            } else {
                                usort($sizeValues, function ($a, $b) use ($sizeOrder) {
                                    $ia = array_search((string) $a, $sizeOrder, true);
                                    $ib = array_search((string) $b, $sizeOrder, true);
                                    $ia = $ia === false ? 999 : $ia;
                                    $ib = $ib === false ? 999 : $ib;
                                    return $ia <=> $ib;
                                });
                            }
                        @endphp
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            @foreach($sizeValues as $s)
                                <button type="button" @click="size = '{{ $s }}'" class="refrens-sizebtn" :class="size === '{{ $s }}' ? 'is-active' : ''">
                                    {{ $s }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <form id="addToCartForm" action="{{ route('cart.add', $product) }}" method="POST" class="d-none d-md-block">
                        @csrf
                        <input type="hidden" name="size" x-model="size">
                        <input type="hidden" name="quantity" x-model="quantity">
                        
                        <button type="submit" 
                                :disabled="!size || {{ $product->stock <= 0 ? 'true' : 'false' }}"
                                class="w-full bg-blue-600 border border-transparent rounded-full py-4 px-8 flex items-center justify-center text-base font-bold text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 disabled:bg-gray-300 disabled:cursor-not-allowed transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            {{ $product->stock > 0 ? 'ADD TO CART' : 'OUT OF STOCK' }}
                        </button>
                    </form>
                </div>

                <div class="mt-2 refrens-textblock">
                    <div>Crafted in Cotton Fleece 375 gsm</div>
                    <div>Applique Embroidery</div>
                    <div>Boxy Fit</div>
                    <div class="refrens-dash"></div>
                    <h4>Info Pengiriman :</h4>
                    <ul>
                        <li>Pengiriman setiap hari senin - sabtu pukul 17:00 WIB.</li>
                        <li>Order Lewat dari 16:00 WIB akan dikirimkan pada hari berikutnya.</li>
                        <li>Order pada hari Sabtu dan Minggu akan dikirimkan pada hari senin</li>
                        <li>Hari libur nasional (tanggal merah) tidak ada pengiriman. Akan dikirimkan pada hari berikutnya.</li>
                    </ul>
                    <div class="refrens-dash"></div>
                    <h4>T&amp;C RETURN PRODUCT</h4>
                    <ul>
                        <li>Penukaran produk hanya berlaku untuk produk yang mengalami defect serta produk yang tidak sesuai dengan pesanan</li>
                        <li>Pengajuan komplain maksimal 2x24 jam dari pesanan sampai</li>
                        <li>Mohon sertakan video unboxing produk saat pesanan datang, tanpa video unboxing paket TIDAK TERIMA KOMPLAIN APAPUN</li>
                        <li>Retur TIDAK BERLAKU untuk kesalahan pembeli salah pilih size, model, dan warna.</li>
                    </ul>
                    <div class="refrens-dash"></div>
                    <div class="refrens-shipcard p-3 mt-3">
                        <div class="refrens-shipcard__title">Pengiriman</div>
                        <div class="refrens-shiprow">
                            <div>
                                <small>Dikirim ke:</small>
                            </div>
                            <a href="#" class="refrens-shipaction" @click.prevent="startPicker()">
                                <span x-text="(selectedProvince && selectedCity && selectedArea) ? (selectedProvince + ', ' + selectedCity + ', ' + selectedArea) : 'Pilih Area'"></span> <i class="bi bi-chevron-down"></i>
                            </a>
                        </div>
                        <div class="refrens-shiprow mt-2">
                            <div>
                                <small>Berat</small>
                            </div>
                            <div class="fw-bold">1000 g</div>
                        </div>
                        <div class="mt-2 text-muted" style="font-size:11px;font-weight:600">
                            Dikirim dalam 24 jam,<br>(Setelah pembayaran dikonfirmasi)
                        </div>
                    </div>
                </div>

                <!-- Size Guide Modal -->
                <div class="refrens-sizeguide-sheet" :class="showSizeGuide ? 'is-open' : ''" x-cloak role="dialog" aria-modal="true">
                    <div class="refrens-sizeguide-backdrop" @click="showSizeGuide = false"></div>
                    <div class="refrens-sizeguide-panel">
                        <div class="px-4 pt-2 pb-3">
                            <div class="refrens-sizeguide-handle"></div>
                            <div class="d-flex align-items-center justify-content-between">
                                <button type="button" class="btn btn-light rounded-circle" @click="showSizeGuide = false" aria-label="Back">
                                    <i class="bi bi-arrow-left"></i>
                                </button>
                                <div></div>
                                <div style="width:40px;"></div>
                            </div>
                        </div>
                        <div class="px-4 pb-4">
                            <img src="{{ asset('img/ukuran.jpeg') }}" alt="Size Chart" class="refrens-sizeguide-image rounded-4 border" style="border-color:rgba(0,0,0,.08);">
                        </div>
                    </div>
                </div>

                <div class="refrens-areaguide-sheet" :class="showLocationPicker ? 'is-open' : ''" x-cloak role="dialog" aria-modal="true">
                    <div class="refrens-areaguide-backdrop" @click="showLocationPicker = false"></div>
                    <div class="refrens-areaguide-panel">
                        <div class="px-4 pt-2 pb-3">
                            <div class="refrens-areaguide-handle"></div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <button type="button" class="btn btn-light rounded-circle" @click="backStep()" aria-label="Back">
                                    <i class="bi bi-arrow-left"></i>
                                </button>
                                <div class="fw-bold" x-text="pickerStep === 1 ? '1. Pilih Provinsi' : (pickerStep === 2 ? '2. Pilih Kota' : '3. Pilih Area')"></div>
                            </div>
                            <template x-if="pickerStep > 1">
                                <div class="refrens-areaguide-breadcrumb">
                                    <span x-text="selectedProvince"></span>
                                    <template x-if="pickerStep > 2">
                                        <span>, <span x-text="selectedCity"></span></span>
                                    </template>
                                </div>
                            </template>
                            <div class="text-muted" style="font-size:12px;font-weight:600;margin-bottom:10px" x-show="pickerStep === 1">
                                Ke mana kamu ingin mengirimkan paket?
                            </div>
                            <div class="refrens-areaguide-search">
                                <input type="text" :placeholder="pickerStep === 1 ? 'Cari Provinsi' : (pickerStep === 2 ? 'Cari Kota' : 'Cari Area')" x-model="areaQuery">
                                <i class="bi bi-search"></i>
                            </div>
                        </div>
                        <div class="px-4 pb-3">
                            <template x-for="item in filteredList()" :key="item">
                                <button type="button" class="w-100 text-start bg-transparent border-0 refrens-areaguide-item" @click="pickItem(item)">
                                    <span x-text="item"></span>
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="refrens-addbar d-md-none">
                    <div class="refrens-addbar__inner">
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1 m-0">
                            @csrf
                            <input type="hidden" name="size" x-model="size">
                            <input type="hidden" name="quantity" x-model="quantity">
                            <button type="submit" class="w-100 refrens-addbtn" :disabled="!size || {{ $product->stock <= 0 ? 'true' : 'false' }}">
                                <i class="bi bi-bag-plus-fill"></i>
                                <span>{{ $product->stock > 0 ? 'Tambah Ke Keranjang' : 'Stok Habis' }}</span>
                            </button>
                        </form>
                        @auth
                            <a class="refrens-msgbtn" href="{{ route('chat.index') }}" aria-label="Pesan">
                                <i class="bi bi-chat-dots-fill"></i>
                            </a>
                        @else
                            <a class="refrens-msgbtn" href="#" data-loginprompt-open aria-label="Pesan">
                                <i class="bi bi-chat-dots-fill"></i>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <style>
            .refrens-cartdrawer{position:fixed;inset:0;z-index:1002;display:none}
            .refrens-cartdrawer.is-open{display:block}
            .refrens-cartdrawer__backdrop{position:absolute;inset:0;background:rgba(0,0,0,.45)}
            .refrens-cartdrawer__panel{position:absolute;right:0;top:0;bottom:0;width:min(420px,90vw);background:#fff;box-shadow:0 18px 48px rgba(0,0,0,.18);transform:translateX(100%);opacity:0;transition:transform .22s cubic-bezier(.22,.61,.36,1),opacity .18s ease;display:flex;flex-direction:column}
            .refrens-cartdrawer.is-open .refrens-cartdrawer__panel{transform:translateX(0);opacity:1}
            .refrens-cartheader{display:flex;align-items:center;justify-content:space-between;padding:14px 14px;border-bottom:1px solid rgba(0,0,0,.06);font-weight:900}
            .refrens-cartbody{flex:1 1 auto;overflow:auto}
            .refrens-cartitem{display:flex;gap:12px;padding:14px 14px;border-bottom:1px solid rgba(0,0,0,.06)}
            .refrens-cartitem__thumb{width:76px;height:92px;border-radius:14px;overflow:hidden;background:#f3f4f6;flex:0 0 auto}
            .refrens-cartitem__thumb img{width:100%;height:100%;object-fit:cover}
            .refrens-cartitem__meta{flex:1 1 auto;min-width:0}
            .refrens-cartitem__name{font-size:12px;font-weight:800;color:#111827;line-height:1.2}
            .refrens-cartitem__sub{font-size:11px;color:#6b7280;font-weight:700;margin-top:4px}
            .refrens-cartitem__price{font-size:12px;font-weight:900;color:#111827;margin-top:6px}
            .refrens-cartitem__bottom{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-top:10px}
            .refrens-cartlinks{display:flex;gap:14px;align-items:center}
            .refrens-cartlink{border:0;background:transparent;padding:0;font-size:11px;font-weight:800;color:#111827;text-decoration:underline}
            .refrens-cartqty{display:flex;align-items:center;border:1px solid rgba(0,0,0,.10);border-radius:999px;overflow:hidden;height:30px;background:#fff}
            .refrens-cartqty button{width:30px;height:30px;border:0;background:transparent;font-weight:900;color:#111827}
            .refrens-cartqty span{min-width:26px;text-align:center;font-weight:900;font-size:12px;color:#111827}
            .refrens-carttrust{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px;border-bottom:1px solid rgba(0,0,0,.06)}
            .refrens-carttrust__col{flex:1 1 0;display:flex;align-items:center;gap:10px;color:#111827}
            .refrens-carttrust__col i{font-size:18px;color:rgba(17,24,39,.55)}
            .refrens-carttrust__txt{font-size:11px;font-weight:800;color:#111827}
            .refrens-carttrust__divider{width:1px;height:26px;background:rgba(0,0,0,.08)}
            .refrens-cartsection{padding:14px}
            .refrens-cartsection__title{font-size:12px;font-weight:900;color:#111827;margin-bottom:10px}
            .refrens-cartgrid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
            .refrens-cartprod{display:block;color:inherit;text-decoration:none}
            .refrens-cartprod__img{position:relative;border-radius:14px;overflow:hidden;background:#f3f4f6;aspect-ratio:1/1}
            .refrens-cartprod__img img{width:100%;height:100%;object-fit:cover}
            .refrens-cartprod__act{position:absolute;right:8px;bottom:8px;width:28px;height:28px;border-radius:10px;background:#fff;border:1px solid rgba(0,0,0,.10);display:flex;align-items:center;justify-content:center;color:#111827}
            .refrens-cartprod__name{margin-top:8px;font-size:11px;font-weight:800;color:#111827;line-height:1.2}
            .refrens-cartprod__price{margin-top:4px;font-size:11px;font-weight:900;color:#dc2626}
            .refrens-cartfooter{border-top:1px solid rgba(0,0,0,.06);padding:12px 14px calc(12px + env(safe-area-inset-bottom));background:#fff}
            .refrens-carttotal{display:flex;align-items:flex-end;justify-content:space-between;gap:10px;margin-bottom:10px}
            .refrens-carttotal .label{font-size:11px;color:#6b7280;font-weight:900}
            .refrens-carttotal .value{font-size:14px;font-weight:900;color:#111827}
            .refrens-cartcheckout{width:100%;height:44px;border-radius:12px;border:0;background:#2563eb;color:#fff;font-weight:900}
        </style>
        <div class="refrens-cartdrawer" :class="cartOpen ? 'is-open' : ''" x-cloak>
            <div class="refrens-cartdrawer__backdrop" @click="cartOpen=false"></div>
            <div class="refrens-cartdrawer__panel">
                <div class="refrens-cartheader">
                    <div>Keranjang Saya</div>
                    <button type="button" class="btn btn-light btn-sm rounded-circle" @click="cartOpen=false"><i class="bi bi-x-lg"></i></button>
                </div>
                @auth
                    @php
                        $cartItems = \App\Models\Cart::where('user_id', Auth::id())->with('product')->get();
                        $cartQty = (int) $cartItems->sum('quantity');
                        $subtotal = (float) $cartItems->sum(fn($i) => (float) ($i->product?->price ?? 0) * (int) $i->quantity);
                    @endphp
                    <div class="refrens-cartbody">
                        @foreach($cartItems as $item)
                            <div class="refrens-cartitem">
                                <div class="refrens-cartitem__thumb">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"><i class="bi bi-image"></i></div>
                                    @endif
                                </div>
                                <div class="refrens-cartitem__meta">
                                    <div class="refrens-cartitem__name">{{ $item->product?->name ?? 'Produk dihapus' }}</div>
                                    <div class="refrens-cartitem__sub">{{ $item->size ?? 'M' }}</div>
                                    <div class="refrens-cartitem__price" data-money-idr="{{ (float) ($item->product?->price ?? 0) }}">Rp {{ number_format((float) ($item->product?->price ?? 0), 0, ',', '.') }}</div>
                                    <div class="refrens-cartitem__bottom">
                                        <div class="refrens-cartlinks">
                                            @if($item->product)
                                                <form method="POST" action="{{ route('wishlist.store', $item->product) }}">
                                                    @csrf
                                                    <input type="hidden" name="open_drawer" value="1">
                                                    <button type="submit" class="refrens-cartlink">Pindahkan ke Wishlist</button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('cart.destroy', $item) }}">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="open_drawer" value="1">
                                                <button type="submit" class="refrens-cartlink">Hapus</button>
                                            </form>
                                        </div>
                                        <div class="refrens-cartqty">
                                            <form method="POST" action="{{ route('cart.update', $item) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="quantity" value="{{ max(1, (int) $item->quantity - 1) }}">
                                                <input type="hidden" name="open_drawer" value="1">
                                                <button type="submit">-</button>
                                            </form>
                                            <span>{{ (int) $item->quantity }}</span>
                                            <form method="POST" action="{{ route('cart.update', $item) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="quantity" value="{{ (int) $item->quantity + 1 }}">
                                                <input type="hidden" name="open_drawer" value="1">
                                                <button type="submit">+</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="refrens-carttrust">
                            <div class="refrens-carttrust__col">
                                <i class="bi bi-truck"></i>
                                <div class="refrens-carttrust__txt">Pembayaran aman</div>
                            </div>
                            <div class="refrens-carttrust__divider"></div>
                            <div class="refrens-carttrust__col">
                                <i class="bi bi-shield-check"></i>
                                <div class="refrens-carttrust__txt">Perlindungan privasi</div>
                            </div>
                        </div>

                        <div class="refrens-cartsection">
                            <div class="refrens-cartsection__title">Baru Saja Dipesan</div>
                            <div class="refrens-cartgrid">
                                @foreach(($recentOrdered ?? collect())->take(6) as $p)
                                    <div>
                                        <a class="refrens-cartprod" href="{{ route('shop.show', $p->slug) }}">
                                            <div class="refrens-cartprod__img">
                                                @if($p->image)
                                                    <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}">
                                                @else
                                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"><i class="bi bi-image"></i></div>
                                                @endif
                                                <span class="refrens-cartprod__act"><i class="bi bi-bag-plus"></i></span>
                                            </div>
                                            <div class="refrens-cartprod__name">{{ $p->name }}</div>
                                            <div class="refrens-cartprod__price" data-money-idr="{{ (float) $p->price }}">Rp {{ number_format((float) $p->price, 0, ',', '.') }}</div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="refrens-cartfooter">
                        <div class="refrens-carttotal">
                            <div class="label">Total Harga ({{ $cartQty }})</div>
                            <div class="value" data-money-idr="{{ (float) $subtotal }}">Rp {{ number_format((float) $subtotal, 0, ',', '.') }}</div>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn refrens-cartcheckout">Checkout</a>
                    </div>
                @else
                    <div class="refrens-cartbody p-4">
                        <div class="text-center text-muted">Login untuk melihat keranjang</div>
                    </div>
                @endauth
            </div>
        </div>
        <div class="mt-4 refrens-detailpad">
            <div class="fw-bold mb-3">Rekomendasi lainnya</div>
            <div class="refrens-strip">
                @foreach(($recommended ?? collect())->take(8) as $p)
                    <div class="refrens-mini">
                        <div class="refrens-mini__img">
                            <a href="{{ route('shop.show', $p->slug) }}">
                                @if($p->image)
                                    <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </a>
                            <a class="refrens-mini__act" href="{{ route('shop.show', $p->slug) }}" aria-label="Open product">
                                <i class="bi bi-bag-plus"></i>
                            </a>
                        </div>
                        <div class="refrens-mini__name">{{ $p->name }}</div>
                        <div class="refrens-mini__price" data-money-idr="{{ (float) $p->price }}">Rp {{ number_format($p->price, 0, ',', '.') }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-4 refrens-detailpad">
            <div class="fw-bold mb-3">Baru Dilihat</div>
            <div class="refrens-grid">
                @foreach(($recentlyViewed ?? collect())->take(4) as $p)
                    <div class="refrens-mini">
                        <div class="refrens-mini__img">
                            <a href="{{ route('shop.show', $p->slug) }}">
                                @if($p->image)
                                    <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </a>
                            @if((int) $p->stock <= 0)
                                <div class="refrens-sold">Stok Habis</div>
                            @endif
                            <a class="refrens-mini__act" href="{{ route('shop.show', $p->slug) }}" aria-label="Open product">
                                <i class="bi bi-bag-plus"></i>
                            </a>
                        </div>
                        <div class="refrens-mini__name">{{ $p->name }}</div>
                        <div class="refrens-mini__price" data-money-idr="{{ (float) $p->price }}">Rp {{ number_format($p->price, 0, ',', '.') }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    (function () {
        const el = document.querySelector('.refrens-productswiper--main');
        if (!el || !window.Swiper) return;

        const slideCount = el.querySelectorAll('.swiper-slide').length;
        const pager = document.querySelector('[data-product-pagination]');
        const swiper = new Swiper(el, {
            direction: 'horizontal',
            loop: false,
            slidesPerView: 1,
            slidesPerGroup: 1, // Memastikan 1 swipe = 1 slide (ga bisa skip)
            spaceBetween: 0,
            speed: 450,
            followFinger: true,
            touchRatio: 0.6, // lebih kecil = ga liar
            threshold: 20, // harus geser agak jauh baru pindah
            longSwipesRatio: 0.5, // HARUS 50% baru pindah
            longSwipesMs: 300, // minimal durasi swipe
            shortSwipes: false, // penting! biar ga lompat2
            resistanceRatio: 0.6,
            touchAngle: 45,
            simulateTouch: true,
            allowTouchMove: true,
            grabCursor: true,
            watchSlidesProgress: true,
            preloadImages: true,
            updateOnImagesReady: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: pager ? { el: pager, clickable: true } : undefined,
        });

        const thumbs = document.querySelectorAll('[data-thumb-index]');
        function syncThumbs(activeIndex) {
            thumbs.forEach((t) => {
                const idx = Number(t.getAttribute('data-thumb-index') || 0);
                t.classList.toggle('is-active', idx === activeIndex);
            });
        }
        thumbs.forEach((t) => {
            t.addEventListener('click', () => {
                const idx = Number(t.getAttribute('data-thumb-index') || 0);
                swiper.slideTo(idx);
            });
        });
        swiper.on('slideChange', () => {
            const idx = swiper.activeIndex || 0;
            syncThumbs(idx);
        });
        syncThumbs(0);
    })();
</script>
@endpush
@endsection
