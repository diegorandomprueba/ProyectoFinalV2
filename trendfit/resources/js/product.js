document.addEventListener('DOMContentLoaded', function() {
    // Manejo del contador de cantidad
    const quantityInput = document.getElementById('cantidad');
    if (quantityInput) {
        const decrementBtn = document.querySelector('.decrement-btn');
        const incrementBtn = document.querySelector('.increment-btn');
        
        decrementBtn?.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            if (value > 1) {
                quantityInput.value = value - 1;
            }
        });
        
        incrementBtn?.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            let max = parseInt(quantityInput.getAttribute('max') || 999);
            if (value < max) {
                quantityInput.value = value + 1;
            }
        });
        
        quantityInput.addEventListener('change', function() {
            let value = parseInt(this.value);
            let min = parseInt(this.getAttribute('min') || 1);
            let max = parseInt(this.getAttribute('max') || 999);
            
            if (value < min) {
                this.value = min;
            } else if (value > max) {
                this.value = max;
            }
        });
    }
    
    // Botón de añadir al carrito SOLO para la página de detalles del producto
    const addToCartBtn = document.querySelector('.product-detail-add-to-cart');
    if (addToCartBtn) {
        console.log('Found add-to-cart button in product details page');
        
        addToCartBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Product detail add button clicked');
            
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');
            const productPrice = parseFloat(this.getAttribute('data-price'));
            const productImage = this.getAttribute('data-image');
            
            // Obtener la cantidad y talla seleccionadas
            const quantity = document.getElementById('cantidad') ? parseInt(document.getElementById('cantidad').value) : 1;
            const size = document.getElementById('selected-size') ? document.getElementById('selected-size').value : null;
            
            if (size === "") {
                alert('Por favor, selecciona una talla');
                return;
            }
            
            // Usar la función global addToCart
            window.addToCart(productId, productName, productPrice, productImage, quantity, size);
        });
    }
    
    // Botón de comprar ahora
    const buyNowBtn = document.querySelector('.buy-now-btn');
    if (buyNowBtn) {
        buyNowBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');
            const productPrice = parseFloat(this.getAttribute('data-price'));
            const productImage = this.getAttribute('data-image');
            
            // Obtener la cantidad y talla seleccionadas
            const quantity = document.getElementById('cantidad') ? parseInt(document.getElementById('cantidad').value) : 1;
            const size = document.getElementById('selected-size') ? document.getElementById('selected-size').value : null;
            
            if (size === "") {
                alert('Por favor, selecciona una talla');
                return;
            }
            
            // Añadir al carrito y redirigir al checkout
            window.addToCart(productId, productName, productPrice, productImage, quantity, size);
            window.location.href = '/cart';
        });
    }
});