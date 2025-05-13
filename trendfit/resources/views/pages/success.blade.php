@extends('layouts.app')

@section('title', 'Pedido Completado - Trendfit')

@section('content')
<div class="container mx-auto py-10 px-4">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <div class="text-center mb-8">
            <div class="inline-block bg-green-100 p-6 rounded-full mb-4">
                <i class="fas fa-check-circle text-green-500 text-5xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800">¡Pedido Completado!</h2>
            <p class="text-gray-600 mt-2">Tu pedido #{{ $order->id }} se ha realizado correctamente.</p>
        </div>
        
        <div class="border-t border-b py-6 mb-6">
            <h3 class="text-xl font-semibold mb-4">Detalles del Pedido</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">Número de Pedido:</p>
                    <p class="font-semibold">#{{ $order->id }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Fecha:</p>
                    <p class="font-semibold">{{ $order->created_at->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Estado:</p>
                    <p class="font-semibold">
                        @if($order->status == 'pending')
                            <span class="text-yellow-600">Pendiente</span>
                        @elseif($order->status == 'processing')
                            <span class="text-blue-600">En proceso</span>
                        @elseif($order->status == 'completed')
                            <span class="text-green-600">Completado</span>
                        @elseif($order->status == 'cancelled')
                            <span class="text-red-600">Cancelado</span>
                        @else
                            {{ $order->status }}
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-gray-600">Método de Pago:</p>
                    <p class="font-semibold">
                        @if($order->payment_method == 'card')
                            Tarjeta de crédito
                        @elseif($order->payment_method == 'paypal')
                            PayPal
                        @elseif($order->payment_method == 'transfer')
                            Transferencia bancaria
                        @else
                            {{ $order->payment_method }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <div class="mb-6">
            <h3 class="text-xl font-semibold mb-4">Productos</h3>
            
            <div class="space-y-4">
                @foreach($order->productes as $product)
                    <div class="flex items-center border-b pb-4">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded mr-4">
                        <div class="flex-1">
                            <p class="font-medium">{{ $product->name }}</p>
                            <p class="text-gray-600 text-sm">
                                @if(isset($product->pivot->size) && $product->pivot->size)
                                    Talla: {{ $product->pivot->size }} | 
                                @endif
                                Cantidad: {{ $product->pivot->cant }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">{{ number_format($product->price, 2) }}€</p>
                            <p class="text-gray-600 text-sm">Total: {{ number_format($product->price * $product->pivot->cant, 2) }}€</p>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                @php
                    $subtotal = 0;
                    foreach ($order->productes as $product) {
                        $subtotal += $product->price * $product->pivot->cant;
                    }
                    $tax = $subtotal * 0.21;
                    $shipping = 4.99;
                    $total = $subtotal + $tax + $shipping;
                @endphp
                
                <div class="flex justify-between mb-2">
                    <span>Subtotal:</span>
                    <span>{{ number_format($subtotal, 2) }}€</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>IVA (21%):</span>
                    <span>{{ number_format($tax, 2) }}€</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>Gastos de envío:</span>
                    <span>{{ number_format($shipping, 2) }}€</span>
                </div>
                <div class="flex justify-between font-bold pt-2 border-t mt-2">
                    <span>Total:</span>
                    <span>{{ number_format($total, 2) }}€</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 p-6 rounded-lg mb-6">
            <h3 class="text-xl font-semibold mb-4">Datos de Envío</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">Nombre:</p>
                    <p class="font-semibold">{{ $order->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Dirección:</p>
                    <p class="font-semibold">{{ $order->address }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Ciudad:</p>
                    <p class="font-semibold">{{ $order->city }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Código Postal:</p>
                    <p class="font-semibold">{{ $order->codigo_postal }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-gray-600">Provincia:</p>
                    <p class="font-semibold">{{ $order->provincia }}</p>
                </div>
            </div>
        </div>
        
        <div class="mt-8 text-center">
            <a href="{{ route('admin.orders.generateInvoice', $order->id) }}" target="_blank" class="inline-block bg-orange-500 text-white font-semibold px-6 py-3 rounded-lg hover:bg-orange-600 transition duration-200 mb-4">
                <i class="fas fa-file-pdf mr-2"></i> Descargar Factura
            </a>
            
            <p class="text-gray-600 mt-4">
                Hemos enviado un correo electrónico con la confirmación a {{ $order->user->email ?? 'Email no disponible' }}.
            </p>
            
            <div class="mt-6">
                <a href="{{ route('orders') }}" class="text-orange-500 hover:text-orange-600 font-medium mr-6">
                    <i class="fas fa-clipboard-list mr-2"></i> Ver mis pedidos
                </a>
                <a href="{{ route('shop') }}" class="text-orange-500 hover:text-orange-600 font-medium">
                    <i class="fas fa-shopping-bag mr-2"></i> Seguir comprando
                </a>
            </div>
        </div>
    </div>
    
    <!-- Sección de valoración de productos -->
    <div class="max-w-2xl mx-auto mt-8 bg-gray-50 p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-4">¿Te han gustado tus productos?</h3>
        <p class="text-gray-600 mb-6">Tu opinión es muy importante para nosotros y ayuda a otros clientes a tomar mejores decisiones.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
            @foreach($order->productes as $product)
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="flex items-center mb-3">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded mr-4">
                        <div>
                            <h4 class="font-semibold">{{ $product->name }}</h4>
                            <div class="flex mt-2 product-stars" data-product="{{ $product->id }}">
                                <button class="rate-product" data-rating="1"><i class="far fa-star text-gray-400"></i></button>
                                <button class="rate-product" data-rating="2"><i class="far fa-star text-gray-400"></i></button>
                                <button class="rate-product" data-rating="3"><i class="far fa-star text-gray-400"></i></button>
                                <button class="rate-product" data-rating="4"><i class="far fa-star text-gray-400"></i></button>
                                <button class="rate-product" data-rating="5"><i class="far fa-star text-gray-400"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center">
            <a href="{{ route('orders') }}" class="bg-gray-800 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-200 inline-block">
                Valorar más tarde
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/order-success.js') }}"></script>
@endpush