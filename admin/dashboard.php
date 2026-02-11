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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Arusha City Hospital ICT Help Desk</title>
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

    <!-- Main Content with Sidebar -->
    <div class="layout">
        <li><?php include 'includes/aside.php'; ?></li>

        <main class="main-content">
            <div class="container">
                <div class="dashboard-main-grid">
                    <!-- Left column: Overview and key stats -->
                    <div class="dashboard-column dashboard-column-primary">
                        <section class="dashboard-intro">
                            <h2><i class="fas fa-tachometer-alt"></i> Overview</h2>
                            <p>Welcome, <?php echo htmlspecialchars($fullName); ?>. Here is a quick overview of the Help Desk system.</p>
                        </section>

                        <section class="dashboard-cards">
                            <div class="card-grid">
                                <div class="card card-stat">
                                    <div class="card-icon card-icon-primary">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="card-body">
                                        <h3>Total Users</h3>
                                        <p class="card-number"><?php echo $totalUsers; ?></p>
                                        <p class="card-subtext">
                                            Active: <?php echo $activeUsers; ?> | Inactive: <?php echo $inactiveUsers; ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="card card-stat">
                                    <div class="card-icon card-icon-success">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <div class="card-body">
                                        <h3>Admins</h3>
                                        <p class="card-number"><?php echo $adminCount; ?></p>
                                        <p class="card-subtext">System administrators</p>
                                    </div>
                                </div>

                                <div class="card card-stat">
                                    <div class="card-icon card-icon-warning">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div class="card-body">
                                        <h3>Incharge Users</h3>
                                        <p class="card-number"><?php echo $inchargeCount; ?></p>
                                        <p class="card-subtext">Department in-charge accounts</p>
                                    </div>
                                </div>

                                <div class="card card-stat">
                                    <div class="card-icon card-icon-info">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="card-body">
                                        <h3>Normal Users</h3>
                                        <p class="card-number"><?php echo $publicCount; ?></p>
                                        <p class="card-subtext">Staff who can create tickets</p>
                                    </div>
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