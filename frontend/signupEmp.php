<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Sign Up | VeganCrate</title>
    <link rel="stylesheet" href="../assets/styles.css"> <!-- General styles -->
    <link rel="stylesheet" href="../assets/signup_employee.css"> <!-- Page-specific styles -->
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
            <h2>Employee Sign Up</h2>
            <form id="signupForm" action="../backend/signup_employee.php" method="POST">
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
                    <label for="job_title">Job Title</label>
                    <input type="text" id="job_title" name="job_title" placeholder="Enter your job title" required>
                </div>
                <div class="form-group">
                    <label for="department">Department</label>
                    <input type="text" id="department" name="department" placeholder="Enter your department" required>
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
