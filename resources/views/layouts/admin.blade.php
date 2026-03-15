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
        html, body { height: 100%; }
        body { background: #f6f7fb; overflow: hidden; }
        .admin-shell { height: 100svh; overflow: hidden; }
        .admin-brand { height: 88px; }
        .admin-sidebar { width: 280px; min-width: 280px; flex: 0 0 280px; height: 100%; overflow-y: auto; background: linear-gradient(180deg, #2f6fb4 0%, #2b66a8 35%, #255c9a 100%); }
        .admin-sidebar .nav-link { color: rgba(255,255,255,.92); border-radius: 14px; padding: 12px 14px; font-weight: 600; }
        .admin-sidebar .nav-link:hover { background: rgba(255,255,255,.12); color: #fff; }
        .admin-sidebar .nav-link.active { background: rgba(255,255,255,.18); box-shadow: 0 10px 24px rgba(0,0,0,.08); color: #fff; }
        .admin-sidebar .nav-link .bi { width: 22px; margin-right: 10px; font-size: 18px; }
        .admin-topbar { height: 64px; position: sticky; top: 0; z-index: 1020; }
        .admin-main { flex: 1 1 auto; overflow-y: auto; -webkit-overflow-scrolling: touch; }
        .content-card { border: 0; border-radius: 18px; box-shadow: 0 12px 30px rgba(16, 24, 40, 0.06); }
        .admin-mobile { background: linear-gradient(180deg, #2f6fb4 0%, #2b66a8 35%, #255c9a 100%); color: #fff; }
        .admin-mobile .admin-mobile-link { color: rgba(255,255,255,.92); border-radius: 14px; padding: 12px 14px; font-weight: 700; display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .admin-mobile .admin-mobile-link:hover { background: rgba(255,255,255,.12); color: #fff; }
        .admin-mobile .admin-mobile-link.active { background: rgba(255,255,255,.18); box-shadow: 0 10px 24px rgba(0,0,0,.08); color: #fff; }
        .admin-mobile .admin-mobile-link .bi { font-size: 18px; width: 22px; }
        .admin-logo{display:flex;align-items:center;justify-content:center;height:100%}
        .admin-logo img{width:160px;max-width:100%;height:auto;filter:drop-shadow(0 12px 22px rgba(0,0,0,.18))}
        @media (min-width: 1200px){ .admin-sidebar { width: 300px; min-width: 300px; flex-basis: 300px; } }
    </style>
</head>
<body>
<div class="admin-shell d-flex">
    <aside class="admin-sidebar text-white d-none d-lg-flex flex-column p-3">
        <div class="admin-brand px-2 mb-3">
            <div class="admin-logo">
                <img src="{{ asset('img/logo.png') }}" alt="Logo">
            </div>
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
            <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.edit') }}">
                <i class="bi bi-gear"></i> Pengaturan
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
                <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
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
    <div class="offcanvas-header admin-mobile border-bottom border-white border-opacity-10">
        <div class="w-100 d-flex justify-content-center">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height:36px;width:auto;">
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body admin-mobile p-3">
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:44px;height:44px;">
                <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
            </div>
            <div class="flex-grow-1">
                <div class="fw-bold text-truncate">{{ Auth::user()->name }}</div>
                <div class="small opacity-75">Administrator</div>
            </div>
        </div>

        <div class="d-flex flex-column gap-2">
            <a class="admin-mobile-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-house"></i> Dashboard
            </a>
            <a class="admin-mobile-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                <i class="bi bi-cart3"></i> Pesanan
            </a>
            <a class="admin-mobile-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                <i class="bi bi-file-earmark-text"></i> Laporan
            </a>
            <a class="admin-mobile-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                <i class="bi bi-box-seam"></i> Stok Produk
            </a>
            <a class="admin-mobile-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}" href="{{ route('admin.messages.index') }}">
                <i class="bi bi-chat-dots"></i> Pesan
            </a>
            <a class="admin-mobile-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.edit') }}">
                <i class="bi bi-gear"></i> Pengaturan
            </a>
        </div>

        <div class="mt-4 pt-3 border-top border-white border-opacity-10">
            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                @csrf
                <button class="btn btn-light w-100 fw-bold" type="submit">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
