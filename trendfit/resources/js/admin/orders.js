document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el editor de pedidos
    initOrderEditor();
});

function initOrderEditor() {
    // Contador para nuevos productos
    let productCounter = 1;
    
    // Elementos DOM
    const addProductBtn = document.getElementById('add-product-btn');
    const productsContainer = document.getElementById('products-container');
    
    // Añadir producto nuevo
    if (addProductBtn) {
        addProductBtn.addEventListener('click', function() {
            addNewProductRow();
        });
    }
    
    // Configurar eventos para eliminar productos iniciales
    setupInitialRemoveButtons();
    
    // Configurar eventos de cambio de cantidad en productos existentes
    setupQuantityChangeEvents();
    
    /**
     * Añade una nueva fila de producto al contenedor
     */
    function addNewProductRow() {
        if (!productsContainer) return;
        
        const newRow = document.createElement('div');
        newRow.className = 'flex items-center product-row mt-2';
        
        // Obtenemos la lista de opciones del primer select de productos
        const firstSelect = document.querySelector('.product-select');
        const optionsHtml = firstSelect ? firstSelect.innerHTML : '';
        
        newRow.innerHTML = `
            <div class="flex-1">
                <select name="new_products[${productCounter}][id]" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 product-select">
                    ${optionsHtml}
                </select>
            </div>
            <div class="ml-2">
                <input type="number" name="new_products[${productCounter}][cant]" placeholder="Cant." min="1" value="1" class="w-16 border rounded px-2 py-2 product-quantity">
            </div>
            <div class="ml-2">
                <button type="button" class="bg-red-500 text-white p-2 rounded hover:bg-red-600 remove-product">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        productsContainer.appendChild(newRow);
        
        // Incrementar contador
        productCounter++;
        
        // Añadir evento para eliminar
        const removeButton = newRow.querySelector('.remove-product');
        if (removeButton) {
            removeButton.addEventListener('click', function() {
                removeProductRow(this);
            });
        }
        
        // Añadir evento para actualizar precio cuando cambia la cantidad
        const quantityInput = newRow.querySelector('.product-quantity');
        if (quantityInput) {
            quantityInput.addEventListener('change', function() {
                updateRowTotal(this);
            });
        }
        
        // Añadir evento para cuando cambia el producto seleccionado
        const productSelect = newRow.querySelector('.product-select');
        if (productSelect) {
            productSelect.addEventListener('change', function() {
                updateRowTotal(quantityInput);
            });
        }
    }
    
    /**
     * Elimina una fila de producto
     * @param {HTMLElement} button El botón de eliminar que fue presionado
     */
    function removeProductRow(button) {
        const row = button.closest('.product-row');
        if (row && row.parentNode) {
            row.parentNode.removeChild(row);
            updateOrderTotal();
        }
    }
    
    /**
     * Configura los botones iniciales de eliminar producto
     */
    function setupInitialRemoveButtons() {
        const removeButtons = document.querySelectorAll('.remove-product');
        if (removeButtons.length > 0) {
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    removeProductRow(this);
                });
            });
        }
    }
    
    /**
     * Configura los eventos de cambio de cantidad para actualizar los totales
     */
    function setupQuantityChangeEvents() {
        // Para productos existentes
        const existingQuantityInputs = document.querySelectorAll('input[name^="products["][name$="][cant]"]');
        if (existingQuantityInputs.length > 0) {
            existingQuantityInputs.forEach(input => {
                input.addEventListener('change', function() {
                    updateExistingProductTotal(this);
                    updateOrderTotal();
                });
            });
        }
        
        // Para nuevos productos
        const newQuantityInputs = document.querySelectorAll('input[name^="new_products["][name$="][cant]"]');
        if (newQuantityInputs.length > 0) {
            newQuantityInputs.forEach(input => {
                input.addEventListener('change', function() {
                    updateRowTotal(this);
                    updateOrderTotal();
                });
            });
        }
    }
    
    /**
     * Actualiza el total de un producto existente
     * @param {HTMLElement} input Input de cantidad
     */
    function updateExistingProductTotal(input) {
        const row = input.closest('tr');
        if (!row) return;
        
        const priceCell = row.querySelector('td:nth-child(2)');
        const totalCell = row.querySelector('td:nth-child(4)');
        
        if (priceCell && totalCell) {
            const quantity = parseInt(input.value) || 0;
            // Extraer el precio (quitando el símbolo € y convirtiendo las comas)
            const priceText = priceCell.textContent.trim();
            const price = parseFloat(priceText.replace('€', '').replace(',', '.')) || 0;
            
            // Calcular y actualizar el total
            const total = (quantity * price).toFixed(2);
            totalCell.textContent = `${total.replace('.', ',')}€`;
        }
    }
    
    /**
     * Actualiza el total de la fila de un nuevo producto
     * @param {HTMLElement} input Input de cantidad o select de producto
     */
    function updateRowTotal(input) {
        const row = input.closest('.product-row');
        if (!row) return;
        
        const productSelect = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.product-quantity');
        
        if (productSelect && quantityInput) {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                const quantity = parseInt(quantityInput.value) || 0;
                
                // Si hay un elemento para mostrar el total, actualizarlo
                const totalElement = row.querySelector('.product-row-total');
                if (totalElement) {
                    totalElement.textContent = `${(price * quantity).toFixed(2)}€`;
                } else {
                    // Si no existe, crear uno nuevo
                    const totalDiv = document.createElement('div');
                    totalDiv.className = 'ml-4 product-row-total font-medium';
                    totalDiv.textContent = `${(price * quantity).toFixed(2)}€`;
                    row.appendChild(totalDiv);
                }
            }
        }
    }
    
    /**
     * Actualiza el total general del pedido
     */
    function updateOrderTotal() {
        const orderTotalElement = document.getElementById('order-total');
        if (!orderTotalElement) return;
        
        let total = 0;
        
        // Sumar totales de productos existentes
        const existingTotalCells = document.querySelectorAll('table tbody td:nth-child(4)');
        existingTotalCells.forEach(cell => {
            const price = parseFloat(cell.textContent.replace('€', '').replace(',', '.')) || 0;
            total += price;
        });
        
        // Sumar totales de nuevos productos
        const newTotals = document.querySelectorAll('.product-row-total');
        newTotals.forEach(elem => {
            const price = parseFloat(elem.textContent.replace('€', '').replace(',', '.')) || 0;
            total += price;
        });
        
        // Aplicar IVA
        const iva = total * 0.21;
        const shipping = 4.99;
        const finalTotal = total + iva + shipping;
        
        // Actualizar los elementos del DOM
        document.getElementById('subtotal-amount').textContent = `${total.toFixed(2)}€`;
        document.getElementById('tax-amount').textContent = `${iva.toFixed(2)}€`;
        document.getElementById('total-amount').textContent = `${finalTotal.toFixed(2)}€`;
    }
}