<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../assets/styles.css"> 
    <link rel="stylesheet" href="../assets/checkout.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <?php include('../includes/header.php'); ?>

    <main class="checkout-container">
        <h2>Checkout</h2>

        <div class="checkout-content">
            <div class="checkout-details">
                <h3>Order Details</h3>
                <table class="checkout-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $subtotal = 0;
                        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $product_id => $product) {
                                $total_price = $product['price'] * $product['quantity'];
                                $subtotal += $total_price;
                                ?>
                                <tr>
                                    <td class="product-info">
                                        <img src="<?php echo '../' . $product['image_url']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="80">
                                        <p><?php echo htmlspecialchars($product['name']); ?></p>
                                    </td>
                                    <td><?php echo number_format($product['price'], 2); ?> SAR</td>
                                    <td><?php echo $product['quantity']; ?></td>
                                    <td><?php echo number_format($total_price, 2); ?> SAR</td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='4' style='text-align: center;'>Your cart is empty.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                
                <h3>Your Address:</h3>
                <p id="customer-address">Loading address...</p>
                <button id="add-address-btn" style="display: none;" onclick="window.location.href='../frontend/add_address_Cus.php'">Add Address</button>

                <label for="shipping">Select Shipping Method:</label>
                <select id="shipping" class="checkout-input">
                    <option value="" disabled selected>Loading...</option>
                </select>

                <label for="payment">Select Payment Method:</label>
                <select id="payment" class="checkout-input">
                    <option value="" disabled selected>Loading...</option>
                </select>

                <div id="card-section" style="display: none;">
                    <label for="saved-card">Select Card:</label>
                    <select id="saved-card" class="checkout-input">
                        <option value="" disabled selected>No cards available</option>
                    </select>
                    <button id="add-card-btn" class="btn">Add New Card</button>
                </div>

                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <p><strong>Subtotal:</strong> <span id="subtotal"><?php echo number_format($subtotal, 2); ?></span> SAR</p>
                    <p><strong>Shipping:</strong> <span id="shipping-price">0</span> SAR</p>
                    <hr>
                    <p class="total"><strong>Total:</strong> <span id="total"><?php echo number_format($subtotal, 2); ?></span> SAR</p>
                    <!-- Place Order button -->
                    <button id="place-order-btn" class="btn">Place Order</button>
                </div>
            </div>
        </div>
    </main>
    
    <?php include('../includes/footer.php'); ?>

    <script>
        // Make the user_id available in JavaScript from the PHP session
        var userId = <?php echo $_SESSION['user_id']; ?>;

        // Fetch payment methods and populate the dropdown
        function fetchPaymentMethods() {
            fetch('../backend/get_payment_methods.php')
                .then(response => response.json())
                .then(data => {
                    let paymentSelect = document.getElementById('payment');
                    paymentSelect.innerHTML = "<option value='' disabled selected>Select a payment method</option>";

                    if (data.length > 0) {
                        data.forEach(method => {
                            let option = document.createElement('option');
                            option.value = method.name.toLowerCase();
                            option.textContent = method.name;
                            paymentSelect.appendChild(option);
                        });
                    } else {
                        paymentSelect.innerHTML = '<option value="">No payment methods available</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching payment methods:', error);
                });
        }

        // Fetch saved cards for the customer
        function fetchMyCards() {
            fetch('../backend/get_my_cards.php?user_id=' + userId)
                .then(response => response.json())
                .then(data => {
                    let savedCardSelect = document.getElementById('saved-card');
                    savedCardSelect.innerHTML = ""; // Clear previous options

                    if (data.error) {
                        let option = document.createElement('option');
                        option.value = "";
                        option.textContent = data.error;
                        savedCardSelect.appendChild(option);
                    } else if (data.message) {
                        let option = document.createElement('option');
                        option.value = "";
                        option.textContent = data.message;
                        savedCardSelect.appendChild(option);
                    } else if (Array.isArray(data)) {
                        if (data.length === 0) {
                            let option = document.createElement('option');
                            option.value = "";
                            option.textContent = "No saved cards found.";
                            savedCardSelect.appendChild(option);
                        } else {
                            data.forEach(card => {
                                let option = document.createElement('option');
                                option.value = card.card_id;
                                option.textContent = "**** **** **** " + card.card_number + " (Exp: " + card.expiry_date + ")";
                                savedCardSelect.appendChild(option);
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching saved cards:', error);
                });
        }

        // Show/hide the card section when the payment method changes
        document.getElementById('payment').addEventListener('change', function () {
            const cardSection = document.getElementById('card-section');
            if (this.value === 'card') {
                cardSection.style.display = 'block';
                fetchMyCards();
            } else {
                cardSection.style.display = 'none';
            }
        });

        // Fetch shipping companies and populate the dropdown
        function fetchShippingCompanies() {
            fetch('../backend/get_shipping_companies.php')
                .then(response => response.json())
                .then(data => {
                    let shippingSelect = document.getElementById('shipping');
                    shippingSelect.innerHTML = "";

                    if (data.length > 0) {
                        data.forEach(company => {
                            let option = document.createElement('option');
                            option.value = company.name;
                            option.setAttribute('data-id', company.company_id);
                            option.setAttribute('data-price', company.shipping_price);
                            option.textContent = company.name + " (" + company.shipping_price + " SAR)";
                            shippingSelect.appendChild(option);
                        });
                        shippingSelect.dispatchEvent(new Event('change'));
                    } else {
                        shippingSelect.innerHTML = '<option value="">No shipping companies available</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching shipping companies:', error);
                });
        }

        // Fetch customer address and display it
        function fetchCustomerAddress() {
            fetch('../backend/get_cus_address.php')
                .then(response => response.json())
                .then(data => {
                    let addressElement = document.getElementById('customer-address');
                    let addButton = document.getElementById('add-address-btn');

                    if (data.error) {
                        addressElement.textContent = "No address found.";
                        addButton.style.display = "block";
                    } else {
                        addressElement.textContent = data.country + ", " + data.city + ", " + data.street + ", " + data.zip_code;
                    }
                })
                .catch(error => {
                    console.error('Error fetching customer address:', error);
                });
        }

        // Update the order summary when the shipping method is selected
        document.getElementById('shipping').addEventListener('change', function () {
            let selectedOption = this.options[this.selectedIndex];
            let shippingCost = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            document.getElementById('shipping-price').textContent = shippingCost + " SAR";
            let subtotal = parseFloat(document.getElementById('subtotal').textContent);
            document.getElementById('total').textContent = (subtotal + shippingCost).toFixed(2);
        });

        // Redirect to add new card page when the add card button is clicked
        document.getElementById('add-card-btn').addEventListener('click', function () {
            window.location.href = 'add_new_cus_card.php';
        });

        // Event listener for the Place Order button
        document.getElementById('place-order-btn').addEventListener('click', function () {
            // Get the total price as a number
            const totalPrice = parseFloat(document.getElementById('total').textContent);
            
            // Get the selected shipping option and its shipping company id
            const shippingSelect = document.getElementById('shipping');
            const shippingOption = shippingSelect.options[shippingSelect.selectedIndex];
            const shippingCompanyId = shippingOption.getAttribute('data-id');

            // Get the selected payment method
            const paymentMethod = document.getElementById('payment').value;

            // Get the card_id only if payment method is 'card'; otherwise, set it to null
            let cardId = null;
            if (paymentMethod === 'card') {
                cardId = document.getElementById('saved-card').value || null;
            }

            // Create the data object to send to the backend
            const orderData = {
                total_price: totalPrice,
                shipping_company_id: shippingCompanyId,
                payment_method: paymentMethod,
                card_id: cardId
            };

            // Send the data as JSON to the backend using a POST request
            fetch('../backend/place_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Display the success message from the backend
                    alert(data.message);
                    // Redirect after a short delay, sending the order_id in the URL
                    setTimeout(() => {
                        window.location.href = "../frontend/detailsOrd.php?order_id=" + data.order_id;
                    }, 2000); // 2-second delay
                } else {
                    console.error('Order placement failed:', data);
                    alert('There was an error placing your order. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error placing order:', error);
                alert('An unexpected error occurred. Please try again.');
            });
        });

        // Initialize the page once the DOM content has loaded
        document.addEventListener('DOMContentLoaded', () => {
            fetchShippingCompanies();
            fetchCustomerAddress();
            fetchPaymentMethods();
        });
    </script>
</body>
</html>



