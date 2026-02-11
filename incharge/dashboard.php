<?php
session_start();

// Protect incharge dashboard
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['role']) || $_SESSION['role'] !== 'incharge') {
    header('Location: ../index.html?error=' . urlencode('Please login as a department incharge.'));
    exit;
}

require_once '../includes/config.php';

$fullName   = $_SESSION['full_name'] ?? 'Incharge User';
$department = $_SESSION['department'] ?? 'Department';

// Basic statistics for the incharge's department
$totalDeptUsers    = 0;
$activeDeptUsers   = 0;
$inactiveDeptUsers = 0;

try {
    $conn = getDBConnection();
    $dept = $conn->real_escape_string($department);

    $sql = "
        SELECT 
            COUNT(*) AS total,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) AS active,
            SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) AS inactive
        FROM users
        WHERE department = '{$dept}'
    ";

    if ($result = $conn->query($sql)) {
        $row = $result->fetch_assoc();
        $totalDeptUsers    = (int)($row['total'] ?? 0);
        $activeDeptUsers   = (int)($row['active'] ?? 0);
        $inactiveDeptUsers = (int)($row['inactive'] ?? 0);
    }

    $conn->close();
} catch (Exception $e) {
    // Silent fail for stats
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incharge Dashboard - Arusha City Hospital ICT Help Desk</title>
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

    <!-- Incharge Navigation -->
    <nav class="main-nav">
        <div class="container nav-content">
            <div class="nav-left">
                <span class="nav-title"><i class="fas fa-user-tie"></i> Department Incharge Dashboard</span>
            </div>
            <div class="nav-right">
                <span class="nav-user">
                    <i class="fas fa-user-circle"></i>
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
        <aside class="sidebar">
            <div class="sidebar-header">Department Menu</div>

            <ul class="sidebar-menu">
                <li>
                    <a href="dashboard.php" class="active">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Overview</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-ticket-alt"></i>
                        <span>Department Tickets</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-check-circle"></i>
                        <span>Approvals</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-user-friends"></i>
                        <span>Team Members</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-chart-bar"></i>
                        <span>Department Reports</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-section-title">Coordination</div>
            <ul class="sidebar-menu">
                <li>
                    <a href="#">
                        <i class="fas fa-headset"></i>
                        <span>Contact ICT</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                Incharge: <?php echo htmlspecialchars($department); ?>
            </div>
        </aside>

        <main class="main-content">
            <div class="container">
                <section class="dashboard-intro">
                    <h2><i class="fas fa-tachometer-alt"></i> Department Overview</h2>
                    <p>Welcome, <?php echo htmlspecialchars($fullName); ?>. Here is an overview of users in the <?php echo htmlspecialchars($department); ?>.</p>
                </section>

                <section class="dashboard-cards">
                    <div class="card-grid">
                        <div class="card card-stat">
                            <div class="card-icon card-icon-primary">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-body">
                                <h3>Department Users</h3>
                                <p class="card-number"><?php echo $totalDeptUsers; ?></p>
                                <p class="card-subtext">
                                    Active: <?php echo $activeDeptUsers; ?> | Inactive: <?php echo $inactiveDeptUsers; ?>
                                </p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h3><i class="fas fa-headset"></i> Help Desk Tickets</h3>
                                <p>Monitor and approve help desk tickets originating from your department.</p>
                                <p><em>Ticket list and approval actions can be added here later.</em></p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h3><i class="fas fa-user-cog"></i> Department Management</h3>
                                <p>Coordinate with ICT team and manage requests for your department.</p>
                                <p><em>Department-level settings and reports can be linked here.</em></p>
                            </div>
                        </div>
                    </div>
                </section>
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