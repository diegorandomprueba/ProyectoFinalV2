@extends('layouts.app')

@section('title', 'Trendfit - Encuentra tu estilo, sin esfuerzo')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-black to-gray-800 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-8 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Encuentra tu estilo, sin esfuerzo.</h1>
                    <p class="text-xl mb-6">Descubre moda que combina a la perfección con tu personalidad y estilo único.</p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('shop') }}" class="bg-orange-500 text-white px-6 py-3 rounded-lg text-center hover:bg-orange-600 transition">
                            Explorar Colección
                        </a>
                        <a href="{{ route('about') }}" class="border border-white text-white px-6 py-3 rounded-lg text-center hover:bg-white hover:text-black transition">
                            Conocer más
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Productos Destacados -->
    <section class="py-12 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Productos Destacados</h2>
            
            <!-- Grid de productos -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            
            <div class="text-center mt-8">
                <a href="{{ route('shop') }}" class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition">
                    Ver más productos
                </a>
            </div>
        </div>
    </section>

    <!-- Categorías Populares -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Categorías Populares</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($popularCategories as $category)
                    <a href="{{ route('shop', ['categoria' => $category->id]) }}" class="group">
                        <div class="relative overflow-hidden rounded-lg shadow-md h-64">
                            <img src="{{ asset('img/category/' . $category->name . '.png') }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                                <h3 class="text-white text-2xl font-bold">{{ $category->name }}</h3>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Top Rated Products -->
    <section class="py-12 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Productos Mejor Valorados</h2>
            
            <!-- Carousel de productos -->
            <div class="splide" id="top-rated-carousel">
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach($topRatedProducts as $product)
                            <li class="splide__slide px-2">
                                <div class="bg-white p-4 shadow-md rounded-lg flex flex-col items-center text-center h-full">
                                    <a href="{{ route('product.show', $product->id) }}" class="block">
                                        <div class="w-full h-48 flex items-center justify-center overflow-hidden bg-gray-100">
                                            <img src="{{ asset('img/product/' . $product->image) }}" alt="{{ $product->name }}" class="h-full w-auto">
                                        </div>
                                        <h3 class="text-xl font-semibold mt-2">{{ $product->name }}</h3>
                                        <div class="flex justify-center mt-1 mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="text-yellow-500">
                                                    @if($i <= round($product->weightedRating))
                                                        ★
                                                    @else
                                                        ☆
                                                    @endif
                                                </span>
                                            @endfor
                                            <span class="text-gray-500 text-sm ml-2">({{ $product->numRatings }})</span>
                                        </div>
                                        <p class="text-gray-600 font-bold">
                                            Precio: {{ number_format($product->price, 2) }}€
                                            <span class="text-gray-500 text-sm">(IVA incluido)</span>
                                        </p>
                                    </a>
                                    <div class="mt-4">
                                        <button 
                                            onclick="addToCart({{ $product->id }})" 
                                            class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition duration-200"
                                        >
                                            Añadir al Carrito
                                        </button>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>


@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.0.7/dist/js/splide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.0.7/dist/css/splide.min.css">
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Splide('#top-rated-carousel', {
            perPage: 4,
            gap: '1rem',
            breakpoints: {
                1024: {
                    perPage: 3,
                },
                768: {
                    perPage: 2,
                },
                640: {
                    perPage: 1,
                },
            }
        }).mount();
    });
</script>
@endpush