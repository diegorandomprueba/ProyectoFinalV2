@extends('layouts.admin')

@section('title', 'Dashboard - Trendfit Admin')

@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total de Ventas -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500">Total de Ventas</p>
                <h2 class="text-3xl font-bold">{{ number_format($totalSales, 2) }}€</h2>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-euro-sign text-blue-500 text-xl"></i>
            </div>
        </div>
        <p class="text-green-500 mt-4">
            <i class="fas fa-arrow-up mr-1"></i> 
            <span>{{ number_format($salesGrowth, 2) }}%</span> 
            <span class="text-gray-500">vs mes anterior</span>
        </p>
    </div>
    
    <!-- Pedidos -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500">Pedidos</p>
                <h2 class="text-3xl font-bold">{{ $totalOrders }}</h2>
            </div>
            <div class="bg-orange-100 p-3 rounded-full">
                <i class="fas fa-shopping-cart text-orange-500 text-xl"></i>
            </div>
        </div>
        <p class="text-green-500 mt-4">
            <i class="fas fa-arrow-up mr-1"></i> 
            <span>{{ number_format($ordersGrowth, 2) }}%</span> 
            <span class="text-gray-500">vs mes anterior</span>
        </p>
    </div>
    
    <!-- Clientes -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500">Clientes</p>
                <h2 class="text-3xl font-bold">{{ $totalCustomers }}</h2>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-users text-green-500 text-xl"></i>
            </div>
        </div>
        <p class="text-green-500 mt-4">
            <i class="fas fa-arrow-up mr-1"></i> 
            <span>{{ number_format($customersGrowth, 2) }}%</span> 
            <span class="text-gray-500">vs mes anterior</span>
        </p>
    </div>
    
    <!-- Productos -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500">Productos</p>
                <h2 class="text-3xl font-bold">{{ $totalProducts }}</h2>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <i class="fas fa-box text-purple-500 text-xl"></i>
            </div>
        </div>
        <p class="text-gray-500 mt-4">
            <span>{{ $productsOutOfStock }}</span> 
            <span class="text-red-500">agotados</span>
        </p>
    </div>
</div>

<!-- Gráficos -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Ventas mensuales -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-4">Ventas Mensuales</h3>
        <canvas id="salesChart" width="400" height="300"></canvas>
    </div>
    
    <!-- Top Productos -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-4">Top Productos Vendidos</h3>
        <canvas id="productsChart" width="400" height="300"></canvas>
    </div>
</div>

<!-- Últimos Pedidos -->
<div class="bg-white rounded-lg shadow-md">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold">Últimos Pedidos</h3>
    </div>
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
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($latestOrders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $order->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->user->name ?? 'Usuario desconocido' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'Fecha no disponible' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ isset($order->status) ? ($order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800')) : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($order->status ?? 'Pendiente') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                Ver
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No hay pedidos recientes
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-200">
        <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:text-indigo-900">
            Ver todos los pedidos
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Pasar los datos desde PHP a JavaScript
    window.dashboardData = {
        monthlySalesLabels: {!! json_encode($monthlySalesLabels) !!},
        monthlySalesData: {!! json_encode($monthlySalesData) !!},
        topProductsLabels: {!! json_encode($topProductsLabels) !!},
        topProductsData: {!! json_encode($topProductsData) !!}
    };
</script>
@vite(['resources/js/admin/dashboard-charts.js'])
@endpush