<?php
session_start();

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    if (isset($_SESSION['cart'][$product_id])) {
        if (isset($_POST['increase'])) {
            $_SESSION['cart'][$product_id]['quantity'] += 1;
        } elseif (isset($_POST['decrease'])) {
            if ($_SESSION['cart'][$product_id]['quantity'] > 1) {
                $_SESSION['cart'][$product_id]['quantity'] -= 1;
            } else {
                unset($_SESSION['cart'][$product_id]); // Remove item if quantity reaches 0
            }
        }
    }
}

header("Location: ../frontend/cartCus.php");
exit();
?>
