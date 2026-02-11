<?php
// Database configuration for Arusha City Hospital Help Desk
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Change as per your setup
define('DB_PASS', ''); // Change as per your setup
define('DB_NAME', 'helpdesk');

// Create connection
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// Set timezone for Arusha, Tanzania
date_default_timezone_set('Africa/Dar_es_Salaam');
?>