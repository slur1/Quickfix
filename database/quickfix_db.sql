-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 28, 2025 at 05:40 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quickfix_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '$2y$10$XtO6w5JqnfxLX/gjcZW2QOUUW2gzMD1NyLecAiZ8cbGK/rmW8LGZS', '2024-11-25 16:19:55', '2025-01-29 14:08:23'),
(2, 'monica', '$2y$10$gLIKTNP8Bk6PpbvxkM20FuLWO2Ds3ERNZMkUmWC2yBm0YwCYecCVG', '2025-01-29 13:47:57', '2025-01-29 14:08:23');

-- --------------------------------------------------------

--
-- Table structure for table `assesment`
--

CREATE TABLE `assesment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `answers` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assesment`
--

INSERT INTO `assesment` (`id`, `user_id`, `answers`) VALUES
(7, 89, 'sdfsdf'),
(8, 89, 'Sa'),
(9, 89, 'xvxcv'),
(10, 90, 'asdasdas'),
(11, 90, 'dfgfgdgdfg'),
(12, 90, 'bvbnvbnvbnvb'),
(13, 84, 'asd'),
(14, 84, 'fdgdf'),
(15, 84, 'hgfh'),
(16, 92, '3'),
(17, 92, '2'),
(18, 92, 'd'),
(19, 105, 's'),
(20, 105, 's'),
(21, 105, 's'),
(22, 113, '1'),
(23, 113, '1'),
(24, 113, '1'),
(25, 114, 'e'),
(26, 114, 'e'),
(27, 114, 'e'),
(28, 114, 'd'),
(29, 114, 'd'),
(30, 114, 'd'),
(31, 114, 'd'),
(32, 114, 'd'),
(33, 114, 'd');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(9, 'Cleaning'),
(10, 'Repair');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `job_id`, `sender_id`, `receiver_id`, `message`, `image`, `created_at`) VALUES
(86, 31, 90, 84, 'good evening po', NULL, '2025-03-06 11:17:54'),
(87, 31, 90, 84, '', 'user-uploads/1741259879_1-5-600x381.jpg', '2025-03-06 11:18:00'),
(88, 42, 89, 90, 'hello po pakyu ka po', NULL, '2025-03-16 14:05:01'),
(89, 42, 89, 90, '', 'user-uploads/1742133906_Clickon_Water_Dispenser.jpg', '2025-03-16 14:05:06'),
(90, 45, 105, 113, 'heller', NULL, '2025-03-27 17:52:43'),
(91, 48, 105, 113, 'fucp', NULL, '2025-03-28 10:12:44'),
(92, 48, 105, 113, 'ff', NULL, '2025-03-28 12:02:16');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `job_id`, `comment`, `created_at`, `parent_id`) VALUES
(74, 105, 167, 'umay', '2025-03-24 14:11:22', NULL),
(75, 105, 167, 'bushet', '2025-03-24 14:11:31', 74),
(76, 105, 165, 'hasyt', '2025-03-24 14:45:15', NULL),
(77, 105, 165, 'AIGOOO', '2025-03-24 15:08:28', NULL),
(78, 113, 180, 'potagnina', '2025-03-24 15:16:39', NULL),
(80, 113, 185, 'Gaano po kalaki yung house?', '2025-03-25 13:40:12', NULL),
(81, 113, 185, 'kasinglaki mo po', '2025-03-25 13:40:23', 80),
(83, 113, 179, 'ffff', '2025-03-27 08:15:00', NULL),
(84, 114, 191, 'monica oi', '2025-03-28 11:47:20', NULL),
(98, 105, 191, 'momoca', '2025-03-28 12:08:57', NULL),
(99, 105, 191, 'tagnina', '2025-03-28 12:09:08', 98),
(100, 105, 191, 'dada', '2025-03-28 12:09:12', 84),
(101, 105, 191, 'potaaa', '2025-03-28 12:16:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `completed_jobs`
--

CREATE TABLE `completed_jobs` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `offer_amount` decimal(10,2) NOT NULL,
  `completion_time` varchar(255) NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rating` decimal(2,1) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `status` enum('completed') DEFAULT 'completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `completed_jobs`
--

INSERT INTO `completed_jobs` (`id`, `job_id`, `offer_id`, `provider_id`, `user_id`, `job_title`, `location`, `description`, `offer_amount`, `completion_time`, `completed_at`, `rating`, `review`, `status`) VALUES
(27, 168, 90, 84, 90, 'Fix a Chandelier', 'Aguila Street, Pangarap, Zone 16, District 1, Caloocan, Northern Manila District, Metro Manila, 1427, Philippines', 'faulty or flickering lights\r\nplease bring your own tools', 400.00, '2-3 hrs', '2025-03-06 11:18:16', 4.0, 'good', 'completed'),
(35, 173, 97, 89, 90, 'Web Developer', 'Caloocan, Northern Manila District, Metro Manila, Philippines', 'WEb developer html css js', 10000.00, '.', '2025-03-16 06:47:58', 5.0, 'goods', 'completed'),
(36, 174, 98, 89, 90, 'consultant', 'Caloocan, Northern Manila District, Metro Manila, Philippines', 'consultant', 123.00, '.', '2025-03-16 12:43:37', 4.0, 'ntest', 'completed'),
(37, 178, 101, 105, 92, 'Cleaning a house', 'Barangay 172, Goodharvest Park, Camarin, District 1, Caloocan, Northern Manila District, Metro Manila, 1422, Philippines', 'sasasa', 2.00, 'e', '2025-03-17 13:12:40', 5.0, 'goods', 'completed'),
(38, 180, 107, 113, 105, 'Cleaning a house', 'Barangay 178, Kasama Step Up Community, Camarin, District 3, Caloocan, Northern Manila District, Metro Manila, 1427, Philippines', '.', 700.00, 'g', '2025-03-27 18:24:52', NULL, NULL, 'completed'),
(39, 179, 106, 113, 105, 'Cleaning a house', 'Barangay 172, Goodharvest Park, Camarin, District 1, Caloocan, Northern Manila District, Metro Manila, 1422, Philippines', 'dd', 500.00, 'h', '2025-03-27 18:39:04', NULL, NULL, 'completed'),
(40, 185, 105, 113, 105, 'Cleaning a house', 'A. Mabini Elementary School, Cherry Blossom Street, Barangay 187, Zone 16, District 3, Caloocan, Northern Manila District, Metro Manila, 1438, Philippines', '.', 500.00, '..', '2025-03-28 05:48:30', 5.0, 'yeyss', 'completed'),
(41, 185, 105, 113, 105, 'Cleaning a house', 'A. Mabini Elementary School, Cherry Blossom Street, Barangay 187, Zone 16, District 3, Caloocan, Northern Manila District, Metro Manila, 1438, Philippines', '.', 500.00, '..', '2025-03-28 12:02:31', NULL, NULL, 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `in_progress_jobs`
--

CREATE TABLE `in_progress_jobs` (
  `id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `job_date` date DEFAULT NULL,
  `job_time` varchar(50) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `budget` decimal(10,2) NOT NULL,
  `offer_amount` decimal(10,2) NOT NULL,
  `offer_message` text DEFAULT NULL,
  `completion_time` varchar(255) NOT NULL,
  `status` enum('in_progress','completed','cancelled') NOT NULL DEFAULT 'in_progress',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `job_date` date DEFAULT NULL,
  `job_time` varchar(50) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `budget` text NOT NULL COMMENT 'oldset:decimal=10,2||current:ranging',
  `images` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('open','in_progress','completed','cancelled') NOT NULL DEFAULT 'open',
  `latitude` decimal(10,6) DEFAULT NULL,
  `longitude` decimal(10,6) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `sub_category_id` int(11) NOT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancelled_by` int(11) DEFAULT NULL,
  `chosen_worker_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `user_id`, `job_title`, `job_date`, `job_time`, `location`, `description`, `budget`, `images`, `created_at`, `status`, `latitude`, `longitude`, `category_id`, `sub_category_id`, `cancelled_at`, `cancelled_by`, `chosen_worker_id`) VALUES
