<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // Redirect to login if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Address | VeganCrate</title>
    <link rel="stylesheet" href="../assets/styles.css"> <!-- General styles -->
    <link rel="stylesheet" href="../assets/add_address_Cus.css"> <!-- Page-specific styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script>
        // JavaScript function to handle form submission
        async function handleFormSubmit(event) {
            event.preventDefault(); // Prevent the default form submission

            // Retrieve the form data
            const street = document.getElementById('street').value.trim();
            const city = document.getElementById('city').value.trim();
            const country = document.getElementById('country').value.trim();
            const zipCode = document.getElementById('zip_code').value.trim();
            const userId = <?php echo json_encode($_SESSION['user_id']); ?>; // User ID from session
            
            // Simple validation
            if (!street || !city || !country || !zipCode) {
                alert('All fields are required.');
                return;
            }

            try {
                // Send data to the API
                const response = await fetch('../backend/save_address_Cus.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        user_id: userId, // Include user_id in the request
                        street,
                        city,
                        country,
                        zip_code: zipCode,
                    }),
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    // If success, display a success message and redirect
                    alert('Address added successfully.');
                    window.history.back(); // Go back to the previous page
                } else {
                    // If failure, display the error message
                    alert(result.message || 'Failed to add address. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An unexpected error occurred. Please try again.');
            }
        }
    </script>
</head>
<body>
<?php include('../includes/header.php'); ?>

    <main class="add-address-container">
        <div class="add-address-section">
            <h2>Add Address</h2>

            <form id="addAddressForm" onsubmit="handleFormSubmit(event)">
                <div class="form-group">
                    <label for="street">Street</label>
                    <input type="text" id="street" name="street" placeholder="Enter your street" required>
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" placeholder="Enter your city" required>
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" placeholder="Enter your country" required>
                </div>
                <div class="form-group">
                    <label for="zip_code">Zip Code</label>
                    <input type="text" id="zip_code" name="zip_code" placeholder="Enter your zip code" required>
                </div>
                <button type="submit" class="save-button">Save</button>
            </form>
        </div>
    </main>

    <!-- Include Footer -->
    <?php include('../includes/footer.php'); ?>
</body>
</html>


