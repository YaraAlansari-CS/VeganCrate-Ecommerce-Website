<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Vendors | VeganCrate</title>
  <link rel="stylesheet" href="../assets/styles.css" />
  <link rel="stylesheet" href="../assets/manage_vendors.css" />
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      loadVendors();

      // Attach event listener for the Add Vendor form
      document.getElementById("addVendorForm").addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("../backend/add_vendor.php", {
          method: "POST",
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          console.log(data); // Debug: Check the response
          alert(data.success || data.error);
          if (data.success) {
            document.getElementById("addVendorModal").style.display = "none";
            loadVendors();
          }
        })
        .catch(error => console.error("Error adding vendor:", error));
      });

      // Attach event listener for the Edit Vendor form (sending JSON)
      document.getElementById("editVendorForm").addEventListener("submit", function (e) {
        e.preventDefault();

        // Retrieve values from the edit form
        const vendor_id = document.getElementById("edit_vendor_id").value;
        const user_id = document.getElementById("edit_user_id").value;
        const name = document.getElementById("edit_name").value;
        const email = document.getElementById("edit_email").value;
        const password = document.getElementById("edit_password").value;
        const role = document.getElementById("edit_role").value;
        const business_name = document.getElementById("edit_business_name").value;
        const business_description = document.getElementById("edit_business_description").value;
        const rating = document.getElementById("edit_rating").value;

        // Build the payload matching the expected JSON structure
        const payload = {
          vendor_id: parseInt(vendor_id),
          user_id: parseInt(user_id),
          name: name,
          email: email,
          password: password,
          role: role,
          business_name: business_name,
          business_description: business_description,
          rating: parseFloat(rating)
        };

        fetch("../backend/save_edit_vendor.php", {
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
            document.getElementById("editVendorModal").style.display = "none";
            loadVendors();
          }
        })
        .catch(error => console.error("Error editing vendor:", error));
      });
    });

    // Load all vendors and render them in the table
    function loadVendors() {
      fetch("../backend/get_vendors.php")
        .then(response => response.json())
        .then(vendors => {
          let tableBody = document.querySelector("tbody");
          tableBody.innerHTML = "";

          if (vendors.message) {
            tableBody.innerHTML = `<tr><td colspan="10">${vendors.message}</td></tr>`;
          } else {
            vendors.forEach(vendor => {
              let row = `
                <tr id="vendor-${vendor.vendor_id}">
                  <td>${vendor.vendor_id}</td>
                  <td>${vendor.user_id}</td>
                  <td>${vendor.name}</td>
                  <td>${vendor.email}</td>
                  <td>${vendor.password}</td>
                  <td>${vendor.role}</td>
                  <td>${vendor.business_name}</td>
                  <td>${vendor.business_description}</td>
                  <td>${vendor.rating}</td>
                  <td>
                    <button class="edit-btn" onclick="openEditModal(${vendor.vendor_id})">Edit</button>
                    <button class="delete-btn" onclick="deleteVendor(${vendor.vendor_id})">Delete</button>
                  </td>
                </tr>
              `;
              tableBody.innerHTML += row;
            });
          }
        })
        .catch(error => console.error("Error fetching vendors:", error));
    }

    // Delete a vendor
    function deleteVendor(vendorId) {
      if (confirm("Are you sure you want to delete this vendor?")) {
        fetch(`../backend/delete_vendor.php?id=${vendorId}`)
          .then(response => response.json())
          .then(data => {
            alert(data.success || data.error);
            if (data.success) {
              document.getElementById(`vendor-${vendorId}`).remove();
            }
          })
          .catch(error => console.error("Error deleting vendor:", error));
      }
    }

    // Open the Add Vendor modal
    function openAddModal() {
      document.getElementById("addVendorModal").style.display = "block";
    }

    // Open the Edit Vendor modal, fetch the vendor details and populate the form
    function openEditModal(vendorId) {
      document.getElementById("editVendorModal").style.display = "block";

      fetch(`../backend/get_vendor_details.php?id=${vendorId}`)
        .then(response => response.json())
        .then(vendor => {
          document.getElementById("edit_vendor_id").value = vendor.vendor_id;
          document.getElementById("edit_user_id").value = vendor.user_id;
          document.getElementById("edit_name").value = vendor.name;
          document.getElementById("edit_email").value = vendor.email;
          document.getElementById("edit_password").value = vendor.password;
          document.getElementById("edit_role").value = vendor.role;
          document.getElementById("edit_business_name").value = vendor.business_name;
          document.getElementById("edit_business_description").value = vendor.business_description;
          document.getElementById("edit_rating").value = vendor.rating;
        })
        .catch(error => console.error("Error fetching vendor details:", error));
    }
  </script>
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

  <main class="manage-vendors-container">
    <h2>Manage Vendors</h2>
    <button class="add-vendor-btn" onclick="openAddModal()">+ Add New Vendor</button>
    <table>
      <thead>
        <tr>
          <th>Vendor ID</th>
          <th>User ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Password</th>
          <th>Role</th>
          <th>Business Name</th>
          <th>Business Description</th>
          <th>Rating/5</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </main>

  <!-- Add Vendor Modal -->
  <div id="addVendorModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="document.getElementById('addVendorModal').style.display='none'">&times;</span>
      <h2>Add New Vendor</h2>
      <form id="addVendorForm" method="POST" enctype="multipart/form-data">
        <label for="add_vendor_id">Vendor ID:</label>
        <input type="number" id="add_vendor_id" name="vendor_id" required />

        <label for="add_user_id">User ID:</label>
        <input type="number" id="add_user_id" name="user_id" required />

        <label for="add_name">Name:</label>
        <input type="text" id="add_name" name="name" required />

        <label for="add_email">Email:</label>
        <input type="email" id="add_email" name="email" required />

        <label for="add_password">Password:</label>
        <input type="password" id="add_password" name="password" required />

        <label for="add_role">Role:</label>
        <input type="text" id="add_role" name="role" required />

        <label for="add_business_name">Business Name:</label>
        <input type="text" id="add_business_name" name="business_name" required />

        <label for="add_business_description">Business Description:</label>
        <textarea id="add_business_description" name="business_description" required></textarea>

        <label for="add_rating">Rating:</label>
        <input type="number" step="0.01" id="add_rating" name="rating" required />

        <button type="submit" class="submit-btn">Add Vendor</button>
      </form>
    </div>
  </div>

  <!-- Edit Vendor Modal -->
  <div id="editVendorModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="document.getElementById('editVendorModal').style.display='none'">&times;</span>
      <h2>Edit Vendor</h2>
      <form id="editVendorForm" method="POST">
        <!-- Hidden input to store the vendor ID -->
        <input type="hidden" id="edit_vendor_id" name="vendor_id" />

        <label for="edit_user_id">User ID:</label>
        <input type="number" id="edit_user_id" name="user_id" required />

        <label for="edit_name">Name:</label>
        <input type="text" id="edit_name" name="name" required />

        <label for="edit_email">Email:</label>
        <input type="email" id="edit_email" name="email" required />

        <label for="edit_password">Password:</label>
        <input type="password" id="edit_password" name="password" required />

        <label for="edit_role">Role:</label>
        <input type="text" id="edit_role" name="role" required />

        <label for="edit_business_name">Business Name:</label>
        <input type="text" id="edit_business_name" name="business_name" required />

        <label for="edit_business_description">Business Description:</label>
        <textarea id="edit_business_description" name="business_description" required></textarea>

        <label for="edit_rating">Rating:</label>
        <input type="number" step="0.01" id="edit_rating" name="rating" required />

        <button type="submit" class="submit-btn">Save</button>
      </form>
    </div>
  </div>

  <footer>
    <p>&copy; 2025 VeganCrate. All Rights Reserved.</p>
  </footer>
</body>
</html>

