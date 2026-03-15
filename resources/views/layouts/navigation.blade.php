<nav x-data="{
        open: false,
        localeOpen: false,
        searchOpen: false,
        searchQuery: '',
        searchLoading: false,
        searchResults: [],
        recentSearches: [],
        searchDebounce: null,
        shippingCountry: 'Indonesia',
        language: 'Bahasa',
        currency: 'IDR - Indonesian Rupiah',
        init() {
            const saved = localStorage.getItem('refrens_locale');
            if (saved) {
                try {
                    const data = JSON.parse(saved);
                    if (data.shippingCountry) this.shippingCountry = data.shippingCountry;
                    if (data.language) this.language = data.language;
                    if (data.currency) this.currency = data.currency;
                } catch (e) {}
            }

            this.loadRecentSearches();
            this.$watch('searchQuery', (val) => {
                const v = String(val || '').trim();
                if (this.searchDebounce) clearTimeout(this.searchDebounce);
                if (!this.searchOpen) return;
                if (v.length === 0) {
                    this.searchResults = [];
                    return;
                }
                this.searchDebounce = setTimeout(() => {
                    this.fetchSearch(v);
                }, 250);
            });
        },
        openSearch() {
            this.open = false;
            this.localeOpen = false;
            this.searchOpen = true;
            this.searchQuery = '';
            this.searchResults = [];
            this.loadRecentSearches();
            this.$nextTick(() => {
                if (this.$refs?.searchInput) this.$refs.searchInput.focus();
            });
        },
        closeSearch() {
            this.searchOpen = false;
            this.searchQuery = '';
            this.searchResults = [];
            this.searchLoading = false;
        },
        clearSearch() {
            this.searchQuery = '';
            this.searchResults = [];
            this.$nextTick(() => {
                if (this.$refs?.searchInput) this.$refs.searchInput.focus();
            });
        },
        loadRecentSearches() {
            try {
                const raw = localStorage.getItem('refrens_recent_searches');
                this.recentSearches = raw ? JSON.parse(raw) : [];
                if (!Array.isArray(this.recentSearches)) this.recentSearches = [];
            } catch (e) {
                this.recentSearches = [];
            }
        },
        saveRecentSearch(q) {
            const v = String(q || '').trim();
            if (!v) return;
            const list = [v, ...(this.recentSearches || [])].filter((x, i, arr) => x && arr.indexOf(x) === i).slice(0, 8);
            this.recentSearches = list;
            localStorage.setItem('refrens_recent_searches', JSON.stringify(list));
        },
        goSearch(q) {
            const v = String(q || this.searchQuery || '').trim();
            if (!v) return;
            this.saveRecentSearch(v);
            window.location.href = `{{ route('shop.index') }}?q=${encodeURIComponent(v)}`;
        },
        async fetchSearch(q) {
            this.searchLoading = true;
            try {
                const res = await fetch(`{{ route('shop.search') }}?q=${encodeURIComponent(q)}`, { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                this.searchResults = Array.isArray(data.results) ? data.results : [];
                window.dispatchEvent(new CustomEvent('refrens:ui-refresh'));
            } catch (e) {
                this.searchResults = [];
            } finally {
                this.searchLoading = false;
            }
        },
        openLocale() {
            this.localeOpen = true;
        },
        closeLocale() {
            this.localeOpen = false;
        },
        saveLocale() {
            localStorage.setItem('refrens_locale', JSON.stringify({
                shippingCountry: this.shippingCountry,
                language: this.language,
                currency: this.currency
            }));
            window.dispatchEvent(new CustomEvent('refrens:locale-updated'));
            this.closeLocale();
        },
        resetLocale() {
            this.shippingCountry = 'Indonesia';
            this.language = 'Bahasa';
            this.currency = 'IDR - Indonesian Rupiah';
            this.saveLocale();
        },
        currencyCode() {
            return (this.currency || '').split(' - ')[0] || 'IDR';
        }
    }"
    x-init="init()"
    class="bg-white/75 backdrop-blur-md sticky top-0 z-50 border-b border-gray-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Left: Hamburger Menu & Logo -->
            <div class="flex items-center gap-2">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-900 hover:text-black focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <a href="{{ url('/') }}" class="flex-shrink-0">
                    <img class="h-16 w-auto object-contain" src="{{ asset('img/logo.jpeg') }}" alt="REFRENS">
                </a>
            </div>

            <!-- Right: Search, Cart, User Icons -->
            <div class="flex items-center space-x-5">
                <!-- Search Icon -->
                <button type="button" @click="openSearch()" class="text-gray-900 hover:text-black focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                <!-- Cart Icon -->
                <a href="{{ route('cart.index') }}" class="text-gray-900 hover:text-black relative p-1 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    @auth
                        <span class="absolute -top-1 -right-1 bg-blue-600 text-white text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center">
                            {{ \App\Models\Cart::where('user_id', Auth::id())->sum('quantity') }}
                        </span>
                    @endauth
                </a>

                <a href="{{ route('chat.index') }}" class="text-gray-900 hover:text-black relative p-1 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h6m8-1c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    @auth
                        @php
                            $unreadChat = \App\Models\Message::where('user_id', Auth::id())
                                ->where('is_from_admin', true)
                                ->where('is_read', false)
                                ->count();
                        @endphp
                        @if($unreadChat > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full h-4 min-w-4 px-1 flex items-center justify-center">
                                {{ $unreadChat > 9 ? '9+' : $unreadChat }}
                            </span>
                        @endif
                    @endauth
                </a>

                <!-- User Profile Icon -->
                @auth
                    <div class="relative" x-data="{ userMenuOpen: false }" @click.outside="userMenuOpen = false">
                        <button @click="userMenuOpen = ! userMenuOpen" class="text-gray-900 hover:text-black focus:outline-none flex items-center">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </button>
                        <div x-show="userMenuOpen" 
                             class="absolute right-0 mt-3 w-48 bg-white rounded-lg shadow-xl border border-gray-100 py-2 z-50"
                             style="display: none;"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100">
                            <div class="px-4 py-2 border-b border-gray-50 mb-1">
                                <p class="text-xs text-gray-400">Signed in as</p>
                                <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            </div>
                            <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                Riwayat Pesanan
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-900 hover:text-black focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <div x-show="searchOpen" x-cloak class="fixed inset-0 z-[90]" @keydown.escape.window="closeSearch()">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-[2px]" @click="closeSearch()"></div>
        <div class="fixed inset-x-0 top-0 bg-white shadow-2xl">
            <div class="max-w-3xl mx-auto px-4 py-4">
                <div class="flex items-center gap-3">
                    <button type="button" @click="closeSearch()" class="text-gray-500 hover:text-gray-900">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </button>
                    <div class="flex-1 relative">
                        <input x-ref="searchInput" x-model="searchQuery" @keydown.enter.prevent="goSearch()" type="text" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 pr-20 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-blue-600 focus:border-blue-600" placeholder="Cari produk (misal: Boxy Tee)...">
                        <button type="button" x-show="searchQuery" x-cloak @click="clearSearch()" class="absolute right-11 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <button type="button" @click="goSearch()" class="absolute right-2 top-1/2 -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl px-3 py-2 text-xs font-black uppercase tracking-wider">
                            Cari
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="min-h-screen pt-24 pb-10 px-4">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                        <div class="text-sm font-black text-gray-900">Hasil Pencarian</div>
                        <div x-show="searchLoading" class="text-xs font-bold text-gray-400">Loading...</div>
                    </div>

                    <div x-show="!searchQuery && recentSearches.length" class="p-5 border-b border-gray-100">
                        <div class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Terakhir dicari</div>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="q in recentSearches" :key="q">
                                <button type="button" @click="goSearch(q)" class="px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold">
                                    <span x-text="q"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div x-show="searchQuery && searchResults.length === 0 && !searchLoading" class="p-8 text-center text-sm text-gray-500">
                        Produk tidak ditemukan. Coba kata kunci lain.
                    </div>

                    <div x-show="searchResults.length" class="divide-y divide-gray-50">
                        <template x-for="p in searchResults" :key="p.id">
                            <a :href="`/shop/${p.slug}`" class="block p-5 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="h-14 w-14 rounded-2xl overflow-hidden bg-gray-100 border border-gray-100 flex-shrink-0">
                                        <template x-if="p.image">
                                            <img :src="`/storage/${p.image}`" :alt="p.name" class="h-full w-full object-cover">
                                        </template>
                                        <template x-if="!p.image">
                                            <div class="h-full w-full flex items-center justify-center text-gray-300">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-black text-gray-900 truncate" x-text="p.name"></div>
                                        <div class="text-xs text-gray-500 mt-1">Klik untuk lihat detail</div>
                                    </div>
                                    <div class="text-sm font-black text-blue-600 text-right" :data-money-idr="Number(p.price) || 0" x-text="`Rp ${Number(p.price || 0).toLocaleString('id-ID')}`"></div>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Sidebar / Overlay -->
    <div x-show="open" 
         class="fixed inset-0 z-[60]" 
         style="display: none;">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/30 backdrop-blur-[2px]" @click="open = false"
             x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>
        
        <!-- Sidebar Content -->
        <div class="fixed inset-y-0 left-0 w-[280px] bg-white shadow-2xl transform transition-transform duration-300 ease-in-out"
             :class="open ? 'translate-x-0' : '-translate-x-full'"
             @click.stop>

            <div class="flex flex-col h-full">
                <div class="flex items-center justify-between p-6 border-b border-gray-100">
                    <button type="button" @click="openSearch()" class="text-gray-900 hover:text-black focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                    <span class="text-xl font-bold tracking-tight text-gray-900">REFRENS</span>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 py-3 space-y-1">
                    <a href="{{ url('/') }}" 
                       class="group flex items-center gap-3 px-6 py-3 text-base font-semibold transition-colors rounded-xl border-l-4 {{ request()->is('/') ? 'border-blue-600 text-blue-600 bg-blue-50' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 {{ request()->is('/') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Home
                    </a>
                    <a href="{{ route('shop.index') }}" 
                       class="group flex items-center gap-3 px-6 py-3 text-base font-semibold transition-colors rounded-xl border-l-4 {{ request()->routeIs('shop.*') ? 'border-blue-600 text-blue-600 bg-blue-50' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 {{ request()->routeIs('shop.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Shop
                    </a>

                    @auth
                        <a href="{{ route('orders.index') }}" class="group flex items-center gap-3 px-6 py-3 text-base font-semibold transition-colors rounded-xl border-l-4 {{ request()->routeIs('orders.*') ? 'border-blue-600 text-blue-600 bg-blue-50' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="h-5 w-5 {{ request()->routeIs('orders.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 8h10M7 12h10M7 16h6M5 6a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6z"/>
                            </svg>
                            My Orders
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="group w-full flex items-center gap-3 px-6 py-3 text-base font-semibold transition-colors rounded-xl text-red-600 hover:bg-red-50">
                                <svg class="h-5 w-5 text-red-500 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 11-4 0v-1m0-10V5a2 2 0 114 0v1"/>
                                </svg>
                                Sign Out
                            </button>
                        </form>
                    @else
                        <div class="px-6 pt-3">
                            <a href="{{ route('login') }}" class="flex items-center justify-center w-full px-4 py-3 bg-gray-900 text-white font-semibold rounded-xl hover:bg-black transition-colors shadow-lg shadow-gray-200">
                                Sign In / Register
                            </a>
                        </div>
                    @endauth
                </div>

                <div class="border-t border-gray-100 p-4">
                    <button type="button" @click="openLocale()" class="w-full flex items-center justify-between px-4 py-3 rounded-xl hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-6 rounded-md overflow-hidden shadow-sm ring-1 ring-black/10 bg-white">
                                <template x-if="shippingCountry === 'Indonesia'">
                                    <div class="w-full h-full flex flex-col">
                                        <div class="h-1/2 bg-red-600"></div>
                                        <div class="h-1/2 bg-white"></div>
                                    </div>
                                </template>
                                <template x-if="shippingCountry !== 'Indonesia'">
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3a9 9 0 100 18 9 9 0 000-18zM3.6 9h16.8M3.6 15h16.8M12 3c2.4 2.25 3.6 5.25 3.6 9s-1.2 6.75-3.6 9c-2.4-2.25-3.6-5.25-3.6-9s1.2-6.75 3.6-9z" />
                                        </svg>
                                    </div>
                                </template>
                            </div>
                            <div class="flex flex-col leading-tight">
                                <span class="text-sm font-bold text-gray-900" x-text="currencyCode()"></span>
                                <span class="text-[11px] text-gray-500 font-medium" x-text="shippingCountry"></span>
                            </div>
                        </div>
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-show="localeOpen" x-cloak class="fixed inset-0 z-[80]" @keydown.escape.window="closeLocale()">
        <div class="fixed inset-0 bg-black/40" @click="closeLocale()"></div>
        <div class="fixed inset-x-0 bottom-0 bg-white rounded-t-3xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-white/15 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3a9 9 0 100 18 9 9 0 000-18zM3.6 9h16.8M3.6 15h16.8M12 3c2.4 2.25 3.6 5.25 3.6 9s-1.2 6.75-3.6 9c-2.4-2.25-3.6-5.25-3.6-9s1.2-6.75 3.6-9z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-black tracking-wide">Pengaturan</div>
                            <div class="text-xs text-blue-100">Negara, bahasa, dan mata uang</div>
                        </div>
                    </div>
                    <button type="button" @click="closeLocale()" class="text-white/90 hover:text-white transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-5">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                    <div class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Dikirim ke</div>
                    <select x-model="shippingCountry" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        <option value="Indonesia">Indonesia</option>
                        <option value="Malaysia">Malaysia</option>
                        <option value="Singapore">Singapore</option>
                    </select>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                    <div class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Language</div>
                    <select x-model="language" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        <option value="Bahasa">Bahasa</option>
                        <option value="English">English</option>
                    </select>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                    <div class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Mata Uang</div>
                    <select x-model="currency" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        <option value="IDR - Indonesian Rupiah">IDR - Indonesian Rupiah</option>
                        <option value="MYR - Malaysian Ringgit">MYR - Malaysian Ringgit</option>
                        <option value="SGD - Singapore Dollar">SGD - Singapore Dollar</option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button type="button" @click="resetLocale()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-4 rounded-2xl transition-colors">
                        Reset
                    </button>
                    <button type="button" @click="saveLocale()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl transition-colors">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>
