<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Details</title>
  <link rel="stylesheet" href="../assets/styles.css"> <!-- General styles -->
  <link rel="stylesheet" href="../assets/detailsOrd.css"> <!-- Page-specific styles -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    /* Example status bar styling */
    .order-status-bar {
      margin-bottom: 20px;
    }
    .status-progress {
      display: flex;
      justify-content: space-between;
      list-style: none;
    }
    .status {
      padding: 10px;
      border: 1px solid #ccc;
      width: 18%;
      text-align: center;
      border-radius: 5px;
      background: #f4f4f4;
    }
    .status.active {
      background: #4caf50;
      color: #fff;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <?php include('../includes/header.php'); ?> <!-- Include header -->

  <main class="order-details-container">
    <!-- Order Status Bar -->
    <div class="order-status-bar">
      <div class="status-progress">
        <div class="status" id="status-pending">Pending</div>
        <div class="status" id="status-processing">Processing</div>
        <div class="status" id="status-shipped">Shipped</div>
        <div class="status" id="status-delivered">Delivered</div>
        <div class="status" id="status-cancelled">Cancelled</div>
      </div>
    </div>

    <!-- Order Summary Details -->
    <section class="order-summary">
      <h2>Order Summary</h2>
      <p><strong>Order Total:</strong> <span id="order-total-price"></span></p>
      <p><strong>Order Date:</strong> <span id="order-created-at"></span></p>
      <p><strong>Payment Method:</strong> <span id="order-payment-method"></span></p>
      
      <!-- Shipping Details (if available) -->
      <div class="shipping-details">
        <h3>Shipping Details</h3>
        <p><strong>Company:</strong> <span id="shipping-company-name"></span></p>
        <p><strong>Price:</strong> <span id="shipping-price"></span></p>
        <p><strong>Contact:</strong> <span id="shipping-contact"></span></p>
        <p><strong>Tracking:</strong> <a href="#" id="shipping-tracking-url" target="_blank"></a></p>
      </div>
    </section>

    <!-- Ordered Products -->
    <section class="ordered-products">
      <h3>Ordered Products</h3>
      <table class="products-table">
        <thead>
          <tr>
            <th>Product Name</th>
            <th>Image</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody id="products-table-body">
          <!-- Products will be inserted here via JavaScript -->
        </tbody>
      </table>
    </section>

    <!-- Order Actions -->
    <div class="order-actions">
      <a href="#" class="btn cancel" id="cancel-order-link">Cancel Order</a>
      <a href="servicesCust.php" class="btn back">Back to Order History</a>
    </div>
  </main>

  <?php include('../includes/footer.php'); ?> <!-- Include footer -->

  <!-- JavaScript to get order_id from URL, call API, and populate the page -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Get order_id from URL query parameters (e.g., ?id=123)
      const urlParams = new URLSearchParams(window.location.search);
      const orderId = urlParams.get('order_id');
      console.log('Order ID from URL:', orderId);
      if (!orderId) {
        console.error('Order ID not found in URL');
        return;
      }
      
      // Build the fetch URL. Make sure the relative path is correct.
      const apiUrl = `../backend/get_my_order.php?id=${orderId}`;
      console.log('Fetching API URL:', apiUrl);

      // Fetch order details from the API
      fetch(apiUrl)
        .then(response => {
          console.log('Raw response from API:', response);
          if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
          }
          return response.json();
        })
        .then(data => {
          console.log('Parsed JSON data:', data);

          // Update Order Status Bar: set the one matching data.status (all in lower case)
          const statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
          statuses.forEach(status => {
            const statusElem = document.getElementById(`status-${status}`);
            if (data.status && data.status.toLowerCase() === status) {
              statusElem.classList.add('active');
            } else {
              statusElem.classList.remove('active');
            }
          });

          // Update Order Summary Details
          document.getElementById('order-total-price').textContent = `$${parseFloat(data.total_price).toFixed(2)}`;
          document.getElementById('order-created-at').textContent = data.created_at;
          document.getElementById('order-payment-method').textContent = data.payment_method;

          // Populate the ordered products table
          const productsTableBody = document.getElementById('products-table-body');
          data.items.forEach(item => {
            const row = document.createElement('tr');
            
            // Product Name
            const nameCell = document.createElement('td');
            nameCell.textContent = item.name;
            row.appendChild(nameCell);
            
            // Product Image
            const imageCell = document.createElement('td');
            const img = document.createElement('img');
            img.src = "../" + item.image_url; // Prepend "../" to the image URL
            img.alt = item.name;
            img.classList.add('product-image');
            imageCell.appendChild(img);
            row.appendChild(imageCell);
            
            // Quantity
            const quantityCell = document.createElement('td');
            quantityCell.textContent = item.quantity;
            row.appendChild(quantityCell);
            
            // Price per item
            const priceCell = document.createElement('td');
            priceCell.textContent = `$${parseFloat(item.price).toFixed(2)}`;
            row.appendChild(priceCell);
            
            // Total for this product
            const totalCell = document.createElement('td');
            const total = parseFloat(item.price) * parseInt(item.quantity);
            totalCell.textContent = `$${total.toFixed(2)}`;
            row.appendChild(totalCell);
            
            productsTableBody.appendChild(row);
          });

          // Update Shipping Details if available
          if (data.shipping_company_name) {
            document.getElementById('shipping-company-name').textContent = data.shipping_company_name;
          }
          if (data.shipping_price) {
            document.getElementById('shipping-price').textContent = `$${parseFloat(data.shipping_price).toFixed(2)}`;
          }
          if (data.shipping_contact) {
            document.getElementById('shipping-contact').textContent = data.shipping_contact;
          }
          if (data.shipping_tracking_url) {
            const trackingLink = document.getElementById('shipping-tracking-url');
            trackingLink.href = data.shipping_tracking_url;
            trackingLink.textContent = "Track Shipment";
          } else {
            document.getElementById('shipping-tracking-url').textContent = "N/A";
          }

          // Update the Cancel Order link with the proper order id
          document.getElementById('cancel-order-link').href = `cancel-order.php?id=${orderId}`;
        })
        .catch(error => console.error('Error fetching order details:', error));
    });
  </script>
</body>
</html>

