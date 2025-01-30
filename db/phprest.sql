-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2025 at 02:43 PM
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
-- Database: `phprest`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Technology', '2025-01-28 15:16:57'),
(2, 'Gaming', '2025-01-28 15:16:57'),
(3, 'Auto', '2025-01-28 15:17:59'),
(4, 'Entertainment', '2025-01-08 17:17:59'),
(5, '', '2025-01-30 16:41:19'),
(6, 'Music', '2025-01-30 16:42:29');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `author` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `category_id`, `title`, `body`, `author`, `created_at`) VALUES
(1, 1, 'Technology post one', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia laboriosam unde neque natus similique a itaque dolores vero commodi, recusandae eum veniam aut sunt aliquam? Asperiores, sunt? Vero, voluptatum repellendus.', 'Sam Smith', '2025-01-29 08:03:08'),
(2, 2, 'Gaming Post One', 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Deleniti, nemo. Rem exercitationem dicta nostrum, ipsam molestias aut tempora ipsa ab.', 'Sam Smith', '2025-01-15 10:04:49'),
(3, 1, 'Technology Post Two', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias necessitatibus in doloribus atque qui provident saepe impedit officia corporis reiciendis, quisquam asperiores, vitae ullam velit laudantium rem exercitationem, nihil vero.', 'Marry Joe', '2025-01-29 08:06:38'),
(4, 4, 'Technology Post Three', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa et a, cum, voluptatibus, labore unde sequi amet ducimus aliquam ipsam autem quam maiores dignissimos animi. Nesciunt ipsum suscipit deleniti officia.', 'Sam Smith', '2025-01-14 10:08:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
