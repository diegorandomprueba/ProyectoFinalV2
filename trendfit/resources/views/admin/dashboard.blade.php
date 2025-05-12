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
        <canvas id="salesChart" height="300"></canvas>
    </div>
    
    <!-- Top Productos -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-4">Top Productos Vendidos</h3>
        <canvas id="productsChart" height="300"></canvas>
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
                        Total
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
                @foreach($latestOrders as $order)
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($order->total, 2) }}€
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                Ver
                            </a>
                        </td>
                    </tr>
                @endforeach
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Datos para el gráfico de ventas mensuales
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlySalesLabels) !!},
                datasets: [{
                    label: 'Ventas (€)',
                    data: {!! json_encode($monthlySalesData) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Datos para el gráfico de productos más vendidos
        const productsCtx = document.getElementById('productsChart').getContext('2d');
        const productsChart = new Chart(productsCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($topProductsLabels) !!},
                datasets: [{
                    label: 'Unidades vendidas',
                    data: {!! json_encode($topProductsData) !!},
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endpush