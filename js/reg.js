       // Form state management
        let formData = {
            basicInfo: {},
            security: {},
            additional: {}
        };
        
        let currentStep = 1;
        const totalSteps = 4;
        
        // Initialize form
        document.addEventListener('DOMContentLoaded', function() {
            // Set default role
            document.querySelector('.role-badge.staff').click();
            
            // Set admin name
            document.getElementById('adminName').textContent = 'System Administrator';
            
            // Initialize progress bar
            updateProgressBar();
            
            // Add event listeners for quick department selection
            document.querySelectorAll('.department-option').forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selected class from all options
                    document.querySelectorAll('.department-option').forEach(o => {
                        o.classList.remove('selected');
                    });
                    
                    // Add selected class to clicked option
                    this.classList.add('selected');
                    
                    // Update select value
                    document.getElementById('department').value = this.dataset.value;
                });
            });
            
            // Add event listener for "Other" department
            document.getElementById('department').addEventListener('change', function() {
                if (this.value === 'Other') {
                    const otherInput = prompt('Please specify the department name:');
                    if (otherInput && otherInput.trim()) {
                        this.value = otherInput.trim();
                        
                        // Update quick select display
                        document.querySelectorAll('.department-option').forEach(o => {
                            o.classList.remove('selected');
                        });
                    } else {
                        this.value = '';
                    }
                }
            });
        });
        
        // Step navigation functions
        function goToStep(step) {
            // Validate current step before leaving
            if (step < currentStep || validateStep(currentStep)) {
                // Animate out current step
                const currentForm = document.getElementById(`step${currentStep}Form`);
                currentForm.classList.add('slide-out');
                
                setTimeout(() => {
                    // Hide current step
                    document.querySelectorAll('.form-step').forEach(form => {
                        form.classList.remove('active');
                    });
                    
                    // Show new step
                    document.getElementById(`step${step}Form`).classList.add('active');
                    document.getElementById(`step${step}Form`).classList.add('slide-in');
                    
                    // Update progress indicators
                    document.querySelectorAll('.step').forEach((stepEl, index) => {
                        if (index + 1 <= step) {
                            stepEl.classList.add('completed');
                            stepEl.classList.remove('active');
                        } else {
                            stepEl.classList.remove('completed');
                            stepEl.classList.remove('active');
                        }
                    });
                    
                    document.getElementById(`step${step}`).classList.add('active');
                    
                    currentStep = step;
                    updateProgressBar();
                    updateProgressText();
                    
                    // Scroll to top of form
                    document.querySelector('.form-container').scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                    
                    // Remove animations after transition
                    setTimeout(() => {
                        document.querySelectorAll('.form-step').forEach(form => {
                            form.classList.remove('slide-out', 'slide-in');
                        });
                    }, 500);
                    
                    // If going to confirmation step, update summary
                    if (step === 4) {
                        updateSummary();
                    }
                }, 300);
            }
        }
        
        function nextStep() {
            if (validateStep(currentStep)) {
                // Save current step data
                saveStepData(currentStep);
                
                if (currentStep < totalSteps) {
                    goToStep(currentStep + 1);
                }
            }
        }
        
        function prevStep() {
            if (currentStep > 1) {
                goToStep(currentStep - 1);
            }
        }
        
        // Step validation
        function validateStep(step) {
            let isValid = true;
            let errorMessage = '';
            
            switch(step) {
                case 1:
                    const fullName = document.getElementById('full_name').value.trim();
                    const phone = document.getElementById('phone_number').value.replace(/\s/g, '');
                    const department = document.getElementById('department').value;
                    const role = document.getElementById('role').value;
                    
                    if (!fullName) {
                        errorMessage = 'Full name is required';
                        isValid = false;
                    } else if (!phone || phone.length < 9) {
                        errorMessage = 'Valid phone number is required';
                        isValid = false;
                    } else if (!department) {
                        errorMessage = 'Department is required';
                        isValid = false;
                    } else if (!role) {
                        errorMessage = 'User role is required';
                        isValid = false;
                    }
                    
                    showStepMessage(step, errorMessage, isValid ? 'success' : 'error');
                    break;
                    
                case 2:
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('confirm_password').value;
                    
                    if (!password || password.length < 6) {
                        errorMessage = 'Password must be at least 6 characters';
                        isValid = false;
                    } else if (password !== confirmPassword) {
                        errorMessage = 'Passwords do not match';
                        isValid = false;
                    }
                    
                    showStepMessage(step, errorMessage, isValid ? 'success' : 'error');
                    break;
                    
                case 4:
                    const confirmAccuracy = document.getElementById('confirm_accuracy').checked;
                    const confirmAuthority = document.getElementById('confirm_authority').checked;
                    
                    if (!confirmAccuracy || !confirmAuthority) {
                        errorMessage = 'Please confirm all statements before submitting';
                        isValid = false;
                    }
                    
                    showStepMessage(step, errorMessage, isValid ? 'success' : 'error');
                    break;
            }
            
            return isValid;
        }
        
        // Save step data
        function saveStepData(step) {
            switch(step) {
                case 1:
                    formData.basicInfo = {
                        full_name: document.getElementById('full_name').value.trim(),
                        phone_number: document.getElementById('phone_number').value.replace(/\s/g, ''),
                        email: document.getElementById('email').value.trim(),
                        department: document.getElementById('department').value,
                        role: document.getElementById('role').value
                    };
                    break;
                    
                case 2:
                    formData.security = {
                        password: document.getElementById('password').value,
                        send_email: document.getElementById('send_email').checked,
                        require_reset: document.getElementById('require_reset').checked,
                        enable_2fa: document.getElementById('enable_2fa').checked,
                        lock_account: document.getElementById('lock_account').checked,
                        account_expiry: document.getElementById('account_expiry').value
                    };
                    break;
                    
                case 3:
                    formData.additional = {
                        notes: document.getElementById('notes').value.trim(),
                        notify_tickets: document.getElementById('notify_tickets').checked,
                        notify_updates: document.getElementById('notify_updates').checked,
                        notify_maintenance: document.getElementById('notify_maintenance').checked,
                        weekly_report: document.getElementById('weekly_report').checked,
                        preferred_contact: document.getElementById('preferred_contact').value
                    };
                    break;
            }
        }
        
        // Update summary on confirmation step
        function updateSummary() {
            // Basic Info
            document.getElementById('summaryFullName').textContent = formData.basicInfo.full_name || '-';
            
            const phone = formData.basicInfo.phone_number;
            const formattedPhone = phone ? `+255 ${phone.substring(3, 6)} ${phone.substring(6, 9)} ${phone.substring(9)}` : '-';
            document.getElementById('summaryPhone').textContent = formattedPhone;
            
            document.getElementById('summaryEmail').textContent = formData.basicInfo.email || 'Not provided';
            document.getElementById('summaryDepartment').textContent = formData.basicInfo.department || '-';
            
            // Format role
            let roleText = '-';
            if (formData.basicInfo.role === 'admin') roleText = 'Administrator';
            if (formData.basicInfo.role === 'technician') roleText = 'ICT Technician';
            if (formData.basicInfo.role === 'staff') roleText = 'Hospital Staff';
            document.getElementById('summaryRole').textContent = roleText;
            
            // Security Options
            const securityOptions = [];
            if (formData.security.send_email) securityOptions.push('Welcome email');
            if (formData.security.require_reset) securityOptions.push('Password reset required');
            if (formData.security.enable_2fa) securityOptions.push('Two-factor auth');
            if (formData.security.lock_account) securityOptions.push('Account lock after 3 attempts');
            if (formData.security.account_expiry) securityOptions.push(`Expires on ${formData.security.account_expiry}`);
            
            document.getElementById('summarySecurity').textContent = 
                securityOptions.length > 0 ? securityOptions.join(', ') : 'Default settings';
            
            // Notifications
            const notifications = [];
            if (formData.additional.notify_tickets) notifications.push('New tickets');
            if (formData.additional.notify_updates) notifications.push('System updates');
            if (formData.additional.notify_maintenance) notifications.push('Maintenance');
            if (formData.additional.weekly_report) notifications.push('Weekly report');
            
            let contactMethod = '';
            switch(formData.additional.preferred_contact) {
                case 'phone': contactMethod = 'Phone call'; break;
                case 'sms': contactMethod = 'SMS'; break;
                case 'email': contactMethod = 'Email'; break;
                case 'whatsapp': contactMethod = 'WhatsApp'; break;
                case 'system': contactMethod = 'System notification'; break;
            }
            
            document.getElementById('summaryNotifications').textContent = 
                notifications.length > 0 ? `${notifications.join(', ')} (via ${contactMethod})` : 'No notifications';
            
            // Notes
            document.getElementById('summaryNotes').textContent = 
                formData.additional.notes || 'No additional notes';
        }
        
        // Submit registration
        function submitRegistration() {
            if (validateStep(4)) {
                // Show loading state
                const submitBtn = document.getElementById('submitRegistration');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                submitBtn.disabled = true;
                
                // Combine all form data
                const completeFormData = {
                    ...formData.basicInfo,
                    ...formData.security,
                    ...formData.additional
                };
                
                // Format phone number
                let phone = completeFormData.phone_number;
                if (phone.startsWith('0')) {
                    phone = '255' + phone.substring(1);
                } else if (!phone.startsWith('255')) {
                    phone = '255' + phone;
                }
                completeFormData.phone_number = phone;
                
                // Simulate API call (in production, this would be a real API call)
                setTimeout(() => {
                    console.log('Complete Registration Data:', completeFormData);
                    
                    // Generate user ID
                    const userId = `HD-${new Date().getFullYear()}-${Math.floor(Math.random() * 1000).toString().padStart(3, '0')}`;
                    const tempPassword = generateTempPassword();
                    
                    // Show success screen
                    showSuccessScreen(userId, tempPassword);
                    
                }, 2000);
            }
        }
        
        // Show success screen
        function showSuccessScreen(userId, tempPassword) {
            // Update success screen
            document.getElementById('successUserId').textContent = userId;
            document.getElementById('successTempPassword').textContent = tempPassword;
            
            // Hide all steps and show success screen
            document.querySelectorAll('.form-step').forEach(form => {
                form.classList.remove('active');
            });
            
            document.getElementById('successScreen').classList.add('active');
            
            // Update progress bar to show completion
            document.querySelectorAll('.step').forEach(stepEl => {
                stepEl.classList.add('completed');
                stepEl.classList.remove('active');
            });
            
            document.getElementById('progressText').textContent = 'Registration Complete!';
            
            // Scroll to top
            document.querySelector('.form-container').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }
        
        // Generate temporary password
        function generateTempPassword() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%';
            let password = '';
            for (let i = 0; i < 10; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return password;
        }
        
        // Success screen actions
        function printSummary() {
            window.print();
        }
        
        function sendCredentials() {
            alert('Credentials sent successfully!');
        }
        
        function registerAnother() {
            // Reset form
            document.getElementById('basicInfoForm').reset();
            document.getElementById('role').value = '';
            document.querySelector('.role-badge.staff').click();
            
            // Reset progress
            currentStep = 1;
            formData = { basicInfo: {}, security: {}, additional: {} };
            
            // Show first step
            document.querySelectorAll('.form-step').forEach(form => {
                form.classList.remove('active');
            });
            
            document.getElementById('step1Form').classList.add('active');
            
            // Reset progress indicators
            document.querySelectorAll('.step').forEach((stepEl, index) => {
                stepEl.classList.remove('completed');
                stepEl.classList.remove('active');
                if (index === 0) stepEl.classList.add('active');
            });
            
            updateProgressBar();
            updateProgressText();
            
            // Scroll to top
            document.querySelector('.form-container').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }
        
        // Progress bar updates
        function updateProgressBar() {
            const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
            document.getElementById('progressBar').style.width = `${progressPercentage}%`;
        }
        
        function updateProgressText() {
            const stepTexts = [
                'Step 1 of 4: Basic Information',
                'Step 2 of 4: Security Settings',
                'Step 3 of 4: Additional Information',
                'Step 4 of 4: Review & Confirmation'
            ];
            document.getElementById('progressText').textContent = stepTexts[currentStep - 1] || '';
        }
        
        // Show step message
        function showStepMessage(step, message, type) {
            const messageDiv = document.getElementById(`step${step}Message`);
            
            if (message) {
                messageDiv.textContent = message;
                messageDiv.className = `alert alert-${type}`;
                messageDiv.style.display = 'block';
                
                // Scroll to message
                setTimeout(() => {
                    messageDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
                
                // Hide message after 5 seconds
                if (type === 'error') {
                    setTimeout(() => {
                        messageDiv.style.display = 'none';
                    }, 5000);
                }
            } else {
                messageDiv.style.display = 'none';
            }
        }
        
        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            
            // Length check
            if (password.length >= 6) {
                document.getElementById('reqLength').classList.add('valid');
                strength++;
            } else {
                document.getElementById('reqLength').classList.remove('valid');
            }
            
            // Uppercase check
            if (/[A-Z]/.test(password)) {
                document.getElementById('reqUppercase').classList.add('valid');
                strength++;
            } else {
                document.getElementById('reqUppercase').classList.remove('valid');
            }
            
            // Lowercase check
            if (/[a-z]/.test(password)) {
                document.getElementById('reqLowercase').classList.add('valid');
                strength++;
            } else {
                document.getElementById('reqLowercase').classList.remove('valid');
            }
            
            // Number check
            if (/[0-9]/.test(password)) {
                document.getElementById('reqNumber').classList.add('valid');
                strength++;
            } else {
                document.getElementById('reqNumber').classList.remove('valid');
            }
            
            // Update strength meter
            const strengthMeter = document.getElementById('passwordStrength');
            const strengthPercentage = (strength / 4) * 100;
            
            strengthMeter.style.width = strengthPercentage + '%';
            
            // Set color based on strength
            if (strengthPercentage <= 25) {
                strengthMeter.style.backgroundColor = '#dc3545'; // Red
            } else if (strengthPercentage <= 75) {
                strengthMeter.style.backgroundColor = '#ffc107'; // Yellow
            } else {
                strengthMeter.style.backgroundColor = '#28a745'; // Green
            }
            
            return strength;
        }
        
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const toggleIcon = field.parentNode.querySelector('.toggle-password i');
            
            if (field.type === 'password') {
                field.type = 'text';
                toggleIcon.className = 'fas fa-eye-slash';
            } else {
                field.type = 'password';
                toggleIcon.className = 'fas fa-eye';
            }
        }
        
        // Role selection
        const roleBadges = document.querySelectorAll('.role-badge');
        const roleInput = document.getElementById('role');
        
        roleBadges.forEach(badge => {
            badge.addEventListener('click', function() {
                // Remove selected class from all badges
                roleBadges.forEach(b => b.classList.remove('selected'));
                
                // Add selected class to clicked badge
                this.classList.add('selected');
                
                // Set hidden input value
                roleInput.value = this.dataset.role;
            });
        });
        
        // Phone number formatting
        document.getElementById('phone_number').addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            
            if (value.length > 9) {
                value = value.substring(0, 9);
            }
            
            if (value.length > 6) {
                value = value.replace(/(\d{3})(\d{3})(\d+)/, '$1 $2 $3');
            } else if (value.length > 3) {
                value = value.replace(/(\d{3})(\d+)/, '$1 $2');
            }
            
            this.value = value;
        });
        
        // Password validation
        document.getElementById('password').addEventListener('input', function() {
            checkPasswordStrength(this.value);
            validatePasswordMatch();
        });
        
        document.getElementById('confirm_password').addEventListener('input', validatePasswordMatch);
        
        function validatePasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const errorElement = document.getElementById('confirmPasswordError');
            
            if (confirmPassword && password !== confirmPassword) {
                errorElement.textContent = 'Passwords do not match';
                errorElement.style.display = 'block';
                return false;
            } else {
                errorElement.style.display = 'none';
                return true;
            }
        }
        
        // Form submission handlers
        document.getElementById('basicInfoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            nextStep();
        });
        
        document.getElementById('nextStep2').addEventListener('click', function(e) {
            e.preventDefault();
            nextStep();
        });
        
        document.getElementById('nextStep3').addEventListener('click', function(e) {
            e.preventDefault();
            nextStep();
        });
        
        document.getElementById('submitRegistration').addEventListener('click', function(e) {
            e.preventDefault();
            submitRegistration();
        });
        
        // Add dynamic styles
        const additionalStyles = document.createElement('style');
        additionalStyles.textContent = `
            .admin-info {
                background: rgba(255, 255, 255, 0.2);
                padding: 8px 15px;
                border-radius: 20px;
                font-weight: 500;
            }
            
            .phone-input-container {
                display: flex;
                align-items: center;
            }
            
            .country-code {
                background: #f8f9fa;
                padding: 12px 15px;
                border: 2px solid #ddd;
                border-right: none;
                border-radius: 8px 0 0 8px;
                font-weight: 500;
                color: #555;
            }
            
            .phone-input-container input {
                border-radius: 0 8px 8px 0;
                flex: 1;
            }
            
            .password-container {
                position: relative;
            }
            
            .toggle-password {
                position: absolute;
                right: 15px;
                top: 50%;
                transform: translateY(-50%);
                cursor: pointer;
                color: #666;
            }
            
            .help-text {
                display: block;
                margin-top: 5px;
                color: #666;
                font-size: 0.85rem;
            }
            
            .form-check {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 10px;
            }
            
            .form-check-input {
                width: 18px;
                height: 18px;
                margin: 0;
            }
            
            .form-check-label {
                cursor: pointer;
                user-select: none;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            .info-box {
                background: #e7f3ff;
                border-left: 4px solid var(--hospital-blue);
                padding: 1rem;
                border-radius: 5px;
                margin-bottom: 1.5rem;
                font-size: 0.9rem;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            
            .info-box i {
                color: var(--hospital-blue);
                font-size: 1.2rem;
            }
            
            .progress-text {
                text-align: center;
                color: #666;
                font-size: 0.9rem;
                font-weight: 500;
            }
            
            @media (max-width: 767px) {
                .form-navigation {
                    flex-direction: column;
                }
                
                .form-navigation .btn-step {
                    width: 100%;
                    margin-bottom: 10px;
                }
                
                .progress-steps {
                    padding: 0 20px;
                }
                
                .step-label {
                    display: none;
                }
                
                .department-options {
                    grid-template-columns: repeat(2, 1fr);
                }
                
                .role-badges {
                    flex-direction: column;
                }
            }
            
            @media (max-width: 480px) {
                .progress-steps {
                    padding: 0 10px;
                }
                
                .step {
                    width: 30px;
                    height: 30px;
                    font-size: 0.9rem;
                }
                
                .department-options {
                    grid-template-columns: 1fr;
                }
            }
        `;
        document.head.appendChild(additionalStyles);

                // Update current time
                function updateTime() {
                    const now = new Date();
                    const options = { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        timeZone: 'Africa/Dar_es_Salaam'
                    };
                    const el = document.getElementById('currentTime');
                    if (el) {
                        el.innerHTML = now.toLocaleDateString('en-TZ', options);
                    }
                }
                
                setInterval(updateTime, 1000);
                updateTime();