@extends('layouts.admin')

@section('title', 'Gestión de Categorías - Trendfit Admin')

@section('header', 'Gestión de Categorías')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-semibold">Listado de Categorías</h2>
        <p class="text-gray-600">Administra las categorías de productos de tu tienda</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition duration-200">
        <i class="fas fa-plus mr-2"></i> Nueva Categoría
    </a>
</div>

<!-- Filtros y búsqueda -->
<div class="bg-white p-4 rounded-lg shadow-md mb-6">
    <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" placeholder="Buscar por nombre..." class="w-full border rounded px-3 py-2" value="{{ request('search') }}">
        </div>
        <div>
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700 transition duration-200">
                <i class="fas fa-search mr-2"></i> Filtrar
            </button>
        </div>
        <div>
            <a href="{{ route('admin.categories.index') }}" class="text-gray-600 px-4 py-2 rounded hover:bg-gray-100 transition duration-200">
                Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Tabla de categorías -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Imagen
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nombre
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Subcategorías
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($categories as $category)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $category->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-12 h-12 object-cover rounded">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $category->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $category->subcategorias->count() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-indigo-500 hover:text-indigo-700" title="Editar categoría">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta categoría? Se eliminarán también todas sus subcategorías y productos asociados.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700" title="Eliminar categoría">
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
        {{ $categories->withQueryString()->links() }}
    </div>
</div>
@endsection