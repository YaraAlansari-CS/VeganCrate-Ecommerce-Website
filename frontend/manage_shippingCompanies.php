<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Shipping | VeganCrate</title>
  <link rel="stylesheet" href="../assets/styles.css" />
  <link rel="stylesheet" href="../assets/manage_shippingCompanies.css" />
</head>
<body>
  <header>
    <div class="header-top">
      <div class="logo">🌱 VeganCrate Admin</div>
      <nav>
        <ul class="nav-links">
          <li><a href="admin_dashboard.php">Dashboard</a></li>
          <li><a href="../backend/logout.php">Logout</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="manage-shipping-container">
    <h2>Manage Shipping Companies</h2>
    <button class="add-shipping-btn" onclick="openAddModal()">+ Add New Shipping Company</button>
    <table>
      <thead>
        <tr>
          <th>Company ID</th>
          <th>Name</th>
          <th>Contact</th>
          <th>Tracking URL</th>
          <th>Shipping Price</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </main>

  <!-- Add Shipping Company Modal -->
  <div id="addShippingModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="document.getElementById('addShippingModal').style.display='none'">&times;</span>
      <h2>Add New Shipping Company</h2>
      <form id="addShippingForm" method="POST" enctype="multipart/form-data">
        <label for="name">Company Name:</label>
        <input type="text" id="name" name="name" required />
        <label for="contact">Contact:</label>
        <input type="text" id="contact" name="contact" required />
        <label for="tracking_url">Tracking URL:</label>
        <input type="url" id="tracking_url" name="tracking_url" required />
        <label for="shipping_price">Shipping Price:</label>
        <input type="number" step="0.01" id="shipping_price" name="shipping_price" required />
        <button type="submit" class="submit-btn">Add Shipping Company</button>
      </form>
    </div>
  </div>

  <!-- Edit Shipping Company Modal -->
  <div id="editShippingModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="document.getElementById('editShippingModal').style.display='none'">&times;</span>
      <h2>Edit Shipping Company</h2>
      <form id="editShippingForm" method="POST">
        <!-- Hidden input to store the company ID -->
        <input type="hidden" id="edit_company_id" name="company_id" />
        <label for="edit_name">Company Name:</label>
        <input type="text" id="edit_name" name="name" required />
        <label for="edit_contact">Contact:</label>
        <input type="text" id="edit_contact" name="contact" required />
        <label for="edit_tracking_url">Tracking URL:</label>
        <input type="url" id="edit_tracking_url" name="tracking_url" required />
        <label for="edit_shipping_price">Shipping Price:</label>
        <input type="number" step="0.01" id="edit_shipping_price" name="shipping_price" required />
        <button type="submit" class="submit-btn">Save</button>
      </form>
    </div>
  </div>

  <footer>
    <p>&copy; 2025 VeganCrate. All Rights Reserved.</p>
  </footer>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      loadShippingCompanies();

      // Attach event listener for the Add Shipping Company form
      document.getElementById("addShippingForm").addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("../backend/add_shippingCompany.php", {
          method: "POST",
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            console.log(data); // Debug: Check the response
            alert(data.success || data.error);
            if (data.success) {
              document.getElementById("addShippingModal").style.display = "none";
              loadShippingCompanies();
            }
          })
          .catch(error => console.error("Error adding shipping company:", error));
      });

      // Attach event listener for the Edit Shipping Company form to send JSON
      document.getElementById("editShippingForm").addEventListener("submit", function (e) {
        e.preventDefault();

        // Extract values from the form fields
        const company_id = document.getElementById("edit_company_id").value;
        const name = document.getElementById("edit_name").value;
        const contact = document.getElementById("edit_contact").value;
        const tracking_url = document.getElementById("edit_tracking_url").value;
        const shipping_price = document.getElementById("edit_shipping_price").value;

        // Build the JSON payload
        const payload = {
          company_id: parseInt(company_id),
          name: name,
          contact: contact,
          tracking_url: tracking_url,
          shipping_price: parseFloat(shipping_price)
        };

        fetch("../backend/save_edit_shippingCompany.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify(payload)
        })
          .then(response => response.json())
          .then(data => {
            console.log(data); // Debug: Check the response
            alert(data.success || data.error);
            if (data.success) {
              document.getElementById("editShippingModal").style.display = "none";
              loadShippingCompanies();
            }
          })
          .catch(error => console.error("Error editing shipping company:", error));
      });
    });

    function loadShippingCompanies() {
      fetch("../backend/get_shipping_companies.php")
        .then(response => response.json())
        .then(companies => {
          let tableBody = document.querySelector("tbody");
          tableBody.innerHTML = "";

          if (companies.message) {
            tableBody.innerHTML = `<tr><td colspan="6">${companies.message}</td></tr>`;
          } else {
            companies.forEach(company => {
              let row = `
                <tr id="company-${company.company_id}">
                  <td>${company.company_id}</td>
                  <td>${company.name}</td>
                  <td>${company.contact}</td>
                  <td><a href="${company.tracking_url}" target="_blank">${company.tracking_url}</a></td>
                  <td>${company.shipping_price} SAR</td>
                  <td>
                    <button class="edit-btn" onclick="openEditModal(${company.company_id})">Edit</button>
                    <button class="delete-btn" onclick="deleteShippingCompany(${company.company_id})">Delete</button>
                  </td>
                </tr>
              `;
              tableBody.innerHTML += row;
            });
          }
        })
        .catch(error => console.error("Error fetching shipping companies:", error));
    }

    function deleteShippingCompany(companyId) {
      if (confirm("Are you sure you want to delete this shipping company?")) {
        fetch(`../backend/delete_shipping_company.php?id=${companyId}`)
          .then(response => response.json())
          .then(data => {
            alert(data.success || data.error);
            if (data.success) {
              document.getElementById(`company-${companyId}`).remove();
            }
          })
          .catch(error => console.error("Error deleting shipping company:", error));
      }
    }

    function openAddModal() {
      document.getElementById("addShippingModal").style.display = "block";
    }

    // Open the Edit modal, fetch the company details and populate the form
    function openEditModal(companyId) {
      // Open the Edit modal
      document.getElementById("editShippingModal").style.display = "block";

      // Fetch company details from the backend
      fetch(`../backend/get_shippingCompany_details.php?id=${companyId}`)
        .then(response => response.json())
        .then(company => {
          // Populate the form fields with the response data
          document.getElementById("edit_company_id").value = company.company_id;
          document.getElementById("edit_name").value = company.name;
          document.getElementById("edit_contact").value = company.contact;
          document.getElementById("edit_tracking_url").value = company.tracking_url;
          document.getElementById("edit_shipping_price").value = company.shipping_price;
        })
        .catch(error => console.error("Error fetching shipping company details:", error));
    }
  </script>
</body>
</html>

