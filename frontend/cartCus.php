<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shopping Cart</title>
    <link rel="stylesheet" href="../assets/styles.css"> 
    <link rel="stylesheet" href="../assets/cartCus.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>

    <!-- Include Header -->
    <?php include('../includes/header.php'); ?>

    <main class="cart-container">
        <h2>My Shopping Cart</h2>

        <div class="cart-content">
            <!-- ✅ Product Table -->
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $subtotal = 0; // Initialize total price     
                    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $product_id => $product) {
                            $total_price = $product['price'] * $product['quantity'];
                            $subtotal += $total_price;
                            ?>
                            <tr>
                                <td class="product-info">
                                    <img src="<?php echo '../' . $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" width="80">
                                    <p><?php echo htmlspecialchars($product['name']); ?></p>
                                </td>
                                <td><?php echo number_format($product['price'], 2); ?> SAR</td>
                                <td>
                                    <form action="../backend/update_cart.php" method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                        <button type="submit" name="decrease" class="quantity-btn">-</button>
                                        <input type="text" name="quantity" value="<?php echo $product['quantity']; ?>" class="quantity-input" readonly>
                                        <button type="submit" name="increase" class="quantity-btn">+</button>
                                    </form>
                                </td>
                                <td><?php echo number_format($total_price, 2); ?> SAR</td>
                                <td>
                                    <form action="../backend/remove_from_cart.php" method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                        <button type="submit" class="remove-btn"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align: center;'>Your cart is empty.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- ✅ Order Summary -->
            <div class="cart-summary">
                <h3>Order Summary</h3>
                <p><strong>Subtotal:</strong> <?php echo number_format($subtotal, 2); ?> SAR</p>
                <hr>
                <p class="total"><strong>Total:</strong> <?php echo number_format($subtotal, 2); ?> SAR</p>
                <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
            </div>

        </div>
    </main>

    <!-- Include Footer -->
    <?php include('../includes/footer.php'); ?>

</body>

</html>
