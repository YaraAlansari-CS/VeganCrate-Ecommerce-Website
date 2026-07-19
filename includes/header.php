<header>
    <div class="header-top">
        <!-- Logo & Site Name -->
        <div class="logo">🌱 VeganCrate</div>

        <!-- Icons (Cart & Menu) -->
        <div class="icons">
            <!-- Cart Icon -->
            <div class="cart-icon">
                <a href="cartCus.php"><i class="fas fa-shopping-cart fa-2x"></i></a>
            </div>

            <!-- Menu Icon -->
            <div class="menu-icon" onclick="openMenu()">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </div>
</header>

<!-- Side Menu -->
<div id="sideMenu" class="side-menu">
    <button class="close-btn" onclick="closeMenu()">×</button>
    <ul>
        <li><a href="../frontend/homeCus.php">Home</a></li>
        <li><a href="myProfile.php">My Profile</a></li>
        <li><a href="myOrders.php">My Orders</a></li>
        <li><a href="contactUs.php">Contact Us</a></li>
        <li><a href="../backend/logout.php">Logout</a></li>
    </ul>
</div>

<!-- Overlay -->
<div id="overlay" class="overlay" onclick="closeMenu()"></div>

<style>
    /* Header Styling */
    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #fff;
        border-bottom: 1px solid #ddd;
    }

    .logo {
        font-size: 24px;
        font-weight: bold;
    }

    .icons {
        display: flex;
        align-items: center;
    }

    .cart-icon {
        position: relative;
        margin-right: 20px;
    }

    .cart-count {
        position: absolute;
        top: -5px;
        right: -10px;
        background: red;
        color: white;
        border-radius: 50%;
        padding: 3px 7px;
        font-size: 12px;
    }

    .menu-icon {
        cursor: pointer;
        font-size: 24px;
    }

    /* Side Menu */
    .side-menu {
        position: fixed;
        top: 0;
        right: -250px;
        width: 250px;
        height: 100%;
        background: white;
        box-shadow: -5px 0 10px rgba(0, 0, 0, 0.1);
        transition: right 0.3s ease-in-out;
        padding-top: 50px;
        z-index: 1000;
    }

    .side-menu ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .side-menu ul li {
        padding: 15px;
        border-bottom: 1px solid #ddd;
    }

    .side-menu ul li a {
        text-decoration: none;
        color: black;
        display: block;
    }

    /* Close Button */
    .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 24px;
        background: none;
        border: none;
        cursor: pointer;
    }

    /* Overlay */
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }

</style>

<script>
    function openMenu() {
        document.getElementById("sideMenu").style.right = "0";
        document.getElementById("overlay").style.display = "block";
    }

    function closeMenu() {
        document.getElementById("sideMenu").style.right = "-250px";
        document.getElementById("overlay").style.display = "none";
    }
</script>
