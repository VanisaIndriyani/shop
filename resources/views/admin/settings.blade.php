@extends('layouts.admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <div class="fw-bold fs-4">Pengaturan</div>
        <div class="text-muted">Ubah email dan password admin.</div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="card content-card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.settings.update') }}" class="d-flex flex-column gap-3">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <hr class="my-1">

                    <div>
                        <label class="form-label fw-semibold">Password Lama</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Isi kalau mau ganti password">
                        @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Password Baru</label>
                            <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Minimal 6 karakter">
                            @error('new_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" class="form-control" placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card content-card">
            <div class="card-body p-4">
                <div class="fw-bold mb-2">Akun</div>
                <div class="text-muted small">Nama</div>
                <div class="fw-semibold">{{ $user->name }}</div>
                <div class="mt-3 text-muted small">Role</div>
                <div class="fw-semibold">{{ strtoupper($user->role ?? 'admin') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

