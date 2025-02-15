-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2025 at 06:01 PM
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
-- Database: `event`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `phone_no` bigint(12) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `department_code` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `name`, `phone_no`, `email`, `password`, `department_code`) VALUES
(1, 'Kavin', 9345569707, 'kavinnandha121@gmail.com', '$2y$10$i5z3RC0welCb3s2x1zMQ7e0r53iop4OmzDB9v0eD3cul4Xz3K6vdu', 107);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `total_amount`) VALUES
(4, 17, 350.00);

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`cart_item_id`, `cart_id`, `event_id`) VALUES
(13, 4, 2),
(14, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_code` int(11) NOT NULL,
  `department_name` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_code`, `department_name`) VALUES
(104, 'Computer Science and Engineering'),
(107, 'Computer Science and Engineering (Cyber Security)');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `event_detail` varchar(256) NOT NULL,
  `category` enum('Technical','Non-Technical') DEFAULT NULL,
  `department_code` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `venue` varchar(100) DEFAULT NULL,
  `registration_fee` decimal(10,2) DEFAULT NULL,
  `image_path` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `event_detail`, `category`, `department_code`, `description`, `event_date`, `start_time`, `end_time`, `venue`, `registration_fee`, `image_path`) VALUES
(1, 'Project K', 'A comphrensive new ctf event', 'Technical', 104, 'Lorem ipsum', '2025-02-14', '10:28:35', '25:17:30', 'Auditorium', 250.00, '/event/networkingnight.webp'),
(2, 'Networking Night', 'India’s Event Search Engine is a comprehensive platform for finding and booking events across various categories, including concerts, workshops, and live online events. Whether you’re looking for local gatherings or virtual experiences, GoEvent.in makes ev', 'Technical', 107, 'GoEvent - India\'s event search engine\r\nGoEvent.in – India’s Event Search Engine is a comprehensive platform for finding and booking events across various categories, including concerts, workshops, and live online events. Whether you’re looking for local gatherings or virtual experiences, GoEvent.in makes event discovery easy and personalized. With a user-friendly interface, it helps you stay updated on the latest happenings and ensures you never miss an exciting event!', '2025-02-14', '10:28:35', '25:17:30', 'Auditorium', 100.00, '/event/networkingnight.webp'),
(4, 'Networking Night 3', 'India’s Event Search Engine is a comprehensive platform for finding and booking events across various categories, including concerts, workshops, and live online events. Whether you’re looking for local gatherings or virtual experiences, GoEvent.in makes ev', 'Non-Technical', 107, 'GoEvent - India\'s event search engine\r\nGoEvent.in – India’s Event Search Engine is a comprehensive platform for finding and booking events across various categories, including concerts, workshops, and live online events. Whether you’re looking for local gatherings or virtual experiences, GoEvent.in makes event discovery easy and personalized. With a user-friendly interface, it helps you stay updated on the latest happenings and ensures you never miss an exciting event!', '2025-02-14', '10:28:35', '12:18:00', 'Auditorium', 99.99, '/event/images/67b0bf908003b.jpg'),
(6, 'Queen Quest', 'A comphrensive a discovery based game who likes to explore the world with different view', 'Non-Technical', 107, 'We envision a vibrant and healthy India that is guided by sound science to manage its natural resources. Our global mission is to conserve the lands and waters on which all life depends. Our global vision is a world where the diversity of life thrives, and people act to conserve nature for its own sake and its ability to fulfill our needs and enrich our lives.', '2026-11-18', '09:00:00', '16:30:00', 'Skype Hall', 140.55, '/event/images/67b0c1e166f98.jpg'),
(7, 'Unquestable Trench', 'We envision a vibrant and healthy India that is guided by sound science to manage its natural resources. ', 'Non-Technical', 107, 'We envision a vibrant and healthy India that is guided by sound science to manage its natural resources. Our global mission is to conserve the lands and waters on which all life depends. Our global vision is a world where the diversity of life thrives, and people act to conserve nature for its own sake and its ability to fulfill our needs and enrich our lives.', '2025-03-14', '09:03:00', '10:30:00', 'Seminar hall II', 149.90, '/event/images/67b0c2a374600.jpg'),
(8, 'dsbdfb', 'fddfgb', 'Non-Technical', 107, 'fuyk', '2025-04-29', '04:54:00', '15:43:00', 'dfgfg', 453.00, '/event/images/67b0c39d5abfa.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `order_date`) VALUES
(8, 17, 450.00, '2025-02-15 09:52:02'),
(9, 17, 450.00, '2025-02-15 09:52:02');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `event_id`, `amount`) VALUES
(5, 8, 2, 250.00),
(6, 8, 1, 100.00),
(7, 9, 4, 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `college_id` varchar(256) DEFAULT NULL,
  `department` varchar(256) DEFAULT NULL,
  `otp` int(11) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `phone`, `college_id`, `department`, `otp`, `password`) VALUES
(17, 'Kavin Nandha', 'kavinnandha121@gmail.com', '9345569707', 'sri shakthi institute of engineering college', 'Cyber security', NULL, '$2y$10$i5z3RC0welCb3s2x1zMQ7e0r53iop4OmzDB9v0eD3cul4Xz3K6vdu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `department_code` (`department_code`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_code`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `department_code` (`department_code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`department_code`) REFERENCES `department` (`department_code`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`department_code`) REFERENCES `department` (`department_code`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
