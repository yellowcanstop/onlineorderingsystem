-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2024 at 08:49 AM
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
-- Database: `cart`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `account_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('employee','customer') NOT NULL DEFAULT 'customer',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `username`, `password`, `role`, `status`, `email`) VALUES
(1, 'employee1', '$2y$10$SfhYIDtn.iOuCW7zfoFLuuZHX6lja4lF4XA4JqNmpiH/.P3zB8JCa', 'employee', 'active', 'employee1@test.com'),
(3, 'test', '$2y$10$SfhYIDtn.iOuCW7zfoFLuuZHX6lja4lF4XA4JqNmpiH/.P3zB8JCa', 'customer', 'active', 'test@test.com'),
(6, 'frodobaggins', '$2y$10$u6lQp5DcYOnN.5K7vM7tJ.p356EV9P9ba8DhJVkKp2vBfP0/n0vzm', 'customer', 'active', 'frodo@test.com'),
(7, 'voldemort', '$2y$10$I.OMTyEh24YPQD3xO95RJupzC/sZfESjrxUjL6u4ohFjdiyLYjB5e', 'customer', 'active', 'voldemort@test.com'),
(8, 'bellatrix', '$2y$10$YkMOScuhLtagU1BT7KfnEuneN9wGMonXYPIe5KfMf0CsYFjN4TJ6i', 'customer', 'active', 'bella@test.com'),
(9, 'ron', '$2y$10$7jzb4cIapwTewxVwjJg6j.0KPA1zelwgmmgDQUbmji46csyPSL.di', 'customer', 'active', 'ron@test.com'),
(10, 'tomriddle', '$2y$10$S69CwSyYX91xxFOR5wTEPO0Sb355cQc0rb..FpVxlAf1LZlLMyhq.', 'customer', 'active', 'tom@test.com'),
(11, 'shutest', '$2y$10$mE0xVG1Owj8NcnFPdqALpuxuqsK2FRvW.0PUL6AXZBuHBx1szcHey', 'customer', 'active', 'shu@test.com');

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `line_1` varchar(255) NOT NULL,
  `line_2` varchar(255) DEFAULT NULL,
  `zip_postcode` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `line_1`, `line_2`, `zip_postcode`, `state`) VALUES
(1, '44 Wool\'s Orphanage', 'London', '56000', 'state4'),
(2, '44 Wool\'s Orphanage', 'London', '56000', 'state4'),
(3, 'Hogwarts', '', '14000', 'Penang'),
(4, 'Hogwarts', '', '14000', 'Penang'),
(5, '44 Grimmauld Place', 'London', '56000', 'Pahang'),
(6, '44 Grimmauld Place', 'London', '56000', ''),
(7, '44 Grimmauld Place', 'London', '56000', 'Pahang'),
(8, '44 Grimmauld Place', 'London', '56000', 'Pahang');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `img`) VALUES
(1, 'Appetizer', 'salad.jpg'),
(2, 'Main Course', 'fish.jpg'),
(3, 'Dessert', 'tart.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `customer_first_name` varchar(255) NOT NULL,
  `customer_last_name` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `date_of_register` datetime NOT NULL,
  `account_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_first_name`, `customer_last_name`, `customer_phone`, `date_of_register`, `account_id`) VALUES
(2, 'Frodo', 'Baggins', '123-420-8888', '2024-03-30 13:31:57', 6),
(4, 'Vol', 'Mort', '888-888-8888', '2024-04-01 06:46:28', 7),
(6, 'Bellatrix', 'Lestrange', '1231231234', '2024-04-02 15:51:58', 8),
(8, 'Ron', 'Weasley', '0107993388', '2024-04-02 16:10:41', 9),
(10, 'Tom', 'Riddle', '1444444444', '2024-04-02 16:12:09', 10),
(11, 'shu', 'test', '1111111111', '2024-04-08 08:45:12', 11);

-- --------------------------------------------------------

--
-- Table structure for table `customer_addresses`
--

CREATE TABLE `customer_addresses` (
  `customer_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `is_default` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `customer_addresses`
--

INSERT INTO `customer_addresses` (`customer_id`, `address_id`, `is_default`) VALUES
(10, 2, 0),
(10, 3, 0),
(10, 4, 0),
(10, 5, 1),
(10, 6, 0),
(10, 7, 0),
(10, 8, 0);

-- --------------------------------------------------------

--
-- Table structure for table `customer_orders`
--

CREATE TABLE `customer_orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_payment_method_id` int(11) NOT NULL,
  `order_status_code` enum('unpaid','paid','fulfilled','cancelled') NOT NULL DEFAULT 'unpaid',
  `date_order_placed` datetime NOT NULL,
  `date_order_paid` datetime NOT NULL,
  `payment_amount` decimal(7,2) NOT NULL,
  `date_order_fulfilled` datetime NOT NULL,
  `date_order_cancelled` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `customer_orders`
--

