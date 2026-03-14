@extends('layouts.admin')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <div class="fw-bold fs-4">Pesan Customer</div>
        <div class="text-muted">Kelola percakapan customer.</div>
    </div>
</div>

<div class="card content-card">
    <div class="list-group list-group-flush">
        @forelse($users as $item)
            @php
                $lastMessage = \App\Models\Message::where('user_id', $item->user_id)->latest()->first();
                $unreadCount = \App\Models\Message::where('user_id', $item->user_id)->where('is_from_admin', false)->where('is_read', false)->count();
            @endphp
            <a href="{{ route('admin.messages.show', $item->user_id) }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width:44px;height:44px;">
                        {{ substr($item->user->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="fw-bold">{{ $item->user->name }}</div>
                        <div class="text-muted small">
                            @if($lastMessage)
                                {{ $lastMessage->is_from_admin ? 'Anda: ' : '' }}{{ \Illuminate\Support\Str::limit($lastMessage->message, 50) }}
                            @else
                                Belum ada pesan
                            @endif
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    @if($lastMessage)
                        <div class="text-muted small">{{ $lastMessage->created_at->diffForHumans() }}</div>
                    @endif
                    @if($unreadCount > 0)
                        <span class="badge rounded-pill text-bg-danger mt-1">{{ $unreadCount }}</span>
                    @endif
                </div>
            </a>
        @empty
            <div class="p-5 text-center text-muted">Belum ada pesan.</div>
        @endforelse
    </div>
</div>
@endsection

