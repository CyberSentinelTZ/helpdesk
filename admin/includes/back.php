<?php
session_start();

// Protect admin dashboard
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.html?error=' . urlencode('Please login as an administrator.'));
    exit;
}

require_once '../includes/config.php';

$fullName   = $_SESSION['full_name'] ?? 'Administrator';
$department = $_SESSION['department'] ?? 'ICT Department';

// Basic system statistics for the dashboard
$totalUsers     = 0;
$activeUsers    = 0;
$inactiveUsers  = 0;
$adminCount     = 0;
$inchargeCount  = 0;
$publicCount    = 0;

try {
    $conn = getDBConnection();

    // Overall user status stats
    $statsSql = "
        SELECT 
            COUNT(*) AS total,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) AS active,
            SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) AS inactive
        FROM users
    ";
    if ($result = $conn->query($statsSql)) {
        $row = $result->fetch_assoc();
        $totalUsers    = (int)($row['total'] ?? 0);
        $activeUsers   = (int)($row['active'] ?? 0);
        $inactiveUsers = (int)($row['inactive'] ?? 0);
    }

    // Users per role
    $rolesSql = "SELECT role, COUNT(*) AS count FROM users GROUP BY role";
    if ($result = $conn->query($rolesSql)) {
        while ($row = $result->fetch_assoc()) {
            switch ($row['role']) {
                case 'admin':
                    $adminCount = (int)$row['count'];
                    break;
                case 'incharge':
                    $inchargeCount = (int)$row['count'];
                    break;
                default:
                    $publicCount += (int)$row['count'];
                    break;
            }
        }
    }

    $conn->close();
} catch (Exception $e) {
    // Fail silently for dashboard if stats cannot be loaded
}
?>