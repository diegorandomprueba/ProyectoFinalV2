<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Administrador - Trendfit')</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- FontAwesome desde CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <!-- Scripts adicionales específicos de cada página -->
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 text-white shadow-xl transform transition-transform duration-300 ease-in-out">
        <div class="flex items-center justify-center h-16 border-b border-gray-800">
            <h1 class="text-xl font-bold">Trendfit Admin</h1>
        </div>
        <nav class="mt-5">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center py-3 px-6 hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800' : '' }}">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center py-3 px-6 hover:bg-gray-800 {{ request()->routeIs('admin.products.*') ? 'bg-gray-800' : '' }}">
                <i class="fas fa-box mr-3"></i>
                Productos
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center py-3 px-6 hover:bg-gray-800 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-800' : '' }}">
                <i class="fas fa-tags mr-3"></i>
                Categorías
            </a>
            <a href="{{ route('admin.subcategories.index') }}" class="flex items-center py-3 px-6 hover:bg-gray-800 {{ request()->routeIs('admin.subcategories.*') ? 'bg-gray-800' : '' }}">
                <i class="fas fa-tag mr-3"></i>
                Subcategorías
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center py-3 px-6 hover:bg-gray-800 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-800' : '' }}">
                <i class="fas fa-shopping-cart mr-3"></i>
                Pedidos
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center py-3 px-6 hover:bg-gray-800 {{ request()->routeIs('admin.users.*') ? 'bg-gray-800' : '' }}">
                <i class="fas fa-users mr-3"></i>
                Usuarios
            </a>
            <a href="{{ route('home') }}" class="flex items-center py-3 px-6 hover:bg-gray-800">
                <i class="fas fa-store mr-3"></i>
                Ver Tienda
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                @csrf
                <button type="submit" class="flex items-center py-3 px-6 hover:bg-gray-800 w-full text-left">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Cerrar Sesión
                </button>
            </form>
        </nav>
    </div>

    <!-- Contenido principal -->
    <div class="ml-64 flex flex-col flex-1">
        <!-- Header -->
        <header class="bg-white shadow h-16 flex items-center justify-between px-6">
            <h2 class="text-xl font-semibold">@yield('header', 'Panel de Administración')</h2>
            <div class="flex items-center">
                <span class="mr-4">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </header>

        <!-- Contenido -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <!-- Scripts adicionales específicos de cada página -->
    @stack('scripts')
</body>
</html><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Administrador - Trendfit')</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- FontAwesome desde CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <!-- Scripts adicionales específicos de cada página -->
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 text-white shadow-xl transform transition-transform duration-300 ease-in-out">
        <div class="flex items-center justify-center h-16 border-b border-gray-800">
            <h1 class="text-xl font-bold">Trendfit Admin</h1>
        </div>
        <nav class="mt-5">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center py-3 px-6 hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800' : '' }}">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center py-3 px-6 hover:bg-gray-800 {{ request()->routeIs('admin.products.*') ? 'bg-gray-800' : '' }}">
                <i class="fas fa-box mr-3"></i>
                Productos
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center py-3 px-6 hover:bg-gray-800 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-800' : '' }}">
                <i class="fas fa-tags mr-3"></i>
                Categorías
            </a>
            <a href="{{ route('admin.subcategories.index') }}" class="flex items-center py-3 px-6 hover:bg-gray-800 {{ request()->routeIs('admin.subcategories.*') ? 'bg-gray-800' : '' }}">
                <i class="fas fa-tag mr-3"></i>
                Subcategorías
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center py-3 px-6 hover:bg-gray-800 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-800' : '' }}">
                <i class="fas fa-shopping-cart mr-3"></i>
                Pedidos
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center py-3 px-6 hover:bg-gray-800 {{ request()->routeIs('admin.users.*') ? 'bg-gray-800' : '' }}">
                <i class="fas fa-users mr-3"></i>
                Usuarios
            </a>
            <a href="{{ route('home') }}" class="flex items-center py-3 px-6 hover:bg-gray-800">
                <i class="fas fa-store mr-3"></i>
                Ver Tienda
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                @csrf
                <button type="submit" class="flex items-center py-3 px-6 hover:bg-gray-800 w-full text-left">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Cerrar Sesión
                </button>
            </form>
        </nav>
    </div>

    <!-- Contenido principal -->
    <div class="ml-64 flex flex-col flex-1">
        <!-- Header -->
        <header class="bg-white shadow h-16 flex items-center justify-between px-6">
            <h2 class="text-xl font-semibold">@yield('header', 'Panel de Administración')</h2>
            <div class="flex items-center">
                <span class="mr-4">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </header>

        <!-- Contenido -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <!-- Scripts adicionales específicos de cada página -->
    @stack('scripts')
</body>
</html>