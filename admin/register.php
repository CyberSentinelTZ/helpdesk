<?php
session_start();

// Protect user management (admin only)
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.html?error=' . urlencode('Please login as an administrator.'));
    exit;
}

require_once '../includes/config.php';

$fullName   = $_SESSION['full_name'] ?? 'Administrator';
$department = $_SESSION['department'] ?? 'ICT Department';

$conn = getDBConnection();
$message = null;

// Handle activate/deactivate actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['user_id'])) {
    $userId = (int)$_POST['user_id'];

    if ($_POST['action'] === 'toggle_active') {
        $stmt = $conn->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
        $stmt->bind_param('i', $userId);
        if ($stmt->execute()) {
            $message = 'User status updated successfully.';
        } else {
            $message = 'Failed to update user status.';
        }
        $stmt->close();
    }
}

// Fetch all users
$users = [];
$result = $conn->query("SELECT id, full_name, phone_number, email, department, role, is_active, last_login, created_at FROM users ORDER BY created_at DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Arusha City Hospital ICT Help Desk</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🏥</text></svg>">
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
                <span class="nav-title"><i class="fas fa-users-cog"></i> User Management - Registration</span>
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

    <!-- Main Content -->
    <div class="layout">
    <li><?php include 'includes/aside.php'; ?></li>

        <main class="main-content">
            <div class="container">
                <div class="dashboard-main-grid">
                    <div class="dashboard-column dashboard-column-primary">
                        <section class="dashboard-intro">
                            <h2><i class="fas fa-user-plus"></i> User Registration</h2>
                            <p>Register a new user for the system.</p>
                        </section>

                        <?php if ($message): ?>
                            <div class="alert alert-success" style="display:block;">
                                <?php echo htmlspecialchars($message); ?>
                            </div>
                        <?php endif; ?>

                        <section class="user-management-section">
                            <div class="user-management-header">
                                <div>
                                    <h3><i class="fas fa-users"></i> Registration Form</h3>
                                    <p class="user-management-subtitle">Current Total Users: <?php echo count($users); ?></p>
                                </div>
                                <div class="user-management-actions">
                                    <a href="register.php" class="btn btn-small">
                                        <i class="fas fa-user-plus"></i> Register New User
                                    </a>
                                </div>
                            </div>
                        </section>
                    </div>
                    <?php include 'includes/quick.php'; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script>
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
    </script>
</body>
</html>

