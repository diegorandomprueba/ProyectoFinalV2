// Validación para el formulario de registro
const registerForm = document.getElementById('register-form');
if (registerForm) {
    registerForm.addEventListener('submit', function(e) {
        let valid = true;
        
        // Validar nombre y apellidos
        const nameInput = document.getElementById('nombre');
        const nameValue = nameInput.value.trim();
        const nameRegex = /^[A-Za-záéíóúÁÉÍÓÚñÑüÜ]+ [A-Za-záéíóúÁÉÍÓÚñÑüÜ]+( [A-Za-záéíóúÁÉÍÓÚñÑüÜ]+)?( [A-Za-záéíóúÁÉÍÓÚñÑüÜ]+)?$/;
        
        if (!nameRegex.test(nameValue)) {
            showError(nameInput, 'Introduce al menos un nombre y un apellido (máximo dos nombres y dos apellidos)');
            valid = false;
        } else {
            hideError(nameInput);
            // Convertir primera letra de cada nombre y apellido a mayúscula
            const nameParts = nameValue.split(' ');
            const formattedName = nameParts.map(part => {
                return part.charAt(0).toUpperCase() + part.slice(1).toLowerCase();
            }).join(' ');
            nameInput.value = formattedName;
        }
        
        // Validar fecha de nacimiento
        const birthDateInput = document.getElementById('birth_date');
        if (birthDateInput) {
            const birthDate = new Date(birthDateInput.value);
            const today = new Date();
            const age = today.getFullYear() - birthDate.getFullYear();
            
            if (age < 18 || age > 100) {
                showError(birthDateInput, 'Debes tener entre 18 y 100 años para registrarte');
                valid = false;
            } else {
                hideError(birthDateInput);
            }
        }
        
        // Validar teléfono
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            const phoneValue = phoneInput.value.trim();
            const phoneRegex = /^\+\d{1,3}\s\d{9}$/;
            
            if (!phoneRegex.test(phoneValue)) {
                showError(phoneInput, 'Introduce un número de teléfono válido con código internacional (ej: +34 666123456)');
                valid = false;
            } else {
                hideError(phoneInput);
            }
        }
        
        // Validar dirección
        const addressInput = document.getElementById('address');
        if (addressInput) {
            const addressValue = addressInput.value.trim();
            
            if (addressValue.length < 5) {
                showError(addressInput, 'Introduce una dirección válida');
                valid = false;
            } else {
                hideError(addressInput);
            }
        }
        
        // Validar email
        const emailInput = document.getElementById('email');
        const emailValue = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!emailRegex.test(emailValue)) {
            showError(emailInput, 'Introduce un correo electrónico válido');
            valid = false;
        } else {
            hideError(emailInput);
        }
        
        // Validar contraseña
        const passwordInput = document.getElementById('password');
        const passwordValue = passwordInput.value;
        const passwordMeter = document.getElementById('password-meter');
        
        if (passwordValue.length < 8) {
            showError(passwordInput, 'La contraseña debe tener al menos 8 caracteres');
            valid = false;
        } else {
            const strength = calculatePasswordStrength(passwordValue);
            
            if (strength < 2) { // 0: débil, 1: media, 2: fuerte
                showError(passwordInput, 'La contraseña es demasiado débil');
                valid = false;
            } else {
                hideError(passwordInput);
            }
        }
        
        // Validar confirmación de contraseña
        const confirmPasswordInput = document.getElementById('confirm_password');
        const confirmPasswordValue = confirmPasswordInput.value;
        
        if (confirmPasswordValue !== passwordValue) {
            showError(confirmPasswordInput, 'Las contraseñas no coinciden');
            valid = false;
        } else {
            hideError(confirmPasswordInput);
        }
        
        if (!valid) {
            e.preventDefault();
        }
    });
    
    // Actualizar indicador de fortaleza de contraseña en tiempo real
    const passwordInput = document.getElementById('password');
    const passwordMeter = document.getElementById('password-meter');
    
    if (passwordInput && passwordMeter) {
        passwordInput.addEventListener('input', function() {
            const strength = calculatePasswordStrength(this.value);
            updatePasswordMeter(passwordMeter, strength);
        });
    }
}

// Calcular fortaleza de la contraseña
function calculatePasswordStrength(password) {
    let strength = 0;
    
    // Longitud mínima
    if (password.length >= 8) strength += 1;
    
    // Contiene letras minúsculas y mayúsculas
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 1;
    
    // Contiene números
    if (/\d/.test(password)) strength += 1;
    
    // Contiene caracteres especiales
    if (/[^A-Za-z0-9]/.test(password)) strength += 1;
    
    // Normalizar de 0 a 2 (débil, media, fuerte)
    return Math.min(Math.floor(strength / 2), 2);
}

// Actualizar indicador de fortaleza de contraseña
function updatePasswordMeter(meterElement, strength) {
    meterElement.value = strength;
    
    switch (strength) {
        case 0:
            meterElement.classList.remove('text-yellow-500', 'text-green-500');
            meterElement.classList.add('text-red-500');
            meterElement.nextElementSibling.textContent = 'Débil';
            break;
        case 1:
            meterElement.classList.remove('text-red-500', 'text-green-500');
            meterElement.classList.add('text-yellow-500');
            meterElement.nextElementSibling.textContent = 'Media';
            break;
        case 2:
            meterElement.classList.remove('text-red-500', 'text-yellow-500');
            meterElement.classList.add('text-green-500');
            meterElement.nextElementSibling.textContent = 'Fuerte';
            break;
    }
}

// Mostrar mensaje de error
function showError(inputElement, message) {
    inputElement.classList.add('border-red-500');
    
    let errorElement = inputElement.nextElementSibling;
    if (!errorElement || !errorElement.classList.contains('error-message')) {
        errorElement = document.createElement('p');
        errorElement.classList.add('error-message', 'text-red-500', 'text-sm', 'mt-1');
        inputElement.parentNode.insertBefore(errorElement, inputElement.nextSibling);
    }
    
    errorElement.textContent = message;
}

// Ocultar mensaje de error
function hideError(inputElement) {
    inputElement.classList.remove('border-red-500');
    
    const errorElement = inputElement.nextElementSibling;
    if (errorElement && errorElement.classList.contains('error-message')) {
        errorElement.textContent = '';
    }
}