<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendor Sign Up | VeganCrate</title>
  <link rel="stylesheet" href="../assets/styles.css"> <!-- General styles -->
  <!-- Update the CSS file if needed for vendor-specific styling -->
  <link rel="stylesheet" href="../assets/signup_vendor.css"> <!-- Page-specific styles -->
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
      <h2>Vendor Sign Up</h2>
      <form id="signupForm">
        <!-- Full Name -->
        <div class="form-group">
          <label for="full_name">Full Name</label>
          <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required>
        </div>
        <!-- Email -->
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <!-- Password -->
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <!-- Business Name -->
        <div class="form-group">
          <label for="business_name">Business Name</label>
          <input type="text" id="business_name" name="business_name" placeholder="Enter your business name" required>
        </div>
        <!-- Business Description -->
        <div class="form-group">
          <label for="business_description">Business Description</label>
          <textarea id="business_description" name="business_description" placeholder="Enter your business description" required></textarea>
        </div>
        <!-- Submit Button -->
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

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const signupForm = document.getElementById("signupForm");

      signupForm.addEventListener("submit", function(e) {
        e.preventDefault();

        // Retrieve and trim input values
        const fullName = document.getElementById("full_name").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;
        const businessName = document.getElementById("business_name").value.trim();
        const businessDescription = document.getElementById("business_description").value.trim();

        // Input validation
        if (fullName.length < 3) {
          alert("Full Name must be at least 3 characters long.");
          return;
        }
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
          alert("Please enter a valid email address.");
          return;
        }
        if (password.length < 6) {
          alert("Password must be at least 6 characters long.");
          return;
        }
        if (businessName.length < 3) {
          alert("Business Name must be at least 3 characters long.");
          return;
        }
        if (businessDescription.length < 10) { // Adjust minimum length as needed
          alert("Business Description must be at least 10 characters long.");
          return;
        }

        // Prepare JSON payload matching the backend's expected format
        const payload = {
          full_name: fullName,
          email: email,
          password: password,
          business_name: businessName,
          business_description: businessDescription
        };

        // Send data to backend (ensure the endpoint matches your PHP file)
        fetch("../backend/signup_vendor.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload)
        })
        .then(response => {
          if (!response.ok) {
            throw new Error("Network response was not ok");
          }
          return response.json();
        })
        .then(data => {
          console.log(data); // Debugging purposes
          if (data.success) {
            alert(data.success);
            signupForm.reset();
            window.location.href = "login.php"; // Redirect to login page on success
          } else {
            alert(data.error);
          }
        })
        .catch(error => {
          console.error("Error during sign-up:", error);
          alert("An error occurred. Please try again later.");
        });
      });
    });
  </script>
</body>
</html>
