<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders</title>
  <!-- Include your styles -->  
  <link rel="stylesheet" href="../assets/styles.css">
  <link rel="stylesheet" href="../assets/myOrders.css">
</head>
<body>
  <?php include('../includes/header.php'); ?>

  <main class="orders-container">
    <h2>My Orders</h2>
    <!-- Table to display orders -->
    <table class="orders-table">
      <thead>
        <tr>
          <th>Order</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="ordersTableBody">
        <!-- Order rows will be populated here by JavaScript -->
      </tbody>
    </table>
    <!-- Message to display if no orders were found -->
    <p id="noOrdersMessage" style="display: none; text-align:center;">No orders found.</p>
  </main>

  <?php include('../includes/footer.php'); ?>

  <script>
    // When the DOM content is loaded, fetch the orders
    document.addEventListener('DOMContentLoaded', () => {
      fetchOrders();
    });

    // Function to fetch orders from the get_my_orders API
    function fetchOrders() {
      fetch('../backend/get_my_orders.php')
        .then(response => response.json())
        .then(data => {
          // Check if the API returned an error or an empty array
          if (data.error || (Array.isArray(data) && data.length === 0)) {
            document.getElementById('ordersTableBody').style.display = 'none';
            document.getElementById('noOrdersMessage').style.display = 'block';
          } else {
            populateOrdersTable(data);
          }
        })
        .catch(error => {
          console.error('Error fetching orders:', error);
          document.getElementById('ordersTableBody').innerHTML =
            '<tr><td colspan="2" style="text-align: center;">Error loading orders.</td></tr>';
        });
    }

    // Function to populate the orders table using the API response
    function populateOrdersTable(orders) {
      const tbody = document.getElementById('ordersTableBody');
      tbody.innerHTML = ''; // Clear any previous content

      orders.forEach(order => {
        const tr = document.createElement('tr');

        // Create the Order column (using the created_at field)
        const orderCell = document.createElement('td');
        // Assuming each order object has a "created_at" property (e.g., "2025-02-11 12:33:46")
        orderCell.textContent = order.created_at;
        tr.appendChild(orderCell);

        // Create the Action column with a View button
        const actionCell = document.createElement('td');
        const viewButton = document.createElement('button');
        viewButton.textContent = 'View';
        viewButton.classList.add('btn');
        // Redirect to the details page with the order_id as a URL parameter
        viewButton.addEventListener('click', () => {
          viewOrder(order.order_id);
        });
        actionCell.appendChild(viewButton);
        tr.appendChild(actionCell);

        // Append the row to the table body
        tbody.appendChild(tr);
      });
    }

    // Function to redirect to detailsOrd.php with the order_id in the URL
    function viewOrder(orderId) {
      window.location.href = `../frontend/detailsOrd.php?order_id=${orderId}`;
    }
  </script>
</body>
</html>
