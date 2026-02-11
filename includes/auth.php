<?php
session_start();
require_once 'config.php';

class Auth {
    private $conn;
    
    public function __construct() {
        $this->conn = getDBConnection();
    }
    
    // User login with phone number
    public function login($phone_number, $password) {
        // Clean phone number (remove spaces, dashes)
        $phone_number = preg_replace('/[^0-9]/', '', $phone_number);
        
        // Add Tanzania country code if not present
        if (!preg_match('/^255/', $phone_number)) {
            if (preg_match('/^0/', $phone_number)) {
                $phone_number = '255' . substr($phone_number, 1);
            } else {
                $phone_number = '255' . $phone_number;
            }
        }
        
        $stmt = $this->conn->prepare("SELECT id, phone_number, password, full_name, role, department, is_active FROM users WHERE phone_number = ?");
        $stmt->bind_param("s", $phone_number);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Check if user is active
            if (!$user['is_active']) {
                return ['success' => false, 'message' => 'Account is deactivated. Contact administrator.'];
            }
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Update last login
                $update_stmt = $this->conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $update_stmt->bind_param("i", $user['id']);
                $update_stmt->execute();
                
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['phone_number'] = $user['phone_number'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['department'] = $user['department'];
                $_SESSION['logged_in'] = true;
                
                // Redirect based on role
                $redirect = 'public/dashboard.php'; // default for normal/public users
                if ($user['role'] === 'admin') {
                    $redirect = 'admin/dashboard.php';
                } elseif ($user['role'] === 'incharge') {
                    $redirect = 'incharge/dashboard.php';
                }
                
                return ['success' => true, 'redirect' => $redirect];
            }
        }
        
        return ['success' => false, 'message' => 'Invalid phone number or password'];
    }
    
    // Register new user (admin only)
    public function register($data) {
        // Check if current user is admin
        if ($_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Only administrators can register users'];
        }
        
        // Validate required fields
        $required = ['phone_number', 'password', 'full_name', 'department', 'role'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'message' => "All fields are required"];
            }
        }
        
        // Format phone number
        $phone_number = preg_replace('/[^0-9]/', '', $data['phone_number']);
        if (!preg_match('/^255/', $phone_number)) {
            if (preg_match('/^0/', $phone_number)) {
                $phone_number = '255' . substr($phone_number, 1);
            } else {
                $phone_number = '255' . $phone_number;
            }
        }
        
        // Check if phone already exists
        $check_stmt = $this->conn->prepare("SELECT id FROM users WHERE phone_number = ?");
        $check_stmt->bind_param("s", $phone_number);
        $check_stmt->execute();
        
        if ($check_stmt->get_result()->num_rows > 0) {
            return ['success' => false, 'message' => 'Phone number already registered'];
        }
        
        // Hash password
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Insert new user
        $stmt = $this->conn->prepare("INSERT INTO users (phone_number, password, full_name, email, department, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", 
            $phone_number,
            $hashed_password,
            $data['full_name'],
            $data['email'],
            $data['department'],
            $data['role']
        );
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'User registered successfully'];
        } else {
            return ['success' => false, 'message' => 'Registration failed: ' . $stmt->error];
        }
    }
    
    // Get all departments
    public function getDepartments() {
        $result = $this->conn->query("SELECT department_name FROM departments ORDER BY department_name");
        $departments = [];
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row['department_name'];
        }
        return $departments;
    }
    
    // Check if user is logged in
    public static function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    // Check if user is admin
    public static function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
    
    // Logout user
    public function logout() {
        session_destroy();
        return ['success' => true, 'redirect' => 'index.html'];
    }
}
?>