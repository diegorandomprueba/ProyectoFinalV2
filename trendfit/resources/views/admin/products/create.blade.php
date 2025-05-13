@extends('layouts.admin')

@section('title', 'Crear Producto - Trendfit Admin')

@section('header', 'Crear Producto')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-orange-500 hover:text-orange-700">
            <i class="fas fa-arrow-left mr-2"></i> Volver al listado
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-semibold mb-6">Crear Nuevo Producto</h2>
            
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-gray-700 font-medium mb-2">Nombre</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('name') border-red-500 @enderror" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="price" class="block text-gray-700 font-medium mb-2">Precio (€)</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" value="{{ old('price') }}" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('price') border-red-500 @enderror" required>
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="descr" class="block text-gray-700 font-medium mb-2">Descripción</label>
                    <textarea id="descr" name="descr" rows="4" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('descr') border-red-500 @enderror" required>{{ old('descr') }}</textarea>
                    @error('descr')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="stock" class="block text-gray-700 font-medium mb-2">Stock</label>
                        <input type="number" id="stock" name="stock" min="0" value="{{ old('stock', 0) }}" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('stock') border-red-500 @enderror" required>
                        @error('stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="image" class="block text-gray-700 font-medium mb-2">Imagen</label>
                        <input type="file" id="image" name="image" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('image') border-red-500 @enderror" accept="image/*">
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="categoria" class="block text-gray-700 font-medium mb-2">Categoría</label>
                        <select id="categoria" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                            <option value="">Seleccionar categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="idCategoria" class="block text-gray-700 font-medium mb-2">Subcategoría</label>
                        <select id="idCategoria" name="idCategoria" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('idCategoria') border-red-500 @enderror" required>
                            <option value="">Seleccionar subcategoría</option>
                            @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" data-category="{{ $subcategory->idCategoria }}">{{ $subcategory->name }}</option>
                            @endforeach
                        </select>
                        @error('idCategoria')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <a href="{{ route('admin.products.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition duration-200 mr-2">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition duration-200">
                        Crear Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/products.js') }}"></script>
@endpush