<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | VeganCrate</title>
  <link rel="stylesheet" href="../assets/styles.css"> <!-- General styles -->
  <link rel="stylesheet" href="../assets/login.css"> <!-- Page-specific styles -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    .error-message {
      color: red;
      font-size: 14px;
      margin-top: 5px;
      display: block;
    }
  </style>
</head>
<body>
  <header>
    <div class="header-top">
      <div class="logo">🌱 VeganCrate</div>
    </div>
  </header>

  <main class="login-container">
    <div class="login-section">
      <h2>Login to Your Account</h2>
      <!-- Container for displaying login error messages from the backend -->
      <p id="loginError" style="color: red;"></p>

      <!-- The form no longer uses the standard submission method -->
      <form id="loginForm">
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
        <button type="submit" class="login-button">Login</button>
      </form>
    </div>
  </main>

  <!-- Include Footer -->
  <?php include('../includes/footer.php'); ?>

  <script>
    document.getElementById('loginForm').addEventListener('submit', async function(event) {
      event.preventDefault(); // Prevent the default form submission

      // Clear previous error messages
      document.getElementById('emailError').textContent = "";
      document.getElementById('passwordError').textContent = "";
      document.getElementById('loginError').textContent = "";

      let hasError = false;
      const emailInput = document.getElementById('email');
      const passwordInput = document.getElementById('password');
      const emailValue = emailInput.value.trim();
      const passwordValue = passwordInput.value.trim();

      // Regex for basic email validation
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!emailValue) {
        document.getElementById('emailError').textContent = "Email is required.";
        hasError = true;
      } else if (!emailRegex.test(emailValue)) {
        document.getElementById('emailError').textContent = "Please enter a valid email address.";
        hasError = true;
      }

      if (!passwordValue) {
        document.getElementById('passwordError').textContent = "Password is required.";
        hasError = true;
      } else if (passwordValue.length < 6) {
        document.getElementById('passwordError').textContent = "Password must be at least 6 characters.";
        hasError = true;
      }

      // Stop if there are validation errors
      if (hasError) {
        return;
      }

      // Prepare JSON payload with user input data
      const payload = {
        email: emailValue,
        password: passwordValue
      };

      try {
        // Send the JSON payload to the backend using the Fetch API
        const response = await fetch('../backend/login_api.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(payload)
        });

        // Parse the JSON response from the backend
        const result = await response.json();

        if (result.success) {
          // Redirect the user based on their role received in the JSON response
          switch (result.role) {
            case 'admin':
              window.location.href = '../frontend/admin_dashboard.php';
              break;
            case 'employee':
              window.location.href = '../frontend/employee_dashboard.php';
              break;
            case 'vendor':
              window.location.href = '../frontend/vendor_dashboard.php';
              break;
            case 'customer':
              window.location.href = '../frontend/homeCus.php';
              break;
            default:
              document.getElementById('loginError').textContent = 'Invalid user role.';
          }
        } else {
          // Display error message returned by the backend
          document.getElementById('loginError').textContent = result.message || 'Login failed.';
        }
      } catch (error) {
        console.error('Error during login:', error);
        document.getElementById('loginError').textContent = 'An error occurred. Please try again later.';
      }
    });
  </script>
</body>
</html>
