@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
    .refrens-chat-shell{min-height:calc(100svh - 64px);display:flex;align-items:stretch;justify-content:center;padding:0}
    .refrens-chat-card{width:100%;max-width:920px;background:#fff;border:0;border-radius:0;overflow:hidden;box-shadow:0 18px 42px rgba(16,24,40,.08);display:flex;flex-direction:column}
    .refrens-chat-header{position:sticky;top:0;z-index:5;background:rgba(255,255,255,.96);backdrop-filter:blur(10px);border-bottom:1px solid rgba(0,0,0,.06);padding:14px 14px;display:flex;align-items:center;justify-content:space-between;gap:12px}
    .refrens-chat-title{display:flex;align-items:center;gap:10px}
    .refrens-chat-avatar{width:38px;height:38px;border-radius:999px;background:linear-gradient(135deg,#2563eb,#4f46e5);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900}
    .refrens-chat-meta{line-height:1.1}
    .refrens-chat-meta .refrens-chat-name{font-weight:900}
    .refrens-chat-meta .refrens-chat-sub{font-size:12px;color:#6b7280;margin-top:2px}
    .refrens-chat-body{flex:1 1 auto;overflow:auto;padding:14px 14px;background:linear-gradient(180deg,#f8fafc 0%,#ffffff 40%)}
    .refrens-msg{display:flex;flex-direction:column;gap:6px;margin-bottom:12px}
    .refrens-msg--admin{align-items:flex-start}
    .refrens-msg--me{align-items:flex-end}
    .refrens-bubble{max-width:min(78%,520px);padding:10px 12px;border-radius:18px;line-height:1.35;font-weight:600;word-break:break-word;white-space:pre-wrap}
    .refrens-msg--admin .refrens-bubble{background:#fff;border:1px solid rgba(0,0,0,.08);color:#111827;border-top-left-radius:8px}
    .refrens-msg--me .refrens-bubble{background:linear-gradient(135deg,#2563eb,#4f46e5);color:#fff;border-top-right-radius:8px}
    .refrens-time{font-size:11px;color:#6b7280}
    .refrens-msg--me .refrens-time{color:rgba(255,255,255,.7)}
    .refrens-chat-footer{position:sticky;bottom:0;z-index:5;background:rgba(255,255,255,.96);backdrop-filter:blur(10px);border-top:1px solid rgba(0,0,0,.06);padding:12px 12px calc(12px + env(safe-area-inset-bottom))}
    .refrens-chat-input{border-radius:999px}
    .refrens-chat-send{border-radius:999px}
    @media (min-width: 768px){
        .refrens-chat-shell{padding:22px 18px}
        .refrens-chat-card{border-radius:22px}
        .refrens-chat-header{padding:16px 16px}
        .refrens-chat-body{padding:18px 18px}
        .refrens-chat-footer{padding:14px 14px}
    }
</style>

<div class="refrens-chat-shell">
    <div class="refrens-chat-card">
        <div class="refrens-chat-header">
            <div class="refrens-chat-title">
                <a href="{{ route('shop.index') }}" class="btn btn-sm btn-light rounded-pill d-md-none">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="refrens-chat-avatar">R</div>
                <div class="refrens-chat-meta">
                    <div class="refrens-chat-name">Admin Refrens</div>
                    <div class="refrens-chat-sub">Balas secepatnya</div>
                </div>
            </div>
            <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary rounded-pill d-none d-md-inline-flex">Kembali</a>
        </div>

        <div id="chatScroll" class="refrens-chat-body">
            @forelse($messages as $msg)
                <div class="refrens-msg {{ $msg->is_from_admin ? 'refrens-msg--admin' : 'refrens-msg--me' }}">
                    <div class="refrens-bubble">{!! nl2br(e((string) $msg->message)) !!}</div>
                    <div class="refrens-time {{ $msg->is_from_admin ? '' : 'text-white-50' }}">{{ $msg->created_at?->format('d M Y, H:i') }}</div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    Belum ada pesan. Tulis pesan pertama kamu ke admin.
                </div>
            @endforelse
        </div>

        <div class="refrens-chat-footer">
            <form action="{{ route('chat.send') }}" method="POST" class="d-flex gap-2 align-items-center">
                @csrf
                <input type="text" name="message" class="form-control form-control-lg refrens-chat-input @error('message') is-invalid @enderror" placeholder="Tulis pesan..." autocomplete="off">
                <button type="submit" class="btn btn-primary btn-lg refrens-chat-send px-4">
                    <i class="bi bi-send"></i>
                </button>
            </form>
            @error('message') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const box = document.getElementById('chatScroll');
        if (!box) return;
        function scrollBottom() {
            box.scrollTop = box.scrollHeight;
        }
        scrollBottom();
        window.setTimeout(scrollBottom, 50);
        window.setTimeout(scrollBottom, 250);
    })();
</script>
@endpush
@endsection
