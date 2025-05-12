/**
 * Implementación del carrito de compras usando localStorage
 * Este archivo contiene todas las funciones necesarias para gestionar el carrito
 */
const cart = {
    items: [],
    
    // Variable para almacenar el último ID de producto añadido y su timestamp
    lastAddedProduct: {
        id: null,
        timestamp: 0,
        cartItemId: null
    },
    
    init() {
        console.log('Cart initialized'); // Para depurar
        
        // Cargar carrito desde localStorage
        const storedCart = localStorage.getItem('trendfit_cart');
        if (storedCart) {
            try {
                this.items = JSON.parse(storedCart);
                
                // Verificar que cada item tiene la estructura correcta
                this.items = this.items.filter(item => {
                    // Verificar que los campos requeridos existen
                    return item && item.id && item.name && 
                           typeof item.price === 'number' && 
                           item.image && 
                           typeof item.quantity === 'number';
                });
                
                this.updateCartCount();
                console.log('Cart loaded from localStorage:', this.items); // Para depurar
            } catch (e) {
                console.error('Error parsing cart from localStorage:', e);
                localStorage.removeItem('trendfit_cart');
                this.items = [];
            }
        }
        
        // Inicializar eventos si estamos en la página del carrito
        if (window.location.pathname === '/cart') {
            console.log('Rendering cart page'); // Para depurar
            this.renderCartPage();
        }
        
        // Agregar manejador de eventos para los botones de la página del carrito
        this.attachCartEvents();
    },
    
    // Nuevo método para adjuntar eventos relacionados con el carrito
    attachCartEvents() {
        const self = this;  // Almacenar referencia a 'this' para usar en manejadores de eventos
        
        // Usar delegación de eventos para capturar clics en cualquier parte del documento
        document.addEventListener('click', function(event) {
            // Si estamos en la página del carrito y se hace clic en un botón de cantidad
            if (window.location.pathname === '/cart' && event.target.classList.contains('quantity-btn')) {
                const button = event.target;
                const itemId = button.getAttribute('data-item-id');
                const action = button.getAttribute('data-action');
                const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
                
                if (!input) return;
                
                let quantity = parseInt(input.value) || 1;
                
                if (action === 'increase') {
                    quantity++;
                } else if (action === 'decrease' && quantity > 1) {
                    quantity--;
                }
                
                input.value = quantity;
                self.updateQuantity(itemId, quantity);
                self.renderCartPage();
            }
            
            // Si estamos en la página del carrito y se hace clic en el botón de eliminar
            if (window.location.pathname === '/cart' && 
                (event.target.classList.contains('remove-btn') || event.target.closest('.remove-btn'))) {
                const button = event.target.classList.contains('remove-btn') ? 
                              event.target : event.target.closest('.remove-btn');
                const itemId = button.getAttribute('data-item-id');
                
                if (itemId) {
                    self.removeItem(itemId);
                    self.renderCartPage();
                }
            }
        });
        
        // Evento para cambios en los inputs de cantidad
        document.addEventListener('change', function(event) {
            if (window.location.pathname === '/cart' && event.target.classList.contains('quantity-input')) {
                const input = event.target;
                const itemId = input.getAttribute('data-item-id');
                let quantity = parseInt(input.value);
                
                if (isNaN(quantity) || quantity < 1) {
                    quantity = 1;
                    input.value = 1;
                }
                
                self.updateQuantity(itemId, quantity);
                self.renderCartPage();
            }
        });
    },
    
    addItem(id, name, price, image, quantity = 1, size = null) {
        // Implementación del mecanismo de debounce
        const now = Date.now();
        const cartItemId = size ? `${id}-${size}` : `${id}`;
        
        // Verificar si es el mismo item añadido en menos de 500ms (evita la doble adición)
        if (cartItemId === this.lastAddedProduct.cartItemId && (now - this.lastAddedProduct.timestamp) < 500) {
            console.log('Preventing duplicate add to cart within 500ms for:', name);
            return false;
        }
        
        // Actualizar el último producto añadido
        this.lastAddedProduct.id = id;
        this.lastAddedProduct.cartItemId = cartItemId;
        this.lastAddedProduct.timestamp = now;
        
        // Validar datos
        if (!id || !name || typeof price !== 'number' || !image || isNaN(price)) {
            console.error('Invalid item data:', { id, name, price, image, quantity });
            return false;
        }
        
        // Crear un ID único para productos con tallas
        const itemId = size ? `${id}-${size}` : `${id}`;
        
        // Verificar si el producto ya está en el carrito
        const existingItemIndex = this.items.findIndex(item => 
            item.id === id && (size === null || item.size === size)
        );
        
        if (existingItemIndex !== -1) {
            // Actualizar cantidad si ya existe
            this.items[existingItemIndex].quantity += quantity;
            console.log('Updated quantity for existing item', this.items[existingItemIndex]); // Para depurar
        } else {
            // Añadir nuevo producto
            this.items.push({
                id,
                itemId,
                name,
                price,
                image,
                quantity,
                size
            });
            console.log('Added new item to cart', this.items[this.items.length - 1]); // Para depurar
        }
        
        this.saveCart();
        this.updateCartCount();
        
        return true;
    },
    
    updateQuantity(itemId, quantity) {
        const index = this.items.findIndex(item => item.itemId === itemId);
        
        if (index !== -1) {
            this.items[index].quantity = Math.max(1, quantity);
            this.saveCart();
            console.log('Updated quantity:', itemId, quantity); // Para depurar
            return true;
        }
        
        return false;
    },
    
    removeItem(itemId) {
        const index = this.items.findIndex(item => item.itemId === itemId);
        
        if (index !== -1) {
            const removed = this.items.splice(index, 1);
            this.saveCart();
            console.log('Removed item:', removed[0]); // Para depurar
            return true;
        }
        
        return false;
    },
    
    clearCart() {
        this.items = [];
        this.saveCart();
        this.updateCartCount();
        console.log('Cart cleared'); // Para depurar
    },
    
    getTotal() {
        return this.items.reduce((total, item) => {
            // Asegurarse de que price y quantity son números
            const price = parseFloat(item.price) || 0;
            const quantity = parseInt(item.quantity) || 0;
            return total + (price * quantity);
        }, 0);
    },
    
    getTotalWithTax() {
        return this.getTotal() * 1.21; // IVA 21%
    },
    
    getTotalItems() {
        return this.items.reduce((total, item) => {
            const quantity = parseInt(item.quantity) || 0;
            return total + quantity;
        }, 0);
    },
    
    saveCart() {
        localStorage.setItem('trendfit_cart', JSON.stringify(this.items));
        console.log('Cart saved to localStorage:', this.items); // Para depurar
    },
    
    updateCartCount() {
        const cartCountElement = document.querySelector('.cart-count');
        if (cartCountElement) {
            const totalItems = this.getTotalItems();
            cartCountElement.textContent = totalItems;
            
            if (totalItems > 0) {
                cartCountElement.classList.remove('hidden');
            } else {
                cartCountElement.classList.add('hidden');
            }
            console.log('Cart count updated:', totalItems); // Para depurar
        }
    },
    
    renderCartPage() {
        console.log('Rendering cart page'); // Para depurar
        
        const cartContainer = document.getElementById('cart-items');
        const subtotalElement = document.getElementById('cart-subtotal');
        const taxElement = document.getElementById('cart-tax');
        const totalElement = document.getElementById('cart-total');
        const emptyCartMessage = document.getElementById('empty-cart-message');
        const cartSummary = document.getElementById('cart-summary');
        
        if (!cartContainer) {
            console.error('Cart container not found'); // Para depurar
            return;
        }
        
        if (this.items.length === 0) {
            cartContainer.innerHTML = '';
            if (emptyCartMessage) emptyCartMessage.classList.remove('hidden');
            if (cartSummary) cartSummary.classList.add('hidden');
            console.log('Cart is empty'); // Para depurar
            return;
        }
        
        if (emptyCartMessage) emptyCartMessage.classList.add('hidden');
        if (cartSummary) cartSummary.classList.remove('hidden');
        
        // Renderizar items
        cartContainer.innerHTML = '';
        console.log('Rendering cart items:', this.items); // Para depurar
        
        this.items.forEach(item => {
            // Verificar que el elemento tiene todos los campos necesarios
            if (!item.id || !item.name || typeof item.price !== 'number' || !item.image) {
                console.error('Invalid item in cart:', item);
                return;
            }
            
            const itemPrice = parseFloat(item.price);
            const itemQuantity = parseInt(item.quantity) || 1;
            
            const itemElement = document.createElement('div');
            itemElement.className = 'flex flex-col sm:flex-row items-center py-4 border-b';
            itemElement.innerHTML = `
                <div class="sm:w-24 h-24 flex-shrink-0 mb-4 sm:mb-0">
                    <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover rounded">
                </div>
                <div class="flex-1 sm:ml-4">
                    <h3 class="font-semibold">${item.name}</h3>
                    <p class="text-gray-600">${itemPrice.toFixed(2)}€</p>
                    ${item.size ? `<p class="text-gray-500 text-sm">Talla: ${item.size}</p>` : ''}
                </div>
                <div class="flex items-center mt-4 sm:mt-0">
                    <div class="flex items-center border rounded">
                        <button type="button" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 quantity-btn" data-action="decrease" data-item-id="${item.itemId}">-</button>
                        <input type="number" class="w-12 text-center border-x quantity-input" value="${itemQuantity}" min="1" data-item-id="${item.itemId}">
                        <button type="button" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 quantity-btn" data-action="increase" data-item-id="${item.itemId}">+</button>
                    </div>
                    <button type="button" class="ml-4 text-red-500 hover:text-red-700 remove-btn" data-item-id="${item.itemId}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="font-semibold text-right mt-4 sm:mt-0 sm:ml-4 sm:w-24">
                    ${(itemPrice * itemQuantity).toFixed(2)}€
                </div>
            `;
            
            cartContainer.appendChild(itemElement);
        });
        
        // Actualizar totales
        if (subtotalElement && taxElement && totalElement) {
            const subtotal = this.getTotal();
            const tax = subtotal * 0.21;
            const total = subtotal + tax;
            
            subtotalElement.textContent = subtotal.toFixed(2) + '€';
            taxElement.textContent = tax.toFixed(2) + '€';
            totalElement.textContent = total.toFixed(2) + '€';
            
            console.log('Totals updated:', { subtotal, tax, total }); // Para depurar
        }
    }
};

