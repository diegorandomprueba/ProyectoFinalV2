@extends('layouts.app')

@section('title', 'Registro - Trendfit')

@section('content')
<div class="container mx-auto py-10">
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-center mb-6">Crear cuenta</h2>
        
        <form method="POST" action="{{ route('register') }}" id="register-form">
            @csrf
            
            <div class="mb-4">
                <label for="name" class="block text-gray-700 mb-2">Nombre completo</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 mb-2">Correo electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-gray-700 mb-2">Contraseña</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                <div id="password-strength" class="mt-2">
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div id="password-meter" class="bg-red-500 h-2.5 rounded-full" style="width: 0%"></div>
                    </div>
                    <p id="password-text" class="text-xs text-gray-500 mt-1">Fortaleza: Muy débil</p>
                </div>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 mb-2">Confirmar contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                @error('password_confirmation')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <button type="submit" class="w-full bg-orange-500 text-white py-2 px-4 rounded-lg hover:bg-orange-600 transition duration-200">
                    Registrarse
                </button>
            </div>
            
            <p class="text-center text-gray-600">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-orange-500 hover:underline">Iniciar sesión</a>
            </p>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/register.js') }}"></script>
@endpush