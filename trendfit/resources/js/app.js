// Import Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Import our custom scripts

import './cart.js';
import './validation.js';
import './product.js';
import './profile.js';
import './register.js';
import './shop.js';
import './video.js';
import './admin/dashboard-charts.js';

// Global utility functions
window.showNotification = function(message, type = 'success') {
    const notification = document.createElement('div');
    notification.classList.add('notification', `notification-${type}`);
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
};

// Check if any elements need to be updated when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add floating cart if in mobile view and not on cart or checkout pages
    if (window.innerWidth < 768 && 
        !window.location.pathname.includes('/cart') && 
        !window.location.pathname.includes('/checkout')) {
        
        const floatingCart = document.createElement('div');
        floatingCart.classList.add('floating-cart');
        floatingCart.innerHTML = `
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-counter" style="display: none;">0</span>
        `;
        
        floatingCart.addEventListener('click', function() {
            window.location.href = '/cart';
        });
        
        document.body.appendChild(floatingCart);
        
        // Update cart counter if necessary
        fetch('/cart/count')
            .then(response => response.json())
            .then(data => {
                if (data.count > 0) {
                    const cartCounter = document.querySelector('.cart-counter');
                    cartCounter.textContent = data.count;
                    cartCounter.style.display = 'flex';
                }
            });
    }
    
    // Show a reminder to review products if applicable
    const reviewReminder = document.getElementById('review-reminder');
    if (reviewReminder) {
        setTimeout(() => {
            reviewReminder.classList.remove('hidden');
        }, 1000);
    }
    
    // Initialize sliders and carousels using Swiper if present
    if (typeof Swiper !== 'undefined' && document.querySelector('.swiper')) {
        new Swiper('.swiper', {
            slidesPerView: 'auto',
            spaceBetween: 20,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    }
});