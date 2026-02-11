<aside class="sidebar">
    <div class="sidebar-header">Navigation</div>

    <ul class="sidebar-menu">
        <li>
            <a href="dashboard.php">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="users.php">
                <i class="fas fa-users-cog"></i>
                <span>Users</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-building"></i>
                <span>Departments</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-ticket-alt"></i>
                <span>Templates</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-chart-line"></i>
                <span>Documents</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-chart-line"></i>
                <span>Reports & Analytics</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-cogs"></i>
                <span>System Settings</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-section-title">Administration</div>
    <ul class="sidebar-menu">
        <li>
            <a href="#">
                <i class="fas fa-user-shield"></i>
                <span>Roles & Permissions</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-clipboard-list"></i>
                <span>Audit Logs</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        Logged in as Admin<br>
        <strong><?php echo htmlspecialchars($department); ?></strong>
    </div>
</aside>

<script>
    // Function to set the active class based on the current URL
    function setActiveSidebarLink() {
        // Get the current page path (e.g., 'dashboard.php')
        const currentPath = window.location.pathname.split('/').pop();
        
        // Select all sidebar links
        const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
        
        // Remove 'active' class from all links
        sidebarLinks.forEach(link => link.classList.remove('active'));
        
        // Find the link with matching href and add 'active' class
        sidebarLinks.forEach(link => {
            const linkHref = link.getAttribute('href');
            if (linkHref === currentPath) {
                link.classList.add('active');
            }
        });
    }
    
    // Call the function when the page loads
    document.addEventListener('DOMContentLoaded', setActiveSidebarLink);
</script>