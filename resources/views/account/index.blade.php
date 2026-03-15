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

    $openLogin = request()->boolean('login') || $hasLoginErrors;
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
    .refrens-card{border:0;border-radius:18px;box-shadow:0 12px 30px rgba(16,24,40,.06)}
    .refrens-sheet{position:fixed;inset:0;z-index:1200;display:none}
    .refrens-sheet.refrens-sheet--open{display:block}
    .refrens-sheet__backdrop{position:absolute;inset:0;background:rgba(0,0,0,.45)}
    .refrens-sheet__panel{position:absolute;left:0;right:0;bottom:0;background:#fff;border-top-left-radius:22px;border-top-right-radius:22px;max-height:86svh;overflow:auto;box-shadow:0 -18px 48px rgba(0,0,0,.18);transform:translateY(16px);opacity:0;transition:transform .18s ease,opacity .18s ease}
    .refrens-sheet.refrens-sheet--open .refrens-sheet__panel{transform:translateY(0);opacity:1}
    .refrens-sheet__handle{width:56px;height:6px;border-radius:999px;background:#e5efff;margin:10px auto}
    .refrens-pilltab{display:flex;gap:10px;border-bottom:1px solid rgba(0,0,0,.06)}
    .refrens-pilltab button{flex:1;border:0;background:transparent;padding:12px 10px;font-weight:800;font-size:12px;text-transform:uppercase;letter-spacing:.3px;color:#6b7280;border-bottom:2px solid transparent}
    .refrens-pilltab button.active{color:var(--refrens-accent);border-bottom-color:var(--refrens-accent)}
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
</style>

<div class="refrens-account py-4">
    <div class="container" x-data="{ tab: '{{ $initialTab }}', loginOpen: {{ $openLogin ? 'true' : 'false' }}, registerOpen: {{ $openRegister ? 'true' : 'false' }}, settingsOpen: {{ $hasSettingsErrors ? 'true' : 'false' }} }">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <div class="fw-bold fs-4">Akun Saya</div>
                @auth
                    <div class="text-muted small">Halo, {{ Auth::user()->name }}</div>
                @endauth
            </div>
            <div class="d-flex align-items-center gap-2">
                @auth
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" @click="settingsOpen = true">Pengaturan</button>
                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm rounded-pill">Keluar</button>
                    </form>
                @endauth
                <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">Kembali</a>
            </div>
        </div>

        @guest
            <div class="card refrens-card p-3 mb-3">
                <div class="fw-bold">Nikmati Diskon Spesial dan Pantau Pesanan Kamu</div>
                <div class="text-muted small mt-1">
                    Dapatkan diskon eksklusif sambil melacak pesanan dan percakapan kamu dengan mudah. Tetap terhubung dengan kami dan selalu tahu perkembangan pembelian kamu, semua dalam satu platform.
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="button" class="btn btn-outline-primary rounded-pill px-3" @click="loginOpen = true">Login</button>
                    <button type="button" class="btn btn-primary rounded-pill px-3" @click="registerOpen = true">Daftar</button>
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
                        $tabs = [
                            ['key' => null, 'label' => 'Semua'],
                            ['key' => 'unpaid', 'label' => 'Belum Bayar'],
                            ['key' => 'processing', 'label' => 'Diproses'],
                            ['key' => 'shipped', 'label' => 'Dikirim'],
                            ['key' => 'completed', 'label' => 'Selesai'],
                            ['key' => 'cancelled', 'label' => 'Dibatalkan'],
                        ];
                        $totalAll = array_sum($counts ?? []);
                        $countsByKey = [
                            'unpaid' => $counts['pending'] ?? 0,
                            'processing' => $counts['processing'] ?? 0,
                            'shipped' => $counts['shipped'] ?? 0,
                            'completed' => $counts['completed'] ?? 0,
                            'cancelled' => $counts['cancelled'] ?? 0,
                        ];
                    @endphp

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="fw-bold">Riwayat Pesanan</div>
                        <div class="text-muted small">{{ $orderCount }} pesanan</div>
                    </div>

                    <div class="mb-3">
                        <div class="refrens-scrolltabs">
                            @foreach($tabs as $tabOpt)
                                @php
                                    $isActive = ($statusParam ?? null) === ($tabOpt['key'] ?? null);
                                    $badgeCount = $tabOpt['key'] ? ($countsByKey[$tabOpt['key']] ?? 0) : $totalAll;
                                    $url = route('account.index', array_filter(['tab' => 'orders', 'status' => $tabOpt['key']]));
                                @endphp
                                <a href="{{ $url }}" class="btn btn-sm rounded-pill {{ $isActive ? 'btn-primary' : 'btn-outline-secondary' }}">
                                    {{ $tabOpt['label'] }}
                                    <span class="badge text-bg-light ms-2">{{ $badgeCount }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    @if($orders->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <div class="mb-2">
                                <i class="bi bi-box-seam fs-1"></i>
                            </div>
                            <div class="fw-bold text-dark">Belum ada pesanan</div>
                            <div class="small">Yuk belanja dulu biar muncul di sini.</div>
                            <div class="mt-3">
                                <a href="{{ route('shop.index') }}" class="btn btn-primary rounded-pill px-4">Mulai Belanja</a>
                            </div>
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
