# 🥗 VeganCrate – Vegetarian Food Marketplace

> **Advanced Web Programming (AWP) Project** | Full-Stack E-Commerce Website

![PHP](https://img.shields.io/badge/PHP-7.4-blue?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-blue?logo=mysql)
![JavaScript](https://img.shields.io/badge/JavaScript-AJAX-yellow?logo=javascript)
![HTML](https://img.shields.io/badge/HTML5-CSS3-orange?logo=html5)
![License](https://img.shields.io/badge/License-MIT-green)

---

## 📌 Overview

**VeganCrate** is an online marketplace that connects users with fresh, nutritious, plant-based food products. It provides a seamless shopping experience with features like product browsing, ordering, delivery tracking, and product management. The platform promotes healthy living through a user-friendly environment.

---

## 📄 Documentation

- **Final Report:** [Final Report](https://drive.google.com/file/d/18xrQSiriiAXdydM0kRyID8Saak0Sy51e/view?usp=sharing)

---

## 🎥 Demo

▶️ **Watch the project demo here:**  
[Demo Video](https://drive.google.com/file/d/1nXlrnkcmSo6qZpXQeLMEt_KCw3q8SnuN/view?usp=sharing)

---

## 👥 User Roles

| **Role** | **Permissions** |
| :--- | :--- |
| **Admin** | Full control – manage users, products, vendors, orders, payments, and shipping companies. |
| **Employee** | Limited permissions – track orders, update statuses (excluding sensitive tasks like user/payment management). |
| **Vendor** | Manage own products only – cannot manage users, orders, or payments. |
| **Customer** | Register, login, browse products, place orders, rate vendors, track orders – cannot manage users or products. |

---

## 🎯 Core Features

### Customer Side
- **Sign Up / Login** with secure password hashing.
- **Browse Products** with filtering and search.
- **Add to Cart** with quantity adjustment.
- **Checkout & Place Order** with shipping and payment selection.
- **Order Tracking** with real-time status updates.

### Admin Side
- **Dashboard** overview of system operations.
- **Manage Products** (add, edit, delete).
- **Manage Vendors** (add, edit, delete).
- **Manage Shipping Companies** (add, edit, delete).
- **Manage Users** (customers, employees, vendors).

### Vendor Side
- **Manage Own Products** (add, edit, delete).
- **View Orders** related to their products.
- **Update Order Status** for their own products.

---

## 🛠️ Tech Stack

| **Category** | **Technologies** |
| :--- | :--- |
| **Frontend** | HTML, CSS (Flexbox, custom styles), JavaScript (AJAX for dynamic updates). |
| **Backend** | PHP (session management, secure MySQL connections, JSON responses). |
| **Database** | MySQL (managed via phpMyAdmin). |
| **Data Exchange** | JSON (primary), XML (for static payment methods). |
| **Security** | Input validation, prepared statements (SQL injection protection), password_hash/verify, session & cookie security. |

---

## 🔐 Security Measures

- **Input Validation & Sanitization** – prevents malicious data processing.
- **Prepared Statements** – protects against SQL injection.
- **Password Hashing** – uses `password_hash()` and `password_verify()`.
- **Session & Cookie Security** – secure session management with protection flags.

---

## 📁 Project Structure

```text
VeganCrate-Ecommerce-Website/
├── assets/ # Images, CSS stylesheets, and frontend JavaScript files
├── backend/ # PHP backend logic (API endpoints, authentication, order processing)
├── data/ # Static data files (e.g., XML for payment methods, sample data)
├── database/ # Database schema and migrations
│ └── vegancrate_db.sql # Main SQL dump (exported from phpMyAdmin)
├── frontend/ # User-facing pages (HTML, CSS, JavaScript) for customers, admin, vendor
├── includes/ # Shared PHP files (database connection, global functions, config)
├── .htaccess # Apache server configuration (URL rewriting, security rules)
└── README.md # This file
```
---

## 🚀 How to Run Locally

### Prerequisites
- **XAMPP** or **WAMP** (Apache + MySQL).
- **phpMyAdmin** (for database import).

### Steps
1. Clone this repository into your local server directory (e.g., `C:\xampp\htdocs\`).
2. Import the SQL file from `database/vegan_crate_db.sql` into phpMyAdmin.
3. Update database credentials in `includes/db_connection.php`.
4. Open your browser and navigate to `http://localhost/VeganCrate-Ecommerce-Website/`.

---

## 🧪 Testing & Usability

- **Target Users:** Ages 18–45, all genders, high school education+, local users.
- **Testing Methods:** Think-aloud protocols, test scenarios (navigation, sign-up, checkout, error handling).
- **Qualitative Feedback:** User insights for continuous improvement.

---

## 👩‍💻 My Role

As the **Core Developer**, I contributed to:

- Designing the **database schema** and managing it via phpMyAdmin.
- Building the **backend PHP API** and integrating with MySQL.
- Implementing **AJAX** for dynamic frontend updates.
- Handling **user authentication** and role-based access control.
- Ensuring **security measures** (prepared statements, password hashing).
- Conducting **usability testing** and documenting results.

---

## 📫 Connect with Me

- **GitHub:** [YaraAlansari-CS](https://github.com/YaraAlansari-CS)
- **LinkedIn:** [Yara Alansari](https://www.linkedin.com/in/yara-alansari-64b17a317)
- **Email:** [yara.alansari01@gmail.com](mailto:yara.alansari01@gmail.com)

---

**⭐ If you like this project, don't forget to give it a star!**
