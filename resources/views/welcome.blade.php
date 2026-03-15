@extends('layouts.app')

@section('content')
<!-- Hero Section (Slider Style) -->
<div class="relative bg-white overflow-hidden">
    <div class="swiper mySwiper" style="height: 100svh; position: relative; z-index: 1;">
        <div class="swiper-wrapper">
            <!-- Slide 1 -->
            <div class="swiper-slide relative">
                <a href="{{ route('shop.index') }}" class="absolute inset-0 bg-white flex items-center justify-center">
                    <img src="{{ asset('img/slide1.jpeg') }}" alt="Slide 1" class="w-full h-full object-cover opacity-100 transition-transform duration-700 hover:scale-105">
                </a>
            </div>
            
            <!-- Slide 2 -->
            <div class="swiper-slide relative">
                <a href="{{ route('shop.index') }}" class="absolute inset-0 bg-white flex items-center justify-center">
                    <img src="{{ asset('img/slide2.jpeg') }}" alt="Slide 2" class="w-full h-full object-cover opacity-100 transition-transform duration-700 hover:scale-105">
                </a>
            </div>

            <!-- Slide 3 -->
            <div class="swiper-slide relative">
                <a href="{{ route('shop.index') }}" class="absolute inset-0 bg-white flex items-center justify-center">
                    <img src="{{ asset('img/slide 3.jpeg') }}" alt="Slide 3" class="w-full h-full object-cover opacity-100 transition-transform duration-700 hover:scale-105">
                </a>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="pointer-events-none" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;z-index:5;">
        <div style="padding:8px 16px;border-radius:999px;border:2px solid #2563eb;background:rgba(255,255,255,.92);box-shadow:0 12px 28px rgba(0,0,0,.16);">
            <span style="color:#1d4ed8;font-weight:900;font-size:clamp(16px,4.2vw,24px);letter-spacing:.6px;text-transform:uppercase;">Shop</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 0,
            centeredSlides: true,
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });
    });
</script>
@endpush
@endsection
