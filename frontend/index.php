<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to VeganCrate</title>
    <link rel="stylesheet" href="../assets/styles.css"> <!-- General styles -->
    <link rel="stylesheet" href="../assets/welcome.css"> <!-- Page-specific styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <header>
    <div class="header-top">
        <div class="logo">🌱 VeganCrate</div>
    </div>
    </header>

    
    <main class="home-container">
        <section class="hero">
            <div class="hero-content">
                <h3>Welcome to VeganCrate</h3>
                <p>Select your role to proceed</p>
            </div>
        </section>

        <section class="user-selection">
            <h2>Choose Your Role</h2>
            <div class="role-list">
                <a href="login.php?role=admin" class="role-button">Admin</a>
                <a href="signupEmp.php?role=employee" class="role-button">Employee</a>
                <a href="signupVen.php?role=vendor" class="role-button">Vendor</a>
                <a href="signupCus.php?role=customer" class="role-button">Customer</a>
            </div>
        </section>
    </main>
    
    <!-- Include Footer -->
    <?php include('../includes/footer.php'); ?>
</body>
</html>