// Función para mostrar notificaciones
function showNotification(message, type = 'success') {
    console.log('Showing notification:', message, type); // Para depurar
    
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = 'fixed top-20 right-4 px-4 py-2 rounded shadow-lg z-50 transform transition-all duration-500 opacity-0 translate-x-10';
    
    if (type === 'success') {
        notification.classList.add('bg-green-500', 'text-white');
    } else {
        notification.classList.add('bg-red-500', 'text-white');
    }
    
    notification.textContent = message;
    document.body.appendChild(notification);
    
    // Mostrar notificación
    setTimeout(() => {
        notification.classList.remove('opacity-0', 'translate-x-10');
    }, 100);
    
    // Ocultar y eliminar notificación
    setTimeout(() => {
        notification.classList.add('opacity-0', 'translate-x-10');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 500);
    }, 3000);
}

// Función global para añadir al carrito (para usar desde cualquier página)
function addToCart(productId, productName, productPrice, productImage, quantity = 1, size = null) {
    console.log('Adding to cart:', { productId, productName, productPrice }); // Para depurar
    
    // Convertir precio a número para asegurar que sea el tipo correcto
    const price = parseFloat(productPrice);
    if (isNaN(price)) {
        console.error('Invalid price:', productPrice);
        return;
    }
    
    cart.addItem(productId, productName, price, productImage, quantity, size);
    showNotification('Producto añadido al carrito', 'success');
}

// Inicializar carrito cuando se carga la página
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded, initializing cart'); // Para depurar
    cart.init();
});

// Exportar el objeto cart para usar desde otros archivos
window.cart = cart;
window.addToCart = addToCart;
window.showNotification = showNotification;