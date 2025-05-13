document.addEventListener('DOMContentLoaded', function() {
    // Inicializar todos los manejadores y validaciones
    initSameAsShippingCheckbox();
    initPaymentMethodToggle();
    initFormValidation();
    initPhoneValidation();
    initPostalCodeValidation();
});

/**
 * Inicializa el checkbox para usar la misma dirección de envío y facturación
 */
function initSameAsShippingCheckbox() {
    const sameAsShippingCheckbox = document.getElementById('same_as_shipping');
    const billingInfoContainer = document.getElementById('billing-info');
    
    if (sameAsShippingCheckbox && billingInfoContainer) {
        sameAsShippingCheckbox.addEventListener('change', function() {
            if (this.checked) {
                billingInfoContainer.classList.add('hidden');
                
                // Desactivar los campos de facturación
                billingInfoContainer.querySelectorAll('input').forEach(input => {
                    input.required = false;
                });
            } else {
                billingInfoContainer.classList.remove('hidden');
                
                // Activar los campos de facturación
                billingInfoContainer.querySelectorAll('input').forEach(input => {
                    input.required = true;
                });
            }
        });
    }
}

/**
 * Inicializa el cambio entre métodos de pago
 */
function initPaymentMethodToggle() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const cardPaymentInfo = document.getElementById('card-payment-info');
    const paypalPaymentInfo = document.getElementById('paypal-payment-info');
    const transferPaymentInfo = document.getElementById('transfer-payment-info');
    
    if (paymentMethods.length && cardPaymentInfo && paypalPaymentInfo && transferPaymentInfo) {
        // Configuración inicial según el método seleccionado por defecto
        const initialMethod = document.querySelector('input[name="payment_method"]:checked');
        if (initialMethod) {
            // Ocultar todos los contenedores al inicio
            cardPaymentInfo.classList.add('hidden');
            paypalPaymentInfo.classList.add('hidden');
            transferPaymentInfo.classList.add('hidden');
            
            // Mostrar el contenedor correspondiente al método seleccionado inicialmente
            if (initialMethod.value === 'card') {
                cardPaymentInfo.classList.remove('hidden');
                // Activar los campos de tarjeta
                cardPaymentInfo.querySelectorAll('input').forEach(input => {
                    input.required = true;
                });
            } else if (initialMethod.value === 'paypal') {
                paypalPaymentInfo.classList.remove('hidden');
            } else if (initialMethod.value === 'transfer') {
                transferPaymentInfo.classList.remove('hidden');
            }
        }
        
        // Añadir listeners para cambios
        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                // Ocultar todos los contenedores
                cardPaymentInfo.classList.add('hidden');
                paypalPaymentInfo.classList.add('hidden');
                transferPaymentInfo.classList.add('hidden');
                
                // Desactivar todos los campos
                cardPaymentInfo.querySelectorAll('input').forEach(input => {
                    input.required = false;
                });
                
                // Mostrar el contenedor correspondiente
                if (this.value === 'card') {
                    cardPaymentInfo.classList.remove('hidden');
                    // Activar los campos de tarjeta
                    cardPaymentInfo.querySelectorAll('input').forEach(input => {
                        input.required = true;
                    });
                } else if (this.value === 'paypal') {
                    paypalPaymentInfo.classList.remove('hidden');
                } else if (this.value === 'transfer') {
                    transferPaymentInfo.classList.remove('hidden');
                }
            });
        });
    }
}

/**
 * Inicializa la validación del formulario
 */
function initFormValidation() {
    const checkoutForm = document.getElementById('checkout-form');
    
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            
            if (paymentMethod === 'card') {
                const cardNumber = document.getElementById('card_number').value;
                const cardName = document.getElementById('card_name').value;
                const cardExpiry = document.getElementById('card_expiry').value;
                const cardCvv = document.getElementById('card_cvv').value;
                
                const cardNumberRegex = /^\d{16}$/;
                const cardExpiryRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
                const cardCvvRegex = /^\d{3,4}$/;
                
                let valid = true;
                
                if (!cardNumberRegex.test(cardNumber.replace(/\s/g, ''))) {
                    showErrorMessage('Por favor, introduce un número de tarjeta válido (16 dígitos)');
                    valid = false;
                }
                
                if (cardName.trim() === '') {
                    showErrorMessage('Por favor, introduce el nombre que aparece en la tarjeta');
                    valid = false;
                }
                
                if (!cardExpiryRegex.test(cardExpiry)) {
                    showErrorMessage('Por favor, introduce una fecha de caducidad válida (MM/YY)');
                    valid = false;
                }
                
                if (!cardCvvRegex.test(cardCvv)) {
                    showErrorMessage('Por favor, introduce un CVV válido (3 o 4 dígitos)');
                    valid = false;
                }
                
                if (!valid) {
                    e.preventDefault();
                }
            }
            
            // Validación de teléfonos
            const phoneInputs = document.querySelectorAll('input[type="tel"]');
            phoneInputs.forEach(input => {
                if (input.value && !validatePhone(input.value)) {
                    showErrorMessage('Por favor, introduce un número de teléfono válido (solo números, máximo 10 dígitos)');
                    e.preventDefault();
                }
            });
            
            // Validación de códigos postales
            const postalCodeInputs = document.querySelectorAll('input[id$="code"]');
            postalCodeInputs.forEach(input => {
                if (input.value && !validatePostalCode(input.value)) {
                    showErrorMessage('El código postal debe tener 5 dígitos');
                    e.preventDefault();
                }
            });
        });
    }
}


/**
 * Inicializa la validación de teléfono
 */
function initPhoneValidation() {
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    
    phoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Eliminar cualquier carácter que no sea un número
            this.value = this.value.replace(/\D/g, '');
            
            // Limitar a 10 dígitos
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
            
            // Validar el formato
            if (this.value && !validatePhone(this.value)) {
                this.setCustomValidity('Por favor, introduce un número de teléfono válido (solo números, máximo 10 dígitos)');
            } else {
                this.setCustomValidity('');
            }
        });
    });
}

/**
 * Inicializa la validación de código postal
 */
function initPostalCodeValidation() {
    const postalCodeInputs = document.querySelectorAll('input[id$="code"]');
    
    postalCodeInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Eliminar cualquier carácter que no sea un número
            this.value = this.value.replace(/\D/g, '');
            
            // Limitar a 5 dígitos
            if (this.value.length > 5) {
                this.value = this.value.slice(0, 5);
            }
            
            // Validar el formato
            if (this.value && !validatePostalCode(this.value)) {
                this.setCustomValidity('El código postal debe tener 5 dígitos');
            } else {
                this.setCustomValidity('');
            }
        });
    });
}

/**
 * Valida un número de teléfono
 * @param {string} phone Número de teléfono a validar
 * @returns {boolean} True si es válido, false en caso contrario
 */
function validatePhone(phone) {
    const phoneRegex = /^[0-9]{9,10}$/;
    return phoneRegex.test(phone);
}

/**
 * Valida un código postal
 * @param {string} postalCode Código postal a validar
 * @returns {boolean} True si es válido, false en caso contrario
 */
function validatePostalCode(postalCode) {
    const postalCodeRegex = /^[0-9]{5}$/;
    return postalCodeRegex.test(postalCode);
}

/**
 * Muestra un mensaje de error
 * @param {string} message Mensaje de error a mostrar
 */
function showErrorMessage(message) {
    // Utilizar alert como solución simple, pero se podría mejorar con una notificación más elegante
    alert(message);
}