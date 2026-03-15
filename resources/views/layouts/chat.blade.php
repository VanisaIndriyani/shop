@php
    use App\Models\Message;
    $unread = 0;
    if (Auth::check()) {
        $unread = Message::where('user_id', Auth::id())
            ->where('is_from_admin', true)
            ->where('is_read', false)
            ->count();
    }
@endphp

<style>
    .refrens-chat-fab{position:fixed;right:16px;bottom:calc(16px + env(safe-area-inset-bottom));z-index:9999}
    .refrens-chat-fab__btn{width:48px;height:48px;border:none;border-radius:9999px;background:linear-gradient(135deg,#2563eb,#4f46e5);box-shadow:0 18px 38px rgba(37,99,235,.35);display:flex;align-items:center;justify-content:center;transition:transform .2s ease,box-shadow .2s ease,filter .2s ease}
    .refrens-chat-fab__btn:hover{transform:scale(1.05);box-shadow:0 22px 44px rgba(37,99,235,.42);filter:saturate(1.1)}
    .refrens-chat-fab__icon{filter:drop-shadow(0 10px 16px rgba(0,0,0,.18))}
    @media (min-width: 768px){.refrens-chat-fab{right:24px;bottom:24px}.refrens-chat-fab__btn{width:52px;height:52px}}

    .refrens-loginprompt{position:fixed;inset:0;z-index:10000;display:none}
    .refrens-loginprompt--open{display:block}
    .refrens-loginprompt__backdrop{position:absolute;inset:0;background:rgba(0,0,0,.45)}
    .refrens-loginprompt__panel{position:absolute;left:0;right:0;bottom:0;background:#fff;border-top-left-radius:22px;border-top-right-radius:22px;max-height:70svh;overflow:auto;box-shadow:0 -18px 48px rgba(0,0,0,.18);transform:translateY(16px);opacity:0;transition:transform .18s ease,opacity .18s ease}
    .refrens-loginprompt--open .refrens-loginprompt__panel{transform:translateY(0);opacity:1}
    .refrens-loginprompt__handle{width:56px;height:6px;border-radius:999px;background:#e5efff;margin:10px auto}
</style>

@if(!request()->routeIs('chat.index'))
    <div class="refrens-chat-fab">
        @auth
            <a href="{{ route('chat.index') }}"
               class="refrens-chat-fab__btn text-white ring-1 ring-white/40 relative z-[1]">
            @if($unread > 0)
                <span class="absolute -top-1 -right-1">
                    <span class="absolute inline-flex h-4 w-4 rounded-full bg-red-400 opacity-75 animate-ping"></span>
                    <span class="relative inline-flex h-4 min-w-4 px-1 bg-white text-red-600 text-[10px] font-black rounded-full items-center justify-center shadow-lg ring-2 ring-white">
                        {{ $unread > 9 ? '9+' : $unread }}
                    </span>
                </span>
            @endif
            <svg class="refrens-chat-fab__icon w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h6m8-1c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            </a>
        @else
            <a href="#"
               data-loginprompt-open
               class="refrens-chat-fab__btn text-white ring-1 ring-white/40 relative z-[1]">
                <svg class="refrens-chat-fab__icon w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h6m8-1c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </a>
        @endauth
    </div>

    @guest
        <div id="refrensLoginPrompt" class="refrens-loginprompt" aria-hidden="true">
            <div class="refrens-loginprompt__backdrop" data-loginprompt-close></div>
            <div class="refrens-loginprompt__panel" role="dialog" aria-modal="true">
                <div class="px-3">
                    <div class="refrens-loginprompt__handle"></div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-bold fs-5">Login dulu ya</div>
                        <button type="button" class="btn btn-sm btn-light rounded-circle" data-loginprompt-close aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="text-muted small">
                        Untuk kirim pesan ke admin, kamu harus login dulu.
                    </div>
                    <div class="d-grid gap-2 mt-3 pb-4">
                        <a href="{{ route('account.index', ['login' => 1]) }}" class="btn btn-primary btn-lg rounded-pill fw-bold">Login</a>
                        <button type="button" class="btn btn-outline-secondary btn-lg rounded-pill fw-bold" data-loginprompt-close>Nanti</button>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                (function () {
                    const openBtn = document.querySelector('[data-loginprompt-open]');
                    const modal = document.getElementById('refrensLoginPrompt');
                    if (!openBtn || !modal) return;

                    const body = document.body;
                    const closeEls = modal.querySelectorAll('[data-loginprompt-close]');

                    function openModal() {
                        modal.classList.add('refrens-loginprompt--open');
                        modal.setAttribute('aria-hidden', 'false');
                        body.style.overflow = 'hidden';
                    }

                    function closeModal() {
                        modal.classList.remove('refrens-loginprompt--open');
                        modal.setAttribute('aria-hidden', 'true');
                        body.style.overflow = '';
                    }

                    openBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        openModal();
                    });

                    closeEls.forEach((el) => {
                        el.addEventListener('click', function (e) {
                            e.preventDefault();
                            closeModal();
                        });
                    });

                    window.addEventListener('keydown', function (e) {
                        if (e.key === 'Escape') closeModal();
                    });
                })();
            </script>
        @endpush
    @endguest
@endif
