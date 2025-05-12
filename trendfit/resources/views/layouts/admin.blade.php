<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Trendfit')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col md:flex-row">
    <!-- Sidebar -->
    <aside class="bg-gray-900 text-white w-full md:w-64 md:min-h-screen" x-data="{ open: false }">
        <div class="flex justify-between items-center p-4 md:p-6">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-orange-500">Trendfit Admin</a>
            <button class="md:hidden text-white" @click="open = !open">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
        
        <nav class="mt-6" :class="{'hidden': !open}" class="md:block">
            <div class="px-4 md:px-6">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-orange-500' : 'hover:bg-gray-800' }}">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-orange-500' : 'hover:bg-gray-800' }}">
                            <i class="fas fa-box mr-2"></i> Productos
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.categories.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-orange-500' : 'hover:bg-gray-800' }}">
                            <i class="fas fa-tags mr-2"></i> Categorías
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.subcategories.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.subcategories.*') ? 'bg-orange-500' : 'hover:bg-gray-800' }}">
                            <i class="fas fa-tag mr-2"></i> Subcategorías
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.orders.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.orders.*') ? 'bg-orange-500' : 'hover:bg-gray-800' }}">
                            <i class="fas fa-shopping-cart mr-2"></i> Pedidos
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-orange-500' : 'hover:bg-gray-800' }}">
                            <i class="fas fa-users mr-2"></i> Usuarios
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1">
        <!-- Top bar -->
        <header class="bg-white shadow">
            <div class="flex justify-between items-center py-4 px-6">
                <h1 class="text-xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h1>
                
                <div class="flex items-center">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-gray-700 focus:outline-none">
                            <span class="mr-2">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                            <a href="{{ route('home') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Ver tienda</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Cerrar Sesión</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="p-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>