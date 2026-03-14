<!-- Floating Chat Button -->
<style>
    .refrens-chat-fab{position:fixed;right:24px;bottom:96px;z-index:9999}
    @media (min-width: 768px){.refrens-chat-fab{bottom:24px}}
</style>
<div x-data="chatSystem()" class="refrens-chat-fab" data-chat-auth="{{ Auth::check() ? '1' : '0' }}">
    <!-- Chat Toggle Button -->
    <button @click="toggleChat()"
            data-chat-toggle
            style="background: linear-gradient(135deg, #2563eb, #4f46e5); width:56px; height:56px; border:none;"
            class="hover:from-blue-700 hover:to-indigo-700 text-white rounded-2xl shadow-[0_18px_38px_rgba(37,99,235,0.35)] ring-1 ring-white/40 transition-all duration-300 transform hover:scale-105 flex items-center justify-center group relative z-[1]"
            aria-label="Chat Admin">
        <span x-show="isAuthenticated && unreadCount > 0 && !isOpen && !loginPromptOpen" x-cloak class="absolute -top-1 -right-1">
            <span class="absolute inline-flex h-5 w-5 rounded-full bg-red-400 opacity-75 animate-ping"></span>
            <span class="relative inline-flex h-5 min-w-5 px-1 bg-red-500 text-white text-[10px] font-black rounded-full items-center justify-center shadow-lg ring-2 ring-white">
                <span x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
            </span>
        </span>
        <svg x-show="!isOpen && !loginPromptOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h6m8-1c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <svg x-show="isOpen || loginPromptOpen" x-cloak style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        <!-- Tooltip -->
        <span class="hidden absolute right-full mr-3 bg-white text-blue-600 text-xs font-bold py-2 px-3 rounded-lg shadow-xl border border-blue-50 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
            Tanya Admin? Chat di sini!
        </span>
    </button>

    <div x-show="loginPromptOpen" x-cloak class="fixed inset-0 z-[60]" style="display: none;" data-chat-login-modal>
        <div class="fixed inset-0 bg-black/35 backdrop-blur-[2px]" @click="closeLoginPrompt()" data-chat-login-backdrop></div>
        <div class="min-h-screen flex items-center justify-center p-6">
            <div class="w-full max-w-md bg-white rounded-[2rem] shadow-2xl overflow-hidden">
                <div class="p-8 text-center">
                    <h3 class="text-xl font-black text-gray-900">Chat Support</h3>
                    <div class="mt-10 mb-10">
                        <p class="text-sm text-gray-700 font-medium">Log in to save your chat and continue on any device.</p>
                    </div>
                    <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl transition-colors">
                        Login
                    </a>
                    <button type="button" @click="closeLoginPrompt()" class="mt-6 text-sm text-gray-400 hover:text-gray-600 underline" data-chat-login-close>
                        Skip for now
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Modal (Overlay Center) -->
    <div x-show="isOpen" x-cloak class="fixed inset-0 z-[60]" style="display: none;">
        <div class="fixed inset-0 bg-black/40" @click="isOpen = false"></div>
        <div class="min-h-screen flex items-center justify-center p-6">
            <div class="w-full max-w-md md:max-w-lg bg-white rounded-[2rem] shadow-[0_20px_60px_rgba(0,0,0,0.15)] border border-blue-50 overflow-hidden flex flex-col max-h-[80vh]">
                <!-- Header -->
                <div class="bg-blue-600 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">Admin Refrens</h3>
                                <div class="flex items-center space-x-1.5">
                                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                    <span class="text-xs text-blue-100">Online & Siap Membantu</span>
                                </div>
                            </div>
                        </div>
                        <button @click="isOpen=false" class="text-white/90 hover:text-white transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Chat Area -->
                <div id="chat-messages" class="flex-1 p-6 overflow-y-auto bg-gray-50/50 space-y-5">
                    <template x-for="msg in messages" :key="msg.id">
                        <div :class="msg.is_from_admin ? 'flex justify-start' : 'flex justify-end'" class="relative px-2">
                            <div 
                                :class="msg.is_from_admin 
                                    ? 'bg-blue-50 text-blue-900 border border-blue-100' 
                                    : 'bg-blue-600 text-white'"
                                class="relative max-w-[78%] px-4 py-3 text-sm font-medium leading-relaxed rounded-3xl shadow-lg">
                                <p x-text="msg.message || '(pesan kosong)'" class="break-words"></p>
                                <span :class="msg.is_from_admin ? 'text-blue-500' : 'text-blue-200'" class="text-[10px] block mt-1" x-text="formatDate(msg.created_at)"></span>
                                <div :class="msg.is_from_admin ? '-left-1 bg-blue-50 border-l border-b border-blue-100' : '-right-1 bg-blue-600'" class="absolute bottom-2 w-3 h-3 rotate-45"></div>
                            </div>
                        </div>
                    </template>
                    <div x-show="errorText" class="text-center py-6">
                        <p class="text-sm text-red-600 font-semibold" x-text="errorText"></p>
                    </div>
                    <div x-show="messages.length === 0" class="text-center py-10">
                        <div class="bg-blue-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <p class="text-gray-400 text-sm">Ada yang ingin ditanyakan?</p>
                        <p class="text-gray-500 text-xs font-bold mt-1 uppercase tracking-wider">Mulai percakapan di bawah</p>
                    </div>
                </div>

                <!-- Footer / Input -->
                <div class="p-6 bg-white border-t border-gray-100">
                    @auth
                        <form @submit.prevent="sendMessage()" class="flex items-center space-x-3">
                            <input x-model="newMessage" 
                                   x-ref="messageInput"
                                   type="text" 
                                   placeholder="Tulis pesan..." 
                                   class="flex-1 bg-gray-50 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-blue-600 transition-all">
                            <button type="submit" 
                                    :disabled="!newMessage.trim()"
                                    class="bg-blue-600 text-white p-3 rounded-xl hover:bg-blue-700 disabled:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5 transform rotate-90" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                </svg>
                            </button>
                        </form>
                    @else
                        <div class="text-center py-2">
                            <p class="text-sm text-gray-500 mb-3 font-medium">Silakan login untuk bertanya ke admin</p>
                            <a href="{{ route('login') }}" class="inline-block bg-blue-600 text-white px-8 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-blue-700 transition-colors">Login Sekarang</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function chatSystem() {
    return {
        isAuthenticated: {{ Auth::check() ? 'true' : 'false' }},
        loginPromptOpen: false,
        isOpen: false,
        newMessage: '',
        messages: [],
        unreadCount: 0,
        errorText: '',
        pollingInterval: null,

        init() {
            if (this.isAuthenticated) {
                this.fetchMessages();
            }
        },

        toggleChat() {
            if (!this.isAuthenticated) {
                if (this.loginPromptOpen) {
                    this.loginPromptOpen = false;
                    return;
                }
                this.loginPromptOpen = true;
                return;
            }
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.fetchMessages().then(() => this.markAdminRead());
                this.scrollToBottom();
                this.startPolling();
                setTimeout(() => {
                    if (this.$refs?.messageInput) this.$refs.messageInput.focus();
                }, 0);
            } else {
                this.stopPolling();
            }
        },

        closeLoginPrompt() {
            this.loginPromptOpen = false;
        },

        fetchMessages() {
            if (!this.isAuthenticated) return;
            return fetch('/messages')
                .then(res => res.json())
                .then(data => {
                    this.errorText = '';
                    this.messages = Array.isArray(data.messages) ? data.messages : [];
                    this.unreadCount = Number(data.unread_admin_count || 0);
                    if (this.messages.length === 0) {
                        this.messages = [{
                            id: 'welcome',
                            user_id: null,
                            message: 'Halo! Ada yang bisa Admin bantu?',
                            is_from_admin: true,
                            created_at: new Date().toISOString()
                        }];
                    }
                    this.scrollToBottom();
                })
                .catch(() => {
                    this.errorText = 'Chat tidak bisa dimuat. Coba reload halaman.';
                });
        },

        markAdminRead() {
            if (!this.isAuthenticated) return;
            if (this.unreadCount <= 0) return;
            fetch('/messages/mark-admin-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            }).then(() => {
                this.unreadCount = 0;
            });
        },

        sendMessage() {
            if (!this.isAuthenticated) return;
            if (!this.newMessage.trim()) return;

            const msg = this.newMessage;
            this.newMessage = '';

            fetch('/messages', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: msg })
            })
            .then(res => res.json())
            .then(data => {
                this.fetchMessages();
            });
        },

        startPolling() {
            if (!this.isAuthenticated) return;
            this.pollingInterval = setInterval(() => this.fetchMessages(), 5000);
        },

        stopPolling() {
            if (this.pollingInterval) clearInterval(this.pollingInterval);
        },

        scrollToBottom() {
            setTimeout(() => {
                const chatArea = document.getElementById('chat-messages');
                if (chatArea) chatArea.scrollTop = chatArea.scrollHeight;
            }, 100);
        },

        formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
    }
}
</script>

<script>
    (function () {
        const root = document.querySelector('.refrens-chat-fab');
        if (!root) return;
        if (root.__x) return;

        const isAuthed = root.getAttribute('data-chat-auth') === '1';
        if (isAuthed) return;

        const toggleBtn = root.querySelector('[data-chat-toggle]');
        const modal = root.querySelector('[data-chat-login-modal]');
        const backdrop = root.querySelector('[data-chat-login-backdrop]');
        const closeBtn = root.querySelector('[data-chat-login-close]');

        if (!toggleBtn || !modal) return;

        function open() { modal.style.display = 'block'; }
        function close() { modal.style.display = 'none'; }
        function toggle() { modal.style.display === 'none' || !modal.style.display ? open() : close(); }

        close();
        toggleBtn.addEventListener('click', toggle);
        if (backdrop) backdrop.addEventListener('click', close);
        if (closeBtn) closeBtn.addEventListener('click', close);
    })();
</script>
