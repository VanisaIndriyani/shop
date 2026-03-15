@extends('layouts.admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <div class="fw-bold fs-4">Detail Pesanan #{{ $order->id }}</div>
        <div class="text-muted">{{ $order->user?->name }} • {{ $order->created_at->format('d M Y H:i') }}</div>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card content-card">
            <div class="card-header bg-white border-0 px-4 py-3 fw-bold">Item Pesanan</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th class="pe-4 text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $item->product?->name }}</td>
                                <td class="fw-semibold">{{ $item->quantity }}</td>
                                <td class="fw-semibold">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="pe-4 text-end fw-semibold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-body d-flex justify-content-end">
                <div class="text-end">
                    <div class="text-muted small">Total</div>
                    <div class="fs-4 fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card content-card mb-4">
            <div class="card-header bg-white border-0 px-4 py-3 fw-bold">Info Pengiriman</div>
            <div class="card-body px-4">
                <div class="text-muted small mb-1">Alamat</div>
                <div class="fw-semibold">{{ $order->shipping_address }}</div>
                <hr>
                <div class="text-muted small mb-1">Kurir</div>
                <div class="fw-semibold">{{ $order->shipping_courier ? strtoupper($order->shipping_courier) : '-' }}</div>
                <div class="mt-2 text-muted small mb-1">No Resi</div>
                <div class="fw-bold text-primary">{{ $order->tracking_number ?: '-' }}</div>
                @if($order->shipping_note)
                    <div class="mt-2 text-muted small mb-1">Catatan</div>
                    <div class="fw-semibold">{{ $order->shipping_note }}</div>
                @endif
                @if($order->shipped_at)
                    <div class="mt-2 text-muted small mb-1">Tanggal Dikirim</div>
                    <div class="fw-semibold">{{ $order->shipped_at->format('d M Y, H:i') }}</div>
                @endif
                <hr>
                <div class="text-muted small mb-1">Metode Pembayaran</div>
                <div class="fw-semibold">{{ strtoupper($order->payment_method) }}</div>
            </div>
        </div>

        <div class="card content-card">
            <div class="card-header bg-white border-0 px-4 py-3 fw-bold">Status</div>
            <div class="card-body px-4">
                <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="d-flex flex-column gap-3">
                    @csrf
                    @method('PATCH')
                    <div class="d-flex gap-2">
                        <select id="orderStatus" name="status" class="form-select">
                            <option value="pending" @selected($order->status === 'pending')>PENDING</option>
                            <option value="paid" @selected($order->status === 'paid')>PAID</option>
                            <option value="processing" @selected($order->status === 'processing')>PROCESSING</option>
                            <option value="shipped" @selected($order->status === 'shipped')>SHIPPED</option>
                            <option value="completed" @selected($order->status === 'completed')>COMPLETED</option>
                            <option value="cancelled" @selected($order->status === 'cancelled')>CANCELLED</option>
                        </select>
                        <button class="btn btn-primary" type="submit">Update</button>
                    </div>

                    <div id="shippingFields" class="border rounded-4 p-3">
                        <div class="fw-bold mb-2">Resi Pengiriman</div>
                        <div class="row g-2">
                            <div class="col-12">
                                <label class="form-label small text-muted fw-semibold mb-1">Kurir</label>
                                <select name="shipping_courier" class="form-select">
                                    <option value="" @selected(!$order->shipping_courier)>Pilih kurir</option>
                                    <option value="JNE" @selected($order->shipping_courier === 'JNE')>JNE</option>
                                    <option value="J&T" @selected($order->shipping_courier === 'J&T')>J&T</option>
                                    <option value="SiCepat" @selected($order->shipping_courier === 'SiCepat')>SiCepat</option>
                                    <option value="AnterAja" @selected($order->shipping_courier === 'AnterAja')>AnterAja</option>
                                    <option value="POS" @selected($order->shipping_courier === 'POS')>POS</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted fw-semibold mb-1">No Resi</label>
                                <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" class="form-control" placeholder="Contoh: JNEXXXXXXXX">
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted fw-semibold mb-1">Catatan (opsional)</label>
                                <textarea name="shipping_note" rows="2" class="form-control" placeholder="Contoh: Dikirim via JNE REG, estimasi 2-3 hari">{{ old('shipping_note', $order->shipping_note) }}</textarea>
                            </div>
                            @if($order->shipped_at)
                                <div class="col-12">
                                    <div class="text-muted small">Dikirim: <span class="fw-semibold">{{ $order->shipped_at->format('d M Y, H:i') }}</span></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const statusEl = document.getElementById('orderStatus');
        const fields = document.getElementById('shippingFields');
        function sync() {
            const v = statusEl ? statusEl.value : '';
            const show = v === 'shipped' || v === 'completed';
            if (fields) fields.style.display = show ? 'block' : 'none';
        }
        if (statusEl) statusEl.addEventListener('change', sync);
        sync();
    })();
</script>
@endpush
@endsection
