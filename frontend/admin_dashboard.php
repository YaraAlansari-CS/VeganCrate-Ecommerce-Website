<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | VeganCrate</title>
    <link rel="stylesheet" href="../assets/styles.css"> <!-- General styles -->
    <link rel="stylesheet" href="../assets/admin_dashboard.css"> <!-- Dashboard-specific styles -->
</head>
<body>
    <header>
        <div class="header-top">
            <div class="logo">🌱 VeganCrate Admin Dashboard</div>
            <nav>
                <ul class="nav-links">
                    <li><a href="../backend/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="dashboard-container">
        <!-- Dashboard Overview Section -->
        
        <!-- Management Tools Section -->
        <section class="management">
            <h2>Management Tools</h2>
            <ul>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_products.php">Manage Products</a></li>
                <li><a href="manage_orders.php">Manage Orders</a></li>
                <li><a href="manage_vendors.php">Manage Vendors</a></li>
                <li><a href="manage_complaints.php">Manage Complaints</a></li>
                <li><a href="manage_shippingCompanies.php">Manage Shipping Companies</a></li>
            </ul>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <p>&copy; 2025 VeganCrate. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