(165, 90, 'Cleaning a house', NULL, 'Midday (10am - 2pm)', 'Kasama Step Up Community, Zone 16, District 1, Caloocan, Northern Manila District, Metro Manila, 1427, Philippines', 'Clean a garden', '500 - 5000', 'user-uploads/philippines-siquijor-island-larena-town-street-scene-BT5NGA.jpg', '2025-03-06 10:46:10', 'open', 14.755447, 121.067568, 9, 3, NULL, NULL, NULL),
(166, 90, 'Repair Oven', '2025-03-14', 'Afternoon (2pm - 6pm)', 'Barangay 176-A, Zone 15, Bagong Silang, District 1, Caloocan, Northern Manila District, Metro Manila, 1428, Philippines', 'Oven light fails to turn on.\r\nplease bring your own tools', '300.00', 'user-uploads/electric-ovens-most-common-faults.jpg', '2025-03-06 10:50:39', 'completed', 14.776733, 121.043048, 10, 10, NULL, NULL, NULL),
(167, 90, 'Clean a Carpet', '2025-03-11', 'Afternoon (2pm - 6pm)', 'Silver Street, Doña Helen Subdivision, Camarin, District 1, Caloocan, Northern Manila District, Metro Manila, 1422, Philippines', 'Clean a dirty carpet.\r\nplease  provide equipment and chemicals.', '1000.00', 'user-uploads/images (2).jpg', '2025-03-06 10:54:26', 'open', 14.755049, 121.047891, 9, 5, NULL, NULL, NULL),
(168, 90, 'Fix a Chandelier', '2025-03-11', 'Afternoon (2pm - 6pm)', 'Aguila Street, Pangarap, Zone 16, District 1, Caloocan, Northern Manila District, Metro Manila, 1427, Philippines', 'faulty or flickering lights\r\nplease bring your own tools', '300.00', 'user-uploads/9114-5.jpg', '2025-03-06 10:56:18', 'completed', 14.762294, 121.092924, 10, 8, NULL, NULL, NULL),
(173, 90, 'Web Developer', '2025-03-20', 'Morning (Before 10am)', 'Caloocan, Northern Manila District, Metro Manila, Philippines', 'WEb developer html css js', '5000 - 10000', 'user-uploads/attachment_132674120.png', '2025-03-16 06:25:36', 'open', 14.651348, 120.972400, 9, 1, NULL, NULL, NULL),
(174, 90, 'consultant', '2025-03-28', NULL, 'Caloocan, Northern Manila District, Metro Manila, Philippines', 'consultant', '1000 - 20000', 'user-uploads/cereals2.jpg', '2025-03-16 12:32:52', 'open', 14.651348, 120.972400, 9, 2, NULL, NULL, NULL),
(175, 90, 'development', '2025-03-27', NULL, 'Navotas, Northern Manila District, Metro Manila, Philippines', 'development', '100 - 200', 'user-uploads/vegetables2.jpg', '2025-03-16 12:34:43', 'open', 14.657186, 120.947969, 9, 2, NULL, NULL, NULL),
(179, 105, 'Cleaning a house', NULL, 'Midday (10am - 2pm)', 'Barangay 172, Goodharvest Park, Camarin, District 1, Caloocan, Northern Manila District, Metro Manila, 1422, Philippines', 'dd', '1000-5000', NULL, '2025-03-17 04:50:19', 'completed', -42.717360, 170.976750, 9, 4, NULL, NULL, NULL),
(180, 105, 'Cleaning a house', NULL, 'Evening (After 6pm)', 'Barangay 178, Kasama Step Up Community, Camarin, District 3, Caloocan, Northern Manila District, Metro Manila, 1427, Philippines', '.', '1000-5000', NULL, '2025-03-18 17:58:09', 'completed', 14.574962, 120.990918, 0, 0, NULL, NULL, NULL),
(185, 105, 'Cleaning a house', '2025-03-03', 'Evening (After 6pm)', 'A. Mabini Elementary School, Cherry Blossom Street, Barangay 187, Zone 16, District 3, Caloocan, Northern Manila District, Metro Manila, 1438, Philippines', '.', '1000', '', '2025-03-18 19:53:30', 'completed', 14.766394, 121.060740, 9, 3, NULL, NULL, NULL),
(190, 105, 'Cleaning my mansioning pleaseabless', NULL, 'Midday (10am - 2pm)', 'Katmon Street, Kasama Step Up Community, Zone 16, District 3, Caloocan, Northern Manila District, Metro Manila, 1427, Philippines', 'Sana maka graduate with them.     please lord', '4000 - 6000', 'user-uploads/istockphoto-1189357377-612x612.jpg,user-uploads/9114-5.jpg', '2025-03-27 15:09:30', 'cancelled', 14.754461, 121.067682, 9, 3, '2025-03-28 05:26:37', 105, NULL),
(191, 105, 'Clean my garage please senpai pwiss', NULL, 'Evening (After 6pm)', 'Katmon Street, Kasama Step Up Community, Zone 16, District 3, Caloocan, Northern Manila District, Metro Manila, 1427, Philippines', 'please sana maka graduate', '2000 - 9000', 'user-uploads/room-stacked-full-of-possessions-DWJDTF.jpg,user-uploads/istockphoto-1189357377-612x612.jpg,user-uploads/9114-5.jpg,user-uploads/480948010_1832506980935620_7537748626361050051_n.jpg', '2025-03-27 15:22:18', 'cancelled', 14.754461, 121.067682, 10, 8, '2025-03-28 03:12:12', 105, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_comments`
--

CREATE TABLE `job_comments` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `offer_amount` decimal(10,2) NOT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','accepted','declined') DEFAULT 'pending',
  `completion_time` varchar(255) NOT NULL,
  `creation_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `job_id`, `provider_id`, `offer_amount`, `message`, `status`, `completion_time`, `creation_time`) VALUES
