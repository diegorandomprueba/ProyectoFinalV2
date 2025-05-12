@extends('layouts.app')

@section('title', 'Dónde Estamos - Trendfit')

@section('content')
<div class="container mx-auto py-10">
    <!-- Sección de introducción -->
    <div class="max-w-4xl mx-auto px-4 mb-12 text-center">
        <h1 class="text-4xl font-bold mb-6">Dónde Estamos</h1>
        <p class="text-xl text-gray-600">Conoce nuestra ubicación y cómo contactarnos</p>
    </div>
    
    <!-- Mapa y dirección -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-12">
        <div class="grid md:grid-cols-2">
            <div class="p-8">
                <h2 class="text-2xl font-semibold mb-4">Nuestra Oficina Central</h2>
                <div class="mb-6">
                    <p class="mb-1"><strong>Dirección:</strong></p>
                    <p>Carrer de l'Exiample, 123</p>
                    <p>08800 Vilanova i la Geltrú</p>
                    <p>Barcelona, España</p>
                </div>
                
                <div class="mb-6">
                    <p class="mb-1"><strong>Horario:</strong></p>
                    <p>Lunes a Viernes: 9:00 - 18:00</p>
                    <p>Sábados: 10:00 - 14:00</p>
                    <p>Domingos: Cerrado</p>
                </div>
                
                <div>
                    <p class="mb-1"><strong>Contacto:</strong></p>
                    <p>Teléfono: +34 93 123 45 67</p>
                    <p>Email: info@trendfit.com</p>
                </div>
            </div>
            <div class="h-96 md:h-auto">
                <!-- Iframe de Google Maps (sustituye las coordenadas) -->
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d24003.459948583752!2d1.7079629771880957!3d41.22446135787308!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12a386e3aaaaaaab%3A0x3442572383d0abf!2sVilanova%20i%20la%20Geltr%C3%BA%2C%20Barcelona!5e0!3m2!1ses!2ses!4v1620000000000!5m2!1ses!2ses" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        </div>
    </div>
    
    <!-- Otras ubicaciones -->
    <div class="max-w-6xl mx-auto px-4 mb-12">
        <h2 class="text-3xl font-bold mb-8 text-center">Tiendas Físicas</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-2">Barcelona</h3>
                <p class="mb-4">Passeig de Gràcia, 45</p>
                <p class="mb-1">Lunes a Sábado: 10:00 - 20:00</p>
                <p>Domingo: Cerrado</p>
                <p class="mt-4 text-orange-500 hover:text-orange-600">
                    <a href="#" class="flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i> Ver en el mapa
                    </a>
                </p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-2">Madrid</h3>
                <p class="mb-4">Calle Serrano, 61</p>
                <p class="mb-1">Lunes a Sábado: 10:00 - 20:00</p>
                <p>Domingo: Cerrado</p>
                <p class="mt-4 text-orange-500 hover:text-orange-600">
                    <a href="#" class="flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i> Ver en el mapa
                    </a>
                </p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-2">Valencia</h3>
                <p class="mb-4">Calle Colón, 34</p>
                <p class="mb-1">Lunes a Sábado: 10:00 - 20:00</p>
                <p>Domingo: Cerrado</p>
                <p class="mt-4 text-orange-500 hover:text-orange-600">
                    <a href="#" class="flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i> Ver en el mapa
                    </a>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Formulario de contacto -->
    <div class="bg-gray-100 py-12 px-4 rounded-lg mb-12">
        <div class="max-w-2xl mx-auto">
            <h2 class="text-3xl font-bold mb-8 text-center">¿Tienes alguna pregunta?</h2>
            
            <form class="bg-white p-6 rounded-lg shadow-md">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-gray-700 font-medium mb-2">Nombre</label>
                        <input type="text" id="name" name="name" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" id="email" name="email" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="subject" class="block text-gray-700 font-medium mb-2">Asunto</label>
                    <input type="text" id="subject" name="subject" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                </div>
                
                <div class="mb-6">
                    <label for="message" class="block text-gray-700 font-medium mb-2">Mensaje</label>
                    <textarea id="message" name="message" rows="5" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required></textarea>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition duration-200">
                        Enviar Mensaje
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Información adicional -->
    <div class="text-center max-w-3xl mx-auto px-4">
        <h2 class="text-2xl font-bold mb-4">¿Prefieres comprar online?</h2>
        <p class="mb-6">Explora nuestra tienda online y descubre las últimas tendencias en moda.</p>
        <a href="{{ route('shop') }}" class="inline-block bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition duration-200">
            Ir a la Tienda
        </a>
    </div>
</div>
@endsection