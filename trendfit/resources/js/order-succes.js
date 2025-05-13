document.addEventListener('DOMContentLoaded', function() {
    // Limpiar el carrito después de completar el pedido
    clearCart();
    
    // Inicializar valoración de productos
    initProductRatings();
});

/**
 * Limpia el carrito local y actualiza el contador
 */
function clearCart() {
    localStorage.removeItem('cart');
    
    // Actualizar contador del carrito
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = '0';
        cartCountElement.classList.add('hidden');
    }
}

/**
 * Inicializa la funcionalidad de valoración de productos
 */
function initProductRatings() {
    const productStars = document.querySelectorAll('.product-stars');
    
    productStars.forEach(starsContainer => {
        const productId = starsContainer.dataset.product;
        const ratingButtons = starsContainer.querySelectorAll('.rate-product');
        
        // Evento al pasar el mouse sobre las estrellas
        ratingButtons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                hoverRatingStars(starsContainer, parseInt(this.dataset.rating));
            });
            
            button.addEventListener('mouseleave', function() {
                resetRatingStars(starsContainer);
            });
            
            button.addEventListener('click', function() {
                selectRatingStars(starsContainer, parseInt(this.dataset.rating));
                sendRating(productId, parseInt(this.dataset.rating));
            });
        });
    });
}

/**
 * Cambia el aspecto de las estrellas al pasar el mouse
 */
function hoverRatingStars(container, rating) {
    const stars = container.querySelectorAll('.rate-product i');
    
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('far', 'text-gray-400');
            star.classList.add('fas', 'text-yellow-500');
        } else {
            star.classList.remove('fas', 'text-yellow-500');
            star.classList.add('far', 'text-gray-400');
        }
    });
}

/**
 * Restaura el aspecto de las estrellas al quitar el mouse
 */
function resetRatingStars(container) {
    const selectedRating = container.dataset.selectedRating || 0;
    const stars = container.querySelectorAll('.rate-product i');
    
    stars.forEach((star, index) => {
        if (index < selectedRating) {
            star.classList.remove('far', 'text-gray-400');
            star.classList.add('fas', 'text-yellow-500');
        } else {
            star.classList.remove('fas', 'text-yellow-500');
            star.classList.add('far', 'text-gray-400');
        }
    });
}

/**
 * Establece la valoración seleccionada
 */
function selectRatingStars(container, rating) {
    container.dataset.selectedRating = rating;
    hoverRatingStars(container, rating);
}

/**
 * Envía la valoración al servidor
 */
function sendRating(productId, rating) {
    fetch('/products/' + productId + '/rate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ rating: rating })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('¡Gracias por tu valoración!', 'success');
        } else {
            showNotification(data.message || 'Error al enviar la valoración', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al enviar la valoración', 'error');
    });
}

/**
 * Muestra una notificación
 */
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