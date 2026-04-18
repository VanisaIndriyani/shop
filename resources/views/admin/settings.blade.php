@extends('layouts.admin')

@section('content')
<style>
    .settings-hero{border:0;border-radius:18px;box-shadow:0 12px 30px rgba(16,24,40,.06);background:linear-gradient(135deg,#2563eb,#4f46e5)}
    .settings-hero__inner{padding:18px;color:#fff}
    .settings-card{border:0;border-radius:18px;box-shadow:0 12px 30px rgba(16,24,40,.06)}
    .settings-label{font-weight:800;color:#334155;font-size:13px}
    .settings-input{border-radius:12px;padding:11px 12px}
    .settings-icon{width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:rgba(37,99,235,.10);color:#2563eb}
    .settings-profile{display:flex;align-items:center;gap:12px}
    .settings-avatar{width:52px;height:52px;border-radius:999px;background:rgba(37,99,235,.14);color:#2563eb;font-weight:900;display:flex;align-items:center;justify-content:center}
</style>

<div class="settings-hero mb-4">
    <div class="settings-hero__inner">
        <div class="fw-bold fs-4">Pengaturan</div>
        <div class="opacity-75">Ubah email dan password admin dengan aman.</div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="card settings-card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.settings.update') }}" class="d-flex flex-column gap-3">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="form-label settings-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control settings-input @error('email') is-invalid @enderror" required>
                        </div>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <hr class="my-1">

                    <div>
                        <label class="form-label settings-label">Password Lama</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                            <input type="password" name="current_password" class="form-control settings-input @error('current_password') is-invalid @enderror" placeholder="Isi kalau mau ganti password">
                        </div>
                        @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label settings-label">Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-key"></i></span>
                                <input type="password" name="new_password" class="form-control settings-input @error('new_password') is-invalid @enderror" placeholder="Minimal 6 karakter">
                            </div>
                            @error('new_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label settings-label">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-shield-lock"></i></span>
                                <input type="password" name="new_password_confirmation" class="form-control settings-input" placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                            <i class="bi bi-check2-circle me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card settings-card">
            <div class="card-body p-4">
                <div class="fw-bold mb-3">Akun</div>
                <div class="settings-profile mb-3">
                    <div class="settings-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div>
                        <div class="fw-bold">{{ $user->name }}</div>
                        <div class="text-muted small">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="settings-icon"><i class="bi bi-person-badge"></i></div>
                        <div>
                            <div class="text-muted small">Role</div>
                            <div class="fw-semibold">{{ strtoupper($user->role ?? 'admin') }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="settings-icon"><i class="bi bi-shield-check"></i></div>
                        <div>
                            <div class="text-muted small">Keamanan</div>
                            <div class="fw-semibold">Akun terlindungi login admin</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
