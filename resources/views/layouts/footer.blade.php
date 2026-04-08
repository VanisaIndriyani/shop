<!-- Accordion Footer Section (Preface Style) -->
<footer class="bg-[#1e40af] text-white pt-16 pb-20 w-full z-40 relative min-h-[60vh] flex flex-col justify-between overflow-hidden" style="background-color: #1e40af !important;" x-data="{
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
            about: 'ABOUT US',
            account: 'ACCOUNT',
            payment: 'PAYMENT',
            shipping: 'Shipment Method',
            hi: en ? 'Hi,' : 'Hi,',
            orderHistory: en ? 'Order History' : 'Riwayat Pesanan',
            login: en ? 'Login' : 'Login',
            register: en ? 'Register' : 'Daftar',
            logout: en ? 'Logout' : 'Keluar',
            findUs: en ? 'Find more about us on Instagram' : 'Temukan lebih banyak tentang kami di Instagram',
            allRights: en ? 'ALL RIGHTS RESERVED.' : 'HAK CIPTA DILINDUNGI.',
        };
        return map[key] || key;
    }
}" x-init="init()">
    <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-6 lg:px-8">
        
        <!-- MOBILE: Accordion (Hidden on Desktop) -->
        <div class="md:hidden space-y-0">
            <!-- ABOUT US -->
            <div x-data="{ open: false }" class="border-b border-white/30">
                <button @click="open = !open" class="flex justify-between items-center w-full py-4 text-left font-black text-[13px] tracking-wider uppercase focus:outline-none" style="font-family: 'Archivo Black', sans-serif;">
                    <span x-text="t('about')">ABOUT US</span>
                    <svg :class="{'rotate-180': open}" class="h-4 w-4 transform transition-transform duration-200 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="pb-4">
                    <p class="text-[11px] font-medium text-blue-100 leading-relaxed uppercase tracking-widest">
                        <span x-text="t('findUs')">Find more about us on Instagram</span> 
                        <a href="https://instagram.com/refrens.co" target="_blank" class="text-white font-bold !no-underline hover:text-blue-200 transition-colors" style="text-decoration: none !important;">@refrens.co</a>
                    </p>
                </div>
            </div>

            <!-- ACCOUNT -->
            <div x-data="{ open: false }" class="border-b border-white/30">
                <button @click="open = !open" class="flex justify-between items-center w-full py-4 text-left font-black text-[13px] tracking-wider uppercase focus:outline-none" style="font-family: 'Archivo Black', sans-serif;">
                    <span x-text="t('account')">ACCOUNT</span>
                    <svg :class="{'rotate-180': open}" class="h-4 w-4 transform transition-transform duration-200 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="pb-4 space-y-4">
                    @auth
                        <div class="flex flex-col space-y-3">
                            <div class="text-[11px] text-blue-200 italic font-medium uppercase"><span x-text="t('hi')"></span> {{ Auth::user()->name }}</div>
                            <a href="{{ route('account.index', ['tab' => 'orders']) }}" class="text-[11px] font-bold text-white hover:text-blue-200 transition-colors uppercase tracking-widest !no-underline" style="text-decoration: none !important;" x-text="t('orderHistory')"></a>
                            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                                @csrf
                                <button type="submit" class="text-[11px] font-bold text-red-400 hover:text-red-300 block w-full text-left uppercase tracking-widest !no-underline" style="text-decoration: none !important;" x-text="t('logout')"></button>
                            </form>
                        </div>
                    @else
                        <div class="flex flex-col space-y-3 pt-2">
                            <a href="{{ route('account.index', ['login' => 1]) }}" class="text-[11px] font-bold uppercase tracking-widest text-white hover:text-blue-100 transition-all !no-underline" style="text-decoration: none !important;" x-text="t('login')"></a>
                            <a href="{{ route('account.index', ['register' => 1]) }}" class="text-[11px] font-bold uppercase tracking-widest text-white hover:text-blue-100 transition-all !no-underline" style="text-decoration: none !important;" x-text="t('register')"></a>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- PAYMENT -->
            <div x-data="{ open: false }" class="border-b border-white/30">
                <button @click="open = !open" class="flex justify-between items-center w-full py-4 text-left font-black text-[13px] tracking-wider uppercase focus:outline-none" style="font-family: 'Archivo Black', sans-serif;">
                    <span x-text="t('payment')">PAYMENT</span>
                    <svg :class="{'rotate-180': open}" class="h-4 w-4 transform transition-transform duration-200 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="pb-4">
                    <div class="flex justify-start">
                        <img src="{{ asset('img/pay.png') }}" alt="PAYMENT" class="h-auto w-[140px] object-contain" loading="lazy">
                    </div>
                </div>
            </div>

            <!-- SHIPPING -->
            <div x-data="{ open: false }" class="border-b border-white/30">
                <button @click="open = !open" class="flex justify-between items-center w-full py-4 text-left font-black text-[13px] tracking-wider uppercase focus:outline-none" style="font-family: 'Archivo Black', sans-serif;">
                    <span x-text="t('shipping')">Shipment Method</span>
                    <svg :class="{'rotate-180': open}" class="h-4 w-4 transform transition-transform duration-200 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="pb-4">
                    <div class="flex justify-start items-center">
                        <img src="{{ asset('img/jne.png') }}" alt="Shipment Method" class="h-auto" style="width: 120px !important;" loading="lazy">
                    </div>
                </div>
            </div>

            <!-- Social Icons Bottom -->
            <div class="border-b border-white/30 py-4">
                <div class="flex space-x-5">
                    <a href="https://www.tiktok.com/@refrens.co" target="_blank" class="text-white hover:scale-110 transition-transform">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.65-1.55-1.1-.06-.06-.11-.13-.17-.19v6.52c-.05 2.97-2.02 5.59-4.91 6.52-2.91.93-6.14.07-8.15-2.15-2.01-2.23-2.33-5.54-.8-8.1 1.53-2.56 4.49-3.9 7.42-3.32v4.06c-1.39-.46-2.95-.08-3.99.98-1.04 1.05-1.3 2.64-.66 3.97.64 1.34 2.15 2.06 3.6 1.72 1.45-.34 2.5-1.63 2.52-3.12V.02z"/></svg>
                    </a>
                 
                    <a href="https://www.instagram.com/refrens.co" target="_blank" class="text-white hover:scale-110 transition-transform">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 A4.902 4.902 0 015.468 2.53c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" /></svg>
                    </a>
                </div>
            </div>

            <!-- Copyright -->
            <div class="py-6 flex justify-between items-center">
                <p class="text-[9px] text-white tracking-[0.05em] font-black uppercase">© 2026 REFRENS.</p>
                <p class="text-[9px] text-white tracking-[0.05em] font-black uppercase text-right" x-text="t('allRights')">ALL RIGHTS RESERVED.</p>
            </div>
        </div>

        <!-- DESKTOP: Grid Layout (Hidden on Desktop) -->
        <div class="hidden md:grid md:grid-cols-4 gap-12 py-20">
            <!-- ABOUT US -->
            <div>
                <h3 class="text-[13px] font-black tracking-[0.1em] uppercase mb-6" style="font-family: 'Archivo Black', sans-serif;" x-text="t('about')">ABOUT US</h3>
                <div class="space-y-4">
                    <p class="text-[12px] text-blue-100 leading-relaxed uppercase tracking-wider"><span x-text="t('findUs')">Find more about us on Instagram</span> <a href="https://instagram.com/refrens.co" target="_blank" class="text-white !no-underline" style="text-decoration: none !important;">@refrens.co</a></p>
                    <div class="flex space-x-5">
                        <a href="https://www.instagram.com/refrens.co" target="_blank" class="text-white hover:text-blue-200 transition-colors">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 A4.902 4.902 0 015.468 2.53c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" /></svg>
                        </a>
                        <a href="https://www.tiktok.com/@refrens.co" target="_blank" class="text-white hover:text-blue-200 transition-colors">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.65-1.55-1.1-.06-.06-.11-.13-.17-.19v6.52c-.05 2.97-2.02 5.59-4.91 6.52-2.91.93-6.14.07-8.15-2.15-2.01-2.23-2.33-5.54-.8-8.1 1.53-2.56 4.49-3.9 7.42-3.32v4.06c-1.39-.46-2.95-.08-3.99.98-1.04 1.05-1.3 2.64-.66 3.97.64 1.34 2.15 2.06 3.6 1.72 1.45-.34 2.5-1.63 2.52-3.12V.02z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- ACCOUNT -->
            <div>
                <h3 class="text-[13px] font-black tracking-[0.1em] uppercase mb-6" style="font-family: 'Archivo Black', sans-serif;" x-text="t('account')">ACCOUNT</h3>
                <div class="flex flex-col space-y-3 text-[12px]">
                    @auth
                        <a href="{{ route('account.index', ['tab' => 'orders']) }}" class="text-blue-100 hover:text-white transition-colors uppercase font-bold tracking-wider !no-underline" style="text-decoration: none !important;" x-text="t('orderHistory')">ORDER HISTORY</a>
                        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                            @csrf
                            <button type="submit" class="text-red-400 hover:text-red-300 text-left uppercase font-bold tracking-wider !no-underline" style="text-decoration: none !important;" x-text="t('logout')">LOGOUT</button>
                        </form>
                    @else
                        <a href="{{ route('account.index', ['login' => 1]) }}" class="text-blue-100 hover:text-white transition-colors uppercase font-bold tracking-wider !no-underline" style="text-decoration: none !important;" x-text="t('login')">LOGIN</a>
                        <a href="{{ route('account.index', ['register' => 1]) }}" class="text-blue-100 hover:text-white transition-colors uppercase font-bold tracking-wider !no-underline" style="text-decoration: none !important;" x-text="t('register')">REGISTER</a>
                    @endauth
                </div>
            </div>

            <!-- PAYMENT -->
            <div>
                <h3 class="text-[13px] font-black tracking-[0.1em] uppercase mb-6" style="font-family: 'Archivo Black', sans-serif;" x-text="t('payment')">PAYMENT</h3>
                <div class="flex justify-start">
                    <img src="{{ asset('img/pay.png') }}" alt="PAYMENT" class="h-auto w-[180px] object-contain" loading="lazy">
                </div>
            </div>

            <!-- SHIPPING -->
            <div>
                <h3 class="text-[13px] font-black tracking-[0.1em] uppercase mb-6" style="font-family: 'Archivo Black', sans-serif;" x-text="t('shipping')">Shipment Method</h3>
                <div class="flex justify-start items-center">
                    <img src="{{ asset('img/jne.png') }}" alt="Shipment Method" class="h-auto" style="width: 70px !important;" loading="lazy">
                </div>
            </div>
        </div>
    </div>
</footer>