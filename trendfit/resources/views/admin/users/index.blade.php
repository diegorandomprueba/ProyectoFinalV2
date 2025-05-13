@extends('layouts.admin')

@section('title', 'Gestión de Usuarios - Trendfit Admin')

@section('header', 'Gestión de Usuarios')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-semibold">Listado de Usuarios</h2>
        <p class="text-gray-600">Administra los usuarios registrados en tu tienda</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition duration-200">
        <i class="fas fa-plus mr-2"></i> Nuevo Usuario
    </a>
</div>

<!-- Filtros y búsqueda -->
<div class="bg-white p-4 rounded-lg shadow-md mb-6">
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" placeholder="Buscar por nombre o email..." class="w-full border rounded px-3 py-2" value="{{ request('search') }}">
        </div>
        <div>
            <select name="role" class="border rounded px-3 py-2">
                <option value="">Todos los roles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administradores</option>
                <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Clientes</option>
            </select>
        </div>
        <div>
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700 transition duration-200">
                <i class="fas fa-search mr-2"></i> Filtrar
            </button>
        </div>
        <div>
            <a href="{{ route('admin.users.index') }}" class="text-gray-600 px-4 py-2 rounded hover:bg-gray-100 transition duration-200">
                Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Tabla de usuarios -->
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
                        Email
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Rol
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Registrado el
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $user->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($user->isAdmin)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Administrador
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Cliente
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="text-blue-500 hover:text-blue-700" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-500 hover:text-indigo-700" title="Editar usuario">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700" title="Eliminar usuario">
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
        {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection