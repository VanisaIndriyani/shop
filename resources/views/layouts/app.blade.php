<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
        
        <!-- AOS Animation CSS -->
        <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
        <!-- Swiper CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

        <style>
            html {
                scroll-behavior: auto;
            }
            :root{
                --bs-primary:#2563eb;
                --bs-primary-rgb:37,99,235;
                --bs-link-color:#2563eb;
                --bs-link-hover-color:#1d4ed8;
            }
            body {
                font-family: 'Poppins', sans-serif;
            }
            .font-sans{font-family:'Poppins',sans-serif !important}
            [x-cloak] { display: none !important; }
            /* Smooth transitions for interactive elements */
            a, button, input, .card, .btn {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .refrens-toast{position:fixed;left:50%;top:calc(70px + env(safe-area-inset-top));transform:translateX(-50%);z-index:99999;max-width:min(560px,calc(100vw - 24px))}
            .refrens-toast__panel{display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:14px;box-shadow:0 18px 40px rgba(16,24,40,.14);border:1px solid rgba(0,0,0,.06);background:#fff}
            .refrens-toast__dot{width:10px;height:10px;border-radius:999px;flex:0 0 auto}
            .refrens-toast__msg{font-weight:700;font-size:13px;line-height:1.25;color:#111827}
            .refrens-toast__close{border:0;background:transparent;width:32px;height:32px;border-radius:999px;display:flex;align-items:center;justify-content:center;color:#6b7280}
            .refrens-toast__close:hover{background:rgba(0,0,0,.06);color:#111827}
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')
            <div class="{{ request()->is('/') ? '' : 'pt-16' }}">

            @php
                $flashSuccess = session('success');
                $flashError = session('error');
                $flashMessage = $flashSuccess ?: $flashError;
                $flashType = $flashSuccess ? 'success' : ($flashError ? 'error' : null);
            @endphp

            @if($flashMessage)
                <div id="refrensToast" class="refrens-toast" data-type="{{ $flashType }}">
                    <div class="refrens-toast__panel">
                        <span class="refrens-toast__dot" style="background:{{ $flashType === 'success' ? '#16a34a' : '#dc2626' }}"></span>
                        <div class="refrens-toast__msg">{{ $flashMessage }}</div>
                        <button type="button" class="refrens-toast__close" aria-label="Close" onclick="(function(){var t=document.getElementById('refrensToast'); if(t) t.remove();})()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow" data-aos="fade-down" data-aos-duration="800">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot ?? '' }}
                @yield('content')
            </main>

            @unless (request()->routeIs('chat.index'))
                @include('layouts.footer')
            @endunless
            @include('layouts.chat')
            </div>
        </div>

        <!-- AOS Animation JS -->
        <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
        
        <!-- Swiper JS -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        
        <script>
            // Initialize AOS
            AOS.init({
                duration: 800,
                once: true,
                offset: 100,
                easing: 'ease-out-cubic'
            });
        </script>

        <script>
            (function () {
                function readLocale() {
                    try {
                        const saved = localStorage.getItem('refrens_locale');
                        if (!saved) return { shippingCountry: 'Indonesia', language: 'Bahasa', currency: 'IDR - Indonesian Rupiah' };
                        const data = JSON.parse(saved);
                        return {
                            shippingCountry: data.shippingCountry || 'Indonesia',
                            language: data.language || 'Bahasa',
                            currency: data.currency || 'IDR - Indonesian Rupiah'
                        };
                    } catch (e) {
                        return { shippingCountry: 'Indonesia', language: 'Bahasa', currency: 'IDR - Indonesian Rupiah' };
                    }
                }

                function getCurrencyMeta(currencyValue) {
                    const code = String(currencyValue || '').split(' - ')[0] || 'IDR';
                    if (code === 'USD') return { code: 'USD', locale: 'en-US', symbol: '$', rateFromIdr: 1 / 16000 };
                    if (code === 'EUR') return { code: 'EUR', locale: 'de-DE', symbol: '€', rateFromIdr: 1 / 17500 };
                    if (code === 'THB') return { code: 'THB', locale: 'th-TH', symbol: '฿', rateFromIdr: 1 / 450 };
                    if (code === 'MYR') return { code: 'MYR', locale: 'ms-MY', symbol: 'RM', rateFromIdr: 1 / 3300 };
                    if (code === 'SGD') return { code: 'SGD', locale: 'en-SG', symbol: 'S$', rateFromIdr: 1 / 11500 };
                    return { code: 'IDR', locale: 'id-ID', symbol: 'Rp', rateFromIdr: 1 };
                }

                function formatMoney(amount, meta, language) {
                    const isId = String(language || '').toLowerCase().includes('bahasa');
                    const locale = meta.code === 'IDR' ? (isId ? 'id-ID' : 'en-US') : meta.locale;
                    try {
                        if (meta.code === 'IDR') {
                            return meta.symbol + ' ' + new Intl.NumberFormat(locale, { maximumFractionDigits: 0 }).format(amount);
                        }
                        return new Intl.NumberFormat(locale, { style: 'currency', currency: meta.code, maximumFractionDigits: 0 }).format(amount);
                    } catch (e) {
                        return meta.symbol + ' ' + Math.round(amount);
                    }
                }

                function applyMoney(localeData) {
                    const meta = getCurrencyMeta(localeData.currency);
                    const nodes = document.querySelectorAll('[data-money-idr]');
                    nodes.forEach((el) => {
                        const idr = Number(el.getAttribute('data-money-idr') || 0);
                        const converted = idr * meta.rateFromIdr;
                        el.textContent = formatMoney(converted, meta, localeData.language);
                    });
                }

                function applyAll() {
                    const localeData = readLocale();
                    applyMoney(localeData);
                }

                window.addEventListener('refrens:locale-updated', function () {
                    applyAll();
                });
                window.addEventListener('refrens:ui-refresh', function () {
                    applyAll();
                });

                document.addEventListener('DOMContentLoaded', function () {
                    applyAll();
                });
            })();
        </script>

        <script>
            (function () {
                function load(src) {
                    return new Promise((resolve, reject) => {
                        const s = document.createElement('script');
                        s.src = src;
                        s.defer = true;
                        s.onload = () => resolve(true);
                        s.onerror = () => reject(new Error('failed: ' + src));
                        document.head.appendChild(s);
                    });
                }

                function ensureAlpine() {
                    if (window.Alpine) return;
                    if (window.__refrensAlpineFallback) return;
                    window.__refrensAlpineFallback = true;

                    load('https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.14.9/dist/cdn.min.js')
                        .then(() => load('https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js'))
                        .then(() => {
                            try {
                                if (window.Alpine && typeof window.Alpine.start === 'function') {
                                    window.Alpine.start();
                                }
                            } catch (e) {}
                        })
                        .catch(() => {});
                }

                window.setTimeout(ensureAlpine, 1200);
            })();
        </script>

        @stack('scripts')

        <script>
            (function () {
                const toast = document.getElementById('refrensToast');
                if (!toast) return;
                window.setTimeout(function () {
                    if (toast && toast.parentNode) toast.remove();
                }, 2200);
            })();
        </script>
    </body>
</html>
