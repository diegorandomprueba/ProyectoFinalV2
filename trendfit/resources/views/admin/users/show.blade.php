@extends('layouts.admin')

@section('title', 'Detalles de Usuario - Trendfit Admin')

@section('header', 'Detalles de Usuario')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-orange-500 hover:text-orange-700">
            <i class="fas fa-arrow-left mr-2"></i> Volver al listado
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-6">
            <h2 class="text-2xl font-semibold mb-6">Información del Usuario</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-gray-600 font-medium">ID:</p>
                    <p class="text-gray-900">{{ $user->id }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600 font-medium">Nombre:</p>
                    <p class="text-gray-900">{{ $user->name }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600 font-medium">Email:</p>
                    <p class="text-gray-900">{{ $user->email }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600 font-medium">Teléfono:</p>
                    <p class="text-gray-900">{{ $user->phone ?? 'No especificado' }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600 font-medium">Fecha de nacimiento:</p>
                    <p class="text-gray-900">{{ $user->birth_date ? $user->birth_date->format('d/m/Y') : 'No especificada' }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600 font-medium">Rol:</p>
                    <p class="text-gray-900">
                        @if($user->isAdmin)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                Administrador
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Cliente
                            </span>
                        @endif
                    </p>
                </div>
                
                <div>
                    <p class="text-gray-600 font-medium">Fecha de registro:</p>
                    <p class="text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600 font-medium">Última actualización:</p>
                    <p class="text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            
            <div class="flex justify-end">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition duration-200">
                    <i class="fas fa-edit mr-2"></i> Editar Usuario
                </a>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-semibold mb-6">Pedidos del Usuario</h2>
            
            @if(count($user->comandes) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID
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
                            @foreach($user->comandes as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $order->id }}
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
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Este usuario no tiene pedidos.</p>
            @endif
        </div>
    </div>
</div>
@endsection