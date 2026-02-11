// Form validation specific functions

// Phone number validation for Tanzania
function validateTanzaniaPhoneNumber(phone) {
    // Remove all non-digits
    phone = phone.replace(/\D/g, '');
    
    // Check length
    if (phone.length < 9 || phone.length > 12) {
        return false;
    }
    
    // Tanzanian phone number patterns
    const patterns = [
        /^2557[0-9]{8}$/, // Standard format
        /^07[0-9]{8}$/,   // Local format
        /^7[0-9]{8}$/,    // Without leading zero
        /^2556[0-9]{8}$/, // Airtel, Tigo
        /^2557[8-9][0-9]{7}$/ // Vodacom, Halotel
    ];
    
    return patterns.some(pattern => pattern.test(phone));
}

// Email validation
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Password validation rules
const passwordRules = {
    minLength: 6,
    requireUppercase: true,
    requireLowercase: true,
    requireNumbers: true,
    requireSpecialChars: false
};

function validatePassword(password) {
    const errors = [];
    
    if (password.length < passwordRules.minLength) {
        errors.push(`Password must be at least ${passwordRules.minLength} characters`);
    }
    
    if (passwordRules.requireUppercase && !/[A-Z]/.test(password)) {
        errors.push('Password must contain at least one uppercase letter');
    }
    
    if (passwordRules.requireLowercase && !/[a-z]/.test(password)) {
        errors.push('Password must contain at least one lowercase letter');
    }
    
    if (passwordRules.requireNumbers && !/[0-9]/.test(password)) {
        errors.push('Password must contain at least one number');
    }
    
    if (passwordRules.requireSpecialChars && !/[^A-Za-z0-9]/.test(password)) {
        errors.push('Password must contain at least one special character');
    }
    
    return {
        isValid: errors.length === 0,
        errors: errors
    };
}

// Real-time validation for form fields
function setupRealTimeValidation() {
    // Phone number validation
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !validateTanzaniaPhoneNumber(this.value)) {
                showFieldError(this, 'Please enter a valid Tanzanian phone number');
            } else {
                clearFieldError(this);
            }
        });
    });
    
    // Email validation
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !validateEmail(this.value)) {
                showFieldError(this, 'Please enter a valid email address');
            } else {
                clearFieldError(this);
            }
        });
    });
    
    // Password validation
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    passwordInputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.value) {
                const result = validatePassword(this.value);
                if (!result.isValid) {
                    showFieldError(this, result.errors[0]);
                } else {
                    clearFieldError(this);
                }
            }
        });
    });
}

// Show field error
function showFieldError(field, message) {
    field.classList.add('error');
    
    // Check if error message already exists
    let errorElement = field.nextElementSibling;
    if (!errorElement || !errorElement.classList.contains('error-message')) {
        errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        field.parentNode.insertBefore(errorElement, field.nextSibling);
    }
    
    errorElement.textContent = message;
    errorElement.style.display = 'block';
}

// Clear field error
function clearFieldError(field) {
    field.classList.remove('error');
    const errorElement = field.nextElementSibling;
    if (errorElement && errorElement.classList.contains('error-message')) {
        errorElement.style.display = 'none';
    }
}

// Initialize validation when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    setupRealTimeValidation();
    
    // Add custom validation styles
    const style = document.createElement('style');
    style.textContent = `
        input.error, select.error {
            border-color: #dc3545 !important;
            background-color: #fff8f8;
        }
        
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
        
        .password-strength {
            margin-top: 5px;
            height: 4px;
            background: #eee;
            border-radius: 2px;
            overflow: hidden;
        }
        
        .password-strength-meter {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease, background-color 0.3s ease;
        }
    `;
    document.head.appendChild(style);
});

// Export validation functions
window.Validation = {
    validateTanzaniaPhoneNumber,
    validateEmail,
    validatePassword,
    showFieldError,
    clearFieldError
};