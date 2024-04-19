-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2024 at 11:02 AM
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
-- Database: `sysorder`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_status`
--

CREATE TABLE `account_status` (
  `account_status_id` int(11) NOT NULL,
  `account_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `account_status`
--

INSERT INTO `account_status` (`account_status_id`, `account_status`) VALUES
(1, 'active'),
(2, 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `dish_id` int(11) NOT NULL,
  `cart_quantity` int(11) NOT NULL,
  `cart_timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `img` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `img`) VALUES
(1, 'Appetizer', 'main-appetizer.jpg'),
(2, 'Main Course', 'main-maincourse.jpg'),
(3, 'Dessert', 'main-dessert.jpg'),
(4, 'Beverage', 'main-beverage.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `customer_accounts`
--

CREATE TABLE `customer_accounts` (
  `customer_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `account_status_id` int(11) NOT NULL DEFAULT 1,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `date_of_register` datetime NOT NULL,
  `remember_me_token` varchar(255) DEFAULT NULL,
  `default_address_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_orders`
--

CREATE TABLE `customer_orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `customer_payment_method_id` int(11) NOT NULL,
  `payment_amount` decimal(7,2) NOT NULL,
  `date_order_placed` datetime NOT NULL,
  `date_order_paid` datetime NOT NULL,
  `date_order_fulfilled` datetime NOT NULL,
  `date_order_cancelled` datetime NOT NULL,
  `order_status_id` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_orders_products`
--

CREATE TABLE `customer_orders_products` (
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `dish_id` int(11) NOT NULL,
  `order_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_payment_method`
--

CREATE TABLE `customer_payment_method` (
  `customer_payment_method_id` int(11) NOT NULL,
  `payment_method` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `customer_payment_method`
--

INSERT INTO `customer_payment_method` (`customer_payment_method_id`, `payment_method`) VALUES
(1, 'cash'),
(2, 'credit card'),
(3, 'ewallet');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_addresses`
--

CREATE TABLE `delivery_addresses` (
  `address_id` int(11) NOT NULL,
  `line_1` varchar(255) NOT NULL,
  `line_2` varchar(255) DEFAULT NULL,
  `zip_postcode` varchar(255) NOT NULL,
  `city_state` varchar(255) NOT NULL,
  `iso_country_code` varchar(255) NOT NULL DEFAULT 'MY'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_recipients`
--

CREATE TABLE `delivery_recipients` (
  `recipient_id` int(11) NOT NULL,
  `recipient_name` varchar(255) NOT NULL,
  `recipient_phone` varchar(255) NOT NULL,
  `recipient_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dishes`
--

CREATE TABLE `dishes` (
  `dish_id` int(11) NOT NULL,
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

INSERT INTO `dishes` (`dish_id`, `name`, `description`, `price`, `quantity`, `img`, `category_id`) VALUES
(1, 'Truffle Crostini', '<em>Appetizer</em>\r\n<p>Indulge in the luxurious aroma and flavor of black truffles delicately infused into a creamy spread atop perfectly toasted crostini. Each bite is a harmonious blend of earthy richness and crisp, golden perfection, elevating the senses with every savory sensation.</p>\r\n', 18.30, 17, 'trufflecrostini.jpg', 1),
(2, 'Michelin\'s Egg', '<em>Main Course</em>\r\n<p>Inspired by the culinary mastery of Michelin-starred chefs, this dish features a perfectly poached egg nestled atop a bed of creamy risotto, enriched with a velvety sauce. With each spoonful, the yolk cascades, mingling with the decadent risotto to create a luxurious symphony of flavors and textures.</p>\r\n', 15.90, 0, 'egg.jpg', 2),
(3, 'Royal Salmon', '<em>Main Course</em>\r\n<p>Fit for royalty, our Royal Salmon is a majestic presentation of premium, melt-in-your-mouth salmon fillet, delicately seasoned and grilled to perfection. Each forkful reveals tender flakes of salmon, infused with a hint of smoky char and served with a garnish of vibrant seasonal vegetables. A regal feast for the senses.</p>\r\n', 39.90, 3, 'fish.jpg', 2),
(4, 'Garden Gourmet', '<em>Appetizer</em>\r\n<p>A vibrant and artfully arranged platter showcasing the freshest seasonal produce, delicately prepared to highlight their natural flavors and textures. Each bite is a symphony of colors and tastes, inviting diners on a journey through the garden.</p>\r\n', 29.90, 8, 'garden.png', 1),
(5, 'Signature Tart', '<em>Dessert</em>\r\n<p>Our Signature Tart is a culinary masterpiece, featuring a delicate, flaky crust filled with a luscious and perfectly balanced blend of seasonal fruits or decadent fillings. Each bite is a symphony of flavors and textures, offering a tantalizing journey for the taste buds that culminates in pure dessert bliss.</p>\r\n', 12.80, 17, 'tart.png', 3),
(6, 'Caviar Blini', '<em>Appetizer</em>\r\n<p>Experience the epitome of opulence with our exquisite caviar blini. Delicate buckwheat pancakes serve as the perfect canvas for the velvety richness of premium caviar, complemented by a whisper of crème fraîche and the subtle crunch of finely diced shallots. Every bite is a decadent celebration of luxury and sophistication.</p>\r\n', 28.50, 15, 'caviar.png', 1),
(7, 'Lobster Medallions', '<em>Appetizer</em>\r\n<p>Succulent lobster medallions, expertly seared to tender perfection and served with a delicate drizzle of clarified butter. Each bite offers a burst of sweet, briny flavor, balanced by the buttery richness of the sauce. A true delicacy that embodies the essence of the sea.</p>\r\n', 22.50, 15, 'lobster.png', 1),
(8, 'Steak Au Poivre', '<em>Main Course</em>\r\n<p>Indulge in the ultimate carnivorous delight with our Steak Au Poivre. Prime cuts of tender beef, expertly seasoned and seared to perfection, are bathed in a rich and peppery cognac sauce. Each bite offers a tantalizing contrast of bold flavors and succulent meat, leaving a lasting impression of pure culinary satisfaction.</p>\r\n', 49.90, 17, 'steak.jpg', 2),
(9, 'Duck Confit', '<em>Main Course</em>\r\n<p>A timeless classic of French cuisine, our Duck Confit is a celebration of tender duck leg, slow-cooked to perfection in its own succulent juices and aromatic herbs. Crispy on the outside yet tender and flavorful on the inside, each bite is a harmonious balance of richness and depth. Served alongside seasonal accompaniments, this dish is sure to captivate even the most discerning palate.</p>\r\n', 32.90, 18, 'duckconfit.jpg', 2),
(10, 'Berry Pavlova', '<em>Dessert</em>\r\n<p>A dreamy confection that captures the essence of summer, our Berry Pavlova is a cloud-like meringue base topped with a vibrant assortment of fresh berries and a whisper of whipped cream. The crisp exterior gives way to a soft, marshmallow-like center, creating a delightful contrast of textures that dances on the palate with bursts of fruity sweetness.</p>\r\n', 12.70, 14, 'pavlova.jpg', 3),
(11, 'Matcha Mousse', '<em>Dessert</em>\r\n<p>Experience the delicate harmony of flavors and textures in our Matcha Mousse. Smooth and velvety green tea-infused mousse is layered atop a buttery biscuit base, creating a dessert that is as visually stunning as it is delicious. Each spoonful is a celebration of the earthy notes of matcha, balanced with the subtle sweetness of cream, culminating in a truly transcendent dessert experience.</p>\r\n', 19.90, 12, 'matchamousse.jpg', 3),
(12, 'Velvet Noir', '<em>Dessert</em>\r\n<p>Indulge in the ultimate chocolate lover\'s fantasy with our Velvet Noir dessert. Rich, decadent layers of dark chocolate ganache and velvety chocolate mousse are enrobed in a glossy chocolate glaze, creating a dessert that is as elegant as it is indulgent. With each luxurious bite, the intense cocoa flavor melts on the tongue, leaving a lingering impression of pure chocolate perfection.</p>\r\n', 18.70, 13, 'chocolate.jpg', 3),
(13, 'Majestic Martini', '<em>Beverage</em>\r\n<p>A timeless classic elevated to new heights, our Majestic Martini is a refined blend of premium vodka or gin, meticulously stirred or shaken to icy perfection. Served in a chilled martini glass and garnished with a twist of lemon or olives, this iconic cocktail is the epitome of sophistication, offering a crisp and refreshing sip that tantalizes the senses.</p>\r\n', 18.70, 13, 'majesticmartini.jpg', 4),
(14, 'Grandiose Gin', '<em>Beverage</em>\r\n<p>Crafted with the finest botanicals and distilled to perfection, our Grandiose Gin cocktail is a celebration of botanical elegance and juniper-forward flavor. Served over ice with a splash of tonic water and garnished with a sprig of fresh herbs or a twist of citrus, each sip is a symphony of complex flavors and aromatic notes, promising a truly indulgent drinking experience.</p>\r\n', 18.70, 13, 'grandiosegin.jpg', 4),
(15, 'Regal Rum', '<em>Beverage</em>\r\n<p>Embark on a journey of Caribbean delight with our Regal Rum cocktail. Crafted with the finest aged rum, balanced with a hint of sweetness and a splash of citrus, this cocktail is served over ice in a chilled glass, transporting you to sun-kissed shores with every sip. Smooth, sophisticated, and irresistibly delicious, it\'s a drink fit for royalty.</p>\r\n', 18.70, 12, 'regalrum.jpg', 4),
(16, 'Prestige Punch', '<em>Beverage</em>\r\n<p>A masterpiece of mixology, our Prestige Punch is a delightful fusion of premium spirits, fruit juices, and a hint of effervescence. Served in an elegant punch bowl with a garnish of fresh fruits and edible flowers, this communal cocktail is perfect for sharing amongst friends or savoring solo. With each sip, you\'ll discover a harmonious blend of flavors that is both refreshing and invigorating, leaving you feeling truly pampered.</p>\r\n', 18.70, 13, 'prestigepunch.jpg', 4);

-- --------------------------------------------------------

--
-- Table structure for table `employee_accounts`
--

CREATE TABLE `employee_accounts` (
  `employee_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `account_status_id` int(11) NOT NULL DEFAULT 1,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `date_of_register` datetime NOT NULL,
  `remember_me_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `employee_accounts`
--

INSERT INTO `employee_accounts` (`employee_id`, `username`, `password`, `email`, `account_status_id`, `name`, `phone`, `date_of_register`, `remember_me_token`) VALUES
(1, 'employee1', '$2y$10$SfhYIDtn.iOuCW7zfoFLuuZHX6lja4lF4XA4JqNmpiH/.P3zB8JCa', 'employee1@test.com', 1, 'Bilbo Baggins', '0105663394', '2024-04-02 16:10:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `order_status_id` int(11) NOT NULL,
  `order_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`order_status_id`, `order_status`) VALUES
(4, 'cancelled'),
(3, 'fulfilled'),
(2, 'paid'),
(1, 'unpaid');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_status`
--
ALTER TABLE `account_status`
  ADD PRIMARY KEY (`account_status_id`),
  ADD UNIQUE KEY `account_status` (`account_status`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD UNIQUE KEY `uc_customer_dish` (`customer_id`,`dish_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `dish_id` (`dish_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `customer_accounts`
--
ALTER TABLE `customer_accounts`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `account_status_id` (`account_status_id`),
  ADD KEY `default_address_id` (`default_address_id`);

--
-- Indexes for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `recipient_id` (`recipient_id`),
  ADD KEY `address_id` (`address_id`),
  ADD KEY `customer_payment_method_id` (`customer_payment_method_id`),
  ADD KEY `order_status_id` (`order_status_id`);

--
-- Indexes for table `customer_orders_products`
--
ALTER TABLE `customer_orders_products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `dish_id` (`dish_id`);

--
-- Indexes for table `customer_payment_method`
--
ALTER TABLE `customer_payment_method`
  ADD PRIMARY KEY (`customer_payment_method_id`),
  ADD UNIQUE KEY `payment_method` (`payment_method`);

--
-- Indexes for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  ADD PRIMARY KEY (`address_id`);

--
-- Indexes for table `delivery_recipients`
--
ALTER TABLE `delivery_recipients`
  ADD PRIMARY KEY (`recipient_id`),
  ADD UNIQUE KEY `recipient_name` (`recipient_name`),
  ADD UNIQUE KEY `recipient_phone` (`recipient_phone`),
  ADD UNIQUE KEY `recipient_email` (`recipient_email`);

--
-- Indexes for table `dishes`
--
ALTER TABLE `dishes`
  ADD PRIMARY KEY (`dish_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `employee_accounts`
--
ALTER TABLE `employee_accounts`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `account_status_id` (`account_status_id`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`order_status_id`),
  ADD UNIQUE KEY `order_status` (`order_status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_status`
--
ALTER TABLE `account_status`
  MODIFY `account_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customer_accounts`
--
ALTER TABLE `customer_accounts`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_orders`
--
ALTER TABLE `customer_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_orders_products`
--
ALTER TABLE `customer_orders_products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_payment_method`
--
ALTER TABLE `customer_payment_method`
  MODIFY `customer_payment_method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_recipients`
--
ALTER TABLE `delivery_recipients`
  MODIFY `recipient_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dishes`
--
ALTER TABLE `dishes`
  MODIFY `dish_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `employee_accounts`
--
ALTER TABLE `employee_accounts`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `order_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer_accounts` (`customer_id`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`dish_id`);

--
-- Constraints for table `customer_accounts`
--
ALTER TABLE `customer_accounts`
  ADD CONSTRAINT `customer_accounts_ibfk_1` FOREIGN KEY (`account_status_id`) REFERENCES `account_status` (`account_status_id`),
  ADD CONSTRAINT `customer_accounts_ibfk_2` FOREIGN KEY (`default_address_id`) REFERENCES `delivery_addresses` (`address_id`);

--
-- Constraints for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD CONSTRAINT `customer_orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer_accounts` (`customer_id`),
  ADD CONSTRAINT `customer_orders_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `delivery_recipients` (`recipient_id`),
  ADD CONSTRAINT `customer_orders_ibfk_3` FOREIGN KEY (`address_id`) REFERENCES `delivery_addresses` (`address_id`),
  ADD CONSTRAINT `customer_orders_ibfk_4` FOREIGN KEY (`customer_payment_method_id`) REFERENCES `customer_payment_method` (`customer_payment_method_id`),
  ADD CONSTRAINT `customer_orders_ibfk_5` FOREIGN KEY (`order_status_id`) REFERENCES `order_status` (`order_status_id`);

--
-- Constraints for table `customer_orders_products`
--
ALTER TABLE `customer_orders_products`
  ADD CONSTRAINT `customer_orders_products_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `customer_orders` (`order_id`),
  ADD CONSTRAINT `customer_orders_products_ibfk_2` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`dish_id`);

--
-- Constraints for table `dishes`
--
ALTER TABLE `dishes`
  ADD CONSTRAINT `category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `employee_accounts`
--
ALTER TABLE `employee_accounts`
  ADD CONSTRAINT `employee_accounts_ibfk_1` FOREIGN KEY (`account_status_id`) REFERENCES `account_status` (`account_status_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
