-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2018 at 03:58 PM
-- Server version: 5.7.21-0ubuntu0.16.04.1
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `extrim`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachment`
--

CREATE TABLE `attachment` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `dir` varchar(455) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(455) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `attachment`
--

INSERT INTO `attachment` (`id`, `name`, `size`, `dir`, `type`) VALUES
(1, 'IMG_0153.JPG', 55434, 'uploads/IMG_0153.JPG1521727507', 'image/jpeg'),
(2, 'IMG_0153.JPG', 55434, 'uploads/1521727658IMG_0153.JPG', 'image/jpeg'),
(3, 'IMG_0153.JPG', 55434, 'uploads/1521727660IMG_0153.JPG', 'image/jpeg'),
(4, 'IMG_0153.JPG', 55434, 'uploads/1521728075IMG_0153.JPG', 'image/jpeg'),
(5, 'IMG_0154.JPG', 76620, 'uploads/IMG_0154.JPG', 'image/jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(455) COLLATE utf8_unicode_ci NOT NULL,
  `parent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `slug`, `description`, `parent`) VALUES
(1, 'bán chạy nhất', 'best_sell', 'Sản phẩm bán chạy nhất', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `category_relationships`
--

CREATE TABLE `category_relationships` (
  `id` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  `category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `category_relationships`
--

INSERT INTO `category_relationships` (`id`, `product`, `category`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `title` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` float NOT NULL,
  `author` int(11) NOT NULL,
  `producer` varchar(455) COLLATE utf8_unicode_ci NOT NULL,
  `date_created` int(11) DEFAULT NULL,
  `attachment` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `title`, `description`, `content`, `price`, `author`, `producer`, `date_created`, `attachment`) VALUES
(1, 'Bàn chải long mềm', 'Bàn chải vệ sinh đặc biệt phù hợp cho những chất liệu giày có cấu tạo phức tạp và vãi dễ bị tổn thương', 'Bàn chải vệ sinh đặc biệt phù hợp cho những chất liệu giày có cấu tạo phức tạp và vãi dễ bị tổn thương', 180, 2, 'ABC S', 1521349480, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `title` varchar(455) COLLATE utf8_unicode_ci NOT NULL,
  `price` float NOT NULL,
  `attachment` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `birthday` int(11) DEFAULT NULL,
  `email` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `level` int(11) DEFAULT NULL COMMENT '0: admin, 1:user',
  `date_created` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL COMMENT '0: current; 1:FB, 2:G+',
  `token` varchar(455) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `display_name`, `password`, `birthday`, `email`, `level`, `date_created`, `type`, `token`) VALUES
(2, 'hieutct', 'Hiếu Ngầu', '202cb962ac59075b964b07152d234b70', 790502400, 'thanhhieu0195@gmail.com', 0, 1521304371, 0, '533780ab61fe28d3c830ef3c0a9de375'),
(3, '856641977805897', 'Hiếu Thanh', '8278fc7fc1d0d696480d6565632ea411', 0, '', 1, 1521595686, 0, 'EAAapDwlPmRkBAF83Ap82y8dy4F6V44NyNm2Tt4FmTsGYstQP9SFhiOC6sVZBXZB0NNxrJHntCrZAM9GAhRfYAZCMbdcJfkyqPyZCtxGDE292oDTe2sqTpTMKFF04BHJwZASWgxLtdgPhZCATZBpZC6nkp9XBXePD5w5UTH9wuTyrHEgZDZD'),
(5, '110778352213465772240', 'HIEU THANH', 'fc9f055a8f196723da3e8cabeae82bbb', 0, 'thanhhieu0195@gmail.com', 1, 1521642874, 0, 'ya29.GluGBdLT3EWPFAcWwMVxZFZ3tVSIVoYtYKqfTy09s_wn7kGFVRPmFsuCY2PA9B_WrXMfQ3edBfTN6QcaGmftz2f5NuTwMjMkne39H45Cx0ugcuT4L4voHNgPF6CT');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachment`
--
ALTER TABLE `attachment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_relationships`
--
ALTER TABLE `category_relationships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_relationship_category` (`category`),
  ADD KEY `category_relationship_product` (`product`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_author` (`author`),
  ADD KEY `product_attachment` (`attachment`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachment`
--
ALTER TABLE `attachment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category_relationships`
--
ALTER TABLE `category_relationships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category_relationships`
--
ALTER TABLE `category_relationships`
  ADD CONSTRAINT `category_relationship_category` FOREIGN KEY (`category`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `category_relationship_product` FOREIGN KEY (`product`) REFERENCES `product` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_attachment` FOREIGN KEY (`attachment`) REFERENCES `attachment` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_author` FOREIGN KEY (`author`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
