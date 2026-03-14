@extends('layouts.admin')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <div class="fw-bold fs-4">Stok Produk</div>
        <div class="text-muted">Kelola produk dan stok di toko Anda.</div>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Tambah Produk
    </a>
</div>

<div class="card content-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th class="pe-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-light rounded-3 border d-flex align-items-center justify-content-center overflow-hidden" style="width:48px;height:48px;">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width:48px;height:48px;object-fit:cover;">
                                    @else
                                        <i class="bi bi-image text-muted"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    <div class="text-muted small">{{ $product->created_at?->format('d M Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="fw-semibold">{{ $product->category }}</td>
                        <td class="fw-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge rounded-pill {{ $product->stock > 0 ? 'text-bg-success' : 'text-bg-danger' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus produk ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">Belum ada produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">
        {{ $products->links() }}
    </div>
</div>
@endsection

