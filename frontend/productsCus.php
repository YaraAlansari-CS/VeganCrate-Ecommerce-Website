<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <link rel="stylesheet" href="../assets/productsCus.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>

    <!-- Header -->
    <?php include('../includes/header.php'); ?>

    <!-- Product Content -->
    <main class="products-container">
        <section class="filters">
            <h2>Filter by</h2>

            <!-- Categories -->
            <div class="categories">
                <h3>Categories</h3>
                <label>
                    <input type="checkbox" name="category" value="Protein"> Protein
                </label><br>
                <label>
                    <input type="checkbox" name="category" value="Dried Fruits"> Dried Fruits
                </label><br>
                <label>
                    <input type="checkbox" name="category" value="Sweet Snacks"> Sweet Snacks
                </label><br>
                <label>
                    <input type="checkbox" name="category" value="Salted Snacks"> Salted Snacks
                </label><br>
            </div>

            <!-- Price Range -->
            <div class="price-range">
                <h3>Price Range</h3>
                <input type="range" id="priceRange" min="0" max="500" step="1" value="500" oninput="updatePriceLabels()">
                <div class="range-values">
                    <span id="minPrice">0 SAR</span> - <span id="maxPrice">500 SAR</span>
                </div>
            </div>
        </section>

        <section class="products-list">
            <h2>Our Products</h2>
            <div class="product-grid" id="productGrid">
                <!-- Dynamic product cards will be inserted here -->
            </div>
        </section>
    </main>

    <!-- Footer -->
    <?php include('../includes/footer.php'); ?>

    <script>
        let allProducts = [];

        async function fetchAllProducts() {
            try {
                const response = await fetch('../backend/get_products.php');
                const data = await response.json();
                if (data.error) {
                    console.error('Error from backend:', data.error);
                    return;
                }
                allProducts = data;
                displayProducts(allProducts);
            } catch (error) {
                console.error('Error fetching products:', error);
            }
        }

        function displayProducts(products) {
            const productGrid = document.getElementById('productGrid');
            productGrid.innerHTML = '';
            if (products.length === 0) {
                productGrid.innerHTML = `<p>No products found</p>`;
                return;
            }
            products.forEach((product, index) => {
                const productCard = document.createElement('div');
                productCard.classList.add('product');
                productCard.innerHTML = `
                    <a href="product_detailsCus.php">
                        <img src="../${product.image_url}" alt="${product.name}">
                    </a>
                    <p>${product.name}</p>
                    <span>${product.price} SAR</span>
                    <div class="quantity-container">
                        <button class="decrease-qty" data-index="${index}">-</button>
                        <span class="quantity" id="qty-${index}">1</span>
                        <button class="increase-qty" data-index="${index}">+</button>
                    </div>
                    <button class="add-to-cart" data-index="${index}">
                        <i class="fa-solid fa-cart-plus"></i> Add to Cart
                    </button>
                `;
                productGrid.appendChild(productCard);
            });
            setupQuantityButtons();
            setupAddToCartButtons();
        }

        function applyFilters() {
            const selectedCategories = Array.from(document.querySelectorAll('input[name="category"]:checked')).map(input => input.value);
            const maxPrice = parseInt(document.getElementById('priceRange').value, 10);
            const filteredProducts = allProducts.filter(product =>
                (selectedCategories.length === 0 || selectedCategories.includes(product.category)) && product.price <= maxPrice
            );
            displayProducts(filteredProducts);
        }

        function updatePriceLabels() {
            document.getElementById('maxPrice').innerText = `${document.getElementById('priceRange').value} SAR`;
            applyFilters();
        }

        document.querySelectorAll('input[name="category"]').forEach(input => {
            input.addEventListener('change', applyFilters);
        });

        document.getElementById('priceRange').addEventListener('input', updatePriceLabels);

        function setupQuantityButtons() {
            document.querySelectorAll('.decrease-qty, .increase-qty').forEach(button => {
                button.addEventListener('click', (event) => {
                    const index = event.target.dataset.index;
                    const quantityElement = document.getElementById(`qty-${index}`);
                    let quantity = parseInt(quantityElement.textContent, 10);
                    if (event.target.classList.contains('decrease-qty')) {
                        if (quantity > 1) {
                            quantity--;
                        }
                    } else if (event.target.classList.contains('increase-qty')) {
                        quantity++;
                    }
                    quantityElement.textContent = quantity;
                });
            });
        }

        function setupAddToCartButtons() {
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', async (event) => {
                    const productIndex = event.target.closest('button').dataset.index;
                    const product = allProducts[productIndex];
                    const quantity = parseInt(document.getElementById(`qty-${productIndex}`).textContent, 10);
                    const productData = {
                        product_id: product.product_id,
                        name: product.name,
                        price: product.price,
                        image_url: product.image_url,
                        category: product.category,
                        quantity: quantity
                    };
                    try {
                        const response = await fetch('../backend/add_to_cart.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(productData)
                        });
                        const result = await response.json();
                        alert(result.success || result.error || 'Something went wrong.');
                    } catch (error) {
                        alert('Something went wrong. Please try again.');
                    }
                });
            });
        }

        window.onload = fetchAllProducts;
    </script>

</body>

</html>





