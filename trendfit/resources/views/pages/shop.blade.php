@extends('layouts.app')

@section('title', 'Tienda - Trendfit')

@section('content')
<div class="container mx-auto py-10">
    <!-- Encabezado de la tienda -->
    <div class="max-w-6xl mx-auto px-4 mb-8">
        <h1 class="text-3xl font-bold mb-2">Nuestra Colección</h1>
        <p class="text-gray-600">Descubre nuestra selección de prendas y accesorios para todos los estilos.</p>
    </div>
    
    <!-- Contenido principal (filtros + productos) -->
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex flex-wrap">
            <!-- Filtros (columna izquierda) -->
            <div class="w-full md:w-1/4 pr-0 md:pr-6 mb-6 md:mb-0">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <!-- Búsqueda -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">Buscar</h3>
                        <form action="{{ route('shop') }}" method="GET">
                            <div class="flex">
                                <input type="text" name="search" placeholder="Buscar productos..." class="w-full p-2 border rounded-l focus:outline-none focus:ring-2 focus:ring-orange-500" value="{{ request('search') }}">
                                <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-r hover:bg-orange-600 transition duration-200">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Categorías -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">Categorías</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('shop') }}" class="text-gray-700 hover:text-orange-500 {{ !request('categoria') ? 'font-semibold text-orange-500' : '' }}">
                                    Todas las categorías
                                </a>
                            </li>
                            @foreach($categorias as $categoria)
                                <li>
                                    <a href="{{ route('shop', ['categoria' => $categoria->id]) }}" class="text-gray-700 hover:text-orange-500 {{ request('categoria') == $categoria->id ? 'font-semibold text-orange-500' : '' }}">
                                        {{ $categoria->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <!-- Subcategorías (si se ha seleccionado una categoría) -->
                    @if(request('categoria') && isset($subcategorias))
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-3">Subcategorías</h3>
                            <ul class="space-y-2">
                                <li>
                                    <a href="{{ route('shop', ['categoria' => request('categoria')]) }}" class="text-gray-700 hover:text-orange-500 {{ !request('subcategoria') ? 'font-semibold text-orange-500' : '' }}">
                                        Todas las subcategorías
                                    </a>
                                </li>
                                @foreach($subcategorias->where('idCategoria', request('categoria')) as $subcategoria)
                                    <li>
                                        <a href="{{ route('shop', ['categoria' => request('categoria'), 'subcategoria' => $subcategoria->id]) }}" class="text-gray-700 hover:text-orange-500 {{ request('subcategoria') == $subcategoria->id ? 'font-semibold text-orange-500' : '' }}">
                                            {{ $subcategoria->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <!-- Filtro de precio -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">Precio</h3>
                        <form action="{{ route('shop') }}" method="GET">
                            @if(request('categoria'))
                                <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                            @endif
                            @if(request('subcategoria'))
                                <input type="hidden" name="subcategoria" value="{{ request('subcategoria') }}">
                            @endif
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            
                            <div class="grid grid-cols-2 gap-2 mb-3">
                                <div>
                                    <label for="min_price" class="block text-sm text-gray-600 mb-1">Mínimo</label>
                                    <input type="number" id="min_price" name="min_price" min="0" step="1" value="{{ request('min_price', '') }}" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-500">
                                </div>
                                <div>
                                    <label for="max_price" class="block text-sm text-gray-600 mb-1">Máximo</label>
                                    <input type="number" id="max_price" name="max_price" min="0" step="1" value="{{ request('max_price', '') }}" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-500">
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700 transition duration-200">
                                Aplicar filtros
                            </button>
                        </form>
                    </div>
                    
                    <!-- Ordenar por -->
                    <div>
                        <h3 class="text-lg font-semibold mb-3">Ordenar por</h3>
                        <form action="{{ route('shop') }}" method="GET" id="sort-form">
                            @if(request('categoria'))
                                <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                            @endif
                            @if(request('subcategoria'))
                                <input type="hidden" name="subcategoria" value="{{ request('subcategoria') }}">
                            @endif
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if(request('min_price'))
                                <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                            @endif
                            @if(request('max_price'))
                                <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                            @endif
                            
                            <select name="sort" id="sort" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-500" onchange="document.getElementById('sort-form').submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Más recientes</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nombre: A-Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nombre: Z-A</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Productos (columna derecha) -->
            <div class="w-full md:w-3/4">
                <!-- Resultados de búsqueda o filtros aplicados -->
                @if(request('search') || request('categoria') || request('subcategoria') || request('min_price') || request('max_price'))
                    <div class="bg-gray-100 p-4 rounded-lg mb-6">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-semibold">Filtros aplicados:</span>
                            
                            @if(request('search'))
                                <span class="bg-white px-3 py-1 rounded-full text-sm flex items-center">
                                    Búsqueda: {{ request('search') }}
                                    <a href="{{ route('shop', array_merge(request()->except('search'), ['page' => 1])) }}" class="ml-2 text-gray-500 hover:text-red-500">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            
                            @if(request('categoria'))
                                <span class="bg-white px-3 py-1 rounded-full text-sm flex items-center">
                                    Categoría: {{ $categorias->find(request('categoria'))->name ?? 'Desconocida' }}
                                    <a href="{{ route('shop', array_merge(request()->except(['categoria', 'subcategoria']), ['page' => 1])) }}" class="ml-2 text-gray-500 hover:text-red-500">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            
                            @if(request('subcategoria'))
                                <span class="bg-white px-3 py-1 rounded-full text-sm flex items-center">
                                    Subcategoría: {{ $subcategorias->find(request('subcategoria'))->name ?? 'Desconocida' }}
                                    <a href="{{ route('shop', array_merge(request()->except('subcategoria'), ['categoria' => request('categoria'), 'page' => 1])) }}" class="ml-2 text-gray-500 hover:text-red-500">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            
                            @if(request('min_price') || request('max_price'))
                                <span class="bg-white px-3 py-1 rounded-full text-sm flex items-center">
                                    Precio: 
                                    @if(request('min_price') && request('max_price'))
                                        {{ request('min_price') }}€ - {{ request('max_price') }}€
                                    @elseif(request('min_price'))
                                        Desde {{ request('min_price') }}€
                                    @else
                                        Hasta {{ request('max_price') }}€
                                    @endif
                                    <a href="{{ route('shop', array_merge(request()->except(['min_price', 'max_price']), ['page' => 1])) }}" class="ml-2 text-gray-500 hover:text-red-500">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            
                            <a href="{{ route('shop') }}" class="text-orange-500 hover:text-orange-600 ml-auto">
                                Limpiar todos los filtros
                            </a>
                        </div>
                    </div>
                @endif
                
                <!-- Listado de productos -->
                @if(count($productos) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($productos as $producto)
                            <div class="bg-white p-4 rounded-lg shadow-md flex flex-col">
                                <a href="{{ route('product.show', $producto->id) }}" class="block">
                                    <div class="w-full h-56 mb-4 overflow-hidden bg-gray-100 rounded-lg">
                                        @if($producto->image)
                                            <img src="{{ asset('storage/' . $producto->image) }}" alt="{{ $producto->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                <span class="text-gray-400 text-4xl">
                                                    <i class="fas fa-image"></i>
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <h3 class="text-lg font-semibold">{{ $producto->name }}</h3>
                                    
                                    <p class="text-sm text-gray-600 mb-2">
                                        {{ Str::limit($producto->descr, 60) }}
                                    </p>
                                </a>
                                
                                <div class="mt-auto pt-4">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-lg font-bold text-orange-500">{{ number_format($producto->price, 2) }}€</span>
                                        <span class="text-sm text-gray-500">Stock: {{ $producto->stock }}</span>
                                    </div>
                                    
                                    <button 
                                    class="add-to-cart-btn w-full bg-gray-800 text-white py-2 rounded hover:bg-gray-700 transition duration-200"                                        data-product-id="{{ $producto->id }}"
                                        data-product-name="{{ $producto->name }}"
                                        data-product-price="{{ $producto->price }}"
                                        data-product-image="{{ asset('storage/' . $producto->image) }}"
                                    >
                                        <i class="fas fa-shopping-cart mr-2"></i> Añadir al Carrito
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Paginación -->
                    <div class="mt-8">
                        {{ $productos->withQueryString()->links() }}
                    </div>
                @else
                    <div class="bg-white p-8 rounded-lg shadow-md text-center">
                        <div class="text-gray-500 mb-4 text-6xl">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">No se encontraron productos</h3>
                        <p class="text-gray-600 mb-4">No hemos encontrado productos que coincidan con tus criterios de búsqueda.</p>
                        <a href="{{ route('shop') }}" class="inline-block bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition duration-200">
                            Ver todos los productos
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/shop.js') }}"></script>
@endpush