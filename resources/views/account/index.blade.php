@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

@php
    $hasRegisterErrors = $errors->has('name')
        || $errors->has('email')
        || $errors->has('phone')
        || $errors->has('address')
        || ($errors->has('password') && (old('name') || old('email') || old('phone') || old('address')));

    $hasLoginErrors = $errors->has('login') || ($errors->has('password') && old('login'));

    $openLogin = (isset($forceLoginOpen) ? (bool) $forceLoginOpen : false) || request()->boolean('login') || $hasLoginErrors;
    $openRegister = request()->boolean('register') || $hasRegisterErrors;

    $orders = $orders ?? collect();
    $counts = $counts ?? [];
    $statusParam = $statusParam ?? request()->get('status');
    $orderCount = $orderCount ?? (is_countable($orders) ? count($orders) : 0);
    $wishlistItems = $wishlistItems ?? collect();

    $hasSettingsErrors = $errors->has('name')
        || $errors->has('email')
        || $errors->has('phone')
        || $errors->has('address')
        || $errors->has('current_pin')
        || $errors->has('new_pin')
        || $errors->has('new_pin_confirmation');

    $initialTab = request('tab', 'orders');
    if (!in_array($initialTab, ['orders', 'wishlist'], true)) {
        $initialTab = 'orders';
    }
@endphp

