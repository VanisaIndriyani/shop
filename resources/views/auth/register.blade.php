@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<div class="container py-5" style="min-height: calc(100vh - 140px);">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-9">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0">
                    <div class="col-lg-5 d-none d-lg-block">
                        <div class="h-100 p-4 text-white d-flex flex-column justify-content-between" style="background: linear-gradient(135deg,#111827,#0d6efd);">
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-white rounded-3 p-2">
                                        <img src="{{ asset('img/logo.jpeg') }}" alt="Logo" style="height:34px;width:auto;">
                                    </div>
                                    <div class="fw-bold fs-4">REFRENS</div>
                                </div>
                                <div class="mt-4 fw-bold fs-3">Create Account</div>
                                <div class="opacity-75 mt-2">Daftar dulu biar bisa checkout, cek pesanan, dan chat admin.</div>
                            </div>
                            <div class="small opacity-75">© {{ date('Y') }} REFRENS</div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="p-4 p-md-5">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div>
                                    <div class="fw-bold fs-4">Daftar</div>
                                    <div class="text-muted">Sudah punya akun? <a href="{{ route('account.index', ['login' => 1]) }}" class="text-decoration-none fw-semibold">Masuk</a></div>
                                </div>
                                <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">Kembali</a>
                            </div>

                            <form action="{{ route('register') }}" method="POST" class="row g-3">
                                @csrf

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Nama Lengkap</label>
                                    <input name="name" type="text" value="{{ old('name') }}" class="form-control form-control-lg @error('name') is-invalid @enderror" placeholder="Nama kamu" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input name="email" type="email" value="{{ old('email') }}" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="nama@email.com" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Nomor HP</label>
                                    <input name="phone" type="text" value="{{ old('phone') }}" class="form-control form-control-lg @error('phone') is-invalid @enderror" placeholder="08xxxx" required>
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Alamat</label>
                                    <textarea name="address" rows="3" class="form-control form-control-lg @error('address') is-invalid @enderror" placeholder="Alamat lengkap untuk pengiriman" required>{{ old('address') }}</textarea>
                                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">PIN (6 digit)</label>
                                    <div class="input-group input-group-lg">
                                        <input id="registerPassword" name="password" type="password" inputmode="numeric" pattern="[0-9]*" maxlength="6" class="form-control @error('password') is-invalid @enderror" placeholder="••••••" required>
                                        <button class="btn btn-outline-secondary" type="button" data-toggle-password="#registerPassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold">Buat Akun</button>
                                </div>
                            </form>

                            <div class="mt-4 small text-muted text-center">
                                Pastikan PIN kamu 6 digit angka.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('[data-toggle-password]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const selector = btn.getAttribute('data-toggle-password');
            const input = document.querySelector(selector);
            if (!input) return;
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            const icon = btn.querySelector('i');
            if (icon) icon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    });
</script>
@endpush
@endsection