(104, 167, 105, 2.00, 'd', 'pending', 'd', '2025-03-24 10:51:11'),
(105, 185, 113, 500.00, '..', 'accepted', '..', '2025-03-27 10:27:38'),
(106, 179, 113, 500.00, 'h', 'accepted', 'h', '2025-03-27 10:27:50'),
(107, 180, 113, 700.00, 'g', 'accepted', 'g', '2025-03-27 10:28:04'),
(108, 190, 114, 23.00, 'dd', 'pending', 'dd', '2025-03-28 12:25:07'),
(109, 191, 114, 3.00, 'dawa', 'pending', 'da', '2025-03-28 12:25:20');

-- --------------------------------------------------------

--
-- Table structure for table `pending_user`
--

CREATE TABLE `pending_user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `id_type` varchar(50) NOT NULL,
  `id_file_path` varchar(255) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `email_verified` tinyint(1) DEFAULT 0,
  `verification_code` varchar(6) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rejected_user`
--

CREATE TABLE `rejected_user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `id_type` varchar(50) NOT NULL,
  `id_file_path` varchar(255) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `rejection_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rejected_user`
--

INSERT INTO `rejected_user` (`id`, `first_name`, `last_name`, `email`, `id_type`, `id_file_path`, `contact_number`, `password_hash`, `created_at`, `rejection_reason`) VALUES
(21, 'Monica', 'Alburo', 'monicalburo12@gmail.com', 'barangay_id', 'user-uploads/Barangay_ID_Sample2.jpg', '+639276245251', '$2y$10$j5wksmSmL2kUM.hKwe07oeUAQttoudqycuDBaUOEpUGGphX9Pati2', '2025-02-11 14:16:44', 'Mismatched details with ID');

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `category_id`, `name`) VALUES
(1, 9, 'Laundry'),
(2, 9, 'Upholstery Cleaning'),
(3, 9, 'Regular Cleaning'),
(4, 9, 'Deep Cleaning'),
(5, 9, 'Carpet Cleaning'),
(6, 9, 'Aircon Cleaning'),
(7, 10, 'Electrical Repair'),
(8, 10, 'Lighting Repair'),
(9, 10, 'Wiring Repair'),
(10, 10, 'Appliance Repair'),
(11, 10, 'Furniture Repair');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `id_type` varchar(50) NOT NULL,
  `id_file_path` varchar(255) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `email_verified` tinyint(1) DEFAULT 0,
  `verification_code` varchar(6) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'approved',
  `take_assesment` tinyint(1) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `about_me` text DEFAULT NULL,
  `general_location` varchar(255) DEFAULT NULL,
  `portfolio` text DEFAULT NULL,
  `verification_status` enum('unverified','identity_verified','fully_verified') NOT NULL DEFAULT 'unverified',
  `id_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `email`, `id_type`, `id_file_path`, `contact_number`, `password_hash`, `created_at`, `email_verified`, `verification_code`, `status`, `take_assesment`, `profile_picture`, `skills`, `about_me`, `general_location`, `portfolio`, `verification_status`, `id_image`) VALUES
