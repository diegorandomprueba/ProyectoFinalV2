@extends('layouts.app')

@section('title', 'Acerca de Nosotros - Trendfit')

@section('content')
<div class="container mx-auto py-10">
    <!-- Sección de introducción -->
    <div class="max-w-4xl mx-auto px-4 mb-12 text-center">
        <h1 class="text-4xl font-bold mb-6">Acerca de Trendfit</h1>
        <p class="text-xl text-gray-600">Descubre nuestra historia y misión en el mundo de la moda.</p>
    </div>
    
    <!-- Sección sobre nosotros -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-12">
        <div class="grid md:grid-cols-2">
            <div class="p-8">
                <h2 class="text-2xl font-semibold mb-4">Nuestra Historia</h2>
                <p class="mb-4">Trendfit nació en 2023 con una visión clara: hacer que la moda sea accesible y personalizada para todos. Fundada por un grupo de apasionados por la moda y la tecnología, nuestra empresa ha crecido rápidamente para convertirse en un referente en el mercado de moda online.</p>
                <p>Lo que comenzó como una pequeña tienda de ropa ha evolucionado para ofrecer una experiencia de compra única, utilizando algoritmos avanzados que ayudan a nuestros clientes a encontrar prendas que realmente se adapten a su estilo personal.</p>
            </div>
            <div class="relative aspect-video rounded-lg overflow-hidden shadow-lg">
                <video id="storyVideo" class="w-full h-full object-cover" poster="{{ asset('img/about/story.jpg') }}">
                    <source src="{{ asset('videos/about-video.mp4') }}" type="video/mp4">
                    <source src="{{ asset('videos/about-video.webm') }}" type="video/webm">
                    Tu navegador no soporta videos HTML5.
                </video>
                
                <button id="playButton" 
                        class="absolute inset-0 m-auto w-16 h-16 bg-orange-500 rounded-full 
                            flex items-center justify-center hover:bg-orange-600 transition-all 
                            duration-300 shadow-lg">
                    <i class="fas fa-play text-white text-2xl"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Nuestra misión -->
    <div class="bg-orange-500 text-white py-16 px-4 mb-12 rounded-lg">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-bold mb-6">Nuestra Misión</h2>
            <p class="text-xl">
                En Trendfit, creemos que encontrar tu estilo perfecto no debería ser un desafío. Nuestra misión es ayudarte a descubrir y desarrollar tu estilo único a través de recomendaciones personalizadas, consejos de moda y productos de alta calidad.
            </p>
        </div>
    </div>
    
    <!-- Equipo -->
    <div class="max-w-6xl mx-auto px-4 mb-12">
        <h2 class="text-3xl font-bold mb-8 text-center">Nuestro Equipo</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="w-32 h-32 mx-auto rounded-full overflow-hidden mb-4">
                    <img src="{{ asset('img/about/team.png') }}" alt="CEO" class="w-full h-full object-cover">
                </div>
                <h3 class="text-xl font-semibold">Diego Pallares</h3>
                <p class="text-gray-600">CEO &amp; Fundadora</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="w-32 h-32 mx-auto rounded-full overflow-hidden mb-4">
                    <img src="{{ asset('img/about/team.png') }}" alt="Director Creativo" class="w-full h-full object-cover">
                </div>
                <h3 class="text-xl font-semibold">Carlos Rodríguez</h3>
                <p class="text-gray-600">Director Creativo</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="w-32 h-32 mx-auto rounded-full overflow-hidden mb-4">
                    <img src="{{ asset('img/about/team.png') }}" alt="Directora de Tecnología" class="w-full h-full object-cover">
                </div>
                <h3 class="text-xl font-semibold">Laura Gómez</h3>
                <p class="text-gray-600">Directora de Tecnología</p>
            </div>
        </div>
    </div>
    
    <!-- Valores -->
    <div class="bg-gray-100 py-16 px-4 mb-12 rounded-lg">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold mb-8 text-center">Nuestros Valores</h2>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold mb-2">Sostenibilidad</h3>
                    <p>Trabajamos con proveedores comprometidos con prácticas sostenibles y buscamos reducir nuestro impacto ambiental en cada paso del proceso.</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold mb-2">Innovación</h3>
                    <p>Constantemente buscamos nuevas formas de mejorar la experiencia de compra utilizando tecnologías avanzadas y algoritmos innovadores.</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold mb-2">Inclusividad</h3>
                    <p>Creemos que la moda es para todos. Ofrecemos opciones para diferentes tallas, estilos y presupuestos.</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold mb-2">Calidad</h3>
                    <p>Nos comprometemos a ofrecer productos de alta calidad que sean duraderos y tengan un buen acabado.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CTA -->
    <div class="text-center max-w-3xl mx-auto px-4">
        <h2 class="text-2xl font-bold mb-4">¿Listo para descubrir tu estilo?</h2>
        <p class="mb-6">Explora nuestra colección cuidadosamente seleccionada y encuentra prendas que te encantarán.</p>
        <a href="{{ route('shop') }}" class="inline-block bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition duration-200">
            Ir de compras
        </a>
    </div>
</div>
@endsection