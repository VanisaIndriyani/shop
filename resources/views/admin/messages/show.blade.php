@extends('layouts.admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <div class="fw-bold fs-4">Chat dengan {{ $user->name }}</div>
        <div class="text-muted">Balas pesan customer dari sini.</div>
    </div>
    <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

<div class="card content-card overflow-hidden">
    <div class="card-body p-0">
        <div id="admin-chat-area" class="p-4" style="height: 55vh; overflow-y: auto; background: #f7f9fc;">
            @forelse($messages as $msg)
                <div class="d-flex {{ $msg->is_from_admin ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                    <div class="px-3 py-2 rounded-4 shadow-sm {{ $msg->is_from_admin ? 'text-white' : 'bg-white border' }}" style="{{ $msg->is_from_admin ? 'background: linear-gradient(135deg,#0d6efd,#4f46e5);' : '' }} max-width: 78%;">
                        <div class="small fw-semibold mb-1 {{ $msg->is_from_admin ? 'text-white-50' : 'text-muted' }}">
                            {{ $msg->is_from_admin ? 'Admin' : $user->name }}
                        </div>
                        <div class="fw-semibold">{{ $msg->message }}</div>
                        <div class="text-end small mt-1 {{ $msg->is_from_admin ? 'text-white-50' : 'text-muted' }}">
                            {{ $msg->created_at->format('H:i') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">Belum ada pesan.</div>
            @endforelse
        </div>
    </div>
    <div class="card-footer bg-white border-0 p-4">
        <form action="{{ route('admin.messages.reply', $user->id) }}" method="POST" class="d-flex gap-2">
            @csrf
            <input type="text" name="message" class="form-control" placeholder="Tulis balasan..." required>
            <button class="btn btn-primary px-4" type="submit">Kirim</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatArea = document.getElementById('admin-chat-area');
        if (chatArea) chatArea.scrollTop = chatArea.scrollHeight;
    });
</script>
@endsection
