document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const passwordMeter = document.getElementById('password-meter');
    const passwordText = document.getElementById('password-text');
    const submitButton = document.getElementById('submit-button');
    
    const strengthTexts = [
        'Muy débil',
        'Débil',
        'Media',
        'Fuerte',
        'Muy fuerte'
    ];
    
    // Verificar fortaleza de contraseña
    function checkPasswordStrength(password) {
        let strength = 0;
        
        // Verificar longitud
        if (password.length >= 8) {
            strength += 1;
        }
        
        // Verificar si tiene letras minúsculas y mayúsculas
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) {
            strength += 1;
        }
        
        // Verificar si tiene números
        if (password.match(/\d/)) {
            strength += 1;
        }
        
        // Verificar si tiene caracteres especiales
        if (password.match(/[^a-zA-Z\d]/)) {
            strength += 1;
        }
        
        return strength;
    }
    
    // Actualizar UI de fortaleza de contraseña
    function updatePasswordStrength() {
        const password = passwordInput.value;
        const strength = checkPasswordStrength(password);
        
        passwordMeter.value = strength;
        passwordText.textContent = `Fortaleza: ${strengthTexts[strength]}`;
        
        // Actualizar el estado del botón de envío
        updateSubmitButtonState();
    }
    
    // Verificar si las contraseñas coinciden
    function checkPasswordsMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (confirmPassword) {
            if (password === confirmPassword) {
                confirmPasswordInput.classList.remove('border-red-500');
                confirmPasswordInput.classList.add('border-green-500');
            } else {
                confirmPasswordInput.classList.remove('border-green-500');
                confirmPasswordInput.classList.add('border-red-500');
            }
        }
        
        // Actualizar el estado del botón de envío
        updateSubmitButtonState();
    }
    
    // Actualizar estado del botón de envío
    function updateSubmitButtonState() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const strength = checkPasswordStrength(password);
        
        // Habilitar el botón si la contraseña es al menos de fortaleza media (2) y coincide con la confirmación
        if (strength >= 2 && password === confirmPassword && password.length > 0) {
            submitButton.removeAttribute('disabled');
            submitButton.classList.remove('bg-gray-400');
            submitButton.classList.add('bg-orange-500', 'hover:bg-orange-600');
        } else {
            submitButton.setAttribute('disabled', 'disabled');
            submitButton.classList.remove('bg-orange-500', 'hover:bg-orange-600');
            submitButton.classList.add('bg-gray-400');
        }
    }
    
    // Agregar eventos para actualizar la fortaleza de contraseña y verificar coincidencia
    if (passwordInput) {
        passwordInput.addEventListener('input', updatePasswordStrength);
    }
    
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', checkPasswordsMatch);
    }
    
    // Formatear automáticamente el nombre (primera letra en mayúscula)
    const nameInput = document.getElementById('name');
    if (nameInput) {
        nameInput.addEventListener('blur', function() {
            this.value = this.value.replace(/\b\w/g, function(char) {
                return char.toUpperCase();
            });
        });
    }
    
    // Validación de fecha de nacimiento
    const birthDateInput = document.getElementById('birth_date');
    if (birthDateInput) {
        birthDateInput.addEventListener('change', function() {
            const birthDate = new Date(this.value);
            const today = new Date();
            
            // Calcular edad
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            // Validar edad
            if (age < 18) {
                alert('Debes ser mayor de 18 años para registrarte.');
                this.value = '';
            } else if (age > 100) {
                alert('Por favor, verifica la fecha de nacimiento.');
                this.value = '';
            }
        });
    }
});