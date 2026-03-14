<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body { background: #f6f7fb; overflow: hidden; }
        .admin-shell { height: 100vh; overflow: hidden; }
        .admin-brand { height: 64px; }
        .admin-sidebar { width: 260px; height: 100vh; overflow-y: auto; background: linear-gradient(180deg, #2f6fb4 0%, #2b66a8 35%, #255c9a 100%); }
        .admin-sidebar .nav-link { color: rgba(255,255,255,.92); border-radius: 14px; padding: 12px 14px; font-weight: 600; }
        .admin-sidebar .nav-link:hover { background: rgba(255,255,255,.12); color: #fff; }
        .admin-sidebar .nav-link.active { background: rgba(255,255,255,.18); box-shadow: 0 10px 24px rgba(0,0,0,.08); color: #fff; }
        .admin-sidebar .nav-link .bi { width: 22px; margin-right: 10px; font-size: 18px; }
        .admin-topbar { height: 64px; }
        .admin-main { flex: 1 1 auto; overflow-y: auto; }
        .content-card { border: 0; border-radius: 18px; box-shadow: 0 12px 30px rgba(16, 24, 40, 0.06); }
    </style>
</head>
<body>
<div class="admin-shell d-flex">
    <aside class="admin-sidebar text-white d-none d-lg-flex flex-column p-3">
        <div class="admin-brand d-flex align-items-center gap-2 px-2 mb-3">
            <div class="bg-white rounded-3 p-1">
                <img src="{{ asset('img/logo.jpeg') }}" alt="Logo" style="height:32px;width:auto;">
            </div>
            <div class="fw-bold fs-5">REFRENS</div>
        </div>

        <nav class="nav nav-pills flex-column gap-2">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-house"></i> Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                <i class="bi bi-cart3"></i> Pesanan
            </a>
            <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                <i class="bi bi-file-earmark-text"></i> Laporan
            </a>
            <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                <i class="bi bi-box-seam"></i> Stok Produk
            </a>
            <a class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}" href="{{ route('admin.messages.index') }}">
                <i class="bi bi-chat-dots"></i> Pesan
            </a>
        </nav>

        <div class="mt-auto pt-4">
            <div class="d-flex align-items-center gap-2 px-2">
                <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                    <span class="fw-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-semibold text-truncate">{{ Auth::user()->name }}</div>
                    <div class="small opacity-75">Administrator</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-light" type="submit">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="flex-grow-1 d-flex flex-column">
        <header class="admin-topbar bg-white border-bottom d-flex align-items-center">
            <div class="container-fluid d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-outline-primary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="fw-bold text-primary">Admin Panel</div>
                </div>
                <a href="{{ route('shop.index') }}" class="btn btn-sm btn-outline-secondary">Kembali ke Toko</a>
            </div>
        </header>

        <main class="container-fluid py-4 admin-main">
            @if (session('success'))
                <div class="alert alert-success content-card px-4 py-3 mb-4">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger content-card px-4 py-3 mb-4">{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="adminSidebarLabel">REFRENS</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-3">
        <div class="d-flex align-items-center gap-2 mb-3">
            <div class="bg-light rounded-3 p-1">
                <img src="{{ asset('img/logo.jpeg') }}" alt="Logo" style="height:32px;width:auto;">
            </div>
            <div class="fw-bold">Menu</div>
        </div>
        <div class="list-group list-group-flush">
            <a class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="list-group-item list-group-item-action {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">Pesanan</a>
            <a class="list-group-item list-group-item-action {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">Laporan</a>
            <a class="list-group-item list-group-item-action {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">Stok Produk</a>
            <a class="list-group-item list-group-item-action {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}" href="{{ route('admin.messages.index') }}">Pesan</a>
        </div>
        <div class="mt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-danger w-100" type="submit">Logout</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
