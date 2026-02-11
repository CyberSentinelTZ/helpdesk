<li><?php include 'includes/back.php'; ?></li>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User - Arusha City Hospital ICT Help Desk</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <link rel="stylesheet" href="../css/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo-container">
                    <div class="hospital-logo">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div class="hospital-info">
                        <h1>Arusha City Hospital</h1>
                        <p>ICT Help Desk Support System</p>
                    </div>
                </div>
                <div class="current-time" id="currentTime">
                    <!-- Time will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </header>
        <!-- Admin Navigation -->
        <nav class="main-nav">
            <div class="container nav-content">
                <div class="nav-left">
                    <span class="nav-title"><i class="fas fa-user-shield"></i> Admin Dashboard</span>
                </div>
                <div class="nav-right">
                    <span class="nav-user">
                        <?php if (!empty($_SESSION['profile_image'])): ?>
                            <img src="<?php echo htmlspecialchars($_SESSION['profile_image']); ?>" alt="Profile picture">
                        <?php else: ?>
                            <i class="fas fa-user-circle"></i>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($fullName); ?> (<?php echo htmlspecialchars($department); ?>)
                    </span>
                    <a href="../logout.php" class="btn btn-small btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
    </nav>

    <li><?php include 'includes/aside.php'; ?></li>
    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="fdashboard-column dashboard-column-primary">
                <!-- Progress Bar -->
                <div class="progress-container">
                    <div class="progress-steps">
                        <div class="progress-bar" id="progressBar"></div>
                        
                        <div class="step active" id="step1">
                            <span>1</span>
                            <div class="step-label">Basic Information</div>
                        </div>
                        
                        <div class="step" id="step2">
                            <span>2</span>
                            <div class="step-label">Security</div>
                        </div>
                        
                        <div class="step" id="step3">
                            <span>3</span>
                            <div class="step-label">Additional Information</div>
                        </div>
                        
                        <div class="step" id="step4">
                            <span>4</span>
                            <div class="step-label">Confirmation</div>
                        </div>
                    </div>
                    
                </div>
                
                <!-- Multi-Step Form -->
                <div class="multi-step-form" id="multiStepForm">
                    <!-- Step 1: Basic Information -->
                    <div class="form-step active" id="step1Form">
                        <div class="form-header">
                            <h2><i class="fas fa-user-plus"></i> Basic Information</h2>
                            <p>Enter user's personal and hospital details</p>
                        </div>
                        
                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            Please fill in all required fields marked with *
                        </div>
                        
                        <div id="step1Message" class="alert" style="display: none;"></div>
                        
                        <form id="basicInfoForm">
                            <div class="form-grid">
                                <!-- Left Column: Personal Information -->
                                <div class="form-section">
                                    <h3><i class="fas fa-user-circle"></i> Personal Information</h3>
                                    
                                    <div class="form-group">
                                        <label for="full_name"><i class="fas fa-user"></i> Full Name *</label>
                                        <input type="text" id="full_name" class="form-control" 
                                               placeholder="Enter user's full name" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="phone_number"><i class="fas fa-phone"></i> Phone Number *</label>
                                        <div class="phone-input-container">
                                            <div class="country-code">+255</div>
                                            <input type="tel" id="phone_number" class="form-control" 
                                                   placeholder="7XX XXX XXX" 
                                                   pattern="[0-9]{9}"
                                                   title="Enter 9 digits after 255"
                                                   required>
                                        </div>
                                        <small class="help-text">Tanzania format: 7XX XXX XXX</small>
                                        <div class="error-message" id="phoneError"></div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                                        <input type="email" id="email" class="form-control" 
                                               placeholder="user@arushahospital.go.tz">
                                    </div>
                                </div>
                                
                                <!-- Right Column: Hospital Information -->
                                <div class="form-section">
                                    <h3><i class="fas fa-hospital-alt"></i> Hospital Information</h3>
                                    
                                    <div class="form-group">
                                        <label for="department"><i class="fas fa-building"></i> Department *</label>
                                        <select id="department" class="form-control" required>
                                            <option value="">Select Department</option>
                                            <option value="Administration">Administration</option>
                                            <option value="ICT Department">ICT Department</option>
                                            <option value="Medical Records">Medical Records</option>
                                            <option value="Pharmacy">Pharmacy</option>
                                            <option value="Laboratory">Laboratory</option>
                                            <option value="Radiology">Radiology</option>
                                            <option value="Finance">Finance</option>
                                            <option value="Human Resources">Human Resources</option>
                                            <option value="Outpatient Department">Outpatient Department</option>
                                            <option value="Inpatient Department">Inpatient Department</option>
                                            <option value="Emergency">Emergency</option>
                                            <option value="Other">Other Department</option>
                                        </select>
                                        
                                        <div class="department-options" id="quickDepartments">
                                            <div class="department-option" data-value="Administration">Administration</div>
                                            <div class="department-option" data-value="ICT Department">ICT</div>
                                            <div class="department-option" data-value="Medical Records">Medical Records</div>
                                            <div class="department-option" data-value="Pharmacy">Pharmacy</div>
                                            <div class="department-option" data-value="Laboratory">Lab</div>
                                            <div class="department-option" data-value="Radiology">Radiology</div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label><i class="fas fa-user-tag"></i> User Role *</label>
                                        <div class="role-badges">
                                            <div class="role-badge admin" data-role="admin">
                                                <i class="fas fa-crown"></i> Administrator
                                            </div>
                                            <div class="role-badge technician" data-role="technician">
                                                <i class="fas fa-tools"></i> Technician
                                            </div>
                                            <div class="role-badge staff" data-role="staff">
                                                <i class="fas fa-user-md"></i> Staff
                                            </div>
                                        </div>
                                        <input type="hidden" id="role" required>
                                        <small class="help-text">Administrators have full system access</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Navigation for Step 1 -->
                            <div class="form-navigation">
                                <button type="button" class="btn btn-secondary btn-step" onclick="window.location.href='dashboard.php'">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                                
                                <button type="submit" class="btn btn-step btn-next" id="nextStep1">
                                    Continue to Security <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Step 2: Security Settings -->
                    <div class="form-step" id="step2Form">
                        <div class="form-header">
                            <h2><i class="fas fa-shield-alt"></i> Security Settings</h2>
                            <p>Configure password and security options</p>
                        </div>
                        
                        <div id="step2Message" class="alert" style="display: none;"></div>
                        
                        <div class="form-grid">
                            <div class="form-section">
                                <h3><i class="fas fa-lock"></i> Password Configuration</h3>
                                
                                <div class="form-group">
                                    <label for="password"><i class="fas fa-key"></i> Password *</label>
                                    <div class="password-container">
                                        <input type="password" id="password" class="form-control" 
                                               placeholder="Create strong password" 
                                               required
                                               minlength="6">
                                        <span class="toggle-password" onclick="togglePassword('password')">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                    
                                    <div class="password-strength">
                                        <div class="password-strength-meter" id="passwordStrength"></div>
                                    </div>
                                    <div class="strength-labels">
                                        <span>Weak</span>
                                        <span>Medium</span>
                                        <span>Strong</span>
                                    </div>
                                    
                                    <ul class="requirements-list" id="passwordRequirements">
                                        <li id="reqLength">At least 6 characters</li>
                                        <li id="reqUppercase">One uppercase letter</li>
                                        <li id="reqLowercase">One lowercase letter</li>
                                        <li id="reqNumber">One number</li>
                                    </ul>
                                </div>
                                
                                <div class="form-group">
                                    <label for="confirm_password"><i class="fas fa-key"></i> Confirm Password *</label>
                                    <div class="password-container">
                                        <input type="password" id="confirm_password" class="form-control" 
                                               placeholder="Re-enter password" 
                                               required>
                                        <span class="toggle-password" onclick="togglePassword('confirm_password')">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                    <div class="error-message" id="confirmPasswordError"></div>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h3><i class="fas fa-cog"></i> Security Options</h3>
                                
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" id="send_email" class="form-check-input">
                                        <label for="send_email" class="form-check-label">
                                            <i class="fas fa-envelope"></i> Send welcome email with credentials
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input type="checkbox" id="require_reset" class="form-check-input" checked>
                                        <label for="require_reset" class="form-check-label">
                                            <i class="fas fa-redo"></i> Require password reset on first login
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input type="checkbox" id="enable_2fa" class="form-check-input">
                                        <label for="enable_2fa" class="form-check-label">
                                            <i class="fas fa-mobile-alt"></i> Enable two-factor authentication
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input type="checkbox" id="lock_account" class="form-check-input">
                                        <label for="lock_account" class="form-check-label">
                                            <i class="fas fa-lock"></i> Lock account after 3 failed attempts
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="account_expiry"><i class="fas fa-calendar-alt"></i> Account Expiry (Optional)</label>
                                    <input type="date" id="account_expiry" class="form-control" 
                                           min="<?php echo date('Y-m-d'); ?>">
                                    <small class="help-text">Leave empty for no expiry</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Navigation for Step 2 -->
                        <div class="form-navigation">
                            <button type="button" class="btn btn-secondary btn-step" onclick="goToStep(1)">
                                <i class="fas fa-arrow-left"></i> Back
                            </button>
                            
                            <button type="button" class="btn btn-step btn-next" id="nextStep2">
                                Continue to Additional Info <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 3: Additional Information -->
                    <div class="form-step" id="step3Form">
                        <div class="form-header">
                            <h2><i class="fas fa-info-circle"></i> Additional Information</h2>
                            <p>Add any extra details or notes (Optional)</p>
                        </div>
                        
                        <div id="step3Message" class="alert" style="display: none;"></div>
                        
                        <div class="form-grid">
                            <div class="form-section">
                                <h3><i class="fas fa-sticky-note"></i> User Notes</h3>
                                
                                <div class="form-group">
                                    <label for="notes"><i class="fas fa-comment"></i> Additional Notes</label>
                                    <textarea id="notes" class="form-control" rows="6" 
                                              placeholder="Add any additional notes about this user, such as:
