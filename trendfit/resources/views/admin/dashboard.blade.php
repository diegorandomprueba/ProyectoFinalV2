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