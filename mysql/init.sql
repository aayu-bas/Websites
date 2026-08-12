-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 10, 2026 at 05:18 AM
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
-- Database: `yarnify_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_type` enum('shipping','billing') DEFAULT 'shipping',
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(50) DEFAULT 'India',
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('super_admin','admin','manager') DEFAULT 'admin',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `email`, `password_hash`, `full_name`, `role`, `is_active`, `created_at`, `last_login`) VALUES
(1, 'admin', 'admin@yarnify.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Yarnify Admin', 'super_admin', 1, '2026-07-23 12:39:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `session_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, '2026-07-27 14:54:42', '2026-07-27 14:54:42');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`cart_item_id`, `cart_id`, `product_id`, `quantity`, `unit_price`, `added_at`) VALUES
(15, 1, 3, 3, 299.00, '2026-08-09 17:20:57'),
(16, 1, 2, 1, 499.00, '2026-08-09 17:35:45');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `slug`, `description`, `icon`, `image`, `display_order`, `is_active`, `created_at`) VALUES
(1, 'Amigurumi', 'amigurumi', 'Adorable crochet stuffed animals and plush toys', 'fa-paw', NULL, 1, 1, '2026-07-23 12:39:23'),
(2, 'Wearables', 'wearables', 'Cozy crochet clothing and accessories', 'fa-hat-cowboy', NULL, 2, 1, '2026-07-23 12:39:23'),
(3, 'Decors', 'decors', 'Beautiful home decoration pieces', 'fa-home', NULL, 3, 1, '2026-07-23 12:39:23'),
(4, 'Characters', 'characters', 'Crochet figures of popular characters', 'fa-star', NULL, 4, 1, '2026-07-23 12:39:23'),
(5, 'Keychains', 'keychains', 'Cute crochet keychains and bag charms', 'fa-key', NULL, 5, 1, '2026-07-23 12:39:23');

-- --------------------------------------------------------

--
-- Table structure for table `custom_crochet_requests`
--

