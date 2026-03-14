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
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        
        <!-- AOS Animation CSS -->
        <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
        <!-- Swiper CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

        <style>
            html {
                scroll-behavior: auto;
            }
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            /* Smooth transitions for interactive elements */
            a, button, input, .card, .btn {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            @if (session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                    <div class="bg-green-50 border border-green-100 text-green-800 rounded-2xl px-5 py-4 font-semibold">
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                    <div class="bg-red-50 border border-red-100 text-red-800 rounded-2xl px-5 py-4 font-semibold">
                        {{ session('error') }}
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

            @include('layouts.footer')
            @include('layouts.chat')
        </div>

        <!-- AOS Animation JS -->
        <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
        <!-- Alpine.js (Added for robustness) -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
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

        @stack('scripts')
    </body>
</html>
