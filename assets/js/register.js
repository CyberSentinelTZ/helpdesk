// Handle registration form submission
document.getElementById('registerFormElement').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form values
    const name = document.getElementById('registerName').value;
    const email = document.getElementById('registerEmail').value;
    const employeeId = document.getElementById('registerEmployeeId').value;
    const department = document.getElementById('registerDepartment').value;
    const phone = document.getElementById('registerPhone').value;
    const position = document.getElementById('registerPosition').value;
    const password = document.getElementById('registerPassword').value;
    const confirmPassword = document.getElementById('registerConfirmPassword').value;
    const agreeTerms = document.getElementById('agreeTerms').checked;
    
    const messageDiv = document.getElementById('registerMessage');
    
    // Validation
    let isValid = true;
    let errorMessage = '';
    
    // Check required fields
    if (!name || !email || !employeeId || !department || !phone || !position || !password || !confirmPassword) {
        isValid = false;
        errorMessage = 'Please fill in all required fields';
    }
    // Check email format
    else if (!validateEmail(email)) {
        isValid = false;
        errorMessage = 'Please enter a valid email address';
    }
    // Check if email is hospital email
    else if (!email.includes('@arushahospital.co.tz') && !email.includes('@hospital.ar')) {
        isValid = false;
        errorMessage = 'Please use a valid Arusha City Hospital email address';
    }
    // Check password strength
    else if (password.length < 8) {
        isValid = false;
        errorMessage = 'Password must be at least 8 characters long';
    }
    // Check password complexity
    else if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(password)) {
        isValid = false;
        errorMessage = 'Password must contain uppercase, lowercase letters and numbers';
    }
    // Check password match
    else if (password !== confirmPassword) {
        isValid = false;
        errorMessage = 'Passwords do not match';
    }
    // Check phone format
    else if (!validatePhone(phone)) {
        isValid = false;
        errorMessage = 'Please enter a valid phone number';
    }
    // Check terms agreement
    else if (!agreeTerms) {
        isValid = false;
        errorMessage = 'You must agree to the Terms of Service and Privacy Policy';
    }
    
    if (!isValid) {
        showMessage(messageDiv, 'error', errorMessage);
        return;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('#registerFormElement .btn');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Creating Account...';
    submitBtn.disabled = true;
    
    // Simulate API call to backend
    setTimeout(() => {
        // In a real application, this would connect to a backend
        // For demo purposes, we'll simulate a successful registration
        const simulatedResponse = {
            success: true,
            message: 'Registration successful! Your account will be activated after verification by ICT Admin.',
            data: {
                name: name,
                email: email,
                employeeId: employeeId,
                department: department,
                phone: phone,
                position: position
            }
        };
        
        if (simulatedResponse.success) {
            showMessage(messageDiv, 'success', simulatedResponse.message);
            
            // Clear form
            document.getElementById('registerFormElement').reset();
            
            // Redirect to login page after successful registration
            setTimeout(() => {
                alert(`Registration Successful!\n\nAccount Details:\nName: ${name}\nEmail: ${email}\nEmployee ID: ${employeeId}\nDepartment: ${department}\nPosition: ${position}\n\nYour account requires administrator approval before you can login.`);
                
                // Redirect to login page
                window.location.href = 'index.html';
            }, 2000);
        } else {
            showMessage(messageDiv, 'error', 'Registration failed. Please try again or contact ICT support.');
        }
        
        // Reset button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }, 2000);
});

// Helper functions
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    // Accepts various phone formats
    const re = /^[\+]?[0-9\s\-\(\)]{10,}$/;
    return re.test(phone);
}

function showMessage(element, type, message) {
    element.textContent = message;
    element.className = 'form-message ' + type;
    element.style.display = message ? 'block' : 'none';
    
    // Auto-hide success messages after 5 seconds
    if (type === 'success') {
        setTimeout(() => {
            if (element.textContent === message) {
                element.style.display = 'none';
            }
        }, 5000);
    }
}

