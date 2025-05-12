@extends('layouts.app')

@section('title', 'Pedido Completado - Trendfit')

@section('content')
<div class="container mx-auto py-10">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <div class="text-center mb-8">
            <div class="inline-block bg-green-100 p-6 rounded-full mb-4">
                <i class="fas fa-check-circle text-green-500 text-5xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800">¡Pedido Completado!</h2>
            <p class="text-gray-600 mt-2">Tu pedido se ha realizado correctamente.</p>
        </div>
        
        <div class="border-t border-b py-6 mb-6">
            <h3 class="text-xl font-semibold mb-4">Detalles del Pedido</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">Número de Pedido:</p>
                    <p class="font-semibold">#{{ $order->id }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Fecha:</p>
                    <p class="font-semibold">{{ $order->created_at->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Total:</p>
                    <p class="font-semibold">{{ number_format($order->total, 2) }}€</p>
                </div>
                <div>
                    <p class="text-gray-600">Método de Pago:</p>
                    <p class="font-semibold">{{ $order->payment_method }}</p>
                </div>
            </div>
        </div>
        
        <div class="mb-6">
            <h3 class="text-xl font-semibold mb-4">Resumen de Productos</h3>
            
            <div class="space-y-4">
                @foreach($order->products as $product)
                    <div class="flex items-center">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded mr-4">
                        <div class="flex-1">
                            <p class="font-medium">{{ $product->name }}</p>
                            <p class="text-gray-600 text-sm">
                                {{ $product->pivot->size ?? 'N/A' }} | {{ $product->pivot->cant }} x {{ number_format($product->price, 2) }}€
                            </p>
                        </div>
                        <span class="font-medium">{{ number_format($product->price * $product->pivot->cant, 2) }}€</span>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="bg-gray-50 p-6 rounded-lg mb-6">
            <h3 class="text-xl font-semibold mb-4">Información de Envío</h3>
            <p><strong>Nombre:</strong> {{ $order->name }}</p>
            <p><strong>Dirección:</strong> {{ $order->address }}</p>
            <p><strong>Ciudad:</strong> {{ $order->city }}</p>
            <p><strong>Código Postal:</strong> {{ $order->codigo_postal }}</p>
            <p><strong>Provincia:</strong> {{ $order->provincia }}</p>
        </div>
        
        <div class="text-center">
            <a href="{{ route('orders.invoice', $order->id) }}" class="bg-orange-500 text-white px-6 py-3 rounded-lg inline-block hover:bg-orange-600 transition duration-200 mb-4">
                <i class="fas fa-file-pdf mr-2"></i> Descargar Factura
            </a>
            <p class="mt-4">
                Te hemos enviado un correo electrónico con los detalles de tu pedido.
            </p>
            <p class="mt-6">
                <a href="{{ route('home') }}" class="text-orange-500 hover:text-orange-600">
                    <i class="fas fa-home mr-2"></i> Volver a la Tienda
                </a>
            </p>
        </div>
    </div>
    
    <!-- Solicitud de valoración -->
    <div class="max-w-2xl mx-auto mt-8 bg-gray-50 p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-4">¿Te gustaría valorar tus productos?</h3>
        <p class="mb-4">Tu opinión es muy importante para nosotros y ayuda a otros clientes a tomar mejores decisiones.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <button onclick="location.href='{{ route('orders.reviews', $order->id) }}'" class="bg-gray-800 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-200">
                Valorar ahora
            </button>
            <button id="remind-later" class="border border-gray-400 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition duration-200">
                Recordármelo más tarde
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('remind-later')?.addEventListener('click', function() {
        fetch('{{ route('orders.remind', $order->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Te recordaremos valorar tus productos la próxima vez que inicies sesión.');
                this.disabled = true;
                this.textContent = 'Recordatorio configurado';
            }
        });
    });
</script>
@endpush