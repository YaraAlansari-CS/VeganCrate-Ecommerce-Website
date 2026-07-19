<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Products | VeganCrate</title>
  <link rel="stylesheet" href="../assets/styles.css" />
  <link rel="stylesheet" href="../assets/manage_products.css" />
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Load products when the DOM is fully loaded
      loadProducts();

      // Attach event listener to the add product form (remains unchanged)
      document
        .getElementById("addProductForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();
          let formData = new FormData(this);

          fetch("../backend/add_product.php", {
            method: "POST",
            body: formData,
          })
            .then((response) => response.json())
            .then((data) => {
              console.log(data); // Debug: Check the response
              alert(data.success || data.error);
              if (data.success) {
                document.getElementById("addProductModal").style.display =
                  "none";
                loadProducts();
              }
            })
            .catch((error) => console.error("Error adding product:", error));
        });

      // Attach event listener to the edit product form.
      // This version builds a JSON object from the input values and sends it.
      document
        .getElementById("editProductForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();

          // Retrieve values from the edit form
          const product_id = document.getElementById("edit_product_id").value;
          const vendor_id = document.getElementById("edit_vendor_id").value;
          const name = document.getElementById("edit_name").value;
          const description = document.getElementById("edit_description").value;
          const price = document.getElementById("edit_price").value;
          const stock = document.getElementById("edit_stock").value;
          const category = document.getElementById("edit_category").value;

          // Handle image: if a file is selected, use its name to build a new URL; otherwise, send null.
          const editImageInput = document.getElementById("edit_image");
          let image_url = null;
          if (editImageInput.files && editImageInput.files.length > 0) {
            const file = editImageInput.files[0];
            // For this example, assume the new image will be stored in "assets/uploads/"
            image_url = "assets/uploads/" + file.name;
          }

          // Build the payload matching the expected JSON structure
          const payload = {
            product_id: parseInt(product_id),
            vendor_id: parseInt(vendor_id),
            name: name,
            description: description,
            price: parseFloat(price),
            stock: parseInt(stock),
            category: category,
            image_url: image_url,
          };

          fetch("../backend/save_edit_product.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(payload),
          })
            .then((response) => response.json())
            .then((data) => {
              console.log(data); // Debug: Check the response
              alert(data.success || data.error);
              if (data.success) {
                document.getElementById("editProductModal").style.display =
                  "none";
                loadProducts();
              }
            })
            .catch((error) =>
              console.error("Error saving edited product:", error)
            );
        });
    });

    // Load all products and render them in the table
    function loadProducts() {
      fetch("../backend/get_products.php")
        .then((response) => response.json())
        .then((products) => {
          let tableBody = document.querySelector("tbody");
          tableBody.innerHTML = "";

          if (products.message) {
            tableBody.innerHTML = `<tr><td colspan="9">${products.message}</td></tr>`;
          } else {
            products.forEach((product) => {
              let row = `
                <tr id="product-${product.product_id}">
                  <td>${product.product_id}</td>
                  <td>${product.vendor_id}</td>
                  <td>${product.name}</td>
                  <td>${product.description}</td>
                  <td>${product.price} SAR</td>
                  <td>${product.stock}</td>
                  <td>${product.category}</td>
                  <td><img src="../${product.image_url}" alt="${product.name}" width="50"></td>
                  <td>
                    <button class="edit-btn" onclick="openEditModal(${product.product_id})">Edit</button>
                    <button class="delete-btn" onclick="deleteProduct(${product.product_id})">Delete</button>
                  </td>
                </tr>
              `;
              tableBody.innerHTML += row;
            });
          }
        })
        .catch((error) => console.error("Error fetching products:", error));
    }

    // Delete a product
    function deleteProduct(productId) {
      if (confirm("Are you sure you want to delete this product?")) {
        fetch(`../backend/delete_product.php?id=${productId}`)
          .then((response) => response.json())
          .then((data) => {
            alert(data.success || data.error);
            if (data.success) {
              document.getElementById(`product-${productId}`).remove();
            }
          })
          .catch((error) => console.error("Error deleting product:", error));
      }
    }

    // Open the Add Product modal
    function openAddModal() {
      document.getElementById("addProductModal").style.display = "block";
    }

    // Open the Edit Product modal, fetch the product details, and populate the form
    function openEditModal(productId) {
      // Open the modal
      document.getElementById("editProductModal").style.display = "block";

      // Fetch product details from the backend
      fetch(`../backend/get_product_details.php?id=${productId}`)
        .then((response) => response.json())
        .then((product) => {
          // Assuming the API returns an object with product_id, name, vendor_id, description, price, stock, category, image_url
          document.getElementById("edit_product_id").value = product.product_id;
          document.getElementById("edit_name").value = product.name;
          document.getElementById("edit_vendor_id").value = product.vendor_id;
          document.getElementById("edit_description").value = product.description;
          document.getElementById("edit_price").value = product.price;
          document.getElementById("edit_stock").value = product.stock;
          document.getElementById("edit_category").value = product.category;
          // Note: For security reasons, file inputs cannot be pre-populated.
        })
        .catch((error) =>
          console.error("Error fetching product details:", error)
        );
    }
  </script>
</head>
<body>
  <header>
    <div class="header-top">
      <div class="logo">🌱 VeganCrate</div>
      <nav>
        <ul class="nav-links">
          <li><a href="admin_dashboard.php">Dashboard</a></li>
          <li><a href="../backend/logout.php">Logout</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="manage-products-container">
    <h2>Manage Products</h2>
    <button class="add-product-btn" onclick="openAddModal()">+ Add New Product</button>
    <table>
      <thead>
        <tr>
          <th>Product ID</th>
          <th>Vendor ID</th>
          <th>Name</th>
          <th>Description</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Category</th>
          <th>Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </main>

  <!-- Add Product Modal -->
  <div id="addProductModal" class="modal">
    <div class="modal-content">
      <span
        class="close"
        onclick="document.getElementById('addProductModal').style.display='none'"
        >&times;</span
      >
      <h2>Add New Product</h2>
      <form id="addProductForm" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required />
        <label for="vendor_id">Vendor ID:</label>
        <input type="number" id="vendor_id" name="vendor_id" required />
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" required />
        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" required />
        <label for="category">Category:</label>
        <input type="text" id="category" name="category" required />
        <label for="image">Upload Image:</label>
        <input type="file" id="image" name="image_url" />
        <button type="submit" class="submit-btn">Add Product</button>
      </form>
    </div>
  </div>

  <!-- Edit Product Modal -->
  <div id="editProductModal" class="modal">
    <div class="modal-content">
      <span
        class="close"
        onclick="document.getElementById('editProductModal').style.display='none'"
        >&times;</span
      >
      <h2>Edit Product</h2>
      <form id="editProductForm">
        <!-- Hidden input to store the product ID -->
        <input type="hidden" id="edit_product_id" name="product_id" />
        <label for="edit_name">Product Name:</label>
        <input type="text" id="edit_name" name="name" required />
        <label for="edit_vendor_id">Vendor ID:</label>
        <input type="number" id="edit_vendor_id" name="vendor_id" required />
        <label for="edit_description">Description:</label>
        <textarea id="edit_description" name="description" required></textarea>
        <label for="edit_price">Price:</label>
        <input type="number" step="0.01" id="edit_price" name="price" required />
        <label for="edit_stock">Stock:</label>
        <input type="number" id="edit_stock" name="stock" required />
        <label for="edit_category">Category:</label>
        <input type="text" id="edit_category" name="category" required />
        <label for="edit_image">Upload Image:</label>
        <input type="file" id="edit_image" name="image_url" />
        <button type="submit" class="submit-btn">Save</button>
      </form>
    </div>
  </div>

  <footer>
    <p>&copy; 2025 VeganCrate. All Rights Reserved.</p>
  </footer>
</body>
</html>


