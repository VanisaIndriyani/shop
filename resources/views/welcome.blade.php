@extends('layouts.app')

@section('content')
<style>
    .mySwiper .swiper-wrapper {
        transition-timing-function: cubic-bezier(0.25, 1, 0.5, 1) !important;
    }
    .mySwiper .swiper-slide {
        transform: translate3d(0,0,0);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }
</style>
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
      
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var swiper = new Swiper(".mySwiper", {
            direction: 'horizontal',
            loop: true,
            speed: 600, // Lebih smooth
            roundLengths: true,
            touchRatio: 0.8, // Tidak terlalu liar
            threshold: 10,
            followFinger: true,
            resistanceRatio: 0.85,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false, // Tetap autoplay walau disentuh
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
