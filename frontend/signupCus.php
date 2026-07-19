<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Sign Up | VeganCrate</title>
    <link rel="stylesheet" href="../assets/styles.css"> <!-- General styles -->
    <link rel="stylesheet" href="../assets/signup_customer.css"> <!-- Page-specific styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Ensure error messages are red */
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            display: block;
        }

        /* Success message styling */
        .success-message {
            color: green;
            font-size: 16px;
            margin-top: 15px;
            display: block;
            text-align: center;
        }

        /* Add spacing below the input fields for better visibility */
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-top">
            <div class="logo">🌱 VeganCrate</div>
        </div>
    </header>

    <main class="signup-container">
        <div class="signup-section">
            <h2>Customer Sign Up</h2>
            <form id="signupForm">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required>
                    <small class="error-message" id="nameError"></small>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    <small class="error-message" id="emailError"></small>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <small class="error-message" id="passwordError"></small>
                </div>
                <button type="submit" class="signup-button">Sign Up</button>
                <small class="success-message" id="successMessage"></small>
            </form>
            <p class="login-link">Already have an account? <a href="login.php">Login</a></p>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <p>&copy; 2025 VeganCrate. All Rights Reserved</p>
        </div>
    </footer>

    <script>
        document.getElementById('signupForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            // Reset previous error messages and success message
            document.querySelectorAll('.error-message').forEach(error => error.textContent = '');
            document.getElementById('successMessage').textContent = '';

            // Input values
            const full_name = document.getElementById('full_name').value.trim(); // Changed from name to full_name
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            let isValid = true;

            // Full Name validation
            if (!full_name || full_name.length < 3) {
                document.getElementById('nameError').textContent = 'Full name must be at least 3 characters.';
                isValid = false;
            }

            // Email validation (Basic regex)
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                document.getElementById('emailError').textContent = 'Invalid email format.';
                isValid = false;
            }

            // Password validation (Minimum length 6)
            if (password.length < 6) {
                document.getElementById('passwordError').textContent = 'Password must be at least 6 characters.';
                isValid = false;
            }

            // Stop submission if validation fails
            if (!isValid) return;

            // Prepare data for API
            const formData = {
                full_name, // Changed from name to full_name
                email,
                password
            };

            try {
                const response = await fetch('../backend/signup_customer.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success) {
                    document.getElementById('successMessage').textContent = result.success; // Display success message
                    document.getElementById('signupForm').reset(); // Clear the form
                    setTimeout(() => {
                        window.location.href = "login.php"; // Redirect after success
                    }, 2000);
                } else if (result.error) {
                    // Display backend error in red
                    document.getElementById('emailError').textContent = result.error;
                } else {
                    document.getElementById('emailError').textContent = 'An unknown error occurred.';
                }
            } catch (error) {
                console.error('Error during sign-up:', error);
                document.getElementById('emailError').textContent = 'Something went wrong. Please try again.';
            }
        });
    </script>
</body>
</html>
