@extends('layouts.admin')

@section('content')
<style>
    .admin-chatcard{border:0;border-radius:18px;box-shadow:0 12px 30px rgba(16,24,40,.06);overflow:hidden;display:flex;flex-direction:column;min-height:calc(100svh - 210px)}
    .admin-chathead{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px 14px;border-bottom:1px solid rgba(0,0,0,.06);background:#fff}
    .admin-chathead__left{display:flex;align-items:center;gap:12px;min-width:0}
    .admin-chatavatar{width:42px;height:42px;border-radius:999px;background:rgba(37,99,235,.10);color:#2563eb;font-weight:900;display:flex;align-items:center;justify-content:center;flex:0 0 auto}
    .admin-chatmeta{min-width:0;line-height:1.1}
    .admin-chatname{font-weight:900;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .admin-chatsub{font-size:12px;color:#6b7280;font-weight:700;margin-top:2px}
    .admin-chatbody{flex:1 1 auto;overflow:auto;padding:14px 14px;background:linear-gradient(180deg,#f8fafc 0%,#ffffff 40%)}
    .admin-msg{display:flex;flex-direction:column;gap:6px;margin-bottom:12px}
    .admin-msg--me{align-items:flex-end}
    .admin-bubble{max-width:min(520px,84%);border-radius:16px;padding:10px 12px;box-shadow:0 10px 22px rgba(16,24,40,.08);border:1px solid rgba(0,0,0,.06);background:#fff;color:#111827}
    .admin-bubble--me{background:linear-gradient(135deg,#2563eb,#4f46e5);border-color:transparent;color:#fff}
    .admin-bubble__meta{font-size:11px;font-weight:800;color:rgba(17,24,39,.55)}
    .admin-bubble--me .admin-bubble__meta{color:rgba(255,255,255,.75)}
    .admin-bubble__text{font-weight:700;white-space:pre-wrap}
    .admin-bubble__time{font-size:11px;font-weight:800;color:rgba(17,24,39,.45);text-align:right}
    .admin-bubble--me .admin-bubble__time{color:rgba(255,255,255,.72)}
    .admin-chatfoot{border-top:1px solid rgba(0,0,0,.06);background:#fff;padding:12px 12px calc(12px + env(safe-area-inset-bottom))}
    .admin-chatinput{border-radius:999px;padding:12px 14px;font-weight:700}
    .admin-chatsend{border-radius:999px;font-weight:900;padding:0 16px}
    @media (min-width: 768px){
        .admin-chathead{padding:16px 16px}
        .admin-chatbody{padding:18px 18px}
        .admin-chatfoot{padding:14px 14px}
        .admin-chatcard{min-height:calc(100svh - 230px)}
    }
</style>

<div class="admin-chatcard">
    <div class="admin-chathead">
        <div class="admin-chathead__left">
            <a href="{{ route('admin.messages.index') }}" class="btn btn-light rounded-pill">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="admin-chatavatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div class="admin-chatmeta">
                <div class="admin-chatname">{{ $user->name }}</div>
                <div class="admin-chatsub">Balas pesan customer dari sini</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary d-none d-md-inline-flex">Kembali</a>
        </div>
    </div>

    <div id="adminChatArea" class="admin-chatbody">
        @forelse($messages as $msg)
            <div class="admin-msg {{ $msg->is_from_admin ? 'admin-msg--me' : '' }}">
                <div class="admin-bubble {{ $msg->is_from_admin ? 'admin-bubble--me' : '' }}">
                    <div class="admin-bubble__meta">{{ $msg->is_from_admin ? 'Admin' : $user->name }}</div>
                    <div class="admin-bubble__text">{{ $msg->message }}</div>
                    <div class="admin-bubble__time">{{ $msg->created_at->format('H:i') }}</div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">Belum ada pesan.</div>
        @endforelse
    </div>

    <div class="admin-chatfoot">
        <form action="{{ route('admin.messages.reply', $user->id) }}" method="POST" class="d-flex gap-2">
            @csrf
            <input type="text" name="message" class="form-control admin-chatinput" placeholder="Tulis balasan..." required autocomplete="off">
            <button class="btn btn-primary admin-chatsend" type="submit">Kirim</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const chatArea = document.getElementById('adminChatArea');
        if (!chatArea) return;
        chatArea.scrollTop = chatArea.scrollHeight;
    })();
</script>
@endpush
@endsection
