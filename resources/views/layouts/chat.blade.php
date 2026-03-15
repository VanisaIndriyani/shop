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
    .refrens-chat-fab{position:fixed;right:24px;bottom:96px;z-index:9999}
    @media (min-width: 768px){.refrens-chat-fab{bottom:24px}}
</style>

<div class="refrens-chat-fab">
    <a href="{{ route('chat.index') }}"
       style="background: linear-gradient(135deg, #2563eb, #4f46e5); width:56px; height:56px; border:none;"
       class="hover:from-blue-700 hover:to-indigo-700 text-white rounded-2xl shadow-[0_18px_38px_rgba(37,99,235,0.35)] ring-1 ring-white/40 transition-all duration-300 transform hover:scale-105 flex items-center justify-center group relative z-[1]">
        @if($unread > 0)
            <span class="absolute -top-1 -right-1">
                <span class="absolute inline-flex h-5 w-5 rounded-full bg-red-400 opacity-75 animate-ping"></span>
                <span class="relative inline-flex h-5 min-w-5 px-1 bg-red-500 text-white text-[10px] font-black rounded-full items-center justify-center shadow-lg ring-2 ring-white">
                    {{ $unread > 9 ? '9+' : $unread }}
                </span>
            </span>
        @endif
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h6m8-1c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
    </a>
</div>

