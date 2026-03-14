@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<div class="bg-white min-h-screen" x-data="{ filterOpen: false, sortOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        
        <!-- Filter & Sort Buttons -->
        <div class="flex gap-4 mb-6 sticky top-16 z-30 bg-white py-2 -mx-4 px-4 shadow-sm sm:static sm:bg-transparent sm:shadow-none sm:py-0 sm:mx-0 sm:px-0">
            @php
                $hasFilters = request('categories') || request('min_price') || request('max_price');
                $hasSort = request('sort') && request('sort') !== 'latest';
            @endphp
            <button @click="filterOpen = true" class="flex-1 flex items-center justify-center px-4 py-2.5 border {{ $hasFilters ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-blue-600 border-blue-600 hover:bg-blue-50' }} rounded-full font-medium text-sm transition-all shadow-sm active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                Filter {{ $hasFilters ? '(Aktif)' : '' }}
            </button>
            <button @click="sortOpen = true" class="flex-1 flex items-center justify-center px-4 py-2.5 border {{ $hasSort ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-blue-600 border-blue-600 hover:bg-blue-50' }} rounded-full font-medium text-sm transition-all shadow-sm active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                Urutan
            </button>
        </div>

        <!-- Filter Modal -->
        <div x-show="filterOpen" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="filterOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="filterOpen = false"></div>

                <div x-show="filterOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-full" class="fixed bottom-4 inset-x-4 w-auto bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:relative">
                    <form action="{{ route('shop.index') }}" method="GET">
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        <div class="bg-white px-4 pt-3 pb-4 sm:p-6 sm:pb-4">
                            <div class="mx-auto w-12 h-1.5 bg-blue-100 rounded-full mb-4"></div>
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg leading-6 font-bold text-gray-900">Filter Produk</h3>
                                <button type="button" @click="filterOpen = false" class="text-gray-400 hover:text-gray-500">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <!-- Filter Options -->
                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 mb-2">Kategori</h4>
                                    <div class="space-y-2">
                                        @foreach($categories as $category)
                                            <label class="flex items-center">
                                                <input type="checkbox" name="categories[]" value="{{ $category }}" class="rounded text-blue-600 focus:ring-blue-500 border-gray-300" {{ in_array($category, request('categories', [])) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">{{ $category }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 mb-2">Harga</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-row-reverse gap-2 sm:gap-3">
                            <button type="submit" class="flex-1 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm">
                                Terapkan
                            </button>
                            <a href="{{ route('shop.index') }}" class="flex-1 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sort Modal -->
        <div x-show="sortOpen" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="sortOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="sortOpen = false"></div>

                <div x-show="sortOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-full" class="fixed bottom-4 inset-x-4 w-auto bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:relative">
                    <div class="bg-white px-4 pt-3 pb-4 sm:p-6 sm:pb-4">
                        <div class="mx-auto w-12 h-1.5 bg-blue-100 rounded-full mb-4"></div>
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg leading-6 font-bold text-gray-900">Urutkan Produk</h3>
                            <button @click="sortOpen = false" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="space-y-1">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" class="block w-full text-left px-4 py-3 rounded-lg text-sm font-medium border {{ request('sort', 'latest') == 'latest' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'text-gray-900 border-transparent hover:bg-gray-50 hover:border-gray-200' }}">Terbaru</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}" class="block w-full text-left px-4 py-3 rounded-lg text-sm font-medium border {{ request('sort') == 'popular' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'text-gray-900 border-transparent hover:bg-gray-50 hover:border-gray-200' }}">Terpopuler</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" class="block w-full text-left px-4 py-3 rounded-lg text-sm font-medium border {{ request('sort') == 'price_asc' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'text-gray-900 border-transparent hover:bg-gray-50 hover:border-gray-200' }}">Harga Terendah</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" class="block w-full text-left px-4 py-3 rounded-lg text-sm font-medium border {{ request('sort') == 'price_desc' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'text-gray-900 border-transparent hover:bg-gray-50 hover:border-gray-200' }}">Harga Tertinggi</a>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-row-reverse gap-2 sm:gap-3">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => null]) }}" class="flex-1 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm">
                            Reset
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
            @foreach($products as $product)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm rounded-4">
                    <div class="position-relative">
                        @if($product->stock <= 0)
                            <span class="badge text-bg-dark position-absolute top-0 end-0 m-2 rounded-pill">Sold Out</span>
                        @elseif($product->created_at->diffInDays(now()) < 7)
                            <span class="badge text-bg-primary position-absolute top-0 start-0 m-2 rounded-pill">New</span>
                        @endif
                        <a href="{{ route('shop.show', $product->slug) }}" class="d-block">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="card-img-top" style="aspect-ratio: 4/5; object-fit: cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light text-muted" style="aspect-ratio: 4/5;">
                                    <i class="bi bi-image fs-3"></i>
                                </div>
                            @endif
                        </a>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title fs-6 fw-bold text-truncate">
                            <a href="{{ route('shop.show', $product->slug) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                        </h3>
                        <div class="mt-1">
                            <div class="fw-black text-primary" data-money-idr="{{ (float) $product->price }}">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
