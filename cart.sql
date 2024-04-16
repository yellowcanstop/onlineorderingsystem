-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2024 at 05:40 PM
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
  `email` varchar(255) DEFAULT NULL,
  `remember_me_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `username`, `password`, `role`, `status`, `email`, `remember_me_token`) VALUES
(1, 'employee1', '$2y$10$SfhYIDtn.iOuCW7zfoFLuuZHX6lja4lF4XA4JqNmpiH/.P3zB8JCa', 'employee', 'active', 'employee1@test.com', NULL),
(3, 'test', '$2y$10$SfhYIDtn.iOuCW7zfoFLuuZHX6lja4lF4XA4JqNmpiH/.P3zB8JCa', 'customer', 'active', 'test@test.com', NULL),
(6, 'frodobaggins', '$2y$10$u6lQp5DcYOnN.5K7vM7tJ.p356EV9P9ba8DhJVkKp2vBfP0/n0vzm', 'customer', 'active', 'frodo@test.com', NULL),
(7, 'voldemort', '$2y$10$I.OMTyEh24YPQD3xO95RJupzC/sZfESjrxUjL6u4ohFjdiyLYjB5e', 'customer', 'active', 'voldemort@test.com', NULL),
(8, 'bellatrix', '$2y$10$YkMOScuhLtagU1BT7KfnEuneN9wGMonXYPIe5KfMf0CsYFjN4TJ6i', 'customer', 'active', 'bella@test.com', NULL),
(9, 'ron', '$2y$10$7jzb4cIapwTewxVwjJg6j.0KPA1zelwgmmgDQUbmji46csyPSL.di', 'customer', 'active', 'ron@test.com', NULL),
(10, 'tomriddle', '$2y$10$S69CwSyYX91xxFOR5wTEPO0Sb355cQc0rb..FpVxlAf1LZlLMyhq.', 'customer', 'active', 'tom@test.com', NULL),
(11, 'shutest', '$2y$10$mE0xVG1Owj8NcnFPdqALpuxuqsK2FRvW.0PUL6AXZBuHBx1szcHey', 'customer', 'active', 'shu@test.com', NULL),
(12, 'dracomalfoy', '$2y$10$6XQbnsyfBz94GOk468yV.eXI69.NP4rr5y50RvYUqfV4zh/LX5Txe', 'customer', 'active', 'draco@test.com', 'f9e8bb07a043eedde0e8ec70f7c1ad34c545603f909c207d');

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
(8, '44 Grimmauld Place', 'London', '56000', 'Pahang'),
(9, 'University of Nottingham Malaysia', 'Semenyih', '43500', 'Selangor'),
(10, '17 Malfoy Manor', 'Wiltshire', '44444', 'Putrajaya'),
(11, '17 Malfoy Manor', 'Wiltshire', '44444', 'Putrajaya'),
(12, '17 Malfoy Manor', 'Wiltshire', '44444', 'Putrajaya'),
(13, '17 Malfoy Manor', 'Wiltshire', '44444', 'Putrajaya'),
(14, '17 Malfoy Manor', 'Wiltshire', '44444', 'Putrajaya');

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
(1, 'Appetizer', 'main-appetizer.jpg'),
(2, 'Main Course', 'main-maincourse.jpg'),
(3, 'Dessert', 'main-dessert.jpg'),
(4, 'Beverage', 'main-beverage.jpg');

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
(11, 'shu', 'test', '1111111111', '2024-04-08 08:45:12', 11),
(12, 'Draco', 'Malfoy', '0107991111', '2024-04-16 09:49:04', 12);

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
(10, 8, 0),
(12, 9, 1),
(12, 10, 0),
(12, 11, 0),
(12, 12, 0),
(12, 13, 0);

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
  `date_order_cancelled` datetime NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `customer_orders`
--

INSERT INTO `customer_orders` (`order_id`, `customer_id`, `customer_payment_method_id`, `order_status_code`, `date_order_placed`, `date_order_paid`, `payment_amount`, `date_order_fulfilled`, `date_order_cancelled`, `name`, `phone`, `email`, `address_id`) VALUES
(1, 10, 1, 'fulfilled', '2024-04-02 17:34:38', '2024-04-12 14:50:47', 101.95, '2024-04-12 14:51:15', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
(2, 10, 2, 'cancelled', '2024-04-02 17:40:26', '2024-04-02 17:40:49', 49.98, '0000-00-00 00:00:00', '2024-04-12 14:54:53', NULL, NULL, NULL, NULL),
(3, 10, 1, 'paid', '2024-04-02 18:14:13', '2024-04-12 16:00:29', 29.99, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
(4, 10, 2, 'cancelled', '2024-04-02 18:17:04', '2024-04-02 18:17:35', 19.99, '0000-00-00 00:00:00', '2024-04-12 16:02:55', NULL, NULL, NULL, NULL),
(5, 10, 1, 'paid', '2024-04-02 18:34:50', '2024-04-12 21:05:35', 69.97, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
(6, 10, 1, 'unpaid', '2024-04-12 18:20:16', '0000-00-00 00:00:00', 69.97, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
(7, 10, 1, 'unpaid', '2024-04-12 20:22:30', '0000-00-00 00:00:00', 39.99, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
(8, 10, 1, 'unpaid', '2024-04-12 20:50:22', '0000-00-00 00:00:00', 29.99, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
(9, 10, 1, 'unpaid', '2024-04-12 20:51:23', '0000-00-00 00:00:00', 19.99, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL),
(10, 11, 1, 'unpaid', '2024-04-16 14:36:03', '0000-00-00 00:00:00', 88.68, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
(11, 12, 1, 'unpaid', '2024-04-16 16:14:25', '0000-00-00 00:00:00', 119.97, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Draco Malfoy', '0107991111', 'draco@test.com', 9),
(12, 12, 3, 'unpaid', '2024-04-16 16:31:30', '0000-00-00 00:00:00', 29.99, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Draco Malfoy', '0107991111', 'draco@test.com', 10),
(13, 12, 3, 'unpaid', '2024-04-16 16:35:51', '0000-00-00 00:00:00', 29.99, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Draco Malfoy', '0107991111', 'draco@test.com', 11),
(14, 12, 2, 'unpaid', '2024-04-16 16:36:05', '0000-00-00 00:00:00', 29.99, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Draco Malfoy', '0107991111', 'draco@test.com', 12),
(15, 12, 3, 'paid', '2024-04-16 16:36:59', '2024-04-16 16:37:06', 29.99, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Draco Malfoy', '0107991111', 'draco@test.com', 13);

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
(9, 1, 1),
(10, 9, 1),
(10, 12, 1),
(10, 16, 1),
(10, 3, 3),
(11, 4, 1),
(12, 4, 1),
(13, 4, 1),
(14, 4, 1);

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
(1, 'Truffle Crostini', '<em>Appetizer</em>\r\n<p>Indulge in the luxurious aroma and flavor of black truffles delicately infused into a creamy spread atop perfectly toasted crostini. Each bite is a harmonious blend of earthy richness and crisp, golden perfection, elevating the senses with every savory sensation.</p>\r\n', 18.35, 17, 'trufflecrostini.jpg', 1),
(2, 'Michelin\'s Egg', '<em>Main Course</em>\r\n<p>Inspired by the culinary mastery of Michelin-starred chefs, this dish features a perfectly poached egg nestled atop a bed of creamy risotto, enriched with a velvety sauce. With each spoonful, the yolk cascades, mingling with the decadent risotto to create a luxurious symphony of flavors and textures.</p>\r\n', 15.99, 0, 'egg.jpg', 2),
(3, 'Royal Salmon', '<em>Main Course</em>\r\n<p>Fit for royalty, our Royal Salmon is a majestic presentation of premium, melt-in-your-mouth salmon fillet, delicately seasoned and grilled to perfection. Each forkful reveals tender flakes of salmon, infused with a hint of smoky char and served with a garnish of vibrant seasonal vegetables. A regal feast for the senses.</p>\r\n', 39.99, 3, 'fish.jpg', 2),
(4, 'Garden Gourmet', '<em>Appetizer</em>\r\n<p>A vibrant and artfully arranged platter showcasing the freshest seasonal produce, delicately prepared to highlight their natural flavors and textures. Each bite is a symphony of colors and tastes, inviting diners on a journey through the garden.</p>\r\n', 29.99, 8, 'garden.png', 1),
(5, 'Signature Tart', '<em>Dessert</em>\r\n<p>Our Signature Tart is a culinary masterpiece, featuring a delicate, flaky crust filled with a luscious and perfectly balanced blend of seasonal fruits or decadent fillings. Each bite is a symphony of flavors and textures, offering a tantalizing journey for the taste buds that culminates in pure dessert bliss.</p>\r\n', 12.89, 17, 'tart.png', 3),
(6, 'Caviar Blini', '<em>Appetizer</em>\r\n<p>Experience the epitome of opulence with our exquisite caviar blini. Delicate buckwheat pancakes serve as the perfect canvas for the velvety richness of premium caviar, complemented by a whisper of crème fraîche and the subtle crunch of finely diced shallots. Every bite is a decadent celebration of luxury and sophistication.</p>\r\n', 28.50, 15, 'caviar.png', 1),
(7, 'Lobster Medallions', '<em>Appetizer</em>\r\n<p>Succulent lobster medallions, expertly seared to tender perfection and served with a delicate drizzle of clarified butter. Each bite offers a burst of sweet, briny flavor, balanced by the buttery richness of the sauce. A true delicacy that embodies the essence of the sea.</p>\r\n', 22.50, 15, 'lobster.png', 1),
(9, 'Steak Au Poivre', '<em>Main Course</em>\r\n<p>Indulge in the ultimate carnivorous delight with our Steak Au Poivre. Prime cuts of tender beef, expertly seasoned and seared to perfection, are bathed in a rich and peppery cognac sauce. Each bite offers a tantalizing contrast of bold flavors and succulent meat, leaving a lasting impression of pure culinary satisfaction.</p>\r\n', 49.99, 17, 'steak.jpg', 2),
(10, 'Duck Confit', '<em>Main Course</em>\r\n<p>A timeless classic of French cuisine, our Duck Confit is a celebration of tender duck leg, slow-cooked to perfection in its own succulent juices and aromatic herbs. Crispy on the outside yet tender and flavorful on the inside, each bite is a harmonious balance of richness and depth. Served alongside seasonal accompaniments, this dish is sure to captivate even the most discerning palate.</p>\r\n', 32.99, 18, 'duckconfit.jpg', 2),
(11, 'Berry Pavlova', '<em>Dessert</em>\r\n<p>A dreamy confection that captures the essence of summer, our Berry Pavlova is a cloud-like meringue base topped with a vibrant assortment of fresh berries and a whisper of whipped cream. The crisp exterior gives way to a soft, marshmallow-like center, creating a delightful contrast of textures that dances on the palate with bursts of fruity sweetness.</p>\r\n', 12.70, 14, 'pavlova.jpg', 3),
(12, 'Matcha Mousse', '<em>Dessert</em>\r\n<p>Experience the delicate harmony of flavors and textures in our Matcha Mousse. Smooth and velvety green tea-infused mousse is layered atop a buttery biscuit base, creating a dessert that is as visually stunning as it is delicious. Each spoonful is a celebration of the earthy notes of matcha, balanced with the subtle sweetness of cream, culminating in a truly transcendent dessert experience.</p>\r\n', 19.99, 12, 'matchamousse.jpg', 3),
(13, 'Velvet Noir', '<em>Dessert</em>\r\n<p>Indulge in the ultimate chocolate lover\'s fantasy with our Velvet Noir dessert. Rich, decadent layers of dark chocolate ganache and velvety chocolate mousse are enrobed in a glossy chocolate glaze, creating a dessert that is as elegant as it is indulgent. With each luxurious bite, the intense cocoa flavor melts on the tongue, leaving a lingering impression of pure chocolate perfection.</p>\r\n', 18.70, 13, 'chocolate.jpg', 3),
(14, 'Majestic Martini', '<em>Beverage</em>\r\n<p>A timeless classic elevated to new heights, our Majestic Martini is a refined blend of premium vodka or gin, meticulously stirred or shaken to icy perfection. Served in a chilled martini glass and garnished with a twist of lemon or olives, this iconic cocktail is the epitome of sophistication, offering a crisp and refreshing sip that tantalizes the senses.</p>\r\n', 18.70, 13, 'majesticmartini.jpg', 4),
(15, 'Grandiose Gin', '<em>Beverage</em>\r\n<p>Crafted with the finest botanicals and distilled to perfection, our Grandiose Gin cocktail is a celebration of botanical elegance and juniper-forward flavor. Served over ice with a splash of tonic water and garnished with a sprig of fresh herbs or a twist of citrus, each sip is a symphony of complex flavors and aromatic notes, promising a truly indulgent drinking experience.</p>\r\n', 18.70, 13, 'grandiosegin.jpg', 4),
(16, 'Regal Rum', '<em>Beverage</em>\r\n<p>Embark on a journey of Caribbean delight with our Regal Rum cocktail. Crafted with the finest aged rum, balanced with a hint of sweetness and a splash of citrus, this cocktail is served over ice in a chilled glass, transporting you to sun-kissed shores with every sip. Smooth, sophisticated, and irresistibly delicious, it\'s a drink fit for royalty.</p>\r\n', 18.70, 12, 'regalrum.jpg', 4),
(17, 'Prestige Punch', '<em>Beverage</em>\r\n<p>A masterpiece of mixology, our Prestige Punch is a delightful fusion of premium spirits, fruit juices, and a hint of effervescence. Served in an elegant punch bowl with a garnish of fresh fruits and edible flowers, this communal cocktail is perfect for sharing amongst friends or savoring solo. With each sip, you\'ll discover a harmonious blend of flavors that is both refreshing and invigorating, leaving you feeling truly pampered.</p>\r\n', 18.70, 13, 'prestigepunch.jpg', 4);

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
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `address_id` (`address_id`);

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
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `customer_orders`
--
ALTER TABLE `customer_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  ADD CONSTRAINT `customer_orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `customer_orders_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`);

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
