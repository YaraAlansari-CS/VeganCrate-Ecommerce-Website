<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | VeganCrate</title>
    <link rel="stylesheet" href="../assets/styles.css"> <!-- General styles -->
    <link rel="stylesheet" href="../assets/signup.css"> <!-- Page-specific styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-top">
            <div class="logo">🌱 VeganCrate</div>
        </div>
    </header>

    <main class="signup-container">
        <div class="signup-section">
            <h2>Create Your Account</h2>
            <form id="signupForm" action="../backend/signup.php" method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="form-group">
                    <label for="role">Select Role</label>
                    <select id="role" name="role" required>
                        <option value="customer">Customer</option>
                        <option value="vendor">Vendor</option>
                        <option value="employee">Employee</option>
                    </select>
                </div>
                <button type="submit" class="signup-button">Sign Up</button>
            </form>
            <p class="login-link">Already have an account? <a href="login.php">Login</a></p>
        </div>
    </main>

    <footer>
    <div class="footer-content">
        <p>&copy; 2025 VeganCrate. All Rights Reserved</p>
    </div>
    </footer>
</body>
</html>