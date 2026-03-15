@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<div class="container py-5" style="min-height: calc(100vh - 140px);">
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <div class="fw-bold fs-3">Chat Admin</div>
            <div class="text-muted">Tanya admin tentang pesanan, pengiriman, atau produk.</div>
        </div>
        <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary rounded-pill">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-4 border-0">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow rounded-4 overflow-hidden">
        <div class="card-header bg-primary text-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
            <div class="fw-bold">Admin Refrens</div>
            <a href="{{ route('admin.messages.index') }}" class="text-white text-decoration-none small d-none">Admin</a>
        </div>

        <div class="card-body p-4" style="max-height: 65vh; overflow:auto;">
            @forelse($messages as $msg)
                <div class="d-flex {{ $msg->is_from_admin ? 'justify-content-start' : 'justify-content-end' }} mb-3">
                    <div class="px-3 py-2 rounded-4 {{ $msg->is_from_admin ? 'bg-light border' : 'bg-primary text-white' }}" style="max-width: 80%;">
                        <div class="fw-semibold">{{ $msg->message }}</div>
                        <div class="small {{ $msg->is_from_admin ? 'text-muted' : 'text-white-50' }}">{{ $msg->created_at?->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    Belum ada pesan. Tulis pesan pertama kamu ke admin.
                </div>
            @endforelse
        </div>

        <div class="card-footer bg-white border-0 p-4">
            <form action="{{ route('chat.send') }}" method="POST" class="d-flex gap-2">
                @csrf
                <input type="text" name="message" class="form-control form-control-lg rounded-pill @error('message') is-invalid @enderror" placeholder="Tulis pesan..." autofocus>
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                    <i class="bi bi-send"></i>
                </button>
                @error('message') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </form>
        </div>
    </div>
</div>
@endsection

