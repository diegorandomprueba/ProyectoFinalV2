@props(['product'])

<div class="bg-white p-4 shadow-md rounded-lg flex flex-col items-center text-center">
    <a href="{{ route('product.show', $product->id) }}">
        <div class="w-full h-48 flex items-center justify-center overflow-hidden bg-gray-100">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-full w-auto">
        </div>
        <h3 class="text-xl font-semibold mt-2">{{ $product->name }}</h3>
        <p class="text-gray-600 font-bold">
            Precio: {{ number_format($product->price, 2) }}€
            <span class="text-gray-500 text-sm">(IVA incluido)</span>
        </p>
    </a>
    <div class="mt-4">
        <button 
            class="add-to-cart-btn bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition duration-200"
            data-id="{{ $product->id }}"
            data-name="{{ $product->name }}"
            data-price="{{ $product->price }}"
            data-image="{{ asset('storage/' . $product->image) }}"
        >
            Añadir al Carrito
        </button>
    </div>
</div>