• Specific system access requirements
• Special permissions needed
• Training requirements
• Contact preferences
• Any other relevant information"></textarea>
                                    <small class="help-text">These notes will be visible to administrators only</small>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h3><i class="fas fa-bell"></i> Notification Preferences</h3>
                                
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" id="notify_tickets" class="form-check-input" checked>
                                        <label for="notify_tickets" class="form-check-label">
                                            <i class="fas fa-ticket-alt"></i> Notify about new tickets
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input type="checkbox" id="notify_updates" class="form-check-input" checked>
                                        <label for="notify_updates" class="form-check-label">
                                            <i class="fas fa-bell"></i> Notify about system updates
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input type="checkbox" id="notify_maintenance" class="form-check-input">
                                        <label for="notify_maintenance" class="form-check-label">
                                            <i class="fas fa-tools"></i> Notify about maintenance
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input type="checkbox" id="weekly_report" class="form-check-input">
                                        <label for="weekly_report" class="form-check-label">
                                            <i class="fas fa-chart-bar"></i> Send weekly report
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="preferred_contact"><i class="fas fa-phone-alt"></i> Preferred Contact Method</label>
                                    <select id="preferred_contact" class="form-control">
                                        <option value="phone">Phone Call</option>
                                        <option value="sms">SMS</option>
                                        <option value="email" selected>Email</option>
                                        <option value="whatsapp">WhatsApp</option>
                                        <option value="system">System Notification</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Navigation for Step 3 -->
                        <div class="form-navigation">
                            <button type="button" class="btn btn-secondary btn-step" onclick="goToStep(2)">
                                <i class="fas fa-arrow-left"></i> Back
                            </button>
                            
                            <button type="button" class="btn btn-step btn-next" id="nextStep3">
                                Review & Submit <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 4: Confirmation -->
                    <div class="form-step" id="step4Form">
                        <div class="form-header">
                            <h2><i class="fas fa-check-circle"></i> Review & Confirmation</h2>
                            <p>Please review all information before submitting</p>
                        </div>
                        
                        <div id="step4Message" class="alert" style="display: none;"></div>
                        
                        <div class="summary-card">
                            <h3 style="color: var(--hospital-blue); margin-bottom: 1rem;">
                                <i class="fas fa-user"></i> User Summary
                            </h3>
                            
                            <div class="summary-item">
                                <div class="summary-label">Full Name:</div>
                                <div class="summary-value" id="summaryFullName">-</div>
                            </div>
                            
                            <div class="summary-item">
                                <div class="summary-label">Phone Number:</div>
                                <div class="summary-value" id="summaryPhone">-</div>
                            </div>
                            
                            <div class="summary-item">
                                <div class="summary-label">Email:</div>
                                <div class="summary-value" id="summaryEmail">-</div>
                            </div>
                            
                            <div class="summary-item">
                                <div class="summary-label">Department:</div>
                                <div class="summary-value" id="summaryDepartment">-</div>
                            </div>
                            
                            <div class="summary-item">
                                <div class="summary-label">Role:</div>
                                <div class="summary-value" id="summaryRole">-</div>
                            </div>
                            
                            <div class="summary-item">
                                <div class="summary-label">Security Options:</div>
                                <div class="summary-value" id="summarySecurity">-</div>
                            </div>
                            
                            <div class="summary-item">
                                <div class="summary-label">Notifications:</div>
                                <div class="summary-value" id="summaryNotifications">-</div>
                            </div>
                            
                            <div class="summary-item">
                                <div class="summary-label">Notes:</div>
                                <div class="summary-value" id="summaryNotes">-</div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" id="confirm_accuracy" class="form-check-input" required>
                                <label for="confirm_accuracy" class="form-check-label">
                                    I confirm that all information provided is accurate and complete
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input type="checkbox" id="confirm_authority" class="form-check-input" required>
                                <label for="confirm_authority" class="form-check-label">
                                    I confirm that I have authority to create this user account
                                </label>
                            </div>
                        </div>
                        
                        <!-- Navigation for Step 4 -->
                        <div class="form-navigation">
                            <button type="button" class="btn btn-secondary btn-step" onclick="goToStep(3)">
                                <i class="fas fa-arrow-left"></i> Back to Edit
                            </button>
                            
                            <button type="button" class="btn btn-step" id="submitRegistration">
                                <i class="fas fa-user-plus"></i> Register User
                            </button>
                        </div>
                    </div>
                    
                    <!-- Success Screen -->
                    <div class="form-step" id="successScreen">
                        <div class="confirmation-message">
                            <div class="success-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            
                            <h2 style="color: var(--hospital-green); margin-bottom: 1rem;">
                                User Registered Successfully!
                            </h2>
                            
                            <p style="margin-bottom: 1.5rem; color: #666;">
                                The new user account has been created and is ready for use.
                            </p>
                            
                            <div class="summary-card" style="max-width: 500px; margin: 0 auto 2rem;">
                                <div class="summary-item">
                                    <div class="summary-label">User ID:</div>
                                    <div class="summary-value" id="successUserId">HD-2024-001</div>
                                </div>
                                
                                <div class="summary-item">
                                    <div class="summary-label">Temporary Password:</div>
                                    <div class="summary-value" id="successTempPassword">••••••••</div>
                                </div>
                                
                                <div class="summary-item">
                                    <div class="summary-label">Access Link:</div>
                                    <div class="summary-value">
                                        <a href="#" style="color: var(--hospital-blue);">
                                            https://helpdesk.arushahospital.go.tz
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-actions" style="border: none; padding-top: 0;">
                                <button type="button" class="btn btn-secondary" onclick="printSummary()">
                                    <i class="fas fa-print"></i> Print Summary
                                </button>
                                
                                <button type="button" class="btn" onclick="sendCredentials()">
                                    <i class="fas fa-paper-plane"></i> Send Credentials
                                </button>
                                
                                <button type="button" class="btn" onclick="window.location.href='dashboard.php'">
                                    <i class="fas fa-tachometer-alt"></i> Go to Dashboard
                                </button>
                                
                                <button type="button" class="btn" onclick="registerAnother()">
                                    <i class="fas fa-user-plus"></i> Register Another
                                </button>
                            </div>
                            
                            <div style="margin-top: 2rem; font-size: 0.9rem; color: #666;">
                                <p>
                                    <i class="fas fa-info-circle"></i>
                                    An audit log entry has been created for this registration.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-footer">
                    <p>
                        <i class="fas fa-shield-alt"></i> Secure registration system
                    </p>
                    <p>
                        <i class="fas fa-history"></i> All actions are logged for audit purposes
                    </p>
                </div>
            </div>
        </div>
    </main>
    <script src="../js/reg.js"></script>
</body>
</html>