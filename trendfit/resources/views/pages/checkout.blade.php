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
                            <input type="tel" id="shipping_phone" name="shipping_phone" value="{{ auth()->user()->phone ?? '' }}" required class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
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
                            <input type="text" id="shipping_code" name="shipping_code" value="{{ auth()->user()->postal_code ?? '' }}" required class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="shipping_province" class="block text-gray-700 font-medium mb-2">Provincia</label>
                            <input type="text" id="shipping_province" name="shipping_province" value="{{ auth()->user()->province ?? '' }}" required class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                    </div>
                </div>
                
                <!-- Información de facturación -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 pb-2 border-b">Información de Facturación</h3>
                    
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" id="same_as_shipping" name="same_as_shipping" class="form-checkbox h-5 w-5 text-orange-500" checked>
                            <span class="ml-2 text-gray-700">Usar la misma dirección de entrega</span>
                        </label>
                    </div>
                    
                    <div id="billing-info" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                        <div>
                            <label for="billing_name" class="block text-gray-700 font-medium mb-2">Nombre Completo</label>
                            <input type="text" id="billing_name" name="billing_name" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        
                        <div>
                            <label for="billing_phone" class="block text-gray-700 font-medium mb-2">Teléfono</label>
                            <input type="tel" id="billing_phone" name="billing_phone" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="billing_address" class="block text-gray-700 font-medium mb-2">Dirección</label>
                            <input type="text" id="billing_address" name="billing_address" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        
                        <div>
                            <label for="billing_city" class="block text-gray-700 font-medium mb-2">Ciudad</label>
                            <input type="text" id="billing_city" name="billing_city" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        
                        <div>
                            <label for="billing_code" class="block text-gray-700 font-medium mb-2">Código Postal</label>
                            <input type="text" id="billing_code" name="billing_code" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="billing_province" class="block text-gray-700 font-medium mb-2">Provincia</label>
                            <input type="text" id="billing_province" name="billing_province" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
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
                                <img src="{{ asset('img/payment/card.png') }}" alt="Tarjeta de crédito" class="w-12 h-12 mb-2">
                                <span class="text-center">Tarjeta de crédito</span>
                            </label>
                            <label class="relative border rounded-lg p-4 flex flex-col items-center cursor-pointer">
                                <input type="radio" name="payment_method" value="paypal" class="absolute top-2 right-2">
                                <img src="{{ asset('img/payment/paypal.png') }}" alt="PayPal" class="w-12 h-12 mb-2">
                                <span class="text-center">PayPal</span>
                            </label>
                            <label class="relative border rounded-lg p-4 flex flex-col items-center cursor-pointer">
                                <input type="radio" name="payment_method" value="transfer" class="absolute top-2 right-2">
                                <img src="{{ asset('img/payment/transfer.png') }}" alt="Transferencia" class="w-12 h-12 mb-2">
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle entre misma dirección de envío/facturación
        const sameAsShippingCheckbox = document.getElementById('same_as_shipping');
        const billingInfoContainer = document.getElementById('billing-info');
        
        sameAsShippingCheckbox.addEventListener('change', function() {
            if (this.checked) {
                billingInfoContainer.classList.add('hidden');
                
                // Desactivar los campos de facturación
                billingInfoContainer.querySelectorAll('input').forEach(input => {
                    input.required = false;
                });
            } else {
                billingInfoContainer.classList.remove('hidden');
                
                // Activar los campos de facturación
                billingInfoContainer.querySelectorAll('input').forEach(input => {
                    input.required = true;
                });
            }
        });
        
        // Toggle entre métodos de pago
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        const cardPaymentInfo = document.getElementById('card-payment-info');
        const paypalPaymentInfo = document.getElementById('paypal-payment-info');
        const transferPaymentInfo = document.getElementById('transfer-payment-info');
        
        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                // Ocultar todos los contenedores
                cardPaymentInfo.classList.add('hidden');
                paypalPaymentInfo.classList.add('hidden');
                transferPaymentInfo.classList.add('hidden');
                
                // Desactivar todos los campos
                cardPaymentInfo.querySelectorAll('input').forEach(input => {
                    input.required = false;
                });
                
                // Mostrar el contenedor correspondiente
                if (this.value === 'card') {
                    cardPaymentInfo.classList.remove('hidden');
                    
                    // Activar los campos de tarjeta
                    cardPaymentInfo.querySelectorAll('input').forEach(input => {
                        input.required = true;
                    });
                } else if (this.value === 'paypal') {
                    paypalPaymentInfo.classList.remove('hidden');
                } else if (this.value === 'transfer') {
                    transferPaymentInfo.classList.remove('hidden');
                }
            });
        });
        
        // Validación del formulario
        const checkoutForm = document.getElementById('checkout-form');
        
        checkoutForm.addEventListener('submit', function(e) {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            
            if (paymentMethod === 'card') {
                const cardNumber = document.getElementById('card_number').value;
                const cardName = document.getElementById('card_name').value;
                const cardExpiry = document.getElementById('card_expiry').value;
                const cardCvv = document.getElementById('card_cvv').value;
                
                const cardNumberRegex = /^\d{16}$/;
                const cardExpiryRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
                const cardCvvRegex = /^\d{3,4}$/;
                
                let valid = true;
                
                if (!cardNumberRegex.test(cardNumber.replace(/\s/g, ''))) {
                    alert('Por favor, introduce un número de tarjeta válido (16 dígitos)');
                    valid = false;
                }
                
                if (cardName.trim() === '') {
                    alert('Por favor, introduce el nombre que aparece en la tarjeta');
                    valid = false;
                }
                
                if (!cardExpiryRegex.test(cardExpiry)) {
                    alert('Por favor, introduce una fecha de caducidad válida (MM/YY)');
                    valid = false;
                }
                
                if (!cardCvvRegex.test(cardCvv)) {
                    alert('Por favor, introduce un CVV válido (3 o 4 dígitos)');
                    valid = false;
                }
                
                if (!valid) {
                    e.preventDefault();
                }
            }
        });
    });
</script>
@endpush