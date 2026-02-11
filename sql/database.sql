CREATE DATABASE IF NOT EXISTS helpdesk;
USE helpdesk;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    phone_number VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    department VARCHAR(100),
    role ENUM('admin', 'technician', 'staff') DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    INDEX idx_phone (phone_number),
    INDEX idx_role (role)
);

-- Departments table
CREATE TABLE departments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    department_name VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default departments
INSERT INTO departments (department_name) VALUES 
('Administration'),
('ICT Department'),
('Medical Records'),
('Pharmacy'),
('Laboratory'),
('Radiology'),
('Finance'),
('Human Resources'),
('Outpatient Department'),
('Inpatient Department'),
('Emergency');

-- Create default admin user (phone: 255700000000, password: Admin@2024)
INSERT INTO users (phone_number, password, full_name, email, department, role) 
VALUES (
    '255700000000', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'System Administrator',
    'admin@arushahospital.go.tz',
    'ICT Department',
    'admin'
);