@extends('layouts.app')

@section('title', 'Carrito de Compra - Trendfit')

@section('content')
<div class="container mx-auto py-10">
    <h2 class="text-3xl font-semibold text-center mb-8">Carrito de Compra</h2>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-4 bg-gray-50 border-b">
                    <h3 class="text-lg font-semibold">Productos</h3>
                </div>
                
                <div id="cart-items" class="p-4">
                    <!-- Los items del carrito se cargarán desde JavaScript -->
                </div>
                
                <div id="empty-cart-message" class="p-8 text-center text-gray-500 hidden">
                    <i class="fas fa-shopping-cart text-4xl mb-4"></i>
                    <p>Tu carrito está vacío</p>
                    <a href="{{ route('shop') }}" class="inline-block mt-4 text-orange-500 hover:underline">
                        Continuar comprando
                    </a>
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-1">
            <div id="cart-summary" class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Resumen del Pedido</h3>
                
                <div class="space-y-2 border-b pb-4 mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span id="cart-subtotal">0.00€</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">IVA (21%)</span>
                        <span id="cart-tax">0.00€</span>
                    </div>
                </div>
                
                <div class="flex justify-between items-center font-semibold text-lg mb-6">
                    <span>Total</span>
                    <span id="cart-total">0.00€</span>
                </div>
                
                <div>
                    <a href="{{ route('checkout') }}" id="checkout-button" class="block w-full bg-orange-500 text-white text-center py-3 rounded-lg hover:bg-orange-600 transition duration-200">
                        Finalizar Compra
                    </a>
                    
                    <a href="{{ route('shop') }}" class="block w-full text-center mt-4 text-orange-500 hover:underline">
                        Continuar comprando
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection