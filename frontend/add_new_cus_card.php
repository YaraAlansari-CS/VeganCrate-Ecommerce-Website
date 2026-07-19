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
    <title>Add New Card | VeganCrate</title>
    <!-- General styles -->
    <link rel="stylesheet" href="../assets/styles.css">
    <!-- Page-specific styles -->
    <link rel="stylesheet" href="../assets/add_card_Cus.css">
    <!-- Font Awesome (optional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script>
        // JavaScript function to handle form submission and perform strict client-side validation
        async function handleCardFormSubmit(event) {
            event.preventDefault(); // Prevent the default form submission

            // Retrieve form values
            const cardNumber = document.getElementById('card_number').value.trim();
            const expiryDate = document.getElementById('expiry_date').value.trim();
            const cvv = document.getElementById('cvv').value.trim();
            const cardholderName = document.getElementById('cardholder_name').value.trim();
            const userId = <?php echo json_encode($_SESSION['user_id']); ?>; // User ID from session

            // Regular expressions for validation:
            // Card Number: Exactly 16 digits
            const cardNumberRegex = /^\d{16}$/;
            // Expiry Date: Format MM/YY, where MM is 01 to 12
            const expiryRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
            // CVV: 3 or 4 digits
            const cvvRegex = /^\d{3,4}$/;
            // Cardholder Name: At least 2 characters (you can add more strict validations if needed)
            if (!cardNumberRegex.test(cardNumber)) {
                alert('Please enter a valid 16-digit card number (numbers only).');
                return;
            }
            if (!expiryRegex.test(expiryDate)) {
                alert('Please enter a valid expiry date in MM/YY format.');
                return;
            }
            if (!cvvRegex.test(cvv)) {
                alert('Please enter a valid CVV (3 or 4 digits).');
                return;
            }
            if (cardholderName.length < 2) {
                alert('Please enter a valid cardholder name.');
                return;
            }

            try {
                // Send data to the backend using fetch
                const response = await fetch('../backend/save_card_Cus.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        card_number: cardNumber,
                        expiry_date: expiryDate,
                        cvv: cvv,
                        cardholder_name: cardholderName
                    }),
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    alert('Card added successfully.');
                    window.history.back(); // Return to the previous page
                } else {
                    alert(result.message || 'Failed to add card. Please try again.');
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

    <main class="add-card-container">
        <div class="add-card-section">
            <h2>Add New Card</h2>
            <form id="addCardForm" onsubmit="handleCardFormSubmit(event)">
                <div class="form-group">
                    <label for="card_number">Card Number</label>
                    <input type="text" id="card_number" name="card_number" placeholder="Enter 16-digit card number" required>
                </div>
                <div class="form-group">
                    <label for="expiry_date">Expiry Date (MM/YY)</label>
                    <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" required>
                </div>
                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" placeholder="Enter CVV" required>
                </div>
                <div class="form-group">
                    <label for="cardholder_name">Cardholder Name</label>
                    <input type="text" id="cardholder_name" name="cardholder_name" placeholder="Enter cardholder name" required>
                </div>
                <button type="submit" class="save-button">Save</button>
            </form>
        </div>
    </main>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
