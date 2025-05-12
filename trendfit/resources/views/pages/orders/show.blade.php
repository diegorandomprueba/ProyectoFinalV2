@extends('layouts.app')

@section('title', 'Detalle de Pedido - Trendfit')

@section('content')
<div class="container mx-auto py-10">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center">
            <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left mr-2"></i> Volver a mis pedidos
            </a>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Cabecera del pedido -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-wrap justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-semibold">Pedido #{{ $order->id }}</h2>
                        <p class="text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <span class="px-4 py-1 rounded-full text-sm font-semibold 
                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                          ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                          'bg-red-100 text-red-800') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
            
            <!-- Productos del pedido -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold mb-4">Productos</h3>
                
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
                
                <!-- Totales -->
                <div class="mt-6 border-t pt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span>{{ number_format($subtotal, 2) }}€</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">IVA (21%)</span>
                        <span>{{ number_format($tax, 2) }}€</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Envío</span>
                        <span>{{ number_format($shipping, 2) }}€</span>
                    </div>
                    @if($discount > 0)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Descuento</span>
                            <span>-{{ number_format($discount, 2) }}€</span>
                        </div>
                    @endif
                    <div class="flex justify-between font-bold text-lg mt-2 pt-2 border-t">
                        <span>Total</span>
                        <span>{{ number_format($order->total, 2) }}€</span>
                    </div>
                </div>
            </div>
            
            <!-- Información de envío y facturación -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Información de Envío</h3>
                    <div class="bg-gray-50 p-4 rounded">
                        <p><strong>Nombre:</strong> {{ $order->name }}</p>
                        <p><strong>Dirección:</strong> {{ $order->address }}</p>
                        <p><strong>Ciudad:</strong> {{ $order->city }}</p>
                        <p><strong>Código Postal:</strong> {{ $order->codigo_postal }}</p>
                        <p><strong>Provincia:</strong> {{ $order->provincia }}</p>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Método de Pago</h3>
                    <div class="bg-gray-50 p-4 rounded">
                        @if($order->payment_method === 'card')
                            <p><i class="fas fa-credit-card mr-2"></i> Tarjeta de crédito</p>
                            <p class="text-gray-600 text-sm">Terminada en **** {{ substr($order->card_number, -4) }}</p>
                        @elseif($order->payment_method === 'paypal')
                            <p><i class="fab fa-paypal mr-2"></i> PayPal</p>
                        @else
                            <p><i class="fas fa-university mr-2"></i> Transferencia bancaria</p>
                        @endif
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('orders.invoice', $order->id) }}" class="inline-block text-orange-500 hover:text-orange-700">
                            <i class="fas fa-file-pdf mr-2"></i> Descargar Factura
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Acciones -->
            <div class="p-6 border-t border-gray-200 bg-gray-50">
                <div class="flex flex-wrap justify-between items-center">
                    <div>
                        @if($order->status === 'pending' || $order->status === 'processing')
                            <p class="text-gray-600">
                                <i class="fas fa-truck mr-2"></i> 
                                Entrega estimada: {{ \Carbon\Carbon::parse($order->created_at)->addDays(3)->format('d/m/Y') }}
                            </p>
                        @elseif($order->status === 'completed')
                            <p class="text-green-600">
                                <i class="fas fa-check-circle mr-2"></i> 
                                Pedido entregado el {{ \Carbon\Carbon::parse($order->updated_at)->format('d/m/Y') }}
                            </p>
                        @endif
                    </div>
                    
                    <div class="mt-4 md:mt-0">
                        @if($order->status === 'pending')
                            <button 
                                onclick="cancelOrder({{ $order->id }})" 
                                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition duration-200"
                            >
                                Cancelar Pedido
                            </button>
                        @endif
                        
                        @if($order->status === 'completed' && !$allProductsReviewed)
                            <a 
                                href="{{ route('orders.reviews', $order->id) }}" 
                                class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition duration-200"
                            >
                                Valorar Productos
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function cancelOrder(orderId) {
        if (confirm('¿Estás seguro de que deseas cancelar este pedido? Esta acción no se puede deshacer.')) {
            fetch(`/orders/${orderId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'No se pudo cancelar el pedido');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cancelar el pedido');
            });
        }
    }
</script>
@endpush