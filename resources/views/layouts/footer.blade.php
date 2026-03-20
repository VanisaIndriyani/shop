<!-- Accordion Footer Section (Preface Style) -->
<footer class="bg-blue-600 text-white py-4 w-full z-40 relative" x-data="{
    language: 'Bahasa',
    init() {
        try {
            const saved = localStorage.getItem('refrens_locale');
            if (saved) {
                const data = JSON.parse(saved);
                if (data && data.language) this.language = data.language;
            }
        } catch (e) {}
        window.addEventListener('refrens:locale-updated', () => {
            try {
                const saved = localStorage.getItem('refrens_locale');
                if (!saved) return;
                const data = JSON.parse(saved);
                if (data && data.language) this.language = data.language;
            } catch (e) {}
        });
    },
    isEnglish() {
        return String(this.language || '').toLowerCase().includes('english');
    },
    t(key) {
        const en = this.isEnglish();
        const map = {
            about: en ? 'About Us' : 'Tentang Kami',
            account: en ? 'Account' : 'Akun',
            payment: en ? 'Payment' : 'Pembayaran',
            shipping: en ? 'Shipping' : 'Pengiriman',
            hi: en ? 'Hi,' : 'Hi,',
            orderHistory: en ? 'Order History' : 'Riwayat Pesanan',
            login: en ? 'Login' : 'Login',
            register: en ? 'Register' : 'Daftar',
            logout: en ? 'Logout' : 'Keluar',
        };
        return map[key] || key;
    }
}" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- MOBILE: Accordion (Hidden on Desktop) -->
        <div class="md:hidden">
            <!-- ABOUT US -->
            <div x-data="{ open: false }" class="border-b border-blue-500/50">
                <button @click="open = !open" class="flex justify-between items-center w-full py-3 text-left font-bold text-xs tracking-wider uppercase focus:outline-none">
                    <span x-text="t('about')"></span>
                    <svg :class="{'rotate-180': open}" class="h-3 w-3 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="pb-3 text-xs text-blue-100 space-y-2">
                    <div class="flex space-x-4 mt-2 pt-2 border-t border-blue-500/30">
                        <a href="https://www.instagram.com/refrens.co" target="_blank" class="text-white hover:text-blue-200">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 A4.902 4.902 0 015.468 2.53c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" /></svg>
                        </a>
                        <a href="https://www.tiktok.com/@refrens.co" target="_blank" class="text-white hover:text-blue-200">
                            <span class="sr-only">TikTok</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.65-1.55-1.1-.06-.06-.11-.13-.17-.19v6.52c-.05 2.97-2.02 5.59-4.91 6.52-2.91.93-6.14.07-8.15-2.15-2.01-2.23-2.33-5.54-.8-8.1 1.53-2.56 4.49-3.9 7.42-3.32v4.06c-1.39-.46-2.95-.08-3.99.98-1.04 1.05-1.3 2.64-.66 3.97.64 1.34 2.15 2.06 3.6 1.72 1.45-.34 2.5-1.63 2.52-3.12V.02z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- ACCOUNT -->
            <div x-data="{ open: false }" class="border-b border-blue-500/50">
                <button @click="open = !open" class="flex justify-between items-center w-full py-3 text-left font-bold text-xs tracking-wider uppercase focus:outline-none">
                    <span x-text="t('account')"></span>
                    <svg :class="{'rotate-180': open}" class="h-3 w-3 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="pb-3 space-y-2">
                    @auth
                        <div class="flex flex-col space-y-2">
                            <div class="text-[11px] text-blue-100 italic"><span x-text="t('hi')"></span> {{ Auth::user()->name }}</div>
                            <a href="{{ route('account.index', ['tab' => 'orders']) }}" class="text-xs text-white hover:text-blue-200 block"><span x-text="t('orderHistory')"></span></a>
                            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                                @csrf
                                <button type="submit" class="text-xs text-red-300 hover:text-red-200 block w-full text-left" x-text="t('logout')"></button>
                            </form>
                        </div>
                    @else
                        <div class="flex space-x-2 mt-2">
                            <a href="{{ route('account.index', ['login' => 1]) }}" class="flex-1 text-center py-2 border border-white rounded-lg text-xs font-bold text-white hover:bg-white hover:text-blue-600 transition-colors">
                                <span x-text="t('login')"></span>
                            </a>
                            <a href="{{ route('account.index', ['register' => 1]) }}" class="flex-1 text-center py-2 bg-white rounded-lg text-xs font-bold text-blue-600 hover:bg-blue-50 transition-colors">
                                <span x-text="t('register')"></span>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Metode Pembayaran -->
            <div x-data="{ open: false }" class="border-b border-blue-500/50">
                <button @click="open = !open" class="flex justify-between items-center w-full py-3 text-left font-bold text-xs tracking-wider uppercase focus:outline-none">
                    <span x-text="t('payment')"></span>
                    <svg :class="{'rotate-180': open}" class="h-3 w-3 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="pb-3 text-[11px] text-blue-100">
                    <ul class="space-y-1.5">
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <span>Bank Transfer (BCA)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            <span>QRIS</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- DESKTOP: Grid Layout (Hidden on Mobile) -->
        <div class="hidden md:grid md:grid-cols-4 gap-8 py-4">
            <!-- ABOUT US -->
            <div>
                <h3 class="text-xs font-bold tracking-wider uppercase mb-3" x-text="t('about')"></h3>
                <div class="flex space-x-4">
                    <a href="https://www.instagram.com/refrens.co" target="_blank" class="text-white hover:text-blue-200 transition-colors">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 A4.902 4.902 0 015.468 2.53c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" /></svg>
                    </a>
                </div>
            </div>

            <!-- ACCOUNT -->
            <div>
                <h3 class="text-xs font-bold tracking-wider uppercase mb-3" x-text="t('account')"></h3>
                <div class="flex flex-col space-y-1.5 text-xs">
                    @auth
                        <a href="{{ route('account.index', ['tab' => 'orders']) }}" class="text-blue-100 hover:text-white transition-colors"><span x-text="t('orderHistory')"></span></a>
                        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                            @csrf
                            <button type="submit" class="text-red-300 hover:text-red-200 text-left uppercase font-bold tracking-tighter" x-text="t('logout')"></button>
                        </form>
                    @else
                        <a href="{{ route('account.index', ['login' => 1]) }}" class="text-blue-100 hover:text-white transition-colors"><span x-text="t('login')"></span></a>
                        <a href="{{ route('account.index', ['register' => 1]) }}" class="text-blue-100 hover:text-white transition-colors"><span x-text="t('register')"></span></a>
                    @endauth
                </div>
            </div>

            <!-- PAYMENT -->
            <div>
                <h3 class="text-xs font-bold tracking-wider uppercase mb-3" x-text="t('payment')"></h3>
                <ul class="space-y-1.5 text-xs text-blue-100">
                    <li class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <span>Bank Transfer</span>
                    </li>
                </ul>
            </div>

            <!-- SHIPPING -->
            <div>
                <h3 class="text-xs font-bold tracking-wider uppercase mb-3" x-text="t('shipping')"></h3>
                <ul class="space-y-1.5 text-xs text-blue-100">
                    <li class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v3.28a1 1 0 00.684.948l6 1.928m5.632 6.648c.84-.236 1.636-.56 2.368-.948V8l-6-2.5" /></svg>
                        <span>JNE / J&T / SiCepat</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="mt-4 pt-4 border-t border-blue-500/30 text-center text-[10px] text-blue-200">
            <p>&copy; 2026 REFRENS. All rights reserved.</p>
        </div>
    </div>
</footer>
