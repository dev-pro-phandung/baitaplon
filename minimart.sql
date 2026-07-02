-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2026 at 02:15 PM
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
-- Database: `minimart`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT '#',
  `title` varchar(255) DEFAULT NULL,
  `position` varchar(50) DEFAULT 'slider',
  `is_active` tinyint(4) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `image`, `link`, `title`, `position`, `is_active`, `sort_order`) VALUES
(6, 'banner.png', '#', 'Quảng cáo', 'banner_body', 1, 0),
(8, '1768276426_Screenshot 2026-01-13 103949.png', '#', 'Banner mới', 'slider', 1, 0),
(10, '1768314741_Screenshot 2025-10-14 002342.png', '#', 'ABC', 'slider', 1, 0),
(11, '1768616229_b3.webp', '#', 'Sản phẩm mới', 'slider', 1, 0),
(12, '1768616217_b2.webp', '#', 'Sản phẩm tiêu biểu', 'slider', 1, 0),
(13, '1768616200_b1.webp', '#', 'Sản phẩm bán chạy', 'slider', 1, 0),
(14, '1768640368__MG_1945.JPG', '#', 'Banner tết', 'slider', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `image`, `description`) VALUES
(1, 'Adidas', '1768562506_Screenshot 2025-12-08 125410.png', 'Giày dép thương hiệu độc quyền');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT '#',
  `parent_id` int(11) DEFAULT 0,
  `icon` varchar(100) DEFAULT 'fas fa-list'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`, `link`, `parent_id`, `icon`) VALUES
(1, 'Chăm sóc tóc', '1768313437_h1.webp', '#', 0, 'fi fi-rr-woman-head'),
(2, 'Chăm sóc da', '1768313479_h2.webp', '#', 0, 'fas fa-allergies'),
(3, 'Thực phẩm tươi sống', '1768313516_h3.webp', '#', 0, 'fas fa-meat'),
(4, 'Mẹ và bé', '1768313586_h4.webp', '#', 0, 'fi fi-rr-baby-carriage'),
(5, 'Dầu gội', NULL, '#', 1, 'fas fa-list'),
(6, 'Dầu xả', NULL, '#', 1, 'fas fa-list'),
(7, 'Hấp dầu', NULL, '#', 1, 'fas fa-list'),
(8, 'Sữa rửa mặt', NULL, '#', 2, 'fas fa-list'),
(9, 'Toner', NULL, '#', 2, 'fas fa-list'),
(10, 'Kem chống nắng', NULL, '#', 2, 'fas fa-list'),
(11, 'Đồ ăn vặt', '1766853560_img2.jpg', '#', 0, 'fas fa-list'),
(12, 'Nước ngọt', '', '#', 11, 'fas fa-list'),
(13, 'Bỉm', '', '#', 4, 'fas fa-list'),
(14, 'Rau quả', '1768313642_h5.webp', '#', 0, 'fas fa-list'),
(15, 'Rau ngót', '', '#', 14, 'fas fa-list'),
(16, 'rau cải', '', '#', 14, 'fas fa-list'),
(17, 'Nước ', '1768315578_h6.webp', '#', 0, 'fas fa-list'),
(18, 'Rau ', '', '#', 0, 'fas fa-list'),
(19, 'quả', '', '#', 0, 'fas fa-list');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT 0,
  `fullname` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `note` text DEFAULT NULL,
  `total_money` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1 COMMENT '1: Mới, 2: Đang giao, 3: Đã giao, 4: Hủy',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) DEFAULT 'cod'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `fullname`, `phone`, `address`, `note`, `total_money`, `status`, `created_at`, `payment_method`) VALUES
(1, 0, 'Phan Tiến Dũng', '0374518490', 'Thanh Bắc, Giao Minh, Ninh Bình', 'Giao luc nào cũng được', 61000, 3, '2025-12-26 23:37:50', 'cod'),
(2, 1, 'Phan Tiến Dũng', '0374518490', 'Thanh Bắc', 'Giao lúc nào cũng được', 25000, 2, '2025-12-27 23:00:47', 'cod'),
(3, 2, 'Nguyễn Văn B', '034553535', 'Hà Nội', 'abc', 66000, 2, '2025-12-29 07:22:47', 'cod'),
(4, 2, 'Nguyễn Văn B', '0395825036', 'Sài Gòng', '', 25000, 3, '2025-12-31 09:11:27', 'cod'),
(5, 1, 'Phan Tiến Dũng', '098787677', 'Hà Nội', 'admin', 446000, 1, '2025-12-31 09:21:55', 'cod'),
(6, 1, 'Phan Tiến Dũng', '7783876836', 'hghgh', 'abc', 25000, 1, '2025-12-31 19:17:53', 'cod'),
(7, 0, 'Nguyễn Văn B', '09786788', 'Hà Nội', 'abc', 10000, 1, '2026-01-06 09:29:35', 'cod'),
(8, 1, 'Phan Tiến Dũng', '0374518490', 'Hà Nội', 'abc', 36000, 1, '2026-01-06 09:30:10', 'cod'),
(9, 1, 'Phan Tiến Dũng', '86868698', 'HCM', 'abc', 436000, 1, '2026-01-06 09:48:51', 'cod'),
(10, 1, 'Phan Tiến Dũng', '0374518490', 'ABC', 'ABC', 100000, 3, '2026-01-13 10:54:27', 'cod'),
(11, 1, 'Phan Tiến Dũng', '0374518490', 'abcdz', 'abc', 50000, 1, '2026-01-13 11:04:12', 'cod'),
(12, 1, 'Phan Tiến Dũng', '0374518490', 'abc', '', 171000, 1, '2026-01-13 11:44:25', 'cod'),
(13, 1, 'Phan Tiến Dũng', '0374518490', 'abc', '', 25000, 1, '2026-01-13 11:45:27', 'cod'),
(14, 1, 'Phan Tiến Dũng', '0374518490', '1', '', 36000, 1, '2026-01-13 11:46:55', 'cod'),
(15, 1, 'Phan Tiến Dũng', '0374518490', '2', '', 100000, 1, '2026-01-13 11:52:17', 'cod'),
(16, 1, 'Phan Tiến Dũng', '0374518490', '3', '', 25000, 1, '2026-01-13 11:52:58', 'cod'),
(17, 1, 'Phan Tiến Dũng', '0374518490', '1', '', 36000, 1, '2026-01-13 11:58:09', 'cod'),
(18, 1, 'Phan Tiến Dũng', '0374518490', '2', '', 10000, 1, '2026-01-13 12:02:41', 'cod'),
(19, 1, 'Phan Tiến Dũng', '0374518490', '3', '', 25000, 1, '2026-01-13 12:09:13', 'cod'),
(20, 1, 'Phan Tiến Dũng', '0374518490', '3', '', 10000, 1, '2026-01-13 12:11:25', 'cod'),
(21, 1, 'Phan Tiến Dũng', '0374518490', '1', '', 189000, 1, '2026-01-13 12:52:32', 'cod'),
(22, 1, 'Phan Tiến Dũng', '0374518490', '1', '', 4000, 1, '2026-01-13 12:53:05', 'cod'),
(23, 1, 'Phan Tiến Dũng', '0374518490', '1', '', 4000, 1, '2026-01-13 13:19:51', 'COD'),
(24, 1, 'Phan Tiến Dũng', '0374518490', '2', '', 36000, 1, '2026-01-13 13:20:08', 'COD'),
(25, 1, 'Phan Tiến Dũng', '0374518490', 'Hà Nội', '', 10000, 1, '2026-01-13 13:23:42', 'COD'),
(26, 1, 'Phan Tiến Dũng', '0374518490', '1', '', 36000, 1, '2026-01-13 13:27:54', 'COD'),
(27, 1, 'Phan Tiến Dũng', '0374518490', '1', '', 10000, 1, '2026-01-13 14:08:31', 'cod'),
(28, 1, 'Phan Tiến Dũng', '0374518490', '1', '', 96000, 1, '2026-01-13 14:49:38', 'BANK'),
(29, 1, 'Phan Tiến Dũng', '0374518490', '1', '', 36000, 1, '2026-01-16 18:55:12', 'COD'),
(30, 1, 'Phan Tiến Dũng', '0374518490', '1', '', 25000, 1, '2026-01-16 19:57:22', 'COD');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `num` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `price`, `num`) VALUES
(1, 1, 3, 36000, 1),
(2, 1, 1, 25000, 1),
(3, 2, 1, 25000, 1),
(4, 3, 2, 10000, 3),
(5, 3, 3, 36000, 1),
(6, 4, 1, 25000, 1),
(7, 5, 1, 25000, 12),
(8, 5, 2, 10000, 1),
(9, 5, 3, 36000, 1),
(10, 5, 4, 100000, 1),
(11, 6, 1, 25000, 1),
(12, 7, 2, 10000, 1),
(13, 8, 3, 36000, 1),
(14, 9, 3, 36000, 12),
(15, 9, 14, 4000, 1),
(16, 10, 4, 100000, 1),
(17, 11, 1, 25000, 2),
(18, 12, 3, 36000, 1),
(19, 12, 2, 10000, 1),
(20, 12, 4, 100000, 1),
(21, 12, 1, 25000, 1),
(22, 13, 1, 25000, 1),
(23, 14, 3, 36000, 1),
(24, 15, 4, 100000, 1),
(25, 16, 5, 25000, 1),
(26, 17, 3, 36000, 1),
(27, 18, 2, 10000, 1),
(28, 19, 1, 25000, 1),
(29, 20, 2, 10000, 1),
(30, 21, 8, 189000, 1),
(31, 22, 14, 4000, 1),
(32, 23, 14, 4000, 1),
(33, 24, 3, 36000, 1),
(34, 25, 2, 10000, 1),
(35, 26, 3, 36000, 1),
(36, 27, 2, 10000, 1),
(37, 28, 11, 48000, 2),
(38, 29, 3, 36000, 1),
(39, 30, 1, 25000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `old_price` int(11) DEFAULT 0,
  `discount` int(11) DEFAULT 0,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `unit` varchar(50) DEFAULT 'Cái',
  `category_id` int(11) DEFAULT 1,
  `views` int(11) DEFAULT 0,
  `is_new` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `brand_id` int(11) DEFAULT 0,
  `quantity` int(11) DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `old_price`, `discount`, `image`, `description`, `unit`, `category_id`, `views`, `is_new`, `created_at`, `brand_id`, `quantity`) VALUES
(1, 'Bánh que Lotte', 25000, 0, 0, 'sp1.png', NULL, 'Hộp', 1, 1000, 1, '2025-12-23 03:02:41', 0, 100),
(2, 'Rá tai nhật Việt Nhật', 10000, 0, 0, 'sp2.png', NULL, 'Cái', 1, 900, 1, '2025-12-23 03:02:41', 0, 99),
(3, 'Ô mai Tiến Thịnh', 36000, 0, 0, 'sp3.png', NULL, 'Hộp', 1, 850, 1, '2025-12-23 03:02:41', 0, 98),
(4, 'Dầu gội dược liệu', 100000, 0, 0, 'sp4.png', NULL, 'Chai', 1, 800, 1, '2025-12-23 03:02:41', 0, 100),
(5, 'Cân điện tử', 25000, 0, 0, 'sp5.png', NULL, 'Cái', 1, 750, 1, '2025-12-23 03:02:41', 0, 100),
(6, 'Hộp giấy chữ nhật 2705', 11500, 0, 0, 'hop-giay.jpg', NULL, 'Cái', 1, 100, 0, '2025-12-23 03:02:41', 0, 100),
(7, 'Dưa hấu đỏ miền Nam', 17000, 0, 0, 'dua-hau.jpg', NULL, 'Kg', 1, 200, 0, '2025-12-23 03:02:41', 0, 100),
(8, 'Dầu xả Valert Grapefruit tinh chất bưởi 850ml', 189000, 0, 0, 'dau-xa-valert.jpg', NULL, 'Chai', 1, 150, 0, '2025-12-23 03:02:41', 0, 100),
(9, 'Dầu xả Sunsilk dưỡng phục hồi 330ml', 81000, 92000, 12, 'sunsilk.jpg', NULL, 'Tuýp', 1, 300, 0, '2025-12-23 03:02:41', 0, 100),
(10, 'Khay tiện ích 4 ngăn Việt Nhật 5696', 18000, 0, 0, 'khay.jpg', NULL, 'Cái', 1, 120, 0, '2025-12-23 03:02:41', 0, 100),
(11, 'Thùng nước 25L có nắp 5360', 48000, 0, 0, 'sp-them-1.jpg', NULL, 'Cái', 1, 50, 0, '2025-12-23 03:02:41', 0, 100),
(12, 'Dầu gội dược liệu Nguyên Xuân đỏ 450ml', 133000, 0, 0, 'sp-them-2.jpg', NULL, 'Chai', 1, 60, 0, '2025-12-23 03:02:41', 0, 100),
(14, 'Cay Cay Hổ', 4000, 7000, 23, '1766853615_img2.jpg', NULL, 'Gói', 11, 0, 1, '2025-12-27 16:40:15', 0, 9),
(15, 'weather', 50000, 80000, 26, '1768275143_abc.png', NULL, 'cái', 2, 0, 0, '2026-01-13 03:32:23', 1, 100),
(16, 'Bỉm', 250000, 300000, 15, '1768312678_Screenshot 2026-01-05 184217.png', NULL, 'Túi', 4, 19000, 1, '2026-01-13 13:57:58', 0, 100),
(17, 'Dầu xả Pantene', 150000, 160000, 3, '1768615010_1.webp', NULL, 'Chai', 1, 0, 1, '2026-01-17 01:56:50', 0, 50),
(18, 'Dầu gội Treseme', 178000, 200000, 10, '1768615092_2.webp', NULL, 'Chai', 1, 0, 1, '2026-01-17 01:58:12', 0, 100),
(19, 'Dầu gội Olexir', 179000, 300000, 45, '1768616019_4.webp', 'Sản phẩm trích xuất từ thảo dược thiện nhiên, 100% đảm bảo tin dùng', 'Chai', 1, 0, 1, '2026-01-17 02:13:39', 0, 100),
(20, 'Dầu thảo dược', 300000, 500000, 40, '1768618303_5.webp', '', 'Chai', 1, 0, 0, '2026-01-17 02:51:43', 0, 100);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` int(1) NOT NULL DEFAULT 0 COMMENT '0: Khách hàng, 1: Admin',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `phone`, `address`, `role`, `created_at`) VALUES
(1, 'Phan Tiến Dũng', 'phantiendung2005gtc@gmail.com', '$2y$10$ggWXx8Og0/oIvLHGeSSnhOPyQJ96cEbrdWT39491EIhp5kvRKJIPq', '0374518490', 'Thanh Bắc', 1, '2025-12-26 23:56:35'),
(2, 'Nguyễn Văn B', 'a@gmail.com', '$2y$10$Ubpdh9JjuRrkoeFqrOHa4OxmMfJK5VgP8r6ElcH0dlkzUhH.42yO6', '0379862828', 'Hà Nội', 0, '2025-12-29 07:22:07'),
(3, 'Phan Văn Giang', '', '$2y$10$XJ5qFba250qUlmgJJxEZz.6qzpPO588cRZxP9PkYX4zShbIc5R42m', '0395825036', NULL, 0, '2026-01-13 10:27:54'),
(7, 'Phan Kiên Trung', 'dung@gmail.com', '$2y$10$w8jJn.thgxyKCPHrsCmFFujltj12w7OsRDot4ahhBNTuQBAtAsOHq', '0373470823', NULL, 0, '2026-01-13 15:03:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
