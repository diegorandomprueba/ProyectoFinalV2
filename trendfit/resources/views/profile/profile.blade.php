@extends('layouts.app')

@section('title', 'Editar Perfil - Trendfit')

@section('content')
<div class="container mx-auto py-10">
    <h2 class="text-3xl font-semibold text-center mb-8">Editar Mi Perfil</h2>
    
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Información básica -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Información Personal</h3>
                    
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 mb-2">Nombre completo</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 mb-2">Correo electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Cambiar contraseña -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4">Cambiar Contraseña</h3>
                    <p class="text-gray-600 mb-4">Deja estos campos en blanco si no deseas cambiar tu contraseña.</p>
                    
                    <div class="mb-4">
                        <label for="current_password" class="block text-gray-700 mb-2">Contraseña actual</label>
                        <input type="password" id="current_password" name="current_password" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 mb-2">Nueva contraseña</label>
                        <input type="password" id="password" name="password" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-gray-700 mb-2">Confirmar nueva contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    
                    <!-- Indicadores de fortaleza de contraseña -->
                    <div id="password-strength" class="mt-2 hidden">
                        <meter id="password-meter" min="0" max="4" value="0" class="w-full h-2"></meter>
                        <p id="password-text" class="text-sm text-gray-600 mt-1">Fortaleza: Muy débil</p>
                    </div>
                    
                    <div id="password-match" class="mt-2 hidden text-sm"></div>
                </div>
            </div>
            
            <div class="mt-8 flex justify-between">
                <a href="{{ route('profile') }}" class="bg-gray-300 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-400 transition duration-200">
                    Cancelar
                </a>
                <button type="submit" class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition duration-200">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/profile.js') }}"></script>
@endpush