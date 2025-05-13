@extends('layouts.admin')

@section('title', 'Gestión de Pedidos - Trendfit Admin')

@section('header', 'Gestión de Pedidos')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-semibold">Listado de Pedidos</h2>
        <p class="text-gray-600">Administra los pedidos de tu tienda</p>
    </div>
</div>

<!-- Filtros y búsqueda -->
<div class="bg-white p-4 rounded-lg shadow-md mb-6">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" placeholder="Buscar por ID o nombre de cliente..." class="w-full border rounded px-3 py-2" value="{{ request('search') }}">
        </div>
        <div>
            <select name="status" class="border rounded px-3 py-2">
                <option value="">Todos los estados</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En proceso</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>
        <div>
            <input type="date" name="from_date" placeholder="Desde fecha" class="border rounded px-3 py-2" value="{{ request('from_date') }}">
        </div>
        <div>
            <input type="date" name="to_date" placeholder="Hasta fecha" class="border rounded px-3 py-2" value="{{ request('to_date') }}">
        </div>
        <div>
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700 transition duration-200">
                <i class="fas fa-search mr-2"></i> Filtrar
            </button>
        </div>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="text-gray-600 px-4 py-2 rounded hover:bg-gray-100 transition duration-200">
                Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Tabla de pedidos -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Cliente
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fecha
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Total
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $order->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                                   'bg-red-100 text-red-800')) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($order->total, 2) }}€
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-500 hover:text-blue-700" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.orders.edit', $order->id) }}" class="text-indigo-500 hover:text-indigo-700" title="Editar pedido">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.orders.generateInvoice', $order->id) }}" class="text-green-500 hover:text-green-700" title="Generar factura" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este pedido?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700" title="Eliminar pedido">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    <div class="px-6 py-4 border-t">
        {{ $orders->withQueryString()->links() }}
    </div>
</div>
@endsection