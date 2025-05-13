@extends('layouts.admin')

@section('title', 'Editar Pedido - Trendfit Admin')

@section('header', 'Editar Pedido')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" class="text-orange-500 hover:text-orange-700">
            <i class="fas fa-arrow-left mr-2"></i> Volver al listado
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-6">
            <h2 class="text-2xl font-semibold mb-6">Editar Pedido #{{ $order->id }}</h2>
            
            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="idUsuari" class="block text-gray-700 font-medium mb-2">Cliente</label>
                    <select id="idUsuari" name="idUsuari" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('idUsuari') border-red-500 @enderror" required>
                        <option value="">Seleccionar cliente</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('idUsuari', $order->idUsuari) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('idUsuari')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-gray-700 font-medium mb-2">Nombre de contacto</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $order->name) }}" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('name') border-red-500 @enderror" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="address" class="block text-gray-700 font-medium mb-2">Dirección</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $order->address) }}" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('address') border-red-500 @enderror" required>
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="city" class="block text-gray-700 font-medium mb-2">Ciudad</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $order->city) }}" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('city') border-red-500 @enderror" required>
                        @error('city')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="provincia" class="block text-gray-700 font-medium mb-2">Provincia</label>
                        <input type="text" id="provincia" name="provincia" value="{{ old('provincia', $order->provincia) }}" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('provincia') border-red-500 @enderror" required>
                        @error('provincia')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="codigo_postal" class="block text-gray-700 font-medium mb-2">Código Postal</label>
                        <input type="text" id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal', $order->codigo_postal) }}" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('codigo_postal') border-red-500 @enderror" required>
                        @error('codigo_postal')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="status" class="block text-gray-700 font-medium mb-2">Estado del Pedido</label>
                    <select id="status" name="status" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('status') border-red-500 @enderror" required>
                        <option value="pending" {{ old('status', $order->status) === 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="processing" {{ old('status', $order->status) === 'processing' ? 'selected' : '' }}>En proceso</option>
                        <option value="completed" {{ old('status', $order->status) === 'completed' ? 'selected' : '' }}>Completado</option>
                        <option value="cancelled" {{ old('status', $order->status) === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 pb-2 border-b">Productos del Pedido</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Producto
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Precio Unit.
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cantidad
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->productes as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($product->price, 2) }}€
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <input type="number" name="products[{{ $product->id }}][cant]" value="{{ $product->pivot->cant }}" min="1" class="w-16 border rounded px-2 py-1 text-center">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($product->price * $product->pivot->cant, 2) }}€
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 pb-2 border-b">Añadir Productos</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6" id="products-container">
                        <div class="flex items-center product-row">
                            <div class="flex-1">
                                <select name="new_products[0][id]" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 product-select">
                                    <option value="">Seleccionar producto</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                            {{ $product->name }} ({{ number_format($product->price, 2) }}€) - Stock: {{ $product->stock }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="ml-2">
                                <input type="number" name="new_products[0][cant]" placeholder="Cant." min="1" value="1" class="w-16 border rounded px-2 py-2 product-quantity">
                            </div>
                            <div class="ml-2">
                                <button type="button" class="bg-red-500 text-white p-2 rounded hover:bg-red-600 remove-product">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="ml-4 product-row-total font-medium hidden">0.00€</div>
                        </div>
                    </div>
                    
                    <button type="button" id="add-product-btn" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-200">
                        <i class="fas fa-plus mr-2"></i> Añadir Producto
                    </button>
                </div>
                
                <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Resumen del Pedido</h3>
                    
                    <div class="flex justify-end">
                        <div class="w-64">
                            <div class="flex justify-between mb-2">
                                <span>Subtotal:</span>
                                <span id="subtotal-amount">{{ number_format($subtotal, 2) }}€</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span>IVA (21%):</span>
                                <span id="tax-amount">{{ number_format($tax, 2) }}€</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span>Gastos de envío:</span>
                                <span>{{ number_format($shipping, 2) }}€</span>
                            </div>
                            @if($discount > 0)
                                <div class="flex justify-between text-green-600 mb-2">
                                    <span>Descuento:</span>
                                    <span>-{{ number_format($discount, 2) }}€</span>
                                </div>
                            @endif
                            <div class="flex justify-between font-bold border-t pt-2 mt-2">
                                <span>Total:</span>
                                <span id="total-amount">{{ number_format($total, 2) }}€</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <a href="{{ route('admin.orders.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition duration-200 mr-2">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition duration-200">
                        Actualizar Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/orders.js') }}"></script>
@endpush