INSERT INTO `customer_orders` (`order_id`, `customer_id`, `customer_payment_method_id`, `order_status_code`, `date_order_placed`, `date_order_paid`, `payment_amount`, `date_order_fulfilled`, `date_order_cancelled`) VALUES
(1, 10, 1, 'fulfilled', '2024-04-02 17:34:38', '2024-04-12 14:50:47', 101.95, '2024-04-12 14:51:15', '0000-00-00 00:00:00'),
(2, 10, 2, 'cancelled', '2024-04-02 17:40:26', '2024-04-02 17:40:49', 49.98, '0000-00-00 00:00:00', '2024-04-12 14:54:53'),
(3, 10, 1, 'paid', '2024-04-02 18:14:13', '2024-04-12 16:00:29', 29.99, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 10, 2, 'cancelled', '2024-04-02 18:17:04', '2024-04-02 18:17:35', 19.99, '0000-00-00 00:00:00', '2024-04-12 16:02:55'),
(5, 10, 1, 'paid', '2024-04-02 18:34:50', '2024-04-12 21:05:35', 69.97, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 10, 1, 'unpaid', '2024-04-12 18:20:16', '0000-00-00 00:00:00', 69.97, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 10, 1, 'unpaid', '2024-04-12 20:22:30', '0000-00-00 00:00:00', 39.99, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 10, 1, 'unpaid', '2024-04-12 20:50:22', '0000-00-00 00:00:00', 29.99, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 10, 1, 'unpaid', '2024-04-12 20:51:23', '0000-00-00 00:00:00', 19.99, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `customer_orders_products`
--

CREATE TABLE `customer_orders_products` (
  `order_id` int(11) NOT NULL,
  `dish_id` int(11) NOT NULL,
  `order_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `customer_orders_products`
--

INSERT INTO `customer_orders_products` (`order_id`, `dish_id`, `order_quantity`) VALUES
(1, 1, 2),
(1, 1, 2),
(1, 2, 2),
(1, 2, 2),
(1, 4, 1),
(1, 4, 1),
(2, 1, 1),
(2, 4, 1),
(3, 4, 1),
(4, 1, 1),
(5, 1, 2),
(5, 4, 1),
(6, 1, 2),
(6, 4, 1),
(7, 3, 1),
(8, 4, 1),
(9, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `dishes`
--

CREATE TABLE `dishes` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `img` text NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `dishes`
--

INSERT INTO `dishes` (`id`, `name`, `description`, `price`, `quantity`, `img`, `category_id`) VALUES
(1, 'Signature Tart', '<p>This tart loves you berry much.</p>\r\n<h3>Why?</h3>\r\n<ul>\r\n<li>There is always room for dessert.</li>\r\n<li>Keto-friendly.</li>\r\n<li>Available whole or by slice.</li>\r\n</ul>', 19.99, 17, 'tart.jpg', 3),
(2, 'Egg', '<p>Eggs are good for you.</p>\r\n<h3>Why?</h3>\r\n<ul>\r\n<li>There is always room for more.</li>\r\n<li>Keto-friendly.</li>\r\n<li>Yes.</li>\r\n</ul>', 15.99, 0, 'egg.jpg', 2),
(3, 'Fish', '<p>Fish is good for you.</p>\r\n<h3>Why?</h3>\r\n<ul>\r\n<li>There is always room for more.</li>\r\n<li>Keto-friendly.</li>\r\n<li>Yes.</li>\r\n</ul>', 39.99, 3, 'fish.jpg', 2),
(4, 'Salad', '<p>Salad is good for you.</p>\r\n<h3>Why?</h3>\r\n<ul>\r\n<li>There is always room for more.</li>\r\n<li>Keto-friendly.</li>\r\n<li>Yes.</li>\r\n</ul>', 29.99, 8, 'salad.jpg', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_phone` (`customer_phone`),
  ADD KEY `account_id_fk` (`account_id`);

--
-- Indexes for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `address_id` (`address_id`);

--
-- Indexes for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `customer_orders_products`
--
ALTER TABLE `customer_orders_products`
  ADD KEY `order_id` (`order_id`),
  ADD KEY `dish_id` (`dish_id`);

--
-- Indexes for table `dishes`
--
ALTER TABLE `dishes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoryid` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customer_orders`
--
ALTER TABLE `customer_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `dishes`
--
ALTER TABLE `dishes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `account_id_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON UPDATE CASCADE;

--
-- Constraints for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD CONSTRAINT `customer_addresses_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `customer_addresses_ibfk_3` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`);

--
-- Constraints for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD CONSTRAINT `customer_orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `customer_orders_products`
--
ALTER TABLE `customer_orders_products`
  ADD CONSTRAINT `customer_orders_products_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `customer_orders` (`order_id`),
  ADD CONSTRAINT `customer_orders_products_ibfk_2` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`id`);

--
-- Constraints for table `dishes`
--
ALTER TABLE `dishes`
  ADD CONSTRAINT `categoryid` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
