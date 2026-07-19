<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="../assets/styles.css"> <!-- تحميل الأنماط العامة -->
    <link rel="stylesheet" href="../assets/product_detailsCus.css"> <!-- تحميل أنماط صفحة التفاصيل -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>

    <!-- تضمين الهيدر -->
    <?php include('../includes/header.php'); ?>

    <?php
    // جلب بيانات المنتج (مثال بيانات وهمية، عدلها حسب قاعدة بياناتك)
    $product = [
        "name" => "Green Apple",
        "image" => "Images/D3.png",
        "price" => "14.99 SAR",
        "store-name" => "Organic Farms",
        "description" => "Fresh and organic green apple, rich in vitamins and antioxidants.",
        "category" => "Fruits"
    ];
    ?>

    <main class="product-details-container">
        <div class="product-details">
            <!-- صورة المنتج -->
            <div class="product-image">
                <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
            </div>

            <!-- تفاصيل المنتج -->
            <div class="product-info">
                <h1 class="product-name"><?= $product['name'] ?></h1>
                <p class="product-price"><?= $product['price'] ?></p>
                <hr>
                <p class="store-name"><strong>Store Name:</strong> <?= $product['store-name'] ?></p>
                <p class="product-description"><strong>Description:</strong> <?= $product['description'] ?></p>
                <p class="product-category"><strong>Category:</strong> <?= $product['category'] ?></p>

                <!-- اختيار الكمية -->
                <div class="quantity-selector">
                    <button class="quantity-btn" onclick="decreaseQuantity()">-</button>
                    <input type="number" id="quantity" value="1" min="1">
                    <button class="quantity-btn" onclick="increaseQuantity()">+</button>
                </div>

                <!-- زر الإضافة للسلة -->
                <button class="add-to-cart">
                    <i class="fa-solid fa-cart-plus"></i> Add to Cart
                </button>
            </div>
        </div>
    </main>

    <!-- تضمين الفوتر -->
    <?php include('../includes/footer.php'); ?>

    <script>
        function decreaseQuantity() {
            let qty = document.getElementById('quantity');
            if (qty.value > 1) qty.value--;
        }

        function increaseQuantity() {
            let qty = document.getElementById('quantity');
            qty.value++;
        }
    </script>

</body>

</html>
