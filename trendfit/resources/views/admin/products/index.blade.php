@extends('layouts.admin')

@section('title', 'Gestión de Productos - Trendfit Admin')

@section('header', 'Gestión de Productos')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-semibold">Listado de Productos</h2>
        <p class="text-gray-600">Administra los productos de tu tienda</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition duration-200">
        <i class="fas fa-plus mr-2"></i> Nuevo Producto
    </a>
</div>

<!-- Filtros y búsqueda -->
<div class="bg-white p-4 rounded-lg shadow-md mb-6">
    <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Nombre o descripción..." class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
            <select id="category" name="category" class="w-full border rounded px-3 py-2">
                <option value="">Todas las categorías</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
            <select id="stock" name="stock" class="w-full border rounded px-3 py-2">
                <option value="">Todos</option>
                <option value="in_stock" {{ request('stock') == 'in_stock' ? 'selected' : '' }}>En stock</option>
                <option value="out_of_stock" {{ request('stock') == 'out_of_stock' ? 'selected' : '' }}>Agotados</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700 transition duration-200">
                <i class="fas fa-search mr-2"></i> Filtrar
            </button>
            <a href="{{ route('admin.products.index') }}" class="ml-2 text-gray-600 px-4 py-2 rounded hover:bg-gray-100 transition duration-200">
                Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Tabla de productos -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Imagen
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nombre
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Categoría
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Precio
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Stock
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($products as $product)
                    <tr id="product-row-{{ $product->id }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $product->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $product->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $product->subcategoria->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="price-display">{{ number_format($product->price, 2) }}€</span>
                            <div class="price-edit hidden">
                                <div class="flex items-center">
                                    <input type="number" step="0.01" min="0" value="{{ $product->price }}" class="w-20 border rounded px-2 py-1 product-price-input">
                                    <button onclick="updatePrice({{ $product->id }})" class="ml-1 text-green-500 hover:text-green-700">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button onclick="cancelEditPrice({{ $product->id }})" class="ml-1 text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="stock-display {{ $product->stock <= 0 ? 'text-red-500' : '' }}">
                                {{ $product->stock }}
                            </span>
                            <div class="stock-edit hidden">
                                <div class="flex items-center">
                                    <input type="number" min="0" value="{{ $product->stock }}" class="w-16 border rounded px-2 py-1 product-stock-input">
                                    <button onclick="updateStock({{ $product->id }})" class="ml-1 text-green-500 hover:text-green-700">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button onclick="cancelEditStock({{ $product->id }})" class="ml-1 text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex space-x-2">
                                <button onclick="editPrice({{ $product->id }})" class="text-blue-500 hover:text-blue-700" title="Editar precio">
                                    <i class="fas fa-dollar-sign"></i>
                                </button>
                                <button onclick="editStock({{ $product->id }})" class="text-green-500 hover:text-green-700" title="Editar stock">
                                    <i class="fas fa-cubes"></i>
                                </button>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="text-indigo-500 hover:text-indigo-700" title="Editar producto">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteProduct({{ $product->id }})" class="text-red-500 hover:text-red-700" title="Eliminar producto">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    <div class="px-6 py-4 border-t">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Editar precio
    function editPrice(productId) {
        const row = document.getElementById(`product-row-${productId}`);
        row.querySelector('.price-display').classList.add('hidden');
        row.querySelector('.price-edit').classList.remove('hidden');
    }
    
    function cancelEditPrice(productId) {
        const row = document.getElementById(`product-row-${productId}`);
        row.querySelector('.price-display').classList.remove('hidden');
        row.querySelector('.price-edit').classList.add('hidden');
    }
    
    function updatePrice(productId) {
        const row = document.getElementById(`product-row-${productId}`);
        const newPrice = row.querySelector('.product-price-input').value;
        
        fetch(`/admin/products/${productId}/update-price`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                price: newPrice
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                row.querySelector('.price-display').textContent = `${parseFloat(newPrice).toFixed(2)}€`;
                row.querySelector('.price-display').classList.remove('hidden');
                row.querySelector('.price-edit').classList.add('hidden');
                
                showNotification('Precio actualizado correctamente', 'success');
            } else {
                showNotification(data.message || 'Error al actualizar el precio', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al actualizar el precio', 'error');
        });
    }
    
    // Editar stock
    function editStock(productId) {
        const row = document.getElementById(`product-row-${productId}`);
        row.querySelector('.stock-display').classList.add('hidden');
        row.querySelector('.stock-edit').classList.remove('hidden');
    }
    
    function cancelEditStock(productId) {
        const row = document.getElementById(`product-row-${productId}`);
        row.querySelector('.stock-display').classList.remove('hidden');
        row.querySelector('.stock-edit').classList.add('hidden');
    }
    
    function updateStock(productId) {
        const row = document.getElementById(`product-row-${productId}`);
        const newStock = row.querySelector('.product-stock-input').value;
        
        fetch(`/admin/products/${productId}/update-stock`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                stock: newStock
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                row.querySelector('.stock-display').textContent = newStock;
                row.querySelector('.stock-display').classList.remove('hidden');
                row.querySelector('.stock-edit').classList.add('hidden');
                
                // Actualizar color del stock si es 0
                if (parseInt(newStock) <= 0) {
                    row.querySelector('.stock-display').classList.add('text-red-500');
                } else {
                    row.querySelector('.stock-display').classList.remove('text-red-500');
                }
                
                showNotification('Stock actualizado correctamente', 'success');
            } else {
                showNotification(data.message || 'Error al actualizar el stock', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al actualizar el stock', 'error');
        });
    }
    
    // Eliminar producto
    function deleteProduct(productId) {
        if (confirm('¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.')) {
            fetch(`/admin/products/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`product-row-${productId}`).remove();
                    showNotification('Producto eliminado correctamente', 'success');
                } else {
                    showNotification(data.message || 'Error al eliminar el producto', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al eliminar el producto', 'error');
            });
        }
    }
    
    // Mostrar notificación
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.classList.add('fixed', 'bottom-4', 'right-4', 'px-4', 'py-2', 'rounded', 'text-white', 'z-50');
        
        if (type === 'success') {
            notification.classList.add('bg-green-500');
        } else if (type === 'error') {
            notification.classList.add('bg-red-500');
        }
        
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>
@endpush