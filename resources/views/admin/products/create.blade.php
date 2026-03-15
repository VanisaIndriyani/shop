@extends('layouts.admin')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <div class="fw-bold fs-4">Tambah Produk</div>
        <div class="text-muted">Masukkan detail produk baru.</div>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

<div class="card content-card">
    <div class="card-body p-4">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
            @csrf

            <div class="col-12 col-md-8">
                <label class="form-label fw-semibold">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Kategori</label>
                <input type="text" name="category" value="{{ old('category') }}" class="form-control @error('category') is-invalid @enderror" required>
                @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Tipe Produk</label>
                <select name="product_type" class="form-select @error('product_type') is-invalid @enderror">
                    <option value="" @selected(!old('product_type'))>Pilih tipe</option>
                    <option value="Top" @selected(old('product_type') === 'Top')>Top</option>
                    <option value="Bottom" @selected(old('product_type') === 'Bottom')>Bottom</option>
                    <option value="Accessories" @selected(old('product_type') === 'Accessories')>Accessories</option>
                </select>
                @error('product_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Harga</label>
                <input type="number" name="price" value="{{ old('price') }}" class="form-control @error('price') is-invalid @enderror" required>
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Harga Promo</label>
                <input type="number" name="sale_price" value="{{ old('sale_price') }}" class="form-control @error('sale_price') is-invalid @enderror">
                @error('sale_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', 0) }}" class="form-control @error('stock') is-invalid @enderror" required>
                @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Size</label>
                @php $sizes = old('sizes', []); @endphp
                <div class="d-flex flex-wrap gap-2">
                    @foreach(['S','M','L','XL','39','40','41','42','43'] as $s)
                        <label class="btn btn-outline-secondary">
                            <input type="checkbox" name="sizes[]" value="{{ $s }}" class="d-none" {{ in_array($s, $sizes) ? 'checked' : '' }}>
                            <span class="fw-semibold">{{ $s }}</span>
                        </label>
                    @endforeach
                </div>
                @error('sizes') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                @error('sizes.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Gambar Produk (wajib 3 foto)</label>
                <div class="row g-3">
                    @for($i = 0; $i < 3; $i++)
                        <div class="col-12 col-md-4">
                            <div class="rounded-4 border bg-light overflow-hidden d-flex align-items-center justify-content-center mb-2" style="aspect-ratio:1/1;">
                                <img id="preview{{ $i }}" alt="Preview {{ $i + 1 }}" style="width:100%;height:100%;object-fit:cover;display:none;">
                                <div id="placeholder{{ $i }}" class="text-muted small fw-semibold">Foto {{ $i + 1 }}</div>
                            </div>
                            <input id="productImage{{ $i }}" type="file" name="images[{{ $i }}]" accept="image/*" class="form-control @error('images') is-invalid @enderror @error('images.'.$i) is-invalid @enderror" required>
                        </div>
                    @endfor
                </div>
                @error('images') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                @error('images.0') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                @error('images.1') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                @error('images.2') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                <div class="form-text mt-2">Upload 3 foto satu-satu (Foto 1, Foto 2, Foto 3). Maks 2MB per foto.</div>
            </div>

            <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                <button id="submitBtn" type="submit" class="btn btn-primary px-4" disabled>Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const submitBtn = document.getElementById('submitBtn');
        const inputs = [0, 1, 2].map(i => document.getElementById(`productImage${i}`));
        const previews = [0, 1, 2].map(i => document.getElementById(`preview${i}`));
        const placeholders = [0, 1, 2].map(i => document.getElementById(`placeholder${i}`));

        function updateState() {
            inputs.forEach((input, idx) => {
                const file = (input.files && input.files[0]) ? input.files[0] : null;
                if (!file) {
                    previews[idx].src = '';
                    previews[idx].style.display = 'none';
                    placeholders[idx].style.display = 'block';
                    return;
                }
                placeholders[idx].style.display = 'none';
                previews[idx].style.display = 'block';
                previews[idx].src = URL.createObjectURL(file);
            });

            const ok = inputs.every((i) => i.files && i.files.length === 1);
            submitBtn.disabled = !ok;
        }

        inputs.forEach((i) => i.addEventListener('change', updateState));
        updateState();
    })();
</script>
@endpush
@endsection
