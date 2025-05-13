@extends('layouts.admin')

@section('title', 'Gestión de Subcategorías - Trendfit Admin')

@section('header', 'Gestión de Subcategorías')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-semibold">Listado de Subcategorías</h2>
        <p class="text-gray-600">Administra las subcategorías de productos de tu tienda</p>
    </div>
    <a href="{{ route('admin.subcategories.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition duration-200">
        <i class="fas fa-plus mr-2"></i> Nueva Subcategoría
    </a>
</div>

<!-- Filtros y búsqueda -->
<div class="bg-white p-4 rounded-lg shadow-md mb-6">
    <form action="{{ route('admin.subcategories.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" placeholder="Buscar por nombre..." class="w-full border rounded px-3 py-2" value="{{ request('search') }}">
        </div>
        <div>
            <select name="category_id" class="border rounded px-3 py-2">
                <option value="">Todas las categorías</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700 transition duration-200">
                <i class="fas fa-search mr-2"></i> Filtrar
            </button>
        </div>
        <div>
            <a href="{{ route('admin.subcategories.index') }}" class="text-gray-600 px-4 py-2 rounded hover:bg-gray-100 transition duration-200">
                Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Tabla de subcategorías -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nombre
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Categoría
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Productos
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($subcategories as $subcategory)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $subcategory->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $subcategory->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $subcategory->categoria->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $subcategory->productes->count() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.subcategories.edit', $subcategory->id) }}" class="text-indigo-500 hover:text-indigo-700" title="Editar subcategoría">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.subcategories.destroy', $subcategory->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta subcategoría? Se eliminarán también todos los productos asociados.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700" title="Eliminar subcategoría">
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
        {{ $subcategories->withQueryString()->links() }}
    </div>
</div>
@endsection