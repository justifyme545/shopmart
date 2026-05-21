-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2026 at 06:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `shipping_address` text NOT NULL,
  `shipping_city` varchar(50) NOT NULL,
  `shipping_zip` varchar(20) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `total_amount`, `status`, `shipping_address`, `shipping_city`, `shipping_zip`, `payment_method`, `created_at`) VALUES
(1, 4, 'ORD-1779289745-888', 5070.00, 'pending', 'Marlum Quarters 3', 'N/A', 'N/A', 'Cash on Delivery', '2026-05-20 15:09:05'),
(2, 5, 'ORD-1779297976-459', 5140.00, 'pending', 'Marlum Quarters 3', 'N/A', 'N/A', 'card', '2026-05-20 17:26:16'),
(3, 5, 'ORD-1779298193-211', 5140.00, 'processing', 'Marlum Quarters 3', 'N/A', 'N/A', 'cash_on_delivery', '2026-05-20 17:29:53'),
(4, 4, 'ORD-1779372170-389', 5115.00, 'pending', 'Marlum Quarters 3', 'N/A', 'N/A', 'card', '2026-05-21 14:02:50'),
(5, 4, 'ORD-1779372210-399', 5115.00, 'pending', 'Marlum Quarters 3', 'N/A', 'N/A', 'bank_transfer', '2026-05-21 14:03:30'),
(6, 4, 'ORD-1779372213-336', 5115.00, 'pending', 'Marlum Quarters 3', 'N/A', 'N/A', 'bank_transfer', '2026-05-21 14:03:33'),
(7, 4, 'ORD-1779372224-626', 5115.00, 'pending', 'Marlum Quarters 3', 'N/A', 'N/A', 'cash_on_delivery', '2026-05-21 14:03:44'),
(8, 4, 'ORD-1779377874-736', 255115.00, 'processing', 'Marlum Quarters 3', 'N/A', 'N/A', 'cash_on_delivery', '2026-05-21 15:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 8, 1, 70.00),
(2, 2, 8, 2, 70.00),
(3, 3, 8, 2, 70.00),
(4, 4, 8, 1, 70.00),
(5, 4, 7, 1, 45.00),
(6, 5, 8, 1, 70.00),
(7, 5, 7, 1, 45.00),
(8, 6, 8, 1, 70.00),
(9, 6, 7, 1, 45.00),
(10, 7, 8, 1, 70.00),
(11, 7, 7, 1, 45.00),
(12, 8, 10, 5, 50000.00),
(13, 8, 8, 1, 70.00),
(14, 8, 7, 1, 45.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `stock` int(11) DEFAULT 0,
  `featured` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `category`, `image`, `description`, `created_at`, `stock`, `featured`, `status`, `created_by`) VALUES
(1, 'Casual T-Shirt', 19.99, 'Fashion', 'fashion1.jpg', 'Comfortable cotton t-shirt for everyday wear', '2026-05-20 13:11:35', 0, 0, 'active', NULL),
(2, 'Slim Fit Jeans', 39.99, 'Fashion', 'fashion2.jpg', 'Modern slim fit jeans with stretch fabric', '2026-05-20 13:11:35', 0, 0, 'active', NULL),
(3, 'Leather Jacket', 89.99, 'Fashion', 'fashion3.jpg', 'Genuine leather jacket for a stylish look', '2026-05-20 13:11:35', 0, 0, 'active', NULL),
(4, 'Wireless Earbuds', 49.99, 'Gadgets', 'gadget1.jpg', 'Bluetooth earbuds with charging case', '2026-05-20 13:11:35', 0, 0, 'active', NULL),
(5, 'Smart Watch', 99.99, 'Gadgets', 'gadget5.jpg', 'Fitness tracker with heart rate monitor', '2026-05-20 13:11:35', 0, 0, 'active', NULL),
(6, 'Gold Necklace', 120.00, 'Jewelries', 'jewel1.jpg', 'Elegant gold plated necklace', '2026-05-20 13:11:35', 0, 0, 'active', NULL),
(7, 'Silver Ring', 45.00, 'Jewelries', 'jewel2.jpg', 'Sterling silver ring with cubic zirconia', '2026-05-20 13:11:35', 0, 0, 'active', NULL),
(8, 'Running Shoes', 70.00, 'Sportwears', 'sport1.jpg', 'Lightweight running shoes for maximum comfort', '2026-05-20 13:11:35', 0, 0, 'active', NULL),
(9, 'Classic Watch', 150.00, 'Wristwatches', 'watch1.jpg', 'Classic analog watch with leather strap', '2026-05-20 13:11:35', 0, 0, 'active', NULL),
(10, 'Royal Museums Greenwich', 50000.00, 'Wristwatches', '1779376077_Royal museums Greenwich.jpg', 'Quality Watch with Original Leather Strap', '2026-05-21 15:07:57', 150, 0, 'active', 3);

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `phone`, `address`, `city`, `zip_code`, `created_at`, `updated_at`, `role`) VALUES
(3, 'Admin', 'User', 'admin@shopmart.com', '$2y$10$Cbakno2lp.cU6/8zNiKqw.f46Qcs.HY6S9Phaisj13dakY7TjDh1O', NULL, NULL, NULL, NULL, '2026-05-20 14:11:19', '2026-05-20 14:11:19', 'admin'),
(4, 'Justice', 'Festus', 'justicefest@gmail.com', '$2y$10$OsiCkUjZcbTXwbgJzpSbU.prglqyuhqEffCQLAQyEJGlD.vRlTF.2', NULL, NULL, NULL, NULL, '2026-05-20 14:24:02', '2026-05-20 14:24:02', 'user'),
(5, 'Akachukwu', 'Nwaukwa', 'just.fest@yahoo.com', '$2y$10$x0Bg4FImAD18lLMnlITmOuYua2I/BojhzxHfn5Dy.rbEC6AX/WDjq', NULL, NULL, NULL, NULL, '2026-05-20 15:38:11', '2026-05-20 15:38:11', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
