@extends('layouts.admin')

@section('title', 'Editar Usuario - Trendfit Admin')

@section('header', 'Editar Usuario')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-orange-500 hover:text-orange-700">
            <i class="fas fa-arrow-left mr-2"></i> Volver al listado
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-semibold mb-6">Editar Usuario: {{ $user->name }}</h2>
            
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-gray-700 font-medium mb-2">Nombre completo</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('name') border-red-500 @enderror" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-gray-700 font-medium mb-2">Correo electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('email') border-red-500 @enderror" required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="password" class="block text-gray-700 font-medium mb-2">Contraseña (dejar en blanco para mantener la actual)</label>
                        <input type="password" id="password" name="password" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-gray-700 font-medium mb-2">Teléfono</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="birth_date" class="block text-gray-700 font-medium mb-2">Fecha de nacimiento</label>
                    <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('birth_date') border-red-500 @enderror">
                    @error('birth_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="isAdmin" class="form-checkbox h-5 w-5 text-orange-500" {{ old('isAdmin', $user->isAdmin) ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700">Es administrador</span>
                    </label>
                </div>
                
                <div class="flex justify-end">
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition duration-200 mr-2">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition duration-200">
                        Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection