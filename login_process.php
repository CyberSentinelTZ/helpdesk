<?php
require_once 'includes/auth.php';

$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone_number = $_POST['phone_number'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $result = $auth->login($phone_number, $password);
    
    if ($result['success']) {
        header('Location: ' . $result['redirect']);
        exit;
    } else {
        // Redirect back to login with error message
        header('Location: index.html?error=' . urlencode($result['message']));
        exit;
    }
} else {
    header('Location: index.html');
    exit;
}
?>