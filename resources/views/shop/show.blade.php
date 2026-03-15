@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Breadcrumb (Optional but good for UX) -->
        <nav class="flex mb-8 text-sm text-gray-500">
            <a href="{{ route('shop.index') }}" class="hover:text-black transition-colors">Home</a>
            <span class="mx-2">/</span>
            <span class="text-black font-medium truncate">{{ $product->name }}</span>
        </nav>

        <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 lg:items-start">
            <div class="flex flex-col gap-4">
                @php
                    $gallery = $product->images ?: [];
                    if (empty($gallery) && $product->image) {
                        $gallery = [$product->image];
                    }
                    if (count($gallery) < 3 && count($gallery) > 0) {
                        $first = $gallery[0];
                        while (count($gallery) < 3) {
                            $gallery[] = $first;
                        }
                    }
                @endphp
                <div id="productCarousel" class="carousel slide rounded-2" data-bs-ride="carousel">
                    <div class="carousel-inner" style="aspect-ratio: 3/4;">
                        @if(!empty($gallery))
                            @foreach($gallery as $idx => $img)
                                <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $img) }}" class="d-block w-100" alt="{{ $product->name }}" style="object-fit: cover; height: 100%;">
                                </div>
                            @endforeach
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light text-muted" style="height: 100%;">
                                <svg class="h-24 w-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        @if($product->stock <= 0)
                            <span class="badge text-bg-dark position-absolute top-0 start-0 m-2 rounded-pill">Sold Out</span>
                        @endif
                    </div>
                    @if(!empty($gallery))
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
                @if(!empty($gallery))
                    <div class="d-flex gap-2 mt-2">
                        @foreach($gallery as $idx => $img)
                            <button class="p-0 border-0 bg-transparent" data-bs-target="#productCarousel" data-bs-slide-to="{{ $idx }}">
                                <img src="{{ asset('storage/' . $img) }}" alt="thumb" class="rounded-2" style="width:72px;height:72px;object-fit:cover;">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right: Product Info -->
            <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0" x-data="{ size: '', quantity: 1, showSizeGuide: false }">
                <div class="border-b border-gray-200 pb-6">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">{{ $product->name }}</h1>
                    <p class="text-2xl font-bold text-red-600" data-money-idr="{{ (float) $product->price }}">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>

                <div class="py-6">
                    <!-- Size Selector -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-gray-900">Ukuran</h3>
                            <button @click="showSizeGuide = true" class="text-sm text-gray-500 underline hover:text-black flex items-center">
                                Size Guide <span class="ml-1">&rsaquo;</span>
                            </button>
                        </div>
                        @php
                            $sizeOrder = ['S','M','L','XL','39','40','41','42','43'];
                            $sizeValues = is_array($product->sizes) ? $product->sizes : [];
                            $sizeValues = array_values(array_filter($sizeValues, fn ($v) => $v !== null && $v !== ''));
                            if (count($sizeValues) === 0) {
                                $sizeValues = ['M', 'L', 'XL'];
                            } else {
                                usort($sizeValues, function ($a, $b) use ($sizeOrder) {
                                    $ia = array_search((string) $a, $sizeOrder, true);
                                    $ib = array_search((string) $b, $sizeOrder, true);
                                    $ia = $ia === false ? 999 : $ia;
                                    $ib = $ib === false ? 999 : $ib;
                                    return $ia <=> $ib;
                                });
                            }
                        @endphp
                        <div class="flex flex-wrap gap-3">
                            @foreach($sizeValues as $s)
                                <button @click="size = '{{ $s }}'"
                                        class="relative border py-3 px-6 text-sm font-medium uppercase transition-all duration-200 focus:outline-none flex items-center justify-center overflow-hidden"
                                        :class="size === '{{ $s }}' ? 'border-blue-600 text-blue-600 bg-blue-50 ring-1 ring-blue-600' : 'border-gray-200 text-gray-900 hover:border-gray-400 bg-white'">
                                    {{ $s }}
                                    @if($product->stock <= 0)
                                        <div class="absolute inset-0">
                                            <svg class="absolute inset-0 w-full h-full text-gray-200" preserveAspectRatio="none" stroke="currentColor" fill="none" viewBox="0 0 100 100" vector-effect="non-scaling-stroke">
                                                <line x1="0" y1="100" x2="100" y2="0" stroke-width="2" />
                                            </svg>
                                        </div>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-8">
                        <h3 class="text-sm font-medium text-gray-900 mb-4">Jumlah</h3>
                        <div class="flex items-center w-32">
                            <button @click="quantity > 1 ? quantity-- : null" class="px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-l-md focus:outline-none transition-colors h-10 flex items-center justify-center">
                                -
                            </button>
                            <input type="text" x-model="quantity" class="w-12 h-10 text-center border-t border-b border-gray-300 focus:ring-0 text-gray-900 font-medium p-0" readonly>
                            <button @click="quantity++" class="px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-r-md focus:outline-none transition-colors h-10 flex items-center justify-center">
                                +
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="size" x-model="size">
                        <input type="hidden" name="quantity" x-model="quantity">
                        
                        <button type="submit" 
                                :disabled="!size || {{ $product->stock <= 0 ? 'true' : 'false' }}"
                                class="w-full bg-blue-600 border border-transparent rounded-full py-4 px-8 flex items-center justify-center text-base font-bold text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 disabled:bg-gray-300 disabled:cursor-not-allowed transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            {{ $product->stock > 0 ? 'ADD TO CART' : 'OUT OF STOCK' }}
                        </button>
                        
                        @if($product->stock > 0)
                    
                        @endif
                    </form>
                </div>

                <!-- Info Accordions (Preface Style) -->
                <div class="border-t border-gray-200 mt-8">
                    <div x-data="{ expanded: false }" class="border-b border-gray-200">
                        <button @click="expanded = !expanded" class="flex justify-between items-center w-full py-4 text-left text-sm font-medium text-gray-900 focus:outline-none">
                            <span>Deskripsi Produk</span>
                            <span x-show="!expanded">+</span>
                            <span x-show="expanded" style="display: none;">-</span>
                        </button>
                        <div x-show="expanded" x-collapse style="display: none;" class="pb-4 text-sm text-gray-600 leading-relaxed">
                            {!! nl2br(e((string) $product->description)) !!}
                        </div>
                    </div>
                    
                    <!-- Size Guide Image in Accordion -->
                    <div x-data="{ expanded: true }" class="border-b border-gray-200">
                        <button @click="expanded = !expanded" class="flex justify-between items-center w-full py-4 text-left text-sm font-medium text-gray-900 focus:outline-none">
                            <span>Panduan Ukuran</span>
                            <span x-show="!expanded">+</span>
                            <span x-show="expanded" style="display: none;">-</span>
                        </button>
                        <div x-show="expanded" x-collapse class="pb-4">
                            <img src="{{ asset('img/ukuran.jpeg') }}" alt="Size Chart" class="w-full rounded-lg border border-gray-200">
                        </div>
                    </div>

                    <div x-data="{ expanded: false }" class="border-b border-gray-200">
                        <button @click="expanded = !expanded" class="flex justify-between items-center w-full py-4 text-left text-sm font-medium text-gray-900 focus:outline-none">
                            <span>Info Pengiriman</span>
                            <span x-show="!expanded">+</span>
                            <span x-show="expanded" style="display: none;">-</span>
                        </button>
                        <div x-show="expanded" x-collapse style="display: none;" class="pb-4 text-sm text-gray-600 leading-relaxed">
                            <p>Pengiriman setiap hari Senin - Sabtu pukul 17:00 WIB.</p>
                            <p class="mt-2">Order lewat dari 16:00 WIB akan dikirimkan pada hari berikutnya.</p>
                        </div>
                    </div>

                    <div x-data="{ expanded: false }" class="border-b border-gray-200">
                        <button @click="expanded = !expanded" class="flex justify-between items-center w-full py-4 text-left text-sm font-medium text-gray-900 focus:outline-none">
                            <span>Kebijakan Pengembalian</span>
                            <span x-show="!expanded">+</span>
                            <span x-show="expanded" style="display: none;">-</span>
                        </button>
                        <div x-show="expanded" x-collapse style="display: none;" class="pb-4 text-sm text-gray-600 leading-relaxed">
                            <p>Penukaran produk hanya berlaku untuk produk yang mengalami defect/cacat.</p>
                            <p class="mt-2">Wajib menyertakan video unboxing.</p>
                        </div>
                    </div>
                </div>

                <!-- Size Guide Modal -->
                <div x-show="showSizeGuide" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" role="dialog" aria-modal="true">
                    <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity" @click="showSizeGuide = false"></div>
                    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                        <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Size Guide</h3>
                                    <button @click="showSizeGuide = false" class="text-gray-400 hover:text-gray-500">
                                        <span class="sr-only">Close</span>
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <img src="{{ asset('img/ukuran.jpeg') }}" alt="Size Chart" class="w-full">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <div class="mt-24 border-t border-gray-200 pt-16 pb-16">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900 text-center mb-12">Rekomendasi Lainnya</h2>
            
            <div style="display: grid !important; grid-template-columns: repeat(2, 1fr) !important; gap: 16px !important; width: 100% !important;" class="md:grid-cols-4">
                @foreach(\App\Models\Product::where('id', '!=', $product->id)->take(4)->get() as $related)
                <div class="group relative bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="aspect-[3/4] w-full overflow-hidden bg-gray-50 relative">
                        <a href="{{ route('shop.show', $related->slug) }}">
                            @if($related->image)
                                <img src="{{ asset('storage/' . $related->image) }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-300">
                                    <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </a>
                    </div>
                    <div class="p-4 text-center">
                        <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wide truncate mb-1">
                            <a href="{{ route('shop.show', $related->slug) }}">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                {{ $related->name }}
                            </a>
                        </h3>
                        <p class="text-sm font-black text-blue-600" data-money-idr="{{ (float) $related->price }}">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
@endsection