<style>
    :root{--refrens-accent:#2563eb;--refrens-accent-dark:#1d4ed8}
    .refrens-account{min-height:calc(100svh - 64px);background:#f3f4f6}
    .refrens-card{border:0;border-radius:16px;box-shadow:0 12px 30px rgba(16,24,40,.06)}
    .refrens-sheet{position:fixed;inset:0;z-index:1200;display:none}
    .refrens-sheet.refrens-sheet--open{display:block}
    .refrens-sheet__backdrop{position:absolute;inset:0;background:rgba(0,0,0,.45)}
    .refrens-sheet__panel{position:absolute;left:0;right:0;bottom:0;background:#fff;border-top-left-radius:22px;border-top-right-radius:22px;max-height:86svh;overflow:auto;box-shadow:0 -18px 48px rgba(0,0,0,.18);transform:translateY(16px);opacity:0;transition:transform .18s ease,opacity .18s ease}
    .refrens-sheet.refrens-sheet--open .refrens-sheet__panel{transform:translateY(0);opacity:1}
    .refrens-sheet__handle{width:56px;height:6px;border-radius:999px;background:#e5efff;margin:10px auto}
    .refrens-pilltab{display:flex;gap:0;border-bottom:1px solid rgba(0,0,0,.10);background:#fff}
    .refrens-pilltab button{flex:1;border:0;background:transparent;padding:14px 10px;font-weight:900;font-size:13px;color:#111827;border-bottom:2px solid transparent}
    .refrens-pilltab button.active{border-bottom-color:#111827}
    .refrens-scrolltabs{display:flex;gap:10px;overflow-x:auto;overflow-y:hidden;flex-wrap:nowrap;padding-bottom:6px;margin-bottom:-6px;-webkit-overflow-scrolling:touch;scroll-snap-type:x proximity;scrollbar-width:none}
    .refrens-scrolltabs::-webkit-scrollbar{display:none}
    .refrens-scrolltabs .btn{flex:0 0 auto;white-space:nowrap;scroll-snap-align:start}
    .refrens-ordercard{border:0;border-radius:18px;box-shadow:0 12px 30px rgba(16,24,40,.06);overflow:hidden}
    .refrens-ordercard__top{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;padding:14px 14px 10px;background:linear-gradient(180deg,#ffffff 0%,#fbfcff 100%)}
    .refrens-ordercard__meta{line-height:1.1}
    .refrens-ordercard__meta .num{font-weight:900}
    .refrens-ordercard__meta .date{font-size:12px;color:#6b7280;font-weight:700;margin-top:4px}
    .refrens-ordercard__status{font-size:11px;letter-spacing:.4px;font-weight:900;text-transform:uppercase}
    .refrens-ordercard__body{padding:10px 14px 14px;background:#fff}
    .refrens-thumbs{display:flex;gap:10px;overflow-x:auto;overflow-y:hidden;flex-wrap:nowrap;-webkit-overflow-scrolling:touch;scrollbar-width:none;padding-bottom:4px}
    .refrens-thumbs::-webkit-scrollbar{display:none}
    .refrens-thumb{width:56px;height:56px;border-radius:14px;overflow:hidden;background:#f3f4f6;border:1px solid rgba(0,0,0,.06);position:relative;flex:0 0 auto}
    .refrens-thumb img{width:100%;height:100%;object-fit:cover}
    .refrens-thumb__qty{position:absolute;top:-6px;right:-6px;min-width:18px;height:18px;padding:0 6px;border-radius:999px;background:#2563eb;color:#fff;font-weight:900;font-size:10px;display:flex;align-items:center;justify-content:center;box-shadow:0 10px 18px rgba(37,99,235,.25);border:2px solid #fff}
    .refrens-thumbmore{width:56px;height:56px;border-radius:14px;border:1px dashed rgba(0,0,0,.14);background:rgba(37,99,235,.06);display:flex;align-items:center;justify-content:center;color:#1d4ed8;font-weight:900;flex:0 0 auto}
    .refrens-ordercard__bottom{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:12px}
    .refrens-ordercard__total .label{font-size:12px;color:#6b7280;font-weight:800}
    .refrens-ordercard__total .price{font-weight:900;color:#2563eb}
    .refrens-hero{background:#fff;border:1px solid rgba(0,0,0,.06);border-radius:16px;padding:14px}
    .refrens-hero .title{font-weight:900;font-size:14px;color:#111827}
    .refrens-hero .desc{margin-top:8px;font-size:12px;line-height:1.4;color:#6b7280;font-weight:600}
    .refrens-hero .actions{margin-top:12px;display:flex;gap:10px}
    .refrens-hero .actions .btn{height:40px;border-radius:12px;font-weight:900;padding:0 18px}
    .refrens-filterrow{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-top:6px}
    .refrens-filterrow .h{font-size:18px;font-weight:900;color:#111827;margin:0}
    .refrens-filterrow .h small{font-size:16px;font-weight:900;color:#111827}
    .refrens-select{appearance:none;-webkit-appearance:none;-moz-appearance:none;background:#fff;border:1px solid rgba(0,0,0,.20);border-radius:14px;padding:12px 40px 12px 14px;font-size:13px;font-weight:800;color:#111827;min-width:200px}
    .refrens-selectwrap{position:relative}
    .refrens-selectwrap i{position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#111827;font-size:14px;pointer-events:none}
    .refrens-emptystate{padding:44px 10px;text-align:center}
    .refrens-emptystate .icon{width:92px;height:92px;margin:0 auto 10px;color:#9ca3af}
    .refrens-emptystate .t{font-size:16px;font-weight:900;color:#111827}
    .refrens-emptystate .d{margin-top:6px;font-size:12px;font-weight:700;color:#6b7280}
</style>

<div class="refrens-account py-4">
    <div class="container" x-data="{ tab: '{{ $initialTab }}', loginOpen: {{ $openLogin ? 'true' : 'false' }}, registerOpen: {{ $openRegister ? 'true' : 'false' }}, settingsOpen: {{ $hasSettingsErrors ? 'true' : 'false' }} }">
        @guest
            <div class="refrens-hero mb-3">
                <div class="title">Nikmati Diskon Spesial dan Pantau Pesanan Kamu</div>
                <div class="desc">
                    Dapatkan diskon eksklusif sambil melacak pesanan dan percakapan kamu dengan mudah. Tetap terhubung dengan kami dan selalu tahu perkembangan pembelian kamu, semua dalam satu platform.
                </div>
                <div class="actions">
                    <button type="button" class="btn btn-outline-primary" @click="loginOpen = true">Login</button>
                    <button type="button" class="btn btn-primary" @click="registerOpen = true">Daftar</button>
                </div>
            </div>
        @endguest

        <div class="card refrens-card overflow-hidden">
            <div class="refrens-pilltab">
                <button type="button" :class="tab === 'orders' ? 'active' : ''" @click="tab = 'orders'">Pesanan</button>
                <button type="button" :class="tab === 'wishlist' ? 'active' : ''" @click="tab = 'wishlist'">Wishlist</button>
            </div>

            <div class="p-3" x-show="tab === 'orders'">
                @guest
                    <div class="text-center py-5 text-muted">
                        <div class="mb-2">
                            <i class="bi bi-box-seam fs-1"></i>
                        </div>
                        <div class="fw-bold text-dark">Login dulu ya</div>
                        <div class="small">Supaya bisa lihat riwayat pesanan kamu.</div>
                        <div class="mt-3 d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-outline-primary rounded-pill px-4" @click="loginOpen = true">Login</button>
                            <button type="button" class="btn btn-primary rounded-pill px-4" @click="registerOpen = true">Daftar</button>
                        </div>
                    </div>
                @endguest
                @auth
                    @php
                        $statusOptions = [
                            ['key' => null, 'label' => 'Semua status'],
                            ['key' => 'unpaid', 'label' => 'Belum Dibayar'],
                            ['key' => 'processing', 'label' => 'Dikemas'],
                            ['key' => 'shipped', 'label' => 'Dikirim'],
                            ['key' => 'completed', 'label' => 'Selesai'],
                            ['key' => 'cancelled', 'label' => 'Dibatalkan'],
                        ];
                    @endphp

                    <div class="refrens-filterrow mb-2">
                        <div class="h">Order Saya <small>({{ $orderCount }})</small></div>
                        <div class="refrens-selectwrap">
                            <select class="refrens-select" onchange="window.location.href=this.value">
                                @foreach($statusOptions as $opt)
                                    @php
                                        $isSelected = ($statusParam ?? null) === ($opt['key'] ?? null);
                                        $url = route('account.index', array_filter(['tab' => 'orders', 'status' => $opt['key']]));
                                    @endphp
                                    <option value="{{ $url }}" {{ $isSelected ? 'selected' : '' }}>{{ $opt['label'] }}</option>
                                @endforeach
                            </select>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                    </div>

                    @if($orders->isEmpty())
                        <div class="refrens-emptystate">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 8l-9-5-9 5v10l9 5 9-5V8z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l9 5 9-5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 13v10"/>
                            </svg>
                            <div class="t">Tidak ada pesanan</div>
                            <div class="d">Silakan buat pesanan untuk melihatnya disini.</div>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($orders as $order)
                                <div class="col-12">
                                    @php
                                        $statusBadge = [
                                            'pending' => 'warning',
                                            'paid' => 'primary',
                                            'processing' => 'primary',
                                            'shipped' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                        ];
                                        $statusLabel = [
                                            'pending' => 'Belum bayar',
                                            'paid' => 'Dibayar',
                                            'processing' => 'Diproses',
                                            'shipped' => 'Dikirim',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan',
                                        ];
                                        $items = $order->items ?? collect();
                                    @endphp
                                    <div class="refrens-ordercard">
                                        <div class="refrens-ordercard__top">
                                            <div class="refrens-ordercard__meta">
                                                <div class="num">Order #{{ $order->id }}</div>
                                                <div class="date">{{ $order->created_at->format('d M Y') }}</div>
                                            </div>
                                            <span class="badge rounded-pill text-bg-{{ $statusBadge[$order->status] ?? 'secondary' }} refrens-ordercard__status">
                                                {{ $statusLabel[$order->status] ?? strtoupper($order->status) }}
                                            </span>
                                        </div>
                                        <div class="refrens-ordercard__body">
                                            <div class="refrens-thumbs">
                                                @foreach($items->take(4) as $item)
                                                    <div class="refrens-thumb">
                                                        @if($item->product && $item->product->image)
                                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                                        @else
                                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                                <i class="bi bi-image"></i>
                                                            </div>
                                                        @endif
                                                        <div class="refrens-thumb__qty">{{ $item->quantity }}</div>
                                                    </div>
                                                @endforeach
                                                @if($items->count() > 4)
                                                    <div class="refrens-thumbmore">+{{ $items->count() - 4 }}</div>
                                                @endif
                                            </div>

                                            <div class="refrens-ordercard__bottom">
                                                <div class="refrens-ordercard__total">
                                                    <div class="label">Total</div>
                                                    <div class="price" data-money-idr="{{ (float) $order->total_price }}">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                                                </div>
                                                <a href="{{ route('orders.show', $order) }}" class="btn btn-primary rounded-pill px-4">
                                                    Detail
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endauth
            </div>

            <div class="p-3" x-show="tab === 'wishlist'" style="display:none;">
                @auth
                    @if($wishlistItems->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <div class="mb-2">
                                <i class="bi bi-heart fs-1"></i>
                            </div>
                            <div class="fw-bold text-dark">Wishlist kosong</div>
                            <div class="small">Tambah wishlist dengan klik icon hati di produk.</div>
                            <div class="mt-3">
                                <a href="{{ route('shop.index') }}" class="btn btn-primary rounded-pill px-4">Cari Produk</a>
                            </div>
                        </div>
                    @else
                        <div class="d-flex flex-column gap-2">
                            @foreach($wishlistItems as $w)
                                @php $p = $w->product; @endphp
                                @if($p)
                                    <div class="border rounded-4 bg-white p-3 d-flex align-items-center gap-3">
                                        <a href="{{ route('shop.show', $p->slug) }}" class="d-block flex-shrink-0">
                                            @if($p->image)
                                                <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}" style="width:64px;height:64px;object-fit:cover;border-radius:12px;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-light text-muted" style="width:64px;height:64px;border-radius:12px;">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @endif
                                        </a>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-dark text-truncate">
                                                <a href="{{ route('shop.show', $p->slug) }}" class="text-decoration-none text-dark">{{ $p->name }}</a>
                                            </div>
                                            <div class="fw-bold text-primary" data-money-idr="{{ (float) $p->price }}">Rp {{ number_format($p->price, 0, ',', '.') }}</div>
                                        </div>
                                        <form method="POST" action="{{ route('wishlist.destroy', $p) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-secondary btn-sm rounded-pill">Hapus</button>
                                        </form>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="text-center py-5 text-muted">
                        <div class="mb-2">
                            <i class="bi bi-heart fs-1"></i>
                        </div>
                        <div class="fw-bold text-dark">Login dulu ya</div>
                        <div class="small">Supaya wishlist kamu bisa tersimpan.</div>
                        <div class="mt-3 d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-outline-primary rounded-pill px-4" @click="loginOpen = true">Login</button>
                            <button type="button" class="btn btn-primary rounded-pill px-4" @click="registerOpen = true">Daftar</button>
                        </div>
                    </div>
                @endauth
            </div>
        </div>

        @auth
            <div class="refrens-sheet" :class="settingsOpen ? 'refrens-sheet--open' : ''" x-show="settingsOpen" x-cloak @keydown.escape.window="settingsOpen = false">
                <div class="refrens-sheet__backdrop" @click="settingsOpen = false"></div>
                <div class="refrens-sheet__panel">
                    <div class="px-3">
                        <div class="refrens-sheet__handle"></div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="fw-bold fs-5">Pengaturan Akun</div>
                            <button type="button" class="btn btn-sm btn-light rounded-circle" @click="settingsOpen = false" aria-label="Close">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>

                        <div class="fw-bold mb-2">Data Diri</div>
                        <form method="POST" action="{{ route('account.profile.update') }}" class="pb-3">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama</label>
                                <input name="name" type="text" value="{{ old('name', Auth::user()?->name) }}" class="form-control form-control-lg @error('name') is-invalid @enderror" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input name="email" type="email" value="{{ old('email', Auth::user()?->email) }}" class="form-control form-control-lg @error('email') is-invalid @enderror" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nomor HP</label>
                                <input name="phone" type="text" value="{{ old('phone', Auth::user()?->phone) }}" class="form-control form-control-lg @error('phone') is-invalid @enderror" required>
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Alamat</label>
                                <textarea name="address" rows="3" class="form-control form-control-lg @error('address') is-invalid @enderror" required>{{ old('address', Auth::user()?->address) }}</textarea>
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold">Simpan Data</button>
                        </form>

                        <div class="fw-bold mb-2">Ubah PIN</div>
                        <form method="POST" action="{{ route('account.pin.update') }}" class="pb-4">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="form-label fw-semibold">PIN Lama</label>
                                <input name="current_pin" type="password" inputmode="numeric" pattern="[0-9]*" class="form-control form-control-lg @error('current_pin') is-invalid @enderror" required>
                                @error('current_pin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">PIN Baru (6 digit)</label>
                                <input name="new_pin" type="password" inputmode="numeric" pattern="[0-9]*" maxlength="6" class="form-control form-control-lg @error('new_pin') is-invalid @enderror" required>
                                @error('new_pin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Konfirmasi PIN Baru</label>
                                <input name="new_pin_confirmation" type="password" inputmode="numeric" pattern="[0-9]*" maxlength="6" class="form-control form-control-lg @error('new_pin_confirmation') is-invalid @enderror" required>
                                @error('new_pin_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="btn btn-outline-primary btn-lg w-100 rounded-pill fw-bold">Ubah PIN</button>
                        </form>
                    </div>
                </div>
            </div>
        @endauth

        <div class="refrens-sheet" :class="loginOpen ? 'refrens-sheet--open' : ''" x-show="loginOpen" x-cloak @keydown.escape.window="loginOpen = false">
            <div class="refrens-sheet__backdrop" @click="loginOpen = false"></div>
            <div class="refrens-sheet__panel">
                <div class="px-3">
                    <div class="refrens-sheet__handle"></div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-bold fs-5">Login</div>
                        <button type="button" class="btn btn-sm btn-light rounded-circle" @click="loginOpen = false" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <form action="{{ route('login') }}" method="POST" class="pb-4">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ url('/') }}">

                        <div class="mb-3">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                                <input name="login" type="text" value="{{ old('login') }}" class="form-control @error('login') is-invalid @enderror" placeholder="Email/Nomor HP kamu" required>
                            </div>
                            @error('login') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white"><i class="bi bi-key"></i></span>
                                <input name="password" type="password" inputmode="numeric" pattern="[0-9]*" maxlength="6" class="form-control @error('password') is-invalid @enderror" placeholder="PIN (6 digit)" required>
                            </div>
                            @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold">Masuk</button>

                        <div class="text-center small text-muted mt-3">
                            Tidak punya akun? <button type="button" class="btn btn-link p-0 align-baseline" @click="loginOpen = false; registerOpen = true">Daftar di sini</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="refrens-sheet" :class="registerOpen ? 'refrens-sheet--open' : ''" x-show="registerOpen" x-cloak @keydown.escape.window="registerOpen = false">
            <div class="refrens-sheet__backdrop" @click="registerOpen = false"></div>
            <div class="refrens-sheet__panel">
                <div class="px-3">
                    <div class="refrens-sheet__handle"></div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-bold fs-5">Daftar</div>
                        <button type="button" class="btn btn-sm btn-light rounded-circle" @click="registerOpen = false" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <form action="{{ route('register') }}" method="POST" class="pb-4">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ url('/') }}">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama</label>
                            <input name="name" type="text" value="{{ old('name') }}" class="form-control form-control-lg @error('name') is-invalid @enderror" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input name="email" type="email" value="{{ old('email') }}" class="form-control form-control-lg @error('email') is-invalid @enderror" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor HP</label>
                            <input name="phone" type="text" value="{{ old('phone') }}" class="form-control form-control-lg @error('phone') is-invalid @enderror" required>
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea name="address" rows="3" class="form-control form-control-lg @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">PIN (6 digit)</label>
                            <input name="password" type="password" inputmode="numeric" pattern="[0-9]*" maxlength="6" class="form-control form-control-lg @error('password') is-invalid @enderror" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold">Buat Akun</button>
                        <div class="text-center small text-muted mt-3">
                            Sudah punya akun? <button type="button" class="btn btn-link p-0 align-baseline" @click="registerOpen = false; loginOpen = true">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
