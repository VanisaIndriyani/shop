@extends('layouts.app')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">About REFRENS</h2>
            <p class="mt-4 text-lg text-gray-500">
                Redefining comfort with our signature T-shirt Boxy collection.
            </p>
        </div>

        <div class="mt-16">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div>
                    <img class="w-full rounded-lg shadow-lg" src="{{ asset('img/logo.jpeg') }}" alt="About REFRENS">
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Our Story</h3>
                    <p class="mt-4 text-gray-500 text-lg">
                        REFRENS was born from a simple idea: the perfect t-shirt should be effortless, comfortable, and stylish. We specialize exclusively in <strong>Boxy Fit T-shirts</strong>, designed to provide a relaxed, modern silhouette that suits everyone.
                    </p>
                    <p class="mt-4 text-gray-500 text-lg">
                        Quality is at the heart of everything we do. We use premium heavyweight cotton that holds its shape while remaining soft against the skin. Whether you're dressing up or keeping it casual, a REFRENS T-shirt is the essential building block of your wardrobe.
                    </p>
                    
                    <div class="mt-8">
                        <h4 class="text-xl font-bold text-gray-900">Follow Us</h4>
                        <p class="mt-2 text-gray-500">
                            Instagram: <a href="https://instagram.com/refrens.co" class="text-indigo-600 hover:text-indigo-500">@refrens.co</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection