<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Orders | VeganCrate</title>
  <link rel="stylesheet" href="../assets/styles.css" />
  <!-- Reusing the same CSS file as manage_vendors.css for consistent styling -->
  <link rel="stylesheet" href="../assets/manage_vendors.css" />
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      loadOrders();
    });

    // Load all orders and render them in the table
    function loadOrders() {
      fetch("../backend/get_vendor_orders.php")
        .then(response => response.json())
        .then(orders => {
          const tableBody = document.querySelector("tbody");
          tableBody.innerHTML = "";
          
          if (orders.message) {
            tableBody.innerHTML = `<tr><td colspan="8">${orders.message}</td></tr>`;
          } else {
            orders.forEach(order => {
              const row = `
                <tr id="order-${order.order_id}">
                  <td>${order.order_id}</td>
                  <td>${order.customer_id}</td>
                  <td>${order.total_price} SAR</td>
                  <td>${order.status}</td>
                  <td>${order.shipping_company_id}</td>
                  <td>${order.created_at}</td>
                  <td>${order.payment_method}</td>
                  <td>
                    <button class="edit-btn" onclick="openViewOrder(${order.order_id})">View</button>
                  </td>
                </tr>
              `;
              tableBody.innerHTML += row;
            });
          }
        })
        .catch(error => console.error("Error fetching orders:", error));
    }

    // Open the View Order modal and populate it with order details
    function openViewOrder(orderId) {
      // Open the modal
      document.getElementById("viewOrderModal").style.display = "block";

      // Fetch order details from the backend (adjust endpoint as needed)
      fetch(`../backend/get_order_details.php?id=${orderId}`)
        .then(response => response.json())
        .then(order => {
          // Populate modal fields with order details
          document.getElementById("view_order_id").innerText = order.order_id;
          document.getElementById("view_customer_id").innerText = order.customer_id;
          document.getElementById("view_total_price").innerText = order.total_price + " SAR";
          document.getElementById("view_status").innerText = order.status;
          document.getElementById("view_shipping_company_id").innerText = order.shipping_company_id;
          document.getElementById("view_created_at").innerText = order.created_at;
          document.getElementById("view_payment_method").innerText = order.payment_method;
        })
        .catch(error => console.error("Error fetching order details:", error));
    }

    // Close the View Order modal
    function closeViewModal() {
      document.getElementById("viewOrderModal").style.display = "none";
    }
  </script>
</head>
<body>
  <header>
    <div class="header-top">
      <div class="logo">🌱 VeganCrate Dashboard</div>
      <nav>
        <ul class="nav-links">
          <li><a href="vendor_dashboard.php">Dashboard</a></li>
          <li><a href="../backend/logout.php">Logout</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="manage-vendors-container">
    <h2>Manage Orders</h2>
    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Customer ID</th>
          <th>Total Price</th>
          <th>Status</th>
          <th>Shipping Company ID</th>
          <th>Created At</th>
          <th>Payment Method</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Order rows will be injected here -->
      </tbody>
    </table>
  </main>

  <!-- View Order Modal -->
  <div id="viewOrderModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeViewModal()">&times;</span>
      <h2>Order Details</h2>
      <p><strong>Order ID:</strong> <span id="view_order_id"></span></p>
      <p><strong>Customer ID:</strong> <span id="view_customer_id"></span></p>
      <p><strong>Total Price:</strong> <span id="view_total_price"></span></p>
      <p><strong>Status:</strong> <span id="view_status"></span></p>
      <p><strong>Shipping Company ID:</strong> <span id="view_shipping_company_id"></span></p>
      <p><strong>Created At:</strong> <span id="view_created_at"></span></p>
      <p><strong>Payment Method:</strong> <span id="view_payment_method"></span></p>
      <button class="submit-btn" onclick="closeViewModal()">Close</button>
    </div>
  </div>

  <footer>
    <div class="footer-content">
      <p>&copy; 2025 VeganCrate. All Rights Reserved.</p>
    </div>
  </footer>
</body>
</html>
