<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">

    <style>
        html, body { height: 100%; }
        :root{
            --bs-primary:#2563eb;
            --bs-primary-rgb:37,99,235;
            --bs-link-color:#2563eb;
            --bs-link-hover-color:#1d4ed8;
            --refrens-admin-blue:#2563eb;
            --refrens-admin-blue-dark:#1e40af;
            --refrens-admin-bg:#ffffff;
            --refrens-admin-soft:#f8fafc;
        }
        body { background: var(--refrens-admin-soft); overflow: hidden; font-family: 'Poppins', sans-serif; }
        .admin-shell { height: 100svh; overflow: hidden; }
        .admin-brand { height: 88px; }
        .admin-sidebar { width: 280px; min-width: 280px; flex: 0 0 280px; height: 100%; overflow-y: auto; background: linear-gradient(180deg, var(--refrens-admin-blue-dark) 0%, var(--refrens-admin-blue) 100%); position: relative; }
        .admin-sidebar::before{content:'';position:absolute;inset:0;background:radial-gradient(1200px 520px at 30% -12%, rgba(255,255,255,.22), transparent 55%),radial-gradient(900px 420px at 70% 18%, rgba(255,255,255,.14), transparent 58%);pointer-events:none}
        .admin-brand,.admin-sidebar nav,.admin-usercard{position:relative;z-index:1}
        .admin-menutitle{padding:0 10px 10px 10px;font-weight:900;letter-spacing:.22em;font-size:10px;color:rgba(255,255,255,.75)}
        .admin-sidebar .nav-link { color: rgba(255,255,255,.94); border-radius: 16px; padding: 12px 14px; font-weight: 800; display:flex; align-items:center; gap:10px; border:1px solid rgba(255,255,255,.14); transition: background-color .15s ease, transform .15s ease, border-color .15s ease; }
        .admin-sidebar .nav-link:hover { background: rgba(255,255,255,.12); color: #fff; transform: translateY(-1px); border-color: rgba(255,255,255,.18); }
        .admin-sidebar .nav-link.active { background: rgba(255,255,255,.18); box-shadow: 0 10px 24px rgba(0,0,0,.10); color: #fff; border-color: rgba(255,255,255,.22); }
        .admin-sidebar .nav-link .bi { width: 22px; font-size: 18px; }
        .admin-usercard{background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.18);border-radius:18px;padding:12px}
        .admin-userbadge{background:rgba(255,255,255,.22);border-radius:999px;padding:2px 8px;font-size:11px;font-weight:800}
        .admin-sidebar::-webkit-scrollbar{width:10px}
        .admin-sidebar::-webkit-scrollbar-thumb{background:rgba(255,255,255,.22);border-radius:999px;border:3px solid rgba(0,0,0,0)}
        .admin-sidebar::-webkit-scrollbar-track{background:transparent}
        .admin-topbar { height: 64px; position: sticky; top: 0; z-index: 1020; }
        .admin-main { flex: 1 1 auto; overflow-y: auto; -webkit-overflow-scrolling: touch; }
        .content-card { border: 0; border-radius: 18px; box-shadow: 0 12px 30px rgba(16, 24, 40, 0.06); }
        .admin-mobile { background: linear-gradient(180deg, var(--refrens-admin-blue-dark) 0%, var(--refrens-admin-blue) 100%); color: #fff; }
        .admin-mobile .admin-mobile-link { color: rgba(255,255,255,.94); border-radius: 16px; padding: 12px 14px; font-weight: 800; display: flex; align-items: center; gap: 10px; text-decoration: none; border:1px solid rgba(255,255,255,.14); }
        .admin-mobile .admin-mobile-link:hover { background: rgba(255,255,255,.12); color: #fff; }
        .admin-mobile .admin-mobile-link.active { background: rgba(255,255,255,.18); box-shadow: 0 10px 24px rgba(0,0,0,.10); color: #fff; border-color: rgba(255,255,255,.22); }
        .admin-mobile .admin-mobile-link .bi { font-size: 18px; width: 22px; }
        .admin-logo{display:flex;align-items:center;justify-content:center;height:100%}
        .admin-logo-text{font-family:'Archivo Black',sans-serif;letter-spacing:-0.02em;text-transform:uppercase}
        .admin-topbar .btn-outline-primary{border-color:rgba(var(--bs-primary-rgb),.35)}
        @media (min-width: 1200px){ .admin-sidebar { width: 300px; min-width: 300px; flex-basis: 300px; } }
    </style>
</head>
<body>
@php
    $adminUnreadMessages = \App\Models\Message::where('is_from_admin', false)
        ->where('is_read', false)
        ->count();
@endphp
<div class="admin-shell d-flex">
    <aside class="admin-sidebar text-white d-none d-lg-flex flex-column p-3">
        <div class="admin-brand px-2 mb-3 d-flex align-items-center justify-content-center">
            <a href="{{ route('admin.dashboard') }}" class="admin-logo-text text-white text-decoration-none" style="font-size:32px;line-height:1">REFRENS</a>
        </div>

        <div class="admin-menutitle">MENU</div>
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
            <a class="nav-link d-flex align-items-center justify-content-between {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}" href="{{ route('admin.messages.index') }}">
                <span class="d-flex align-items-center">
                    <i class="bi bi-chat-dots"></i> Pesan
                </span>
                @if($adminUnreadMessages > 0)
                    <span class="badge rounded-pill text-bg-danger">{{ $adminUnreadMessages > 99 ? '99+' : $adminUnreadMessages }}</span>
                @endif
            </a>
            <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.edit') }}">
                <i class="bi bi-gear"></i> Pengaturan
            </a>
        </nav>

        <div class="mt-auto pt-4">
            <div class="admin-usercard">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                        <span class="fw-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-semibold text-truncate">{{ Auth::user()->name }}</div>
                        <div class="admin-userbadge">Administrator</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                        @csrf
                        <button class="btn btn-sm btn-light" type="submit">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>
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
            <a href="{{ route('admin.dashboard') }}" class="admin-logo-text text-white text-decoration-none" style="font-size:26px;line-height:1">REFRENS</a>
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

        <div class="admin-menutitle">MENU</div>
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
            <a class="admin-mobile-link justify-content-between {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}" href="{{ route('admin.messages.index') }}">
                <span class="d-flex align-items-center gap-2">
                    <i class="bi bi-chat-dots"></i> Pesan
                </span>
                @if($adminUnreadMessages > 0)
                    <span class="badge rounded-pill text-bg-danger">{{ $adminUnreadMessages > 99 ? '99+' : $adminUnreadMessages }}</span>
                @endif
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
