@extends('layouts.admin')

@section('title', 'Editar Subcategoría - Trendfit Admin')

@section('header', 'Editar Subcategoría')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.subcategories.index') }}" class="text-orange-500 hover:text-orange-700">
            <i class="fas fa-arrow-left mr-2"></i> Volver al listado
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-semibold mb-6">Editar Subcategoría: {{ $subcategory->name }}</h2>
            
            <form action="{{ route('admin.subcategories.update', $subcategory->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="name" class="block text-gray-700 font-medium mb-2">Nombre</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $subcategory->name) }}" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="descr" class="block text-gray-700 font-medium mb-2">Descripción</label>
                    <textarea id="descr" name="descr" rows="4" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('descr') border-red-500 @enderror">{{ old('descr', $subcategory->descr) }}</textarea>
                    @error('descr')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="idCategoria" class="block text-gray-700 font-medium mb-2">Categoría</label>
                    <select id="idCategoria" name="idCategoria" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('idCategoria') border-red-500 @enderror" required>
                        <option value="">Seleccionar categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('idCategoria', $subcategory->idCategoria) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('idCategoria')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end">
                    <a href="{{ route('admin.subcategories.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition duration-200 mr-2">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition duration-200">
                        Actualizar Subcategoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection