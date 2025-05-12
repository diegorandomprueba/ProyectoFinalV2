/**
 * Funcionalidad específica para la página de tienda
 * Este archivo maneja la funcionalidad de añadir productos al carrito desde la vista de tienda
 */
document.addEventListener('DOMContentLoaded', function() {
    // Agregar evento a todos los botones "Añadir al carrito" de la tienda
    const addToCartButtons = document.querySelectorAll('.shop-add-to-cart');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Obtener datos del producto
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            const productPrice = parseFloat(this.getAttribute('data-product-price'));
            const productImage = this.getAttribute('data-product-image');
            
            // Verificar si la función global addToCart está disponible (desde cart.js)
            if (typeof window.addToCart === 'function') {
                // Usar la función global addToCart
                window.addToCart(productId, productName, productPrice, productImage, 1);
            } else {
                // Fallback para el caso de que cart.js no esté cargado correctamente
                console.error('La función addToCart no está disponible. Verifica que cart.js esté cargado correctamente.');
                
                // Mostrar notificación de error
                const notification = document.createElement('div');
                notification.className = 'fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg z-50';
                notification.textContent = 'Error al añadir al carrito. Por favor, intenta de nuevo.';
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transition = 'opacity 0.3s';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }
        });
    });
});