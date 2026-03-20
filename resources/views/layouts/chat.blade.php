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
    .refrens-chat-fab__badge{position:absolute;top:-6px;right:-6px;min-width:18px;height:18px;padding:0 6px;border-radius:9999px;background:#ef4444;color:#fff;font-size:11px;font-weight:900;display:flex;align-items:center;justify-content:center;box-shadow:0 10px 18px rgba(239,68,68,.28);border:2px solid #fff}
    @media (min-width: 768px){.refrens-chat-fab{right:24px;bottom:24px}.refrens-chat-fab__btn{width:52px;height:52px}}

    .refrens-loginprompt{position:fixed;inset:0;z-index:10000;display:none}
    .refrens-loginprompt--open{display:block}
    .refrens-loginprompt__backdrop{position:absolute;inset:0;background:rgba(0,0,0,.52);backdrop-filter:blur(4px)}
    .refrens-loginprompt__panel{position:absolute;left:0;right:0;bottom:0;background:#fff;border-top-left-radius:26px;border-top-right-radius:26px;max-height:72svh;overflow:auto;box-shadow:0 -22px 58px rgba(0,0,0,.22);transform:translateY(16px);opacity:0;transition:transform .18s ease,opacity .18s ease}
    .refrens-loginprompt--open .refrens-loginprompt__panel{transform:translateY(0);opacity:1}
    .refrens-loginprompt__handle{width:56px;height:6px;border-radius:999px;background:#e5efff;margin:10px auto}
    .refrens-loginprompt__head{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-top:2px}
    .refrens-loginprompt__title{font-weight:900;font-size:16px;letter-spacing:-.01em;color:#0f172a;line-height:1.25}
    .refrens-loginprompt__sub{margin-top:6px;color:#64748b;font-weight:700;font-size:12px;line-height:1.55}
    .refrens-loginprompt__icon{width:44px;height:44px;border-radius:16px;background:linear-gradient(135deg,rgba(37,99,235,.12),rgba(79,70,229,.10));display:flex;align-items:center;justify-content:center;box-shadow:0 10px 24px rgba(37,99,235,.16);flex:0 0 auto}
    .refrens-loginprompt__close{width:38px;height:38px;border-radius:9999px;border:1px solid rgba(0,0,0,.08);background:#fff;display:flex;align-items:center;justify-content:center;color:#0f172a;font-weight:900;line-height:1}
    .refrens-loginprompt__actions{display:grid;gap:10px;margin-top:14px;padding-bottom:16px}
    .refrens-loginprompt__btn{height:48px;border-radius:9999px;font-weight:900;display:flex;align-items:center;justify-content:center;gap:10px}
    .refrens-loginprompt__btn--primary{background:#2563eb;border:0;color:#fff;box-shadow:0 16px 30px rgba(37,99,235,.24)}
    .refrens-loginprompt__btn--ghost{background:#fff;border:1px solid rgba(0,0,0,.14);color:#0f172a}
</style>

@if(!request()->routeIs('chat.index'))
    <div class="refrens-chat-fab">
        @auth
            <a href="{{ route('chat.index') }}"
               class="refrens-chat-fab__btn text-white ring-1 ring-white/40 relative z-[1]">
            @if($unread > 0)
                <span class="refrens-chat-fab__badge">{{ $unread > 9 ? '9+' : $unread }}</span>
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
                    <div class="refrens-loginprompt__head">
                        <div class="d-flex align-items-start gap-2">
                            <div class="refrens-loginprompt__icon" aria-hidden="true">
                                <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h6m8-1c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <div>
                                <div class="refrens-loginprompt__title">Login dulu ya</div>
                                <div class="refrens-loginprompt__sub">Biar kamu bisa kirim pesan ke admin, cek pesanan, dan lanjut checkout.</div>
                            </div>
                        </div>
                        <button type="button" class="refrens-loginprompt__close" data-loginprompt-close aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="refrens-loginprompt__actions">
                        <a href="{{ route('account.index', ['login' => 1]) }}" class="refrens-loginprompt__btn refrens-loginprompt__btn--primary">
                            <span>Login</span>
                        </a>
                        <button type="button" class="refrens-loginprompt__btn refrens-loginprompt__btn--ghost" data-loginprompt-close>
                            <span>Login nanti</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                (function () {
                    const openBtns = document.querySelectorAll('[data-loginprompt-open]');
                    const modal = document.getElementById('refrensLoginPrompt');
                    if (!openBtns.length || !modal) return;

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

                    openBtns.forEach((btn) => {
                        btn.addEventListener('click', function (e) {
                            e.preventDefault();
                            openModal();
                        });
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
