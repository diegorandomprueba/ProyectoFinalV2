@extends('layouts.app')

@section('title', 'Mi Perfil - Trendfit')

@section('content')
<div class="container mx-auto py-10">
    <h2 class="text-3xl font-semibold text-center mb-8">Mi Perfil</h2>
    
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Información Personal</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-gray-600 font-medium">Nombre:</p>
                    <p class="text-gray-900">{{ $user->name }}</p>
                </div>
                
                <div>
                    <p class="text-gray-600 font-medium">Correo electrónico:</p>
                    <p class="text-gray-900">{{ $user->email }}</p>
                </div>
            </div>
        </div>
        
        <div class="flex flex-col md:flex-row justify-center gap-4">
            <a href="{{ route('profile.edit') }}" class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition duration-200 text-center">
                Editar Perfil
            </a>
            
            <button 
                type="button" 
                onclick="confirmDelete()" 
                class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition duration-200"
            >
                Eliminar Cuenta
            </button>
        </div>
        
        <!-- Modal de confirmación (oculto por defecto) -->
        <div id="delete-confirmation-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white p-6 rounded-lg shadow-xl max-w-md mx-4">
                <h3 class="text-xl font-semibold mb-4">Eliminar cuenta</h3>
                <p class="mb-6">Esta acción no se puede deshacer. ¿Estás seguro de que quieres eliminar permanentemente tu cuenta?</p>
                
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-4">
                        <label for="delete-password" class="block text-gray-700 mb-2">Introduce tu contraseña para confirmar:</label>
                        <input type="password" id="delete-password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                    </div>
                    
                    <div class="flex justify-end gap-4">
                        <button type="button" onclick="hideDeleteModal()" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition">
                            Cancelar
                        </button>
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                            Eliminar Cuenta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete() {
        document.getElementById('delete-confirmation-modal').classList.remove('hidden');
    }
    
    function hideDeleteModal() {
        document.getElementById('delete-confirmation-modal').classList.add('hidden');
    }
</script>
@endpush