CREATE TABLE `custom_crochet_requests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_type` enum('amigurumi','wearable','decor','character','keychain','other') NOT NULL,
  `color` varchar(100) NOT NULL,
  `size` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `budget` decimal(10,2) NOT NULL,
  `reference_image` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed') DEFAULT 'pending',
  `admin_remarks` text DEFAULT NULL,
  `final_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_number` varchar(20) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled','refunded') DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `shipping_amount` decimal(10,2) DEFAULT 0.00,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `final_amount` decimal(10,2) NOT NULL,
  `shipping_address_id` int(11) DEFAULT NULL,
  `billing_address_id` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_method` enum('cod','card','upi','wallet') NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','success','failed','refunded') DEFAULT 'pending',
  `payment_data` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` longtext DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `sku` varchar(50) DEFAULT NULL,
  `weight` varchar(20) DEFAULT NULL,
  `dimensions` varchar(50) DEFAULT NULL,
  `materials` text DEFAULT NULL,
  `care_instructions` text DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_on_sale` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `product_name`, `slug`, `description`, `short_description`, `price`, `sale_price`, `stock_quantity`, `sku`, `weight`, `dimensions`, `materials`, `care_instructions`, `is_featured`, `is_on_sale`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bunny Amigurumi', 'bunny-amigurumi', 'Handmade with love, this adorable bunny amigurumi is perfect for gifting or keeping as a desk companion. Made with premium cotton yarn and safety eyes.', 'Adorable handmade bunny plush', 599.00, 449.00, 15, 'AMI-001', NULL, NULL, '100% Cotton Yarn, Safety Eyes, Polyester Fill', NULL, 1, 1, 1, '2026-07-23 12:39:23', '2026-07-23 15:27:01'),
(2, 1, 'Bear Plush', 'bear-plush', 'This sleepy bear is the perfect bedtime companion. Soft, squishy, and irresistibly cute!', 'Soft bedtime bear companion', 499.00, NULL, 20, 'AMI-002', NULL, NULL, 'Acrylic Yarn, Safety Eyes', NULL, 1, 0, 1, '2026-07-23 12:39:23', '2026-07-23 15:27:14'),
(3, 2, 'Beanie', 'beanie', 'Stay warm and stylish with this handmade crochet beanie. Available in multiple colors and fits all head sizes.', 'Warm handmade winter beanie', 399.00, 299.00, 25, 'WEA-001', NULL, NULL, 'Wool Blend Yarn', NULL, 1, 1, 1, '2026-07-23 12:39:23', '2026-07-23 15:27:32'),
(4, 2, 'Boho Crochet Scarf', 'boho-crochet-scarf', 'A beautiful bohemian-style crochet scarf that adds charm to any outfit. Lightweight yet warm.', 'Boho style handmade scarf', 599.00, NULL, 10, 'WEA-002', NULL, NULL, 'Cotton Blend Yarn', NULL, 1, 0, 1, '2026-07-23 12:39:23', '2026-07-23 12:39:23'),
(5, 3, 'Flower Vase', 'flower-vase', 'A unique decorative vase cover that transforms any plain vase into a work of art. Perfect for dried flowers.', 'Decorative crochet vase cover', 349.00, 249.00, 30, 'DEC-001', NULL, NULL, 'Cotton Yarn, Jute Rope', NULL, 1, 1, 1, '2026-07-23 12:39:23', '2026-07-23 15:28:04'),
(6, 3, 'Hollow Knight', 'Hollow_knight', 'The Hollow Knight Collectible Figures is a crochet made to bring the iconic indie game protagonist to life', 'Hollow knight figure', 899.00, NULL, 8, 'DEC-002', NULL, NULL, 'Cotton Yarn, Wooden Ring', NULL, 1, 0, 1, '2026-07-23 12:39:23', '2026-07-28 04:37:12'),
(7, 4, 'Mini Pikachu Character', 'mini-pikachu-character', 'A cute mini Pikachu crochet figure that every Pokemon fan will love. Perfect for desk display!', 'Cute Pokemon character figure', 449.00, 349.00, 12, 'CHR-001', NULL, NULL, 'Cotton Yarn, Felt Details', NULL, 1, 1, 1, '2026-07-23 12:39:23', '2026-07-23 12:39:23'),
(8, 4, 'Groot Plant Holder', 'groot-plant-holder', 'I am Groot! This adorable crochet Groot doubles as a small plant holder. Perfect for succulents!', 'Groot plant holder figure', 549.00, NULL, 6, 'CHR-002', NULL, NULL, 'Cotton Yarn, Wire Frame', NULL, 1, 0, 1, '2026-07-23 12:39:23', '2026-07-23 12:39:23'),
(9, 5, 'Cat Paw Keychain', 'cat-paw-keychain', 'A tiny, cute cat paw keychain that adds personality to your keys or bag. Lightweight and durable.', 'Cute cat paw keychain', 149.00, 99.00, 50, 'KEY-001', NULL, NULL, 'Cotton Yarn, Key Ring', NULL, 1, 1, 1, '2026-07-23 12:39:23', '2026-07-23 12:39:23'),
(10, 5, 'Mini Donut Keychain', 'mini-donut-keychain', 'A sweet mini donut keychain that looks good enough to eat! Great gift for food lovers.', 'Sweet donut keychain', 129.00, NULL, 40, 'KEY-002', NULL, NULL, 'Cotton Yarn, Key Ring', NULL, 1, 0, 1, '2026-07-23 12:39:23', '2026-07-23 12:39:23');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `image_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `alt_text` varchar(100) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`image_id`, `product_id`, `image_path`, `alt_text`, `is_primary`, `display_order`, `created_at`) VALUES
(1, 1, 'bunny-amigurumi.jpg', 'Cute Bunny Amigurumi', 1, 1, '2026-07-23 12:39:23'),
(2, 2, 'sleepy-bear.jpg', 'Sleepy Bear Plush', 1, 1, '2026-07-23 12:39:23'),
(3, 3, 'crochet-beanie.jpg', 'Crochet Beanie', 1, 1, '2026-07-23 12:39:23'),
(4, 4, 'boho-scarf.webp', 'Boho Crochet Scarf', 1, 1, '2026-07-23 12:39:23'),
(5, 5, 'flower-vase.jpg', 'Crochet Flower Vase', 1, 1, '2026-07-23 12:39:23'),
(6, 6, 'hk.jpg', 'hollow knight figures', 1, 1, '2026-07-23 12:39:23'),
(7, 7, 'pikachu-figure.jpg', 'Mini Pikachu Character', 1, 1, '2026-07-23 12:39:23'),
(8, 8, 'groot-holder.jpg', 'Groot Plant Holder', 1, 1, '2026-07-23 12:39:23'),
(9, 9, 'cat-paw-keychain.jpg', 'Cat Paw Keychain', 1, 1, '2026-07-23 12:39:23'),
(10, 10, 'donut-keychain.jpg', 'Mini Donut Keychain', 1, 1, '2026-07-23 12:39:23');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `title` varchar(100) DEFAULT NULL,
  `review_text` text DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password_hash`, `phone`, `avatar`, `is_active`, `created_at`, `updated_at`, `last_login`, `remember_token`) VALUES
(1, 'keira', 'rai', 'keira@gmail.com', '$2y$10$cyc7hXSh1htBi8fMLFV/uO7tqBaC/JHab9R4AzWemjZKArD6vsdRy', '', NULL, 1, '2026-07-27 14:54:42', '2026-08-09 13:54:49', '2026-08-09 13:54:49', '5182edebb700ac800e4e13457929dc42d072c3a65186b838eb14429bcf5871c2');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_default` (`is_default`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_session` (`session_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD UNIQUE KEY `unique_cart_product` (`cart_id`,`product_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_cart` (`cart_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `custom_crochet_requests`
--
ALTER TABLE `custom_crochet_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `shipping_address_id` (`shipping_address_id`),
  ADD KEY `billing_address_id` (`billing_address_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_order_number` (`order_number`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_order` (`order_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `idx_order` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_featured` (`is_featured`),
  ADD KEY `idx_sale` (`is_on_sale`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD UNIQUE KEY `unique_user_product_review` (`user_id`,`product_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_approved` (`is_approved`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD UNIQUE KEY `unique_wishlist` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `custom_crochet_requests`
--
ALTER TABLE `custom_crochet_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `custom_crochet_requests`
--
ALTER TABLE `custom_crochet_requests`
  ADD CONSTRAINT `custom_crochet_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`shipping_address_id`) REFERENCES `addresses` (`address_id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`billing_address_id`) REFERENCES `addresses` (`address_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
