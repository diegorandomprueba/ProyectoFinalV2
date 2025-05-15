@extends('layouts.app')

@section('title', 'Contacto - Trendfit')

@section('content')
<div class="container mx-auto py-10">
    <!-- Sección de introducción -->
    <div class="max-w-4xl mx-auto px-4 mb-12 text-center">
        <h1 class="text-4xl font-bold mb-6">Contáctanos</h1>
        <p class="text-xl text-gray-600">Estamos aquí para ayudarte. ¿Tienes alguna pregunta o comentario?</p>
    </div>
    
    <!-- Información de contacto + Formulario -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-12">
        <div class="grid md:grid-cols-2">
            <!-- Información de contacto -->
            <div class="bg-orange-500 text-white p-8">
                <h2 class="text-2xl font-semibold mb-6">Información de Contacto</h2>
                
                <div class="mb-8">
                    <h3 class="font-semibold mb-2">Dirección:</h3>
                    <p>Carrer de l'Exiample, 123</p>
                    <p>08800 Vilanova i la Geltrú</p>
                    <p>Barcelona, España</p>
                </div>
                
                <div class="mb-8">
                    <h3 class="font-semibold mb-2">Teléfono:</h3>
                    <p>+34 93 123 45 67</p>
                </div>
                
                <div class="mb-8">
                    <h3 class="font-semibold mb-2">Email:</h3>
                    <p>info@trendfit.com</p>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-2">Horario de Atención:</h3>
                    <p>Lunes a Viernes: 9:00 - 18:00</p>
                    <p>Sábados: 10:00 - 14:00</p>
                    <p>Domingos: Cerrado</p>
                </div>
                
                <div class="mt-10">
                    <h3 class="font-semibold mb-4">Síguenos:</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-white hover:text-gray-200 transition">
                            <i class="fab fa-facebook-f text-2xl"></i>
                        </a>
                        <a href="#" class="text-white hover:text-gray-200 transition">
                            <i class="fab fa-twitter text-2xl"></i>
                        </a>
                        <a href="#" class="text-white hover:text-gray-200 transition">
                            <i class="fab fa-instagram text-2xl"></i>
                        </a>
                        <a href="#" class="text-white hover:text-gray-200 transition">
                            <i class="fab fa-linkedin-in text-2xl"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Formulario de contacto -->
            <div class="p-8">
                <h2 class="text-2xl font-semibold mb-6">Envíanos un Mensaje</h2>
                
                <form id="contact-form" class="space-y-6">
                    @csrf
                    <div>
                        <label for="name" class="block text-gray-700 font-medium mb-2">Nombre Completo</label>
                        <input type="text" id="name" name="name" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                    </div>
                    <div>
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" id="email" name="email" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                    </div>
                    <div>
                        <label for="subject" class="block text-gray-700 font-medium mb-2">Asunto</label>
                        <input type="text" id="subject" name="subject" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                    </div>
                    <div>
                        <label for="message" class="block text-gray-700 font-medium mb-2">Mensaje</label>
                        <textarea id="message" name="message" rows="5" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required></textarea>
                    </div>
                    <div>
                        <button type="button" onclick="showThankYouMessage()" class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition duration-200">
                            Enviar Mensaje
                        </button>
                    </div>
                </form>

                <div id="thank-you-message" class="hidden mt-6 p-4 bg-green-100 text-green-700 rounded-lg">
                    <p class="font-medium">¡Gracias por tu mensaje!</p>
                    <p>Nos pondremos en contacto contigo a la brevedad posible.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Preguntas frecuentes -->
    <div class="max-w-4xl mx-auto px-4 mb-12">
        <h2 class="text-3xl font-bold mb-8 text-center">Preguntas Frecuentes</h2>
        
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-lg shadow-md" x-data="{ open: false }">
                <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                    <h3 class="text-lg font-semibold">¿Cuál es el tiempo de entrega?</h3>
                    <span x-show="!open" class="text-orange-500"><i class="fas fa-plus"></i></span>
                    <span x-show="open" class="text-orange-500"><i class="fas fa-minus"></i></span>
                </div>
                <div x-show="open" class="mt-4">
                    <p>El tiempo de entrega estándar es de 2-3 días laborables para envíos nacionales. Para envíos internacionales, el tiempo de entrega puede variar entre 5-10 días laborables dependiendo del destino.</p>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md" x-data="{ open: false }">
                <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                    <h3 class="text-lg font-semibold">¿Cuál es la política de devoluciones?</h3>
                    <span x-show="!open" class="text-orange-500"><i class="fas fa-plus"></i></span>
                    <span x-show="open" class="text-orange-500"><i class="fas fa-minus"></i></span>
                </div>
                <div x-show="open" class="mt-4">
                    <p>Aceptamos devoluciones dentro de los 30 días posteriores a la fecha de compra. Los productos deben estar en su estado original, sin usar y con todas las etiquetas. Para iniciar una devolución, por favor contáctanos a través de nuestro formulario de contacto o envíanos un email a devoluciones@trendfit.com.</p>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md" x-data="{ open: false }">
                <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                    <h3 class="text-lg font-semibold">¿Tienen tiendas físicas?</h3>
                    <span x-show="!open" class="text-orange-500"><i class="fas fa-plus"></i></span>
                    <span x-show="open" class="text-orange-500"><i class="fas fa-minus"></i></span>
                </div>
                <div x-show="open" class="mt-4">
                    <p>Sí, tenemos tiendas físicas en Barcelona, Madrid y Valencia. Puedes encontrar las direcciones y horarios detallados en nuestra página de "Dónde Estamos".</p>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md" x-data="{ open: false }">
                <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                    <h3 class="text-lg font-semibold">¿Ofrecen envío gratuito?</h3>
                    <span x-show="!open" class="text-orange-500"><i class="fas fa-plus"></i></span>
                    <span x-show="open" class="text-orange-500"><i class="fas fa-minus"></i></span>
                </div>
                <div x-show="open" class="mt-4">
                    <p>Sí, ofrecemos envío gratuito para pedidos superiores a 50€ en envíos nacionales. Para envíos internacionales, ofrecemos envío gratuito para pedidos superiores a 100€.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
function showThankYouMessage() {
    // Validar el formulario manualmente
    const form = document.getElementById('contact-form');
    if (form.checkValidity()) {
        // Ocultar formulario
        form.classList.add('hidden');
        
        // Mostrar mensaje de agradecimiento
        document.getElementById('thank-you-message').classList.remove('hidden');
        
        // Opcional: Resetear el formulario
        form.reset();
    } else {
        // Activar la validación visual del navegador
        form.reportValidity();
    }
}
</script>