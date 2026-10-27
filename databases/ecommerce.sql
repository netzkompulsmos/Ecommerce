-- phpMyAdmin SQL Dump
-- Database: `ecommerce`
-- Unified All-in-One Database

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `ecommerce` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ecommerce`;

-- --------------------------------------------------------
-- Drop existing tables to ensure clean schema update
-- --------------------------------------------------------

DROP TABLE IF EXISTS `account_sessions`;
DROP TABLE IF EXISTS `account`;
DROP TABLE IF EXISTS `admins`;
DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `system_settings`;
DROP TABLE IF EXISTS `login`;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `sales_order_history`;
DROP TABLE IF EXISTS `customer_profiles`;
DROP TABLE IF EXISTS `inventory_adjustments`;
DROP TABLE IF EXISTS `sales_orders`;
DROP TABLE IF EXISTS `product_activity`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `users`;

-- --------------------------------------------------------
-- Table structure for table `account`
-- --------------------------------------------------------

CREATE TABLE `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `google_id` varchar(255) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `google_id` (`google_id`),
  KEY `idx_email` (`email`),
  KEY `idx_google_id` (`google_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `account_sessions`
-- --------------------------------------------------------

CREATE TABLE `account_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  KEY `idx_session_token` (`session_token`),
  KEY `idx_expires_at` (`expires_at`),
  CONSTRAINT `account_sessions_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `admins`
-- --------------------------------------------------------

CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(154, 'netsmos100', '$2y$10$irn4aO/mzBkAuGRK.fnYwuVT4yI8rcBXUuX.7Qxs.eKhVYBsx95bO');

-- --------------------------------------------------------
-- Table structure for table `audit_logs`
-- --------------------------------------------------------

CREATE TABLE `audit_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `system_settings`
-- --------------------------------------------------------

CREATE TABLE `system_settings` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(50) NOT NULL,
  `setting_value` text NOT NULL,
  `description` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`setting_id`),
  UNIQUE KEY `setting_name` (`setting_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `login`
-- --------------------------------------------------------

CREATE TABLE `login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `google_id` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `picture` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `google_id` (`google_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `messages`
-- --------------------------------------------------------

CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'NHOR NETH', 'nhorneth1@gmail.com', 'good website', '2025-07-01 07:36:05'),
(2, 'NHOR NETH', 'nhorneth1@gmail.com', 'good website', '2025-07-01 07:39:01'),
(3, 'NHOR NETH', 'nhorneth1@gmail.com', 'good website', '2025-07-01 07:43:35'),
(4, 'NHOR NETH', 'nhorneth1@gmail.com', 'good website', '2025-07-01 07:46:14'),
(5, 'Muzik', 'plobplub@gmail.com', 'good', '2025-07-01 07:47:18'),
(6, 'Muzik', 'plobplub@gmail.com', 'good', '2025-07-01 07:58:44'),
(7, 'Neth Gaming', 'nhorneth1@gmail.com', 'help me', '2025-07-01 07:59:40'),
(8, 'NHOR NETH', 'nhorneth1@gmail.com', 'i love you', '2025-07-01 08:00:22'),
(9, 'NHOR NETH', 'nhorneth1@gmail.com', 'help me', '2025-07-01 08:00:43'),
(20, 'Muzik', 'plobplub@gmail.com', 'help me your website this is cool', '2025-07-15 07:02:33'),
(21, 'Muzik', 'plobplub@gmail.com', 'hello ', '2025-07-15 10:00:59');

-- --------------------------------------------------------
-- Table structure for table `products`
-- --------------------------------------------------------

CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(10) unsigned NOT NULL DEFAULT 0,
  `low_stock_threshold` int(10) unsigned NOT NULL DEFAULT 5,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `sales_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `phone_number` varchar(32) NOT NULL,
  `location` varchar(300) NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `unit_cost` decimal(10,2) DEFAULT NULL,
  `order_reference` varchar(40) DEFAULT NULL,
  `payment_method` varchar(20) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `payment_transaction_id` varchar(120) DEFAULT NULL,
  `paid_amount` decimal(10,2) DEFAULT NULL,
  `receipt_filename` varchar(100) DEFAULT NULL,
  `receipt_mime` varchar(100) DEFAULT NULL,
  `inventory_reserved` tinyint(1) NOT NULL DEFAULT 0,
  `phone_normalized` varchar(32) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_sales_created_at` (`created_at`),
  KEY `idx_sales_product_id` (`product_id`),
  KEY `idx_sales_phone_normalized` (`phone_normalized`),
  KEY `idx_sales_status_created` (`status`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `customer_profiles` (
  `phone_normalized` varchar(32) NOT NULL,
  `phone_number` varchar(32) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `notes` text DEFAULT NULL,
  `is_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`phone_normalized`),
  KEY `idx_customer_name` (`customer_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sales_order_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `event_type` varchar(32) NOT NULL,
  `old_value` varchar(120) DEFAULT NULL,
  `new_value` varchar(120) DEFAULT NULL,
  `note` varchar(500) DEFAULT NULL,
  `transaction_id` varchar(120) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `actor` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_order_history_order_id` (`order_id`),
  KEY `idx_order_history_type` (`event_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `inventory_adjustments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `change_amount` int(11) NOT NULL,
  `stock_before` int(10) unsigned NOT NULL,
  `stock_after` int(10) unsigned NOT NULL,
  `reason` varchar(255) NOT NULL,
  `actor` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_inventory_adjustments_product` (`product_id`),
  KEY `idx_inventory_adjustments_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_activity` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `action` varchar(20) NOT NULL,
  `actor` varchar(100) NOT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_product_activity_created_at` (`created_at`),
  KEY `idx_product_activity_product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `created_at`) VALUES
(7, 'CAPCUT STANDARD', 'new version', 6.99, 'img/products/icon_6875e94aa88cc3.21060813.jpg', '2025-07-14 04:56:44'),
(12, 'PIC ART PRO', 'new', 4.99, 'img/products/icon_6875d8b8358053.04381892.jpg', '2025-07-15 04:27:36'),
(13, 'SPORTIFY PREMIUM', 'new', 4.99, 'img/products/icon_6875e3926df173.92895209.webp', '2025-07-15 05:13:54'),
(14, 'GPT PLUS', 'new', 12.00, 'img/products/icon_687760fcad1520.40227095.jpg', '2025-07-16 01:58:49'),
(15, 'TIKTOK BLUETICK REQUEST', 'new in stock', 2.99, 'img/products/icon_6877667fe9b542.95633520.jpg', '2025-07-16 08:24:15'),
(16, 'Alight Motion Premium', 'new', 8.00, 'img/products/icon_68776803df79a3.67737119.jpg', '2025-07-16 08:51:15');

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'nhorneth1@gmail.com', '$2y$10$6DfHhdv3yIRXZ.PmpgcLqueMQ7I/.KVnjf8AnC2Q0F8FFz6lTXdpy'),
(2, 'yyou19492@gmail.com', '$2y$10$03RDoY4pHozM5bnKQ88GheS34fKmB62od.GFFGYC8.8aAj/QA1F8.'),
(3, 'aicheat184@gmail.com', '$2y$10$njm0CQKwahl8iGXe4uYWQ.Hhs9Z/rtsBmuNoMGwvIX.SROCcJootS'),
(4, 'phaklovgaming@gmail.com', '$2y$10$ih7.vXLO3cfH9bOF3GgZ6ua8kVq2pa9r3Dy6aWQNXWxpLjlYcoCYi'),
(5, 'nethnhor1@gmail.com', '$2y$10$zKde3eEpMuPpwJ2L6ELFc.2nPCWoKL6MBTC8TEA9JABFF/K7PMa2O'),
(6, 'netsmos100', '$2y$10$.nIOKRxLoo5ZnslrlwIFH.YLo5nJsTGMGHhEzkI.MKziIorHpCvIi'),
(7, 'net', '$2y$10$EqQnn.Cx.msTVQvLdJ2/Zup5a6/P1n0ZsPJoAOzZe8/KZ0XYrSNfa'),
(8, 'plo', '$2y$10$ATi3OvtcvDw2t4yeg1u3iuK299VyFkuNyp2Szu19KaeKhCFmC4zhy'),
(9, 'plobplub@gmail.com', '$2y$10$uBG4f5VMByUvZGUD9Vi9gOVJZM197ndGa9QDl.WLqmRnIMKixBpEC'),
(10, 'plobplub11@gmail.com', '$2y$10$9D72cEGC7mx3QSVFlL/e1.VScrg5r9jh.rfUMVmKIFb02A3UHbCQe');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