// Demo auto-fill for testing
document.getElementById('registerEmail').addEventListener('dblclick', function() {
    document.getElementById('registerName').value = 'Dr. Sarah Johnson';
    document.getElementById('registerEmail').value = 'sarah.johnson@arushahospital.co.tz';
    document.getElementById('registerEmployeeId').value = 'ACH-EMP-2023-0789';
    document.getElementById('registerDepartment').value = 'Emergency';
    document.getElementById('registerPhone').value = '+255 712 345 678';
    document.getElementById('registerPosition').value = 'Doctor';
    document.getElementById('registerPassword').value = 'Demo@1234';
    document.getElementById('registerConfirmPassword').value = 'Demo@1234';
    document.getElementById('agreeTerms').checked = true;
});

// Form validation on input
const formInputs = document.querySelectorAll('#registerFormElement input, #registerFormElement select');
formInputs.forEach(input => {
    input.addEventListener('blur', function() {
        validateField(this);
    });
    
    input.addEventListener('input', function() {
        // Clear any error styling when user starts typing
        const parent = this.closest('.input-group');
        if (parent) {
            parent.querySelector('input, select').style.borderColor = '#e6f2ff';
        }
    });
});

function validateField(field) {
    const value = field.value.trim();
    const parent = field.closest('.input-group');
    
    if (!parent) return;
    
    if (field.required && !value) {
        field.style.borderColor = '#f44336';
    } else {
        field.style.borderColor = '#e6f2ff';
    }
    
    // Specific validations
    if (field.id === 'registerEmail' && value) {
        if (!validateEmail(value)) {
            field.style.borderColor = '#f44336';
        }
    }
    
    if (field.id === 'registerPhone' && value) {
        if (!validatePhone(value)) {
            field.style.borderColor = '#f44336';
        }
    }
    
    if ((field.id === 'registerPassword' || field.id === 'registerConfirmPassword') && value) {
        if (field.id === 'registerPassword' && value.length < 8) {
            field.style.borderColor = '#f44336';
        }
        
        // Check if passwords match
        const password = document.getElementById('registerPassword').value;
        const confirmPassword = document.getElementById('registerConfirmPassword').value;
        
        if (password && confirmPassword && password !== confirmPassword) {
            document.getElementById('registerPassword').style.borderColor = '#f44336';
            document.getElementById('registerConfirmPassword').style.borderColor = '#f44336';
        } else if (password && confirmPassword && password === confirmPassword) {
            document.getElementById('registerPassword').style.borderColor = '#4CAF50';
            document.getElementById('registerConfirmPassword').style.borderColor = '#4CAF50';
        }
    }
}

// Initialize any remembered form data
document.addEventListener('DOMContentLoaded', function() {
    // Check if there's partial data in localStorage
    const savedFormData = localStorage.getItem('registrationDraft');
    if (savedFormData) {
        try {
            const data = JSON.parse(savedFormData);
            Object.keys(data).forEach(key => {
                const field = document.getElementById(key);
                if (field) {
                    field.value = data[key];
                }
            });
        } catch (e) {
            console.log('No saved form data found');
        }
    }
    
    // Save form data on input (draft)
    const saveFormData = () => {
        const formData = {
            registerName: document.getElementById('registerName').value,
            registerEmail: document.getElementById('registerEmail').value,
            registerEmployeeId: document.getElementById('registerEmployeeId').value,
            registerDepartment: document.getElementById('registerDepartment').value,
            registerPhone: document.getElementById('registerPhone').value,
            registerPosition: document.getElementById('registerPosition').value
        };
        localStorage.setItem('registrationDraft', JSON.stringify(formData));
    };
    
    // Attach save function to input events
    formInputs.forEach(input => {
        input.addEventListener('input', saveFormData);
    });
    
    // Clear draft when form is submitted
    document.getElementById('registerFormElement').addEventListener('submit', function() {
        localStorage.removeItem('registrationDraft');
    });
});