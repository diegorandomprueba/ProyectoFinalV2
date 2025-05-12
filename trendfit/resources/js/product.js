document.addEventListener('DOMContentLoaded', function() {
    // Manejo del contador de cantidad
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        const decrementBtn = document.querySelector('.decrement-btn');
        const incrementBtn = document.querySelector('.increment-btn');
        
        decrementBtn.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            if (value > 1) {
                quantityInput.value = value - 1;
            }
        });
        
        incrementBtn.addEventListener('click', function() {
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
    // Esta clase debe ser única para la página de detalles del producto
    const addToCartBtn = document.querySelector('.product-detail-add-to-cart');
    if (addToCartBtn) {
        console.log('Found add-to-cart button in product details page');
        
        // Marcar el botón para evitar duplicidad (por si acaso)
        addToCartBtn.setAttribute('data-event-bound', 'true');
        
        addToCartBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Product detail add button clicked');
            
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');
            const productPrice = parseFloat(this.getAttribute('data-price'));
            const productImage = this.getAttribute('data-image');
            const quantity = document.getElementById('quantity') ? parseInt(document.getElementById('quantity').value) : 1;
            const size = document.getElementById('size') ? document.getElementById('size').value : null;
            
            // Asegúrate de que quantity sea 1 si no está definido o es NaN
            const safeQuantity = isNaN(quantity) ? 1 : quantity;

            // Usar la función global addToCart
            window.addToCart(productId, productName, productPrice, productImage, safeQuantity, size);
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
            const quantity = document.getElementById('quantity') ? parseInt(document.getElementById('quantity').value) : 1;
            const size = document.getElementById('size') ? document.getElementById('size').value : null;
            
            // Asegúrate de que quantity sea 1 si no está definido o es NaN
            const safeQuantity = isNaN(quantity) ? 1 : quantity;
            
            // Añadir al carrito y redirigir al checkout
            window.addToCart(productId, productName, productPrice, productImage, safeQuantity, size);
            window.location.href = '/cart';
        });
    }
});