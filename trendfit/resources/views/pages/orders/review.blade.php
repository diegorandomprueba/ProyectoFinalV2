@extends('layouts.app')

@section('title', 'Valorar Productos - Trendfit')

@section('content')
<div class="container mx-auto py-10">
    <h2 class="text-3xl font-semibold text-center mb-8">Valorar Productos</h2>
    
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-4">Pedido #{{ $order->id }}</h3>
        <p class="text-gray-600 mb-6">Fecha: {{ $order->created_at->format('d/m/Y') }}</p>
        
        <div class="space-y-8">
            @foreach($order->products as $product)
                <div class="border-t pt-6" id="product-{{ $product->id }}">
                    <div class="flex flex-col md:flex-row md:items-center mb-4">
                        <div class="md:w-1/4 mb-4 md:mb-0">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded">
                        </div>
                        <div class="md:w-3/4 md:pl-6">
                            <h4 class="text-lg font-semibold">{{ $product->name }}</h4>
                            <p class="text-gray-600">Talla: {{ $product->pivot->size ?? 'N/A' }}</p>
                            <p class="text-gray-600">Cantidad: {{ $product->pivot->cant }}</p>
                            
                            @if(!$product->pivot->has_comment)
                                <div class="mt-4">
                                    <p class="text-gray-700 mb-3">¿Qué te ha parecido este producto?</p>
                                    
                                    <div class="flex items-center mb-3">
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span 
                                                    data-rating="{{ $i }}" 
                                                    data-product="{{ $product->id }}" 
                                                    class="text-2xl cursor-pointer star-rating text-gray-300"
                                                >★</span>
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-gray-700 rating-text-{{ $product->id }}"></span>
                                    </div>
                                    
                                    <textarea 
                                        id="comment-{{ $product->id }}" 
                                        placeholder="Escribe tu comentario..." 
                                        class="w-full p-2 border rounded focus:ring focus:ring-orange-300"
                                        maxlength="150"
                                    ></textarea>
                                    <div class="text-right text-sm text-gray-500 character-count-{{ $product->id }}">0/150</div>
                                    
                                    <div class="mt-3 flex space-x-4">
                                        <button 
                                            data-product="{{ $product->id }}" 
                                            class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition duration-200 submit-review"
                                        >
                                            Enviar valoración
                                        </button>
                                        <button 
                                            data-product="{{ $product->id }}" 
                                            class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition duration-200 skip-review"
                                        >
                                            No valorar
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="mt-4 bg-gray-50 p-4 rounded">
                                    <p class="font-medium">Ya has valorado este producto</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-8 text-center">
            <a href="{{ route('orders.index') }}" class="text-orange-500 hover:text-orange-600">
                <i class="fas fa-arrow-left mr-2"></i> Volver a mis pedidos
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Selección de estrellas
        const stars = document.querySelectorAll('.star-rating');
        const ratingTexts = {
            1: 'Muy malo',
            2: 'Malo',
            3: 'Regular',
            4: 'Bueno',
            5: 'Excelente'
        };
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                const productId = this.dataset.product;
                const starsForProduct = document.querySelectorAll(`.star-rating[data-product="${productId}"]`);
                
                // Actualizar estrellas
                starsForProduct.forEach(s => {
                    if (s.dataset.rating <= rating) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-yellow-500');
                    } else {
                        s.classList.remove('text-yellow-500');
                        s.classList.add('text-gray-300');
                    }
                });
                
                // Actualizar texto de valoración
                document.querySelector(`.rating-text-${productId}`).textContent = ratingTexts[rating];
            });
            
            star.addEventListener('mouseover', function() {
                const rating = this.dataset.rating;
                const productId = this.dataset.product;
                const starsForProduct = document.querySelectorAll(`.star-rating[data-product="${productId}"]`);
                
                starsForProduct.forEach(s => {
                    if (s.dataset.rating <= rating) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-yellow-400');
                    }
                });
                
                document.querySelector(`.rating-text-${productId}`).textContent = ratingTexts[rating];
            });
            
            star.addEventListener('mouseout', function() {
                const productId = this.dataset.product;
                const starsForProduct = document.querySelectorAll(`.star-rating[data-product="${productId}"]`);
                
                starsForProduct.forEach(s => {
                    if (!s.classList.contains('text-yellow-500')) {
                        s.classList.remove('text-yellow-400');
                        s.classList.add('text-gray-300');
                    }
                });
                
                // Obtener la valoración actual (si existe)
                const selectedStar = document.querySelector(`.star-rating[data-product="${productId}"].text-yellow-500`);
                if (selectedStar) {
                    document.querySelector(`.rating-text-${productId}`).textContent = ratingTexts[selectedStar.dataset.rating];
                } else {
                    document.querySelector(`.rating-text-${productId}`).textContent = '';
                }
            });
        });
        
        // Contador de caracteres para comentarios
        const commentTexts = document.querySelectorAll('textarea[id^="comment-"]');
        
        commentTexts.forEach(textarea => {
            const productId = textarea.id.split('-')[1];
            
            textarea.addEventListener('input', function() {
                const count = this.value.length;
                document.querySelector(`.character-count-${productId}`).textContent = `${count}/150`;
            });
        });
        
        // Enviar valoración
        const submitButtons = document.querySelectorAll('.submit-review');
        
        submitButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.product;
                const selectedStar = document.querySelector(`.star-rating[data-product="${productId}"].text-yellow-500`);
                const comment = document.getElementById(`comment-${productId}`).value;
                
                if (!selectedStar) {
                    alert('Por favor, selecciona una valoración');
                    return;
                }
                
                const rating = selectedStar.dataset.rating;
                
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
                
                fetch('/api/opinions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        productId: productId,
                        rating: rating,
                        comment: comment
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Marcar como valorado
                        document.getElementById(`product-${productId}`).innerHTML = `
                            <div class="flex flex-col md:flex-row md:items-center mb-4">
                                <div class="md:w-1/4 mb-4 md:mb-0">
                                    <img src="${document.querySelector(`#product-${productId} img`).src}" alt="${document.querySelector(`#product-${productId} h4`).textContent}" class="w-32 h-32 object-cover rounded">
                                </div>
                                <div class="md:w-3/4 md:pl-6">
                                    <h4 class="text-lg font-semibold">${document.querySelector(`#product-${productId} h4`).textContent}</h4>
                                    <p class="text-gray-600">${document.querySelector(`#product-${productId} p:nth-of-type(1)`).textContent}</p>
                                    <p class="text-gray-600">${document.querySelector(`#product-${productId} p:nth-of-type(2)`).textContent}</p>
                                    <div class="mt-4 bg-green-50 p-4 rounded">
                                        <p class="text-green-600">¡Gracias por tu valoración!</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        alert(data.message || 'Error al enviar la valoración');
                        this.disabled = false;
                        this.textContent = 'Enviar valoración';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al enviar la valoración');
                    this.disabled = false;
                    this.textContent = 'Enviar valoración';
                });
            });
        });
        
        // No valorar
        const skipButtons = document.querySelectorAll('.skip-review');
        
        skipButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.product;
                
                if (confirm('¿Estás seguro de que no deseas valorar este producto?')) {
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
                    
                    fetch(`/orders/reviews/skip/${productId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Marcar como no valorado
                            document.getElementById(`product-${productId}`).innerHTML = `
                                <div class="flex flex-col md:flex-row md:items-center mb-4">
                                    <div class="md:w-1/4 mb-4 md:mb-0">
                                        <img src="${document.querySelector(`#product-${productId} img`).src}" alt="${document.querySelector(`#product-${productId} h4`).textContent}" class="w-32 h-32 object-cover rounded">
                                    </div>
                                    <div class="md:w-3/4 md:pl-6">
                                        <h4 class="text-lg font-semibold">${document.querySelector(`#product-${productId} h4`).textContent}</h4>
                                        <p class="text-gray-600">${document.querySelector(`#product-${productId} p:nth-of-type(1)`).textContent}</p>
                                        <p class="text-gray-600">${document.querySelector(`#product-${productId} p:nth-of-type(2)`).textContent}</p>
                                        <div class="mt-4 bg-gray-50 p-4 rounded">
                                            <p class="text-gray-600">Has decidido no valorar este producto</p>
                                        </div>
                                    </div>
                                </div>
                            `;
                        } else {
                            alert(data.message || 'Error al procesar la solicitud');
                            this.disabled = false;
                            this.textContent = 'No valorar';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al procesar la solicitud');
                        this.disabled = false;
                        this.textContent = 'No valorar';
                    });
                }
            });
        });
    });
</script>
@endpush