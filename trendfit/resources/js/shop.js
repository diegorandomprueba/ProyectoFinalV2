/**
 * Funcionalidad específica para la página de tienda
 * Este archivo maneja la funcionalidad de añadir productos al carrito desde la vista de tienda
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Shop.js loaded - Initializing shop add to cart buttons');
    
    // Agregar evento a todos los botones "Añadir al carrito" de la tienda
    const addToCartButtons = document.querySelectorAll('.shop-add-to-cart');
    
    console.log(`Found ${addToCartButtons.length} shop-add-to-cart buttons`);
    
    addToCartButtons.forEach(button => {
        // Inspeccionar los atributos de datos para depuración
        console.log('Button data attributes:', {
            id: button.getAttribute('data-id') || button.getAttribute('data-product-id'),
            name: button.getAttribute('data-name') || button.getAttribute('data-product-name'),
            price: button.getAttribute('data-price') || button.getAttribute('data-product-price'),
            image: button.getAttribute('data-image') || button.getAttribute('data-product-image')
        });
        
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Intentar obtener los datos con diferentes nombres de atributos
            const productId = this.getAttribute('data-id') || this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-name') || this.getAttribute('data-product-name');
            const productPrice = parseFloat(this.getAttribute('data-price') || this.getAttribute('data-product-price') || 0);
            const productImage = this.getAttribute('data-image') || this.getAttribute('data-product-image');
            
            console.log(`Shop button clicked for: ${productName} (${productId})`);
            
            // Comprobar si tenemos todos los datos necesarios
            if (!productId || !productName || isNaN(productPrice) || !productImage) {
                console.error('Missing required product data:', { productId, productName, productPrice, productImage });
                showNotification('Error: Datos del producto incompletos', 'error');
                return;
            }
            
            // Verificar si la función global addToCart está disponible
            if (typeof window.addToCart === 'function') {
                // Usar la función global addToCart con cantidad fija = 1
                window.addToCart(productId, productName, productPrice, productImage, 1);
            } else {
                console.error('La función addToCart no está disponible. Verifica que cart.js esté cargado correctamente.');
                
                // Mostrar notificación de error
                showNotification('Error al añadir al carrito. Por favor, intenta de nuevo.', 'error');
            }
        });
    });
});

// Función de notificación local si la global no está disponible
function showNotification(message, type = 'success') {
    if (typeof window.showNotification === 'function') {
        window.showNotification(message, type);
    } else {
        const notification = document.createElement('div');
        notification.className = `fixed bottom-4 right-4 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white px-4 py-2 rounded shadow-lg z-50`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.3s';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
}