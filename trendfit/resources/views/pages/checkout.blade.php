@extends('layouts.app')

@section('title', 'Checkout - Trendfit')

@section('content')
<div class="container mx-auto py-10">
    <h2 class="text-3xl font-semibold text-center mb-8">Finalizar Compra</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Formulario de checkout -->
        <div class="md:col-span-2">
            <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
                @csrf
                
                <!-- Información de entrega -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 pb-2 border-b">Información de Entrega</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="shipping_name" class="block text-gray-700 font-medium mb-2">Nombre Completo</label>
                            <input type="text" id="shipping_name" name="shipping_name" value="{{ auth()->user()->name }}" required class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        
                        <div>
                            <label for="shipping_phone" class="block text-gray-700 font-medium mb-2">Teléfono</label>
                            <input type="tel" id="shipping_phone" name="shipping_phone" value="{{ auth()->user()->phone ?? '' }}" required pattern="[0-9]{9,10}" maxlength="10" title="Introduce un número de teléfono válido (solo números, máximo 10 dígitos)" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <p class="text-sm text-gray-500 mt-1">Solo números, máximo 10 dígitos</p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="shipping_address" class="block text-gray-700 font-medium mb-2">Dirección</label>
                            <input type="text" id="shipping_address" name="shipping_address" value="{{ auth()->user()->address ?? '' }}" required class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        
                        <div>
                            <label for="shipping_city" class="block text-gray-700 font-medium mb-2">Ciudad</label>
                            <input type="text" id="shipping_city" name="shipping_city" value="{{ auth()->user()->city ?? '' }}" required class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        
                        <div>
                            <label for="shipping_code" class="block text-gray-700 font-medium mb-2">Código Postal</label>
                            <input type="text" id="shipping_code" name="shipping_code" value="{{ auth()->user()->postal_code ?? '' }}" required pattern="[0-9]{5}" maxlength="5" title="El código postal debe tener 5 dígitos" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <p class="text-sm text-gray-500 mt-1">5 dígitos numéricos</p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="shipping_province" class="block text-gray-700 font-medium mb-2">Provincia</label>
                            <input type="text" id="shipping_province" name="shipping_province" value="{{ auth()->user()->province ?? '' }}" required class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                    </div>
                </div>
                
                <!-- Información de pago -->
                <div>
                    <h3 class="text-xl font-semibold mb-4 pb-2 border-b">Método de Pago</h3>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Selecciona un método de pago</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="relative border rounded-lg p-4 flex flex-col items-center cursor-pointer">
                                <input type="radio" name="payment_method" value="card" class="absolute top-2 right-2" checked>
                                <img src="{{ asset('img/payment/card.png') }}" class="w-12 h-12 mb-2">
                                <span class="text-center">Tarjeta de crédito</span>
                            </label>
                            <label class="relative border rounded-lg p-4 flex flex-col items-center cursor-pointer">
                                <input type="radio" name="payment_method" value="paypal" class="absolute top-2 right-2">
                                <img src="{{ asset('img/payment/paypal.png') }}" class="w-12 h-12 mb-2">
                                <span class="text-center">PayPal</span>
                            </label>
                            <label class="relative border rounded-lg p-4 flex flex-col items-center cursor-pointer">
                                <input type="radio" name="payment_method" value="transfer" class="absolute top-2 right-2">
                                <img src="{{ asset('img/payment/transfer.png') }}" class="w-12 h-12 mb-2">
                                <span class="text-center">Transferencia</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Información de tarjeta de crédito -->
                    <div id="card-payment-info">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="card_number" class="block text-gray-700 font-medium mb-2">Número de Tarjeta</label>
                                <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            </div>
                            
                            <div>
                                <label for="card_name" class="block text-gray-700 font-medium mb-2">Nombre en la Tarjeta</label>
                                <input type="text" id="card_name" name="card_name" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="card_expiry" class="block text-gray-700 font-medium mb-2">Fecha de Caducidad</label>
                                    <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                                </div>
                                
                                <div>
                                    <label for="card_cvv" class="block text-gray-700 font-medium mb-2">CVV</label>
                                    <input type="text" id="card_cvv" name="card_cvv" placeholder="123" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información de PayPal (oculto por defecto) -->
                    <div id="paypal-payment-info" class="hidden">
                        <p class="text-gray-700 mb-4">Serás redirigido a PayPal para completar el pago después de confirmar el pedido.</p>
                    </div>
                    
                    <!-- Información de transferencia (oculto por defecto) -->
                    <div id="transfer-payment-info" class="hidden">
                        <div class="bg-gray-100 p-4 rounded-lg mb-4">
                            <p class="font-semibold mb-2">Datos para la transferencia:</p>
                            <p>Banco: Banco Trendfit</p>
                            <p>IBAN: ES12 3456 7890 1234 5678 9012</p>
                            <p>Titular: Trendfit S.L.</p>
                            <p class="mt-2 text-sm text-gray-600">Tu pedido será procesado una vez confirmemos el ingreso.</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <input type="hidden" name="cart_items" id="cart-items-input" value="{{ session()->has('cart_items') ? json_encode(session('cart_items')) : '[]' }}">
                    <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition duration-200">
                        Finalizar Compra
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Resumen del pedido -->
        <div class="md:col-span-1">
            <div class="bg-white p-6 rounded-lg shadow-md sticky top-6">
                <h3 class="text-xl font-semibold mb-4 pb-2 border-b">Resumen del Pedido</h3>
                
                <div class="space-y-4 mb-6">
                    @foreach($cartItems as $item)
                        <div class="flex items-center">
                            <img src="{{ asset('storage/' . $item['product']->image) }}" alt="{{ $item['product']->name }}" class="w-16 h-16 object-cover rounded mr-4">
                            <div class="flex-1">
                                <p class="font-medium">{{ $item['product']->name }}</p>
                                <p class="text-gray-600 text-sm">
                                    {{ $item['size'] ?? 'N/A' }} | {{ $item['quantity'] }} x {{ number_format($item['product']->price, 2) }}€
                                </p>
                            </div>
                            <span class="font-medium">{{ number_format($item['product']->price * $item['quantity'], 2) }}€</span>
                        </div>
                    @endforeach
                </div>
                
                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span>{{ number_format($subtotal, 2) }}€</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">IVA (21%)</span>
                        <span>{{ number_format($tax, 2) }}€</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Envío</span>
                        <span>{{ number_format($shipping, 2) }}€</span>
                    </div>
                    @if($discount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Descuento</span>
                            <span>-{{ number_format($discount, 2) }}€</span>
                        </div>
                    @endif
                    <div class="flex justify-between font-bold text-lg pt-2 border-t mt-2">
                        <span>Total</span>
                        <span>{{ number_format($total, 2) }}€</span>
                    </div>
                </div>
                
                <div class="mt-6">
                    <a href="{{ route('cart') }}" class="block text-center text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left mr-2"></i> Volver al carrito
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/checkout.js') }}"></script>
@endpush