(105, 'Monica', 'Alburo', 'monicarumbide12@gmail.com', 'national_id', '../uploads/ids/id_67d94afa9afb34.31116672.jpg', '09276245261', '$2y$10$tq1p5ygYmHqnM3euHPaGG.5pcZqQGNX.0nWKWVtI.tzJFCitUta5u', '2025-03-17 10:11:32', 1, 'f8dd8d', 'approved', 1, '../uploads/profile_pictures/1742236941_momo.jpg', 'Backend Dev,            Expert Cleaner,          songerist', 'Too boring to be with.\r\nI cannot code without ai and you need to have the tools and equipment when you want me to clean your house or something. duhh', 'North Caloocan, Metro Manila', 'https://www.facebook.com/monica.alburo.3', 'identity_verified', NULL),
(113, 'Bitch na', 'Peke', 'secreswallowtail112@gmail.com', '', '', '', '$2y$10$T7.Lg2gm7dxgNVvlPjHHJ.EupeHtwlA6idwnqSlh.yjK08fd15Dp6', '2025-03-18 01:48:55', 1, '75042e', 'approved', 1, '../uploads/profile_pictures/default-avatar.jpg', '', '', '', '', 'identity_verified', NULL),
(114, 'Monica', 'Swallowtail', 'monicalburo12@gmail.com', '', '', '9817562749', '$2y$10$nxSNrY8hr5ac0UcdZ8aFfeG9kxn8pCL72l4ddNDz6Sikpupx8lmZm', '2025-03-19 02:17:36', 1, '15ecf1', 'approved', 1, '../uploads/profile_pictures/1742803180_535361b1eaf3a871024ad773933751d4.jpg', 'Dev', 'hey hey hey', 'North Caloocan, Metro Manila', '', 'unverified', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_verifications`
--

CREATE TABLE `user_verifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `id_type` varchar(50) NOT NULL,
  `id_file_path` varchar(255) NOT NULL,
  `verification_status` enum('unverified','identity_verified','fully_verified') NOT NULL DEFAULT 'unverified',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `assesment`
--
ALTER TABLE `assesment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `completed_jobs`
--
ALTER TABLE `completed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `in_progress_jobs`
--
ALTER TABLE `in_progress_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offer_id` (`offer_id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jobs_user` (`user_id`);

--
-- Indexes for table `job_comments`
--
ALTER TABLE `job_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `pending_user`
--
ALTER TABLE `pending_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `rejected_user`
--
ALTER TABLE `rejected_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `verification_code` (`verification_code`);

--
-- Indexes for table `user_verifications`
--
ALTER TABLE `user_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `assesment`
--
ALTER TABLE `assesment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `completed_jobs`
--
ALTER TABLE `completed_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `in_progress_jobs`
--
ALTER TABLE `in_progress_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT for table `job_comments`
--
ALTER TABLE `job_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `pending_user`
--
ALTER TABLE `pending_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `rejected_user`
--
ALTER TABLE `rejected_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `user_verifications`
--
ALTER TABLE `user_verifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`);

--
-- Constraints for table `in_progress_jobs`
--
ALTER TABLE `in_progress_jobs`
  ADD CONSTRAINT `in_progress_jobs_ibfk_1` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `in_progress_jobs_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `in_progress_jobs_ibfk_3` FOREIGN KEY (`provider_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `in_progress_jobs_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `fk_jobs_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_comments`
--
ALTER TABLE `job_comments`
  ADD CONSTRAINT `job_comments_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `offers_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `offers_ibfk_2` FOREIGN KEY (`provider_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD CONSTRAINT `sub_categories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_verifications`
--
ALTER TABLE `user_verifications`
  ADD CONSTRAINT `user_verifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
