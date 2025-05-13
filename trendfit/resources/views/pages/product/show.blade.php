@extends('layouts.app')

@section('title', $producto->name . ' - Trendfit')

@section('content')
<div class="container mx-auto py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
        <!-- Columna izquierda: Imágenes -->
        <div class="flex mb-6">
            <!-- Imágenes pequeñas a la izquierda -->
            <div class="flex flex-col space-y-2 mr-4">
                <img src="{{ asset('storage/' . $producto->image) }}" alt="{{ $producto->name }}" class="w-16 h-16 border rounded cursor-pointer hover:border-orange-500 producto-thumbnail active" data-image="{{ asset('storage/' . $producto->image) }}">
                
                @if(isset($producto->images) && count($producto->images) > 0)
                    @foreach($producto->images as $image)
                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $producto->name }}" class="w-16 h-16 border rounded cursor-pointer hover:border-orange-500 producto-thumbnail" data-image="{{ asset('storage/' . $image) }}">
                    @endforeach
                @endif
            </div>
        
            <!-- Imagen principal -->
            <div class="w-3/4">
                <img id="main-image" src="{{ asset('storage/' . $producto->image) }}" alt="{{ $producto->name }}" class="w-full h-full object-cover rounded-lg">
            </div>
        </div>

        <!-- Columna derecha: Detalles de producto -->
        <div class="space-y-6">
            <!-- Título y descripción -->
            <div>
                <h2 class="text-3xl font-semibold text-gray-900">{{ $producto->name }}</h2>
                <p class="text-gray-600 mb-4">{{ $producto->descr }}</p>
            </div>

            <!-- Precio -->
            <div class="text-3xl font-bold text-gray-800">
                <span class="text-orange-500">{{ number_format($producto->price, 2) }}€</span> (IVA incluido)
            </div>

            <!-- Valoraciones (estrellas) -->
            <div class="flex items-center">
                @php
                    $rating = $producto->averageRating ?? 0;
                    $fullStars = floor($rating);
                    $halfStar = $rating - $fullStars >= 0.5;
                @endphp
                
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $fullStars)
                        <span class="text-yellow-500 text-xl">★</span>
                    @elseif($i == $fullStars + 1 && $halfStar)
                        <span class="text-yellow-500 text-xl">★</span>
                    @else
                        <span class="text-gray-300 text-xl">★</span>
                    @endif
                @endfor
                
                <span class="ml-2 text-gray-600">({{ $producto->numRatings ?? 0 }} valoraciones)</span>
            </div>

            <!-- Selección de talla (botones) -->
            <div class="mb-6">
                <label for="talla" class="text-gray-700 font-medium block mb-2">Selecciona tu talla:</label>
                <div class="flex space-x-4">
                    @php
                        $sizes = ['S', 'M', 'L', 'XL'];
                    @endphp
                    
                    @foreach($sizes as $size)
                        <button type="button" 
                                class="w-16 h-16 border rounded-lg text-gray-700 font-bold bg-white hover:bg-orange-500 hover:text-white transition duration-200 size-button"
                                data-size="{{ $size }}">
                            {{ $size }}
                        </button>
                    @endforeach
                </div>
                <input type="hidden" id="selected-size" name="size" value="">
            </div>
            
            <!-- Selección de cantidad -->
            <div class="mb-6">
                <label for="cantidad" class="text-gray-700 font-medium block mb-2">Selecciona la cantidad:</label>
                <div class="flex items-center">
                    <button type="button" class="bg-gray-200 px-3 py-1 rounded-l" onclick="decreaseQuantity()">-</button>
                    <input type="number" id="cantidad" min="1" max="{{ $producto->stock }}" value="1" class="w-16 p-2 border text-center">
                    <button type="button" class="bg-gray-200 px-3 py-1 rounded-r" onclick="increaseQuantity({{ $producto->stock }})">+</button>
                </div>
                <p class="text-sm text-gray-500 mt-1">{{ $producto->stock }} unidades disponibles</p>
            </div>

            <!-- Botones de compra -->
            <div class="mt-6 space-y-3">
                <button type="button" 
                    class="product-detail-add-to-cart w-full bg-orange-500 text-white py-3 px-6 rounded-lg hover:bg-orange-600 transition duration-200 flex items-center justify-center"
                    data-id="{{ $producto->id }}"
                    data-name="{{ $producto->name }}"
                    data-price="{{ $producto->price }}"

                    data-image="{{ asset('storage/' . $producto->image) }}">
                    <i class="fas fa-shopping-cart mr-2"></i> Añadir al Carrito
                </button>
                
                <button type="button"
                    class="buy-now-btn w-full border border-orange-500 text-orange-500 py-3 px-6 rounded-lg hover:bg-orange-50 transition duration-200"
                    data-id="{{ $producto->id }}"
                    data-name="{{ $producto->name }}"
                    data-price="{{ $producto->price }}"
                    data-image="{{ asset('storage/' . $producto->image) }}">
                    Comprar Ahora
                </button>
            </div>

            <!-- Métodos de pago -->
            <div class="mt-6">
                <p class="text-gray-600">Métodos de pago aceptados:</p>
                <div class="flex space-x-6 mt-2">
                    <img src="{{ asset('img/payment/visa.png') }}" class="w-12 h-8">
                    <img src="{{ asset('img/payment/mastercard.jpg') }}" class="w-12 h-8">
                    <img src="{{ asset('img/payment/paypal.jpg') }}" class="w-12 h-8">
                </div>
            </div>

            <!-- Información adicional -->
            <div class="text-sm text-gray-600 mt-6">
                <p>🚚 <span class="font-semibold">Envío en 24-48h</span></p>
                <p>Devolución gratuita en 30 días.</p>
            </div>
        </div>
    </div>

    <!-- Tabs de información adicional -->
    <div class="mt-16">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex" x-data="{ activeTab: 'description' }">
                <button @click="activeTab = 'description'" :class="{'text-orange-500 border-orange-500': activeTab === 'description', 'text-gray-500 border-transparent': activeTab !== 'description'}" class="py-4 px-6 border-b-2 font-medium">
                    Descripción
                </button>
                <button @click="activeTab = 'details'" :class="{'text-orange-500 border-orange-500': activeTab === 'details', 'text-gray-500 border-transparent': activeTab !== 'details'}" class="py-4 px-6 border-b-2 font-medium">
                    Detalles
                </button>
                <button @click="activeTab = 'reviews'" :class="{'text-orange-500 border-orange-500': activeTab === 'reviews', 'text-gray-500 border-transparent': activeTab !== 'reviews'}" class="py-4 px-6 border-b-2 font-medium">
                    Valoraciones
                </button>
            </nav>
        </div>
        
        <div class="py-6">
            <!-- Descripción -->
            <div x-show="activeTab === 'description'" class="prose max-w-none">
                <p>{{ $producto->descr }}</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam euismod, nisl eget aliquam ultricies, nunc nisl aliquet nunc, quis aliquam lorem lorem quis nunc. Nullam euismod, nisl eget aliquam ultricies, nunc nisl aliquet nunc, quis aliquam lorem lorem quis nunc.</p>
            </div>
            
            <!-- Detalles -->
            <div x-show="activeTab === 'details'" class="prose max-w-none">
                <h3>Características</h3>
                <ul>
                    <li>Material: Algodón 100%</li>
                    <li>Color: Como se muestra en la imagen</li>
                    <li>Cuidado: Lavado a máquina, no usar blanqueador</li>
                    <li>Origen: España</li>
                </ul>
                
                <h3>Tallas Disponibles</h3>
                <p>S, M, L, XL</p>
            </div>
            
            <!-- Valoraciones -->
            <div x-show="activeTab === 'reviews'">
                <x-product-rating :productId="$producto->id" />
            </div>
        </div>
    </div>

    <!-- Productos relacionados -->
    <div class="mt-16">
        <h2 class="text-2xl font-bold mb-6">Productos que podrían interesarte</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
                <x-product-card :product="$relatedProduct" />
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Cambio de imagen principal al hacer clic en una miniatura
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnails = document.querySelectorAll('.producto-thumbnail');
        const mainImage = document.getElementById('main-image');
        
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                // Quitar clase 'active' de todas las miniaturas
                thumbnails.forEach(t => t.classList.remove('active', 'border-orange-500'));
                
                // Añadir clase 'active' a la miniatura clickeada
                this.classList.add('active', 'border-orange-500');
                
                // Actualizar imagen principal
                mainImage.src = this.dataset.image;
            });
        });
        
        // Selección de talla
        const sizeButtons = document.querySelectorAll('.size-button');
        const selectedSizeInput = document.getElementById('selected-size');
        
        sizeButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Quitar clase 'active' de todos los botones
                sizeButtons.forEach(b => {
                    b.classList.remove('bg-orange-500', 'text-white');
                    b.classList.add('bg-white', 'text-gray-700');
                });
                
                // Añadir clase 'active' al botón clickeado
                this.classList.remove('bg-white', 'text-gray-700');
                this.classList.add('bg-orange-500', 'text-white');
                
                // Actualizar valor del input hidden
                selectedSizeInput.value = this.dataset.size;
            });
        });
    });
    
    // Funciones para aumentar/disminuir cantidad
    function decreaseQuantity() {
        const input = document.getElementById('cantidad');
        const value = parseInt(input.value);
        if (value > 1) {
            input.value = value - 1;
        }
    }
    
    function increaseQuantity(maxStock) {
        const input = document.getElementById('cantidad');
        const value = parseInt(input.value);
        if (value < maxStock) {
            input.value = value + 1;
        }
    }
    
    // Añadir al carrito con opciones (talla, cantidad)
    function addToCartWithOptions(productId) {
        const size = document.getElementById('selected-size').value;
        const quantity = parseInt(document.getElementById('cantidad').value);
        
        if (!size) {
            alert('Por favor, selecciona una talla');
            return;
        }
        
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                productId: productId,
                quantity: quantity,
                size: size
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar el contador del carrito
                updateCartCounter(data.totalItems);
                
                // Mostrar mensaje de éxito
                showNotification('Producto añadido al carrito', 'success');
            } else {
                showNotification(data.message || 'Error al añadir al carrito', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al añadir al carrito', 'error');
        });
    }
    
    // Comprar ahora
    function buyNow(productId) {
        addToCartWithOptions(productId);
        setTimeout(() => {
            window.location.href = "{{ route('checkout') }}";
        }, 500);
    }
</script>
@endpush