-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 05, 2025 at 02:59 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `invoice_wibo`
--

-- --------------------------------------------------------

--
-- Table structure for table `adjustments`
--

CREATE TABLE `adjustments` (
  `id` int NOT NULL,
  `type` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `who_created` int NOT NULL,
  `who_updated` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` enum('active','canceled') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  `canceled_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adjustments`
--

INSERT INTO `adjustments` (`id`, `type`, `product_id`, `quantity`, `description`, `who_created`, `who_updated`, `created_at`, `updated_at`, `status`, `canceled_at`) VALUES
(1, 1, 6, 100, 'qwerty', 2, 2, '2025-06-27 10:46:00', '2025-07-03 21:21:16', 'canceled', NULL),
(2, 3, 5, 5, 'iii', 2, 2, '2025-07-05 08:56:45', '2025-07-05 09:01:19', 'canceled', NULL),
(3, 1, 6, 700, '23', 2, 2, '2025-07-05 10:29:29', '2025-07-05 10:29:35', 'canceled', NULL),
(4, 1, 5, 40, 'r32', 2, 2, '2025-07-05 10:40:29', '2025-07-05 10:40:47', 'canceled', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `who_created` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`, `who_created`) VALUES
(1, 'LINEN', '2025-05-31 16:05:38', '2025-05-31 16:05:38', 1),
(2, 'akrilik', '2025-06-01 08:46:49', '2025-06-01 08:46:49', 1),
(3, 'Asesoris', '2025-06-03 09:52:31', '2025-06-03 09:52:31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int NOT NULL,
  `code_invoice` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `customer_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `address` text COLLATE utf8mb4_general_ci NOT NULL,
  `discount` int NOT NULL DEFAULT '0',
  `status` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `who_created` int NOT NULL,
  `updated_who` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `code_invoice`, `customer_name`, `address`, `discount`, `status`, `description`, `created_at`, `updated_at`, `who_created`, `updated_who`) VALUES
(1, 'WBT-0001', 'Agus', 'Jl. Gatot Subroto No. 123, Denpasar', 0, '1', '', '2025-06-01 19:26:07', '2025-07-05 10:36:57', 1, 2),
(3, 'WBT-00002', 'Gede', 'Jl. Nangka Selatan No. 45, Denpasar', 1000, '4', '', '2025-06-01 14:29:18', '2025-06-23 18:56:53', 1, 3),
(4, 'WBT-00003', 'Gede', 'Jl. Ahmad Yani Utara No. 8, Denpasar', 0, '4', '', '2025-06-01 14:35:16', NULL, 1, NULL),
(5, 'WBT-00004', 'Gede', 'Jl. Diponegoro No. 99, Denpasar Barat', 0, '4', '', '2025-06-01 14:37:46', NULL, 1, NULL),
(6, 'WBT-00005', 'Agus', 'Jl. Imam Bonjol No. 34, Denpasar Timur', 0, '4', '', '2025-06-03 09:49:57', NULL, 1, NULL),
(7, 'WBT-00006', 'Ivan', 'Jl. Teuku Umar No. 27, Denpasar', 20000, '3', '', '2025-06-06 10:28:14', '2025-06-22 23:47:01', 1, 3),
(8, 'WBT-00007', 'ucok', 'Jl. Hayam Wuruk No. 11, Denpasar Utara', 200000, '2', '', '2025-06-06 10:38:12', NULL, 1, NULL),
(12, 'WBT-00008', 'nanda', 'Jl. Kebo Iwa No. 5, Denpasar Barat', 0, '4', '', '2025-06-12 02:51:27', NULL, 1, NULL),
(18, 'WBT-00009', 'q', 'Jl. Tukad Yeh Aya No. 88, Renon', 0, '3', '', '2025-06-12 03:10:44', '2025-06-25 08:57:59', 1, NULL),
(21, 'WBT-00010', 'aa', 'Jl. Danau Poso No. 19, Sanur', 0, '2', '', '2025-06-12 03:45:02', NULL, 1, NULL),
(23, 'WBT-00011', 'Zescra', 'Jl. Gatot Subroto No. 1223, Denpasar', 0, '1', 'alamak', '2025-06-12 05:53:11', '2025-06-23 09:14:03', 1, 3),
(24, 'WBT-00012', 'wisnawan', 'jl...', 0, '3', '', '2025-06-12 09:10:03', '2025-06-14 03:57:53', 1, 3),
(25, 'WBT-00013', 'defanda', 'tangeb', 0, '1', '', '2025-06-12 09:11:05', '2025-07-05 10:41:25', 1, NULL),
(26, 'WBT-00014', 'defanda', 'tangeb', 0, '4', '', '2025-06-12 09:59:43', '2025-07-05 10:52:01', 1, 3),
(28, 'WBT-00015', 'kk2', 'kk', 0, '1', '', '2025-06-12 10:23:48', '2025-06-16 07:28:13', 1, 3),
(31, 'WBT-00016', 'gede', 'kk', 0, '4', '', '2025-06-24 18:54:13', '2025-06-24 18:55:45', 2, 2),
(32, 'WBT-00017', 'Gede Indrawan', 'Jl..', 0, '4', '', '2025-06-29 18:56:47', '2025-06-29 18:59:54', 2, NULL),
(33, 'WBT-00018', 'gede', 'qwe', 0, '4', '', '2025-06-29 19:00:38', '2025-06-29 19:25:20', 2, NULL),
(34, 'WBT-00019', 'putu', 'ggg', 0, '4', '', '2025-06-29 19:26:31', '2025-06-29 19:26:52', 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `who_created` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `quantity`, `created_at`, `updated_at`, `who_created`) VALUES
(6, 6, 5, 10, '2025-06-03 09:49:57', '2025-06-03 09:49:57', 1),
(9, 7, 6, 100, '2025-06-06 10:28:14', '2025-06-16 04:39:18', 1),
(11, 12, 5, 500, '2025-06-12 02:51:27', '2025-06-12 02:51:27', 1),
(12, 18, 5, 1, '2025-06-12 03:10:44', '2025-06-12 03:10:44', 1),
(17, 23, 5, 7, '2025-06-12 05:53:11', '2025-06-14 04:37:19', 1),
(18, 24, 5, 123, '2025-06-12 09:10:03', '2025-06-12 09:10:03', 1),
(19, 25, 5, 19, '2025-06-12 09:11:05', '2025-06-12 09:11:05', 1),
(20, 26, 5, 19, '2025-06-12 09:59:43', '2025-06-12 09:59:43', 1),
(21, 28, 7, 50, '2025-06-12 10:23:48', '2025-06-12 10:23:48', 1),
(22, 1, 7, 50, '2025-06-14 01:16:26', '2025-06-24 18:57:13', 1),
(24, 3, 6, 20, '2025-06-16 14:20:33', NULL, 3),
(31, 3, 5, 30, '2025-06-19 07:07:25', '2025-06-19 07:07:25', 3),
(32, 31, 6, 10, '2025-06-24 18:54:13', '2025-06-24 18:54:13', 2),
(34, 32, 5, 111, '2025-06-29 18:56:47', '2025-06-29 18:56:47', 2),
(35, 32, 6, 10, '2025-06-29 18:56:47', '2025-06-29 18:56:47', 2),
(36, 33, 5, 9, '2025-06-29 19:00:38', '2025-06-29 19:00:38', 2),
(37, 34, 5, 2, '2025-06-29 19:26:31', '2025-06-29 19:26:31', 2);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(7, '2025-05-28-145539', 'App\\Database\\Migrations\\Users', 'default', 'App', 1748705002, 1),
(8, '2025-05-28-145600', 'App\\Database\\Migrations\\Categories', 'default', 'App', 1748705002, 1),
(9, '2025-05-31-120000', 'App\\Database\\Migrations\\Products', 'default', 'App', 1748705002, 1),
(10, '2025-05-31-120002', 'App\\Database\\Migrations\\Invoices', 'default', 'App', 1748705002, 1),
(11, '2025-05-31-120427', 'App\\Database\\Migrations\\InvoiceItmes', 'default', 'App', 1748705002, 1),
(12, '2025-05-31-121041', 'App\\Database\\Migrations\\ProdukStok', 'default', 'App', 1748705002, 1),
(13, '2025-06-23-110210', 'App\\Database\\Migrations\\Adjustment', 'default', 'App', 1750681038, 2);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `category_id` int NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `bay` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `who_created` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `description`, `price`, `bay`, `created_at`, `updated_at`, `who_created`) VALUES
(5, 'Baju', 1, '-', 1000000.00, 55000.00, '2025-06-01 13:48:18', '2025-06-01 13:48:18', NULL),
(6, 'gantungan Kunci', 2, '-', 100000.00, 50000.00, '2025-06-03 09:53:09', '2025-06-04 12:43:16', 1),
(7, 'Mug', 3, '-', 30000.00, 15000.00, '2025-06-12 10:21:30', '2025-06-12 10:21:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `produk_stok`
--

CREATE TABLE `produk_stok` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `who_created` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk_stok`
--

INSERT INTO `produk_stok` (`id`, `product_id`, `quantity`, `created_at`, `updated_at`, `who_created`) VALUES
(11, 5, 140, '2025-06-06 10:35:59', '2025-07-05 10:40:47', 1),
(12, 6, 1790, '2025-06-06 10:36:19', '2025-07-05 10:29:35', 1),
(13, 7, 50, '2025-06-12 10:21:50', '2025-06-24 18:57:13', 1),
(14, 7, 150, '2025-06-16 06:12:16', '2025-06-16 06:12:16', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Nanda', 'zes', 'admin', '2025-06-23 09:17:36', NULL),
(2, 'Gede', 'admin', '12345', '2025-06-23 20:40:12', '2025-06-23 20:40:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adjustments`
--
ALTER TABLE `adjustments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stok_id` (`product_id`),
  ADD KEY `who_created` (`who_created`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_who_created_foreign` (`who_created`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_invoice` (`code_invoice`),
  ADD KEY `who_created` (`who_created`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_who_created_foreign` (`who_created`),
  ADD KEY `invoice_items_product_id_foreign` (`product_id`),
  ADD KEY `invoice_items_invoice_id_foreign` (`invoice_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `who_created` (`who_created`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `produk_stok`
--
ALTER TABLE `produk_stok`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produk_stok_who_created_foreign` (`who_created`),
  ADD KEY `produk_stok_product_id_foreign` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adjustments`
--
ALTER TABLE `adjustments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `produk_stok`
--
ALTER TABLE `produk_stok`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_who_created_foreign` FOREIGN KEY (`who_created`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`who_created`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
