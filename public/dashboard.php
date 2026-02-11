<?php
session_start();

// Protect public (normal user) dashboard
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['role']) || $_SESSION['role'] !== 'public') {
    header('Location: ../index.html?error=' . urlencode('Please login as a normal user.'));
    exit;
}

$fullName   = $_SESSION['full_name'] ?? 'Staff Member';
$department = $_SESSION['department'] ?? 'Department';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Arusha City Hospital ICT Help Desk</title>
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

    <!-- User Navigation -->
    <nav class="main-nav">
        <div class="container nav-content">
            <div class="nav-left">
                <span class="nav-title"><i class="fas fa-desktop"></i> User Dashboard</span>
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
            <div class="sidebar-header">User Menu</div>

            <ul class="sidebar-menu">
                <li>
                    <a href="dashboard.php" class="active">
                        <i class="fas fa-desktop"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-plus-circle"></i>
                        <span>Create Ticket</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-list"></i>
                        <span>My Tickets</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-section-title">Support</div>
            <ul class="sidebar-menu">
                <li>
                    <a href="#">
                        <i class="fas fa-info-circle"></i>
                        <span>Help & Guidelines</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                Department: <?php echo htmlspecialchars($department); ?>
            </div>
        </aside>

        <main class="main-content">
            <div class="container">
                <section class="dashboard-intro">
                    <h2><i class="fas fa-headset"></i> ICT Help Desk</h2>
                    <p>Welcome, <?php echo htmlspecialchars($fullName); ?>. From here you can submit ICT support requests and track their status.</p>
                </section>

                <section class="dashboard-cards">
                    <div class="card-grid">
                        <div class="card">
                            <div class="card-body">
                                <h3><i class="fas fa-plus-circle"></i> Create New Ticket</h3>
                                <p>Report any ICT issue you are experiencing in your department.</p>
                                <p><em>The ticket creation form can be added and linked here.</em></p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h3><i class="fas fa-list"></i> My Tickets</h3>
                                <p>View the status and responses for tickets you have submitted.</p>
                                <p><em>A list of your tickets and details can be integrated here.</em></p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h3><i class="fas fa-info-circle"></i> Help & Guidelines</h3>
                                <p>Read ICT support guidelines and best practices.</p>
                                <p><em>Documentation and FAQs can be attached to this section.</em></p>
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