@extends('layouts.admin')

@section('title', 'Detalles de Pedido - Trendfit Admin')

@section('header', 'Detalles de Pedido')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" class="text-orange-500 hover:text-orange-700">
            <i class="fas fa-arrow-left mr-2"></i> Volver al listado
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-6">
            <h2 class="text-2xl font-semibold mb-2">Pedido #{{ $order->id }}</h2>
            <p class="text-gray-600 mb-6">Realizado el {{ $order->date }}</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Información del Cliente</h3>
                    <p><strong>Nombre:</strong> {{ $order->user->name ?? 'No especificado'}}</p>
                    <p><strong>Email:</strong> {{ $order->user->email ?? 'No especificado'}}</p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-2">Información de Envío</h3>
                    <p><strong>Nombre:</strong> {{ $order->name ?? 'No especificado'}}</p>
                    <p><strong>Dirección:</strong> {{ $order->address ?? 'No especificado'}}</p>
                    <p><strong>Ciudad:</strong> {{ $order->city ?? 'No especificado'}}</p>
                    <p><strong>Código Postal:</strong> {{ $order->codigo_postal ?? 'No especificado'}}</p>
                    <p><strong>Provincia:</strong> {{ $order->provincia ?? 'No especificado'}}</p>
                </div>
            </div>
            
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Estado del Pedido</h3>
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="flex items-center">
                    @csrf
                    @method('PUT')
                    
                    <select name="status" class="border rounded-l px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>En proceso</option>
                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completado</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                    
                    <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-r hover:bg-orange-600 transition duration-200">
                        Actualizar Estado
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Productos</h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Producto
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Precio
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
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $product->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($product->price, 2) }}€
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->pivot->cant }}
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
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Resumen</h3>
            
            <div class="border-b pb-4 mb-4">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Subtotal:</span>
                    <span>{{ number_format($subtotal, 2) }}€</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">IVA (21%):</span>
                    <span>{{ number_format($tax, 2) }}€</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Gastos de envío:</span>
                    <span>{{ number_format($shipping, 2) }}€</span>
                </div>
                @if($discount > 0)
                    <div class="flex justify-between text-green-600 mb-2">
                        <span>Descuento:</span>
                        <span>-{{ number_format($discount, 2) }}€</span>
                    </div>
                @endif
            </div>
            
            <div class="flex justify-between font-bold text-lg">
                <span>Total:</span>
                <span>{{ number_format($total, 2) }}€</span>
            </div>
        </div>
    </div>
    
    <div class="flex justify-end space-x-4">
        <a href="{{ route('admin.orders.generateInvoice', $order->id) }}" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700 transition duration-200" target="_blank">
            <i class="fas fa-file-pdf mr-2"></i> Generar Factura
        </a>
        <a href="{{ route('admin.orders.edit', $order->id) }}" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition duration-200">
            <i class="fas fa-edit mr-2"></i> Editar Pedido
        </a>
    </div>
</div>
@endsection