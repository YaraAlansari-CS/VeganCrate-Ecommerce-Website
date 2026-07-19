-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2025 at 06:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vegancrate_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `customer_id` int(11) NOT NULL,
  `street` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`customer_id`, `street`, `city`, `country`, `zip_code`) VALUES
(3, '2712', 'Makkah', 'Saudi Arabia', '24372'),
(6, '2712', 'Makkah', 'Saudi Arabia', '24372');

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `card_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `card_number` varchar(255) DEFAULT NULL,
  `expiry_date` varchar(5) NOT NULL,
  `cvv` varchar(255) DEFAULT NULL,
  `cardholder_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`card_id`, `customer_id`, `card_number`, `expiry_date`, `cvv`, `cardholder_name`) VALUES
(2, 3, '1234123412341234', '05/31', '123', 'yara alansari'),
(3, 3, '0987098709870987', '09/43', '098', 'yara alansari'),
(4, 6, '0987098709870987', '09/34', '098', 'yara alansari');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `complaint_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `complaint_text` text NOT NULL,
  `status` enum('received','open','resolved','closed') DEFAULT 'received',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `user_id`) VALUES
(2, 6),
(3, 7),
(6, 17);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `shipping_company_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` enum('Cash','Card') NOT NULL DEFAULT 'Cash',
  `card_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `total_price`, `status`, `shipping_company_id`, `created_at`, `payment_method`, `card_id`) VALUES
(1, 3, 78.90, 'pending', 5, '2025-02-10 16:48:14', 'Card', 2),
(2, 3, 86.91, 'pending', 6, '2025-02-11 09:33:46', 'Card', 3),
(7, 6, 69.92, 'pending', 4, '2025-02-12 12:03:01', 'Card', 4),
(8, 6, 116.97, 'pending', 4, '2025-02-12 12:18:57', 'Card', 4);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`, `vendor_id`) VALUES
(1, 2, 2, 2, 19.96, 1),
(2, 2, 3, 1, 21.99, 1),
(7, 7, 2, 2, 19.96, 1),
(9, 8, 5, 2, 8.99, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `category` varchar(100) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `vendor_id`, `name`, `description`, `price`, `stock`, `category`, `image_url`) VALUES
(2, 1, 'Dried Peach', 'Premium quality dried peach. Good source of fiber, gluten-free, vegan, fat-free, non-GMO.', 19.96, 70, 'Dried Fruits', 'assets/uploads/D1.png'),
(3, 1, 'Peanut Butter Banana', 'High-quality plant protein with peanut butter and banana flavor. Vegan, no added sugar, and dietary supplement. Contains 25 servings (775g).', 21.99, 49, 'Dried Fruits', 'assets/uploads/D2.png'),
(5, 1, 'Sweet Chilli Puffs', 'Plant-based snacks with a delicious Thai sweet chilli flavor. Gluten-free and includes 6 packs (18g each). Perfect for healthy snacking.', 8.99, 72, 'Salted Snacks', 'assets/uploads/SS1.png'),
(6, 1, 'Bites-Triple Chocolate', 'Delicious triple chocolate millionaire bites. Gluten-free, plant-based, and perfect for a guilt-free treat.', 11.45, 90, 'Sweet Snacks', 'assets/uploads/SW1.jpg'),
(10, 8, 'Lindt vegan chocolate', 'Lindt vegan chocolate is a vegan chocolate that were made in the USA , 40g', 22.89, 40, 'chocolate', 'assets/uploads/Lindt Vegan Original Chocolate Bar, 100g.webp');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `rating` decimal(3,2) NOT NULL CHECK (`rating` between 0 and 5),
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_companies`
--

CREATE TABLE `shipping_companies` (
  `company_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `tracking_url` varchar(255) DEFAULT NULL,
  `shipping_price` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_companies`
--

INSERT INTO `shipping_companies` (`company_id`, `name`, `contact`, `tracking_url`, `shipping_price`) VALUES
(4, 'SMSA Express', '+966 11 218 7777', 'https://www.smsaexpress.com/tracking', 30.00),
(5, 'Aramex', '+966 9200 27447', 'https://www.aramex.com/track', 40.00),
(6, 'Zajel', '+966 9200 00277', 'https://www.zajel.com/track', 25.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','employee','vendor','customer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`) VALUES
(1, 'yara yasser', 'yara@gmail.com', '$2y$10$vA1S966KWQAsqCnsAuvsdeCmZ7sNEAWXrvUjmxa.pZL9IW4yGHfVG', 'admin'),
(2, 'Ahmed Khaled', 'ahmed@gmail.com', '$2y$10$bWKS4xTfSRI0ZuKSAHmbquODnIRiE75eMIpZErgBU1ejTx3ZozD2e', 'vendor'),
(6, 'Sara Ahmed', 'sara@gmail.com', '$2y$10$Yil0OozdFpVu5t.f5RJQIeRJUhtloyMz0alPhkdv98T8Pb.R6Bdlu', 'customer'),
(7, 'yara alansari', 'yaraaa@gmail.com', '$2y$10$cTalHYEnIVF2x2Ow.Plw4O0y1MjaP0AZoJ1f3XGqvBua2AcCQ/kM2', 'customer'),
(8, 'sara alotaibi', 'saraOtaibi@gmail.com', '$2y$10$1U3761yF0SMF8Fc/r82oTO8NEmDSp7.ySZZB3DJzgnTj7d7spu4Ue', 'vendor'),
(9, 'Saleh Alzehrani', 'saleh@gmail.com', '$2y$10$I3xzyPGnsM/yB1nCwu3sjOxA56H26QStPD8SfM5PJmEHwZ8uz7Cx6', 'vendor'),
(10, 'Jack Adams', 'jack@gmail.com', '$2y$10$M2JinL7z9SxwiWkquo.KNemxYIbY7zIio.tGHBWQL0M2Q9e7npFLy', 'vendor'),
(17, 'yara yasser', 'yara2@gmail.com', '$2y$10$S87ayKTt6XHMIlUZVBT2HuJvWiaG3mZ3ezgok5wBBEh/AYXgsN0c6', 'customer'),
(18, 'Alma Adams', 'Alma@gmail.com', '$2y$10$JZMcpnU2zMJlP.CobSlvkuX/S8FRqPvVjC/TOYiC.CbI1Sok1gPwe', 'vendor'),
(19, 'Jasmine Jake', 'jasmine@gmail.com', '$2y$10$Vvujn00r6iq9m3R.IcYyDO5gpdVburDKjbJ.90mzZZWle6eavCroy', 'vendor'),
(21, 'Jane Abrams', 'jane@gmail.com', '$2y$10$e1ack6Wtl5s1SAXLTUnLaefu8YvbiDhMASDX0ABOX4vr3p4zDOBSO', 'vendor');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `vendor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `business_description` text DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`vendor_id`, `user_id`, `business_name`, `business_description`, `rating`) VALUES
(1, 2, 'vegan world', 'vegan world provides you with the best vegan options with the best price from anywhere in the world!', 4.80),
(3, 9, 'veganMarket', 'best vegan products from the uk', 0.00),
(4, 10, 'ArizonaVe', 'we provide you with a huge collection of vegan products from Arizona-USA', 0.00),
(8, 21, 'Jane\'s Goods', 'Jane\'s Goods provides 100% vegan products for you from Arizon-USA', 4.96);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`card_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`complaint_id`),
  ADD KEY `fk_complaints_customer` (`customer_id`),
  ADD KEY `fk_complaints_order` (`order_id`),
  ADD KEY `fk_complaints_vendor` (`vendor_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_orders_customer` (`customer_id`),
  ADD KEY `fk_orders_shipping_company` (`shipping_company_id`),
  ADD KEY `fk_orders_card` (`card_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `fk_order_items_order` (`order_id`),
  ADD KEY `fk_order_items_product` (`product_id`),
  ADD KEY `fk_vendor_id` (`vendor_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_products_vendor` (`vendor_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `fk_ratings_customer` (`customer_id`),
  ADD KEY `fk_ratings_vendor` (`vendor_id`),
  ADD KEY `fk_ratings_product` (`product_id`);

--
-- Indexes for table `shipping_companies`
--
ALTER TABLE `shipping_companies`
  ADD PRIMARY KEY (`company_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vendor_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `card_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_companies`
--
ALTER TABLE `shipping_companies`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `fk_complaints_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_complaints_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_complaints_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON DELETE CASCADE;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `fk_customers_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `fk_employees_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_card` FOREIGN KEY (`card_id`) REFERENCES `cards` (`card_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_orders_shipping_company` FOREIGN KEY (`shipping_company_id`) REFERENCES `shipping_companies` (`company_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_vendor_id` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `fk_ratings_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ratings_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ratings_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON DELETE CASCADE;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `fk_vendors_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
