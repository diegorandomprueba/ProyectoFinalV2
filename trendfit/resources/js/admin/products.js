document.addEventListener('DOMContentLoaded', function() {
    // Manejo de categoría y subcategoría
    const categoriaSelect = document.getElementById('categoria');
    const subcategoriaSelect = document.getElementById('idCategoria');
    
    if (categoriaSelect && subcategoriaSelect) {
        const subcategoriaOptions = Array.from(subcategoriaSelect.options);
        const currentSubcategoria = subcategoriaSelect.getAttribute('data-current');
        
        // Función para filtrar subcategorías
        function filterSubcategories() {
            const selectedCategory = categoriaSelect.value;
            
            // Mostrar solo las subcategorías de la categoría seleccionada
            subcategoriaSelect.innerHTML = '<option value="">Seleccionar subcategoría</option>';
            
            subcategoriaOptions.forEach(option => {
                if (option.dataset.category === selectedCategory || option.value === '') {
                    subcategoriaSelect.appendChild(option.cloneNode(true));
                }
            });
            
            // Seleccionar la subcategoría actual si pertenece a la categoría seleccionada
            if (currentSubcategoria) {
                Array.from(subcategoriaSelect.options).forEach(option => {
                    if (option.value === currentSubcategoria) {
                        option.selected = true;
                    }
                });
            }
        }
        
        // Evento para filtrar subcategorías cuando cambia la categoría
        categoriaSelect.addEventListener('change', filterSubcategories);
        
        // Filtrar subcategorías al cargar la página
        filterSubcategories();
    }
    
    // Manejo de actualización de precio
    const updatePriceButtons = document.querySelectorAll('.edit-price-btn');
    updatePriceButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            editPrice(productId);
        });
    });
    
    // Manejo de actualización de stock
    const updateStockButtons = document.querySelectorAll('.edit-stock-btn');
    updateStockButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            editStock(productId);
        });
    });
    
    // Manejo de eliminación de producto
    const deleteButtons = document.querySelectorAll('.delete-product-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            deleteProduct(productId);
        });
    });
});

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