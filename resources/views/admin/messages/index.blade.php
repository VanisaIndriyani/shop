@extends('layouts.admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <div class="fw-bold fs-4">Pesan Customer</div>
        <div class="text-muted">Kelola percakapan customer.</div>
    </div>
</div>

<style>
    .admin-msgsearch{border-radius:14px}
    .admin-thread{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:14px 16px;text-decoration:none}
    .admin-thread:hover{background:#f8fafc}
    .admin-thread__left{display:flex;align-items:center;gap:12px;min-width:0}
    .admin-avatar{width:42px;height:42px;border-radius:999px;background:rgba(37,99,235,.10);color:#2563eb;font-weight:900;display:flex;align-items:center;justify-content:center;flex:0 0 auto}
    .admin-thread__meta{min-width:0}
    .admin-thread__name{font-weight:900;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .admin-thread__preview{font-size:12px;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:52ch}
    .admin-thread__right{display:flex;flex-direction:column;align-items:flex-end;gap:6px;flex:0 0 auto}
    .admin-thread__time{font-size:12px;color:#94a3b8;font-weight:700}
    .admin-unread{min-width:22px;height:22px;border-radius:999px;background:#dc2626;color:#fff;font-size:12px;font-weight:900;display:flex;align-items:center;justify-content:center;padding:0 7px}
</style>

<div class="card content-card overflow-hidden">
    <div class="card-body border-bottom bg-white p-3">
        <div class="d-flex flex-column flex-md-row gap-2 align-items-md-center justify-content-between">
            <div class="fw-bold">Daftar Chat</div>
            <div class="d-flex gap-2">
                <input id="adminMsgSearch" type="search" class="form-control admin-msgsearch" placeholder="Cari nama customer...">
            </div>
        </div>
    </div>
    <div class="list-group list-group-flush" id="adminThreadList">
        @forelse($threads as $thread)
            @php
                $u = $thread['user'];
                $last = $thread['last_message'];
                $unread = (int) $thread['unread_count'];
                $preview = $last ? (($last->is_from_admin ? 'Anda: ' : '') . \Illuminate\Support\Str::limit($last->message, 70)) : 'Belum ada pesan';
                $time = $last ? $last->created_at->diffForHumans() : '';
            @endphp
            <a href="{{ route('admin.messages.show', $u->id) }}"
               class="list-group-item list-group-item-action admin-thread"
               data-name="{{ strtolower($u->name) }}">
                <div class="admin-thread__left">
                    <div class="admin-avatar">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                    <div class="admin-thread__meta">
                        <div class="admin-thread__name">{{ $u->name }}</div>
                        <div class="admin-thread__preview">{{ $preview }}</div>
                    </div>
                </div>
                <div class="admin-thread__right">
                    @if($time !== '')
                        <div class="admin-thread__time">{{ $time }}</div>
                    @else
                        <div class="admin-thread__time">&nbsp;</div>
                    @endif
                    @if($unread > 0)
                        <div class="admin-unread">{{ $unread > 99 ? '99+' : $unread }}</div>
                    @else
                        <div style="height:22px"></div>
                    @endif
                </div>
            </a>
        @empty
            <div class="p-5 text-center text-muted">Belum ada pesan.</div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const input = document.getElementById('adminMsgSearch');
        const list = document.getElementById('adminThreadList');
        if (!input || !list) return;
        function apply() {
            const q = String(input.value || '').trim().toLowerCase();
            list.querySelectorAll('[data-name]').forEach((row) => {
                const name = row.getAttribute('data-name') || '';
                row.style.display = q === '' || name.includes(q) ? '' : 'none';
            });
        }
        input.addEventListener('input', apply);
        apply();
    })();
</script>
@endpush
@endsection
