@extends('layouts.admin')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <div class="fw-bold fs-4">Edit Produk</div>
        <div class="text-muted">{{ $product->name }}</div>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

<div class="card content-card">
    <div class="card-body p-4">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="row g-3">
            @csrf
            @method('PUT')

            <div class="col-12 col-md-8">
                <label class="form-label fw-semibold">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Kategori</label>
                <input type="text" name="category" value="{{ old('category', $product->category) }}" class="form-control @error('category') is-invalid @enderror" required>
                @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Harga</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" class="form-control @error('price') is-invalid @enderror" required>
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Harga Promo</label>
                <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="form-control @error('sale_price') is-invalid @enderror">
                @error('sale_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control @error('stock') is-invalid @enderror" required>
                @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Gambar Produk (3 foto)</label>
                @php
                    $imgs = $product->images ?: ($product->image ? [$product->image] : []);
                    $imgs = array_values($imgs);
                    while (count($imgs) < 3) {
                        $imgs[] = $imgs[0] ?? null;
                    }
                    $imgs = array_slice($imgs, 0, 3);
                @endphp
                <div class="row g-3 mt-1">
                    @for($i = 0; $i < 3; $i++)
                        <div class="col-12 col-md-4">
                            <div class="rounded-4 border bg-light overflow-hidden d-flex align-items-center justify-content-center mb-2" style="aspect-ratio:1/1;">
                                @if(!empty($imgs[$i]))
                                    <img src="{{ asset('storage/' . $imgs[$i]) }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <div class="text-muted small fw-semibold">Foto {{ $i + 1 }}</div>
                                @endif
                            </div>
                            <input id="productImage{{ $i }}" type="file" name="images[{{ $i }}]" accept="image/*" class="form-control @error('images') is-invalid @enderror @error('images.'.$i) is-invalid @enderror">
                        </div>
                    @endfor
                </div>
                @error('images') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                @error('images.0') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                @error('images.1') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                @error('images.2') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                <div class="form-text mt-2">Bisa ganti foto satu-satu (Foto 1/2/3). Yang tidak dipilih akan tetap pakai foto lama.</div>
            </div>

            <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                <button id="submitBtn" type="submit" class="btn btn-primary px-4">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const inputs = [0, 1, 2].map(i => document.getElementById(`productImage${i}`));
        inputs.forEach((i) => {
            if (!i) return;
            i.addEventListener('change', () => {});
        });
    })();
</script>
@endpush
@endsection
