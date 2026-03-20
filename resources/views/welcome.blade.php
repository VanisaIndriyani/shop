@extends('layouts.app')

@section('content')
<!-- Hero Section (Slider Style) -->
<div class="relative bg-white overflow-hidden">
    <style>
        .mySwiper { direction: ltr; }
    </style>
    <div class="swiper mySwiper" dir="ltr" style="height: 100svh; position: relative; z-index: 1;">
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
            spaceBetween: 0,
            centeredSlides: true,
            loop: true,
            speed: 850,
            touchRatio: 1,
            followFinger: true,
            threshold: 10,
            autoplay: {
                delay: 4500,
                disableOnInteraction: true,
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
