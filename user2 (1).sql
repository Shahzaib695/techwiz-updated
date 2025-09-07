-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 07, 2025 at 02:18 PM
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
-- Database: `user2`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `designer_id` int(11) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `service` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `designer_id`, `employee_name`, `client_name`, `phone`, `service`, `amount`, `date`, `time`, `status`, `created_at`) VALUES
(1, 0, 'Moin', 'Fahad', '03708541533', 'Massage', 2500.00, '2025-07-26', '20:57:00', 'Approved', '2025-07-26 15:58:01'),
(2, 0, 'Faiz', 'Farrukh', '03708541533', 'Facial', 2000.00, '2025-07-26', '20:58:00', 'Rejected', '2025-07-26 15:58:38'),
(3, 0, 'Taimoor Bhai', 'Zaki Bhai', '03708541533', 'Haircut', 1500.00, '2025-07-26', '20:59:00', 'Approved', '2025-07-26 15:59:19'),
(4, 0, 'Fahad', 'Zaki Bhai', '03708541533', 'Manicure', 1200.00, '2025-07-26', '21:02:00', 'Approved', '2025-07-26 16:03:05'),
(5, 0, 'Faiz', 'Farrukh', '03708541533', 'Manicure', 1200.00, '2025-07-27', '22:08:00', 'Approved', '2025-07-27 17:08:54'),
(6, 0, 'Zeeshan Bhai', '#', '78546123982', 'Massage', 2500.00, '2025-07-27', '22:09:00', 'Approved', '2025-07-27 17:09:54'),
(7, 0, 'Taimoor Bhai', '#', '78546123982', 'Haircut', 1500.00, '2025-07-28', '03:30:00', 'Pending', '2025-07-28 22:30:52'),
(8, 0, 'Faiz', '$', '03708541533', 'Massage', 2500.00, '2025-07-29', '03:36:00', 'Pending', '2025-07-28 22:36:31'),
(9, 0, 'Taimoor Bhai', '$', '03708541533', 'Haircut', 1500.00, '2025-07-29', '03:52:00', 'Pending', '2025-07-28 22:52:55'),
(10, 0, 'Taimoor Bhai', 'Muhammad Farrukh', '03708541533', 'Facial', 2000.00, '2025-07-29', '04:06:00', 'Pending', '2025-07-28 23:07:24'),
(11, 0, 'Zeeshan Bhai', 'Zaki Bhai', '03708541533', 'Massage', 2500.00, '2025-07-29', '07:27:00', 'Pending', '2025-07-29 02:27:32'),
(12, 0, 'Taimoor Bhai', 'Fahad', '03708541533', 'Haircuts', 125.00, '2025-07-29', '07:56:00', 'Pending', '2025-07-29 02:57:24'),
(13, 0, 'Taimoor Bhai', 'Fahad', '03457812691', 'Skin Fade', 155.00, '2025-07-29', '08:14:00', 'Approved', '2025-07-29 03:14:36'),
(14, 0, 'Faiz', 'Fahad', '03457812691', 'Head Massage', 60.00, '2025-07-29', '08:43:00', 'Pending', '2025-07-29 03:43:47');

-- --------------------------------------------------------

--
-- Table structure for table `appointments2`
--

CREATE TABLE `appointments2` (
  `id` int(11) NOT NULL,
  `designer_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('Pending','Confirmed','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `designer_profiles`
--

CREATE TABLE `designer_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `bio` text NOT NULL,
  `expertise` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `day` varchar(20) DEFAULT NULL,
  `from_time` time DEFAULT NULL,
  `to_time` time DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `designer_profiles`
--

INSERT INTO `designer_profiles` (`id`, `user_id`, `name`, `bio`, `expertise`, `image`, `day`, `from_time`, `to_time`, `status`, `created_at`) VALUES
(1, 37, 'shahzaib', 'hyello hi bye', 'fala', 'uploads/designers/1757231329_cat-4.png', 'Monday', '16:48:00', '00:48:00', 'Approved', '2025-09-07 07:48:49');

-- --------------------------------------------------------

--
-- Table structure for table `designer_reviews`
--

CREATE TABLE `designer_reviews` (
  `id` int(11) NOT NULL,
  `designer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `review` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `experience` varchar(100) DEFAULT NULL,
  `available_days` varchar(100) DEFAULT NULL,
  `available_time_from` time DEFAULT NULL,
  `available_time_to` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `image`, `designation`, `experience`, `available_days`, `available_time_from`, `available_time_to`, `created_at`) VALUES
(10, 'Ali Raza', '68bc976fe28a1_vertical-shot-man-with-long-beard-mustache-fixing-broken-chair.jpg', 'Carpenter Lead', '10+ Years', 'wed-Sat', '14:30:00', '21:30:00', '2025-09-05 12:48:06'),
(11, 'Sarah Khan', '68bc973a10a1f_female-cutting-wood-plank.jpg', 'Upholstery Specialist', '8+ Years', 'Tue-Sat', '11:20:00', '18:40:00', '2025-09-05 12:50:54'),
(12, 'David Smith', '68bc974438b98_close-up-handyman-sawing-long-wooden-plank.jpg', 'Showroom Manager', '10+ Years', 'Mon-Fri', '09:00:00', '18:00:00', '2025-09-05 12:52:45'),
(13, 'Maria Lopez', '68bc97535ad2f_medium-shot-woman-with-screwdriver.jpg', 'Furniture Sales Executive', '6+ Years', 'Mon-Sun', '11:00:00', '20:00:00', '2025-09-05 12:52:45'),
(14, 'Ahmed Khan', '68bc95da21b20_medium-shot-plus-size-man-working-as-barista.jpg', 'Woodcraft Expert', '20+ Years', 'Mon-Sat', '07:30:00', '16:30:00', '2025-09-05 12:52:45');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `product_name`, `product_image`, `product_price`, `quantity`, `username`, `email`, `payment_method`, `order_date`, `status`) VALUES
(1, 'Classic Handbag', 'images/hand bags.jpg', 89.00, 1, 'faiz', 'faiz@faiz.com', 'rast', '2025-07-09 02:03:44', 'Approved'),
(2, 'Urban Sneakers', 'images/sneaker.jpg', 99.00, 1, 'faiz', 'faiz@faiz.com', 'jazzcash', '2025-07-09 02:17:22', 'Pending'),
(10, 'Urban Sneakers', 'images/sneaker.jpg', 99.00, 15, 'Farrokh', 'farrokh@latop.com', 'bank', '2025-07-09 03:16:22', 'Approved'),
(11, 'Classic Handbag', 'images/hand bags.jpg', 89.00, 1, 'Farrokh', 'farrokh@latop.com', 'jazzcash', '2025-07-09 03:19:28', 'Pending'),
(12, 'Fashion Sunglasses', 'images/Fashion Sunglasses.jpg', 39.00, 5, 'Farrokh', 'farrokh@latop.com', 'jazzcash', '2025-07-09 03:19:49', 'Approved'),
(13, 'Casual Hoodie', 'images/Casual Hoodie.jpg', 58.00, 5, 'Farrokh', 'farrokh@latop.com', 'jazzcash', '2025-07-09 03:19:49', 'Pending'),
(14, 'Keratin Shampoo', 'elegance salon images/1acd12c0cb7e7f74bdd8b9f916576d74.jpg', 14.50, 4, 'Farrokh', 'farrokh@latop.com', 'rast', '2025-07-19 22:18:58', 'Pending'),
(15, 'Argan Oil', 'elegance salon images/1acd12c0cb7e7f74bdd8b9f916576d74.jpg', 22.00, 2, 'Fahad', 'Fahad@Hafiz.com', 'easypaisa', '2025-07-19 22:25:40', 'Approved'),
(16, 'Volume Spray', 'elegance salon images/1acd12c0cb7e7f74bdd8b9f916576d74.jpg', 12.75, 5, 'Fahad', 'Fahad@Hafiz.com', 'jazzcash', '2025-07-19 22:27:10', 'Pending'),
(17, 'Hair Serum', 'elegance salon images/1acd12c0cb7e7f74bdd8b9f916576d74.jpg', 18.99, 7, 'Fahad', 'Fahad@Hafiz.com', 'rast', '2025-07-19 22:28:17', 'Approved'),
(18, 'Hair Mask', 'elegance salon images/1acd12c0cb7e7f74bdd8b9f916576d74.jpg', 19.99, 5, 'Fahad', 'Fahad@Hafiz.com', 'bank', '2025-07-19 23:08:24', 'Pending'),
(19, 'Volume Spray', 'elegance salon images/1acd12c0cb7e7f74bdd8b9f916576d74.jpg', 12.75, 7, 'Fahad', 'Fahad@Hafiz.com', 'rast', '2025-07-19 23:13:45', 'Approved'),
(20, 'Hair Serum', 'elegance salon images/1acd12c0cb7e7f74bdd8b9f916576d74.jpg', 18.99, 1, 'Fahad', 'Fahad@Hafiz.com', 'rast', '2025-07-20 02:52:26', 'Approved'),
(21, 'Argan Oil', '', 22.00, 5, 'Fahad', 'Fahad@Hafiz.com', 'rast', '2025-07-21 02:17:45', 'Pending'),
(22, 'argan oil', 'uploads/1753267685_1acd12c0cb7e7f74bdd8b9f916576d74.jpg', 3900.00, 1, 'moin', 'moin@gmail.com', 'rast', '2025-07-23 16:25:40', 'Pending'),
(23, 'argan oil', 'uploads/1753267685_1acd12c0cb7e7f74bdd8b9f916576d74.jpg', 3900.00, 1, 'moin', 'moin@gmail.com', 'cash', '2025-07-23 16:33:01', 'Pending'),
(24, 'argan oil', 'uploads/1753267685_1acd12c0cb7e7f74bdd8b9f916576d74.jpg', 3900.00, 1, 'ali', 'ali@gmail.com', 'Bank Transfer', '2025-07-23 17:13:54', 'Pending'),
(25, 'argan oil', 'uploads/1753267685_1acd12c0cb7e7f74bdd8b9f916576d74.jpg', 3900.00, 1, 'ali', 'ali@gmail.com', 'rast', '2025-07-23 19:55:32', 'Pending'),
(27, 'Keratin Shampoo', 'uploads/1753284311_Keratin Shampoo.jpg', 39.00, 1, 'arman', 'arman@gmail.com', 'rast', '2025-07-23 21:04:25', 'Pending'),
(28, 'Volume Spray', 'uploads/1753286302_Volume Spray.jpg', 55.00, 1, 'arman', 'arman@gmail.com', 'rast', '2025-07-23 21:04:25', 'Pending'),
(29, 'Hair Mask', 'uploads/1753284516_Hair Mask.jpg', 45.00, 1, 'ayaz', 'aliraza@gmail.com', 'easypaisa', '2025-07-25 18:07:23', 'Approved'),
(30, 'Hair Serum', 'uploads/1753284429_Hair Serum.jpg', 49.00, 1, 'ayaz', 'aliraza@gmail.com', 'easypaisa', '2025-07-25 18:09:58', 'Pending'),
(31, 'Volume Spray', 'uploads/1753286302_Volume Spray.jpg', 55.00, 1, 'ayaz', 'aliraza@gmail.com', 'rast', '2025-07-25 18:27:39', 'Pending'),
(32, 'Argan Oil', 'uploads/1753284166_Argan Oil.jpg', 50.00, 1, 'ayaz', 'aliraza@gmail.com', 'rast', '2025-07-25 18:29:17', 'Pending'),
(33, 'Volume Spray', 'uploads/1753286302_Volume Spray.jpg', 55.00, 1, 'ayaz', 'aliraza@gmail.com', 'rast', '2025-07-25 18:36:16', 'Pending'),
(34, 'Hair Mask', 'uploads/1753284516_Hair Mask.jpg', 45.00, 1, 'ayaz', 'aliraza@gmail.com', 'easypaisa', '2025-07-25 18:43:51', 'Pending'),
(35, 'Hair Mask', 'uploads/1753284516_Hair Mask.jpg', 45.00, 1, 'saim', 'saim@gmail.com', 'easypaisa', '2025-07-25 18:59:14', 'Approved'),
(36, 'EdgePro Precision Trimmer', 'uploads/1753465170_8c747b6459e6c56de7758c44b3765049.jpg', 90.00, 1, 'saim', 'saim@gmail.com', 'rast', '2025-07-25 23:54:04', 'Approved'),
(37, 'BuzzClean Hair & Scalp Tonic', 'uploads/1753464152_BuzzClean Hair & Scalp Tonic.jpg', 245.00, 1, 'Fahad', '12345@gmail.com', 'easypaisa', '2025-07-26 17:54:24', 'Pending'),
(38, 'EdgePro Precision Trimmer', 'uploads/1753465170_8c747b6459e6c56de7758c44b3765049.jpg', 90.00, 1, 'Farrokh', 'Fahad@Hafiz.com', 'rast', '2025-07-27 19:52:11', 'Pending'),
(39, 'Ocean Vibe Body Spray', 'uploads/1753463821_Ocean Vibe Body Spray.jpg', 89.00, 1, 'Farrokh', 'Fahad@Hafiz.com', 'rast', '2025-07-27 22:07:22', 'Pending'),
(40, 'SpikeFix Hair Wax', 'uploads/1753463400_SpikeFix Hair Wax.jpg', 89.00, 1, 'Farrokh', 'Fahad@Hafiz.com', 'rast', '2025-07-27 22:07:39', 'Pending'),
(41, 'Charcoal Detox Face Wash', 'uploads/1753463113_Charcoal Detox Face Wash.jpg', 78.00, 1, 'Farrokh', 'Fahad@Hafiz.com', 'jazzcash', '2025-07-27 22:08:01', 'Pending'),
(42, 'Hair Serum', 'uploads/1753284429_Hair Serum.jpg', 49.00, 1, 'Farrokh', 'Fahad@Hafiz.com', 'rast', '2025-07-29 01:47:35', 'Pending'),
(43, 'SmoothStrand Conditioner', 'uploads/1753463732_SmoothStrand Conditioner.jpg', 97.00, 1, 'Farrokh', 'Fahad@Hafiz.com', 'easypaisa', '2025-07-29 01:51:03', 'Pending'),
(44, 'EdgePro Precision Trimmer', 'uploads/1753465170_8c747b6459e6c56de7758c44b3765049.jpg', 90.00, 1, 'Farrokh', 'Fahad@Hafiz.com', 'rast', '2025-07-29 01:52:18', 'Pending'),
(45, 'SmoothStrand Conditioner', 'uploads/1753463732_SmoothStrand Conditioner.jpg', 97.00, 1, 'Farrokh', 'Fahad@Hafiz.com', 'rast', '2025-07-29 03:23:22', 'Pending'),
(46, 'IceBlast Aftershave', 'uploads/1753463305_IceBlast Aftershave.jpg', 78.00, 1, 'Farrokh', 'Fahad@Hafiz.com', 'rast', '2025-07-29 03:23:22', 'Pending'),
(47, 'BuzzClean Hair & Scalp Tonic', 'uploads/1753464152_BuzzClean Hair & Scalp Tonic.jpg', 245.00, 1, 'Farrokh', 'Fahad@Hafiz.com', 'rast', '2025-07-29 03:24:13', 'Pending'),
(48, ' ActiveGlow Face Moisturizer', 'uploads/1753464028_ActiveGlow Face Moisturizer.jpg', 78.00, 1, 'Farrokh', 'Fahad@Hafiz.com', 'rast', '2025-07-29 03:39:02', 'Pending'),
(49, 'EdgePro Precision Trimmer', 'uploads/1753465170_8c747b6459e6c56de7758c44b3765049.jpg', 90.00, 1, 'Farrokh', 'Fahad@Hafiz.com', 'easypaisa', '2025-07-29 03:40:06', 'Pending'),
(50, 'Ocean Vibe Body Spray', 'uploads/1753463821_Ocean Vibe Body Spray.jpg', 89.00, 1, 'Farrokh', 'Fahad@Hafiz.com', 'easypaisa', '2025-07-29 04:07:49', 'Pending'),
(51, ' ActiveGlow Face Moisturizer', 'uploads/1753464028_ActiveGlow Face Moisturizer.jpg', 78.00, 1, 'Moin', '12345@gmail.com', 'rast', '2025-07-31 17:50:42', 'Pending'),
(52, 'EdgePro Precision Trimmer', 'uploads/1753465170_8c747b6459e6c56de7758c44b3765049.jpg', 90.00, 1, 'Fahad', 'Fahad@Hafiz.com', 'easypaisa', '2025-07-31 17:51:23', 'Approved'),
(53, 'CleanEdge Shaving Foam', 'uploads/1753463474_CleanEdge Shaving Foam.jpg', 78.00, 1, 'AHMED', 'moin69603@gmail.com', 'rast', '2025-07-31 17:52:36', 'Approved'),
(54, 'Hair Serum', 'uploads/1753284429_Hair Serum.jpg', 49.00, 1, 'farrokh', 'Fahad@Hafiz.com', 'rast', '2025-08-02 17:48:56', 'Approved'),
(55, ' 3-in-1 Electric grooming kit', 'uploads/1754141549_images (1).jpg', 100.00, 1, 'Farrokh', 'Fahad@Hafiz.com', 'easypaisa', '2025-08-03 00:06:25', 'Pending'),
(56, 'Sabalon Hair Styling Spray', 'uploads/1754141461_images (4).jpg', 80.00, 1, 'Ahmed raza', 'ahmed@raza.com', 'easypaisa', '2025-08-03 19:00:04', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `description`, `price`, `image`, `created_at`) VALUES
(7, 'Living Room Essentials', 'Includes a modern sofa, coffee table, and TV stand for a complete living room setup.', 120000.00, 'uploads/1757192896_Living-Room-Essentials-1.jpg', '2025-09-06 21:05:58'),
(10, 'Office Starter Pack', 'Ergonomic office chair, work desk, and bookshelf for your home office.', 80000.00, 'uploads/1757194973_woman-suit-sitting-desk-office-with-belongings-box-using-smartphone.jpg', '2025-09-06 21:05:58'),
(11, 'Student Furniture Set', 'Single bed, study desk, chair, and small bookshelf designed for students.', 55000.00, 'uploads/1757193723_200-000521.jpg', '2025-09-06 21:05:58'),
(12, 'Luxury Lounge Package', 'Premium recliner chair, sofa set, and center coffee table for relaxation.', 200000.00, 'uploads/1757193647_cda729f1bef83e90d7255d429dc8d326.jpg', '2025-09-06 21:05:58'),
(13, 'Compact Apartment Set', 'Space-saving bed, wardrobe, folding dining table, and multi-use sofa.', 95000.00, 'uploads/1757193434_43258cec28aaaf860fc489a2b6a35ed495b32215.jpg', '2025-09-06 21:05:58'),
(14, 'Classic Wooden Package', 'Traditional wooden dining table, chairs, and cabinet crafted from solid oak.', 160000.00, 'uploads/1757193304_wrapped-packages-wooden-table-paper-fabric-sit-ready-shipping-storage-379794471.jpg', '2025-09-06 21:05:58'),
(15, 'Minimalist Style Set', 'Minimalist sofa, glass coffee table, and sleek TV stand for modern homes.', 110000.00, 'uploads/1757193241_just-in-case_minimalist-packaging-roundup_dezeen-2364-sq-1704x1705.jpg', '2025-09-06 21:05:58'),
(16, 'Complete Home Package', 'Full home package including living room, dining, and bedroom essentials.', 350000.00, 'uploads/1757193119_Living-Room-Essentials-1.jpg', '2025-09-06 21:05:58');

-- --------------------------------------------------------

--
-- Table structure for table `package_orders`
--

CREATE TABLE `package_orders` (
  `id` int(11) NOT NULL,
  `client_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `package` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `staff` varchar(100) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `service_id` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package_orders`
--

INSERT INTO `package_orders` (`id`, `client_name`, `phone`, `package`, `price`, `order_date`, `email`, `address`, `staff`, `status`, `service_id`, `amount`) VALUES
(1, 'Farrukh', '78546123982', 'Moin Bhai', 78945.00, '2025-07-27 15:06:01', NULL, NULL, NULL, 'Approved', NULL, NULL),
(2, 'Muhammad Fahad', '03708541533', 'Farrukh', 21545646.00, '2025-07-27 15:16:00', 'Fahad@Hafiz.com', 'Orangi town data nagar karachi pakistan', 'Fahad', 'Approved', NULL, NULL),
(3, 'Muhammad Fahad', '03708541533', 'Moin Bhai', 78945.00, '2025-07-27 17:05:59', 'Fahad@Hafiz.com', 'Europe thailand italy', 'Taimoor Bhai', 'Pending', NULL, NULL),
(4, 'Muhammad Farrukh', '03708541533', 'Salone', 456.00, '2025-07-27 17:06:52', 'farrokh984@gmail.com', 'Orangi town data nagar karachi pakistan', 'Zeeshan Bhai', 'Pending', NULL, NULL),
(5, 'Muhammad Farrukh', '03708541533', 'Moin Bhai', 78945.00, '2025-07-27 17:19:08', 'farrokh984@gmail.com', 'Orangi town data nagar karachi pakistan', 'Fahad', 'Pending', NULL, NULL),
(6, 'Muhammad Farrukh', '03708541533', 'Farrukh', 21545646.00, '2025-07-27 20:37:32', 'farrokh984@gmail.com', 'Europe thailand italy', 'Fahad', 'Pending', NULL, NULL),
(7, 'Muhammad Farrukh', '03708541533', ' Summer Fresh Men’s Deal', 167.00, '2025-07-28 22:46:54', 'farrokh984@gmail.com', 'Orangi town data nagar karachi pakistan', 'Faiz', 'Pending', NULL, NULL),
(8, 'Muhammad Farrukh', '03708541533', ' Summer Fresh Men’s Deal', 167.00, '2025-07-28 22:52:19', 'farrokh984@gmail.com', 'Orangi town data nagar karachi pakistan', 'Zeeshan Bhai', 'Pending', NULL, NULL),
(9, 'Muhammad Farrukh', '03708541533', 'Manicure', 1200.00, '2025-07-28 23:27:47', 'farrokh984@gmail.com', 'Orangi town data nagar karachi pakistan', 'Faizan Bhai', 'Pending', NULL, NULL),
(10, 'Muhammad Farrukh', '03708541533', 'Haircut', 1500.00, '2025-07-28 23:32:14', 'farrokh984@gmail.com', 'Orangi town data nagar karachi pakistan', 'hjhkjhkjh', 'Pending', NULL, NULL),
(11, 'Muhammad Farrukh', '03708541533', 'Men’s Signature Grooming', 0.00, '2025-07-29 01:35:42', 'farrokh984@gmail.com', 'Europe thailand italy', 'Faizan Bhai', 'Pending', 4, NULL),
(12, 'Muhammad Farrukh', '03708541533', ' Summer Fresh Men’s Deal', 0.00, '2025-07-29 01:49:18', 'farrokh984@gmail.com', 'Orangi town data nagar karachi pakistan', 'Faizan Bhai', 'Pending', 5, 85),
(13, 'Muhammad Farrukh', '03708541533', 'Men’s Signature Grooming', 0.00, '2025-07-29 01:57:22', 'farrokh984@gmail.com', 'Orangi town data nagar karachi pakistan', 'Fahad', 'Pending', 4, 145),
(14, 'Muhammad Fahad', '03708541533', ' Summer Fresh Men’s Deal', 167.00, '2025-07-29 02:26:34', 'Fahad@Hafiz.com', 'Orangi town data nagar karachi pakistan', 'Faizan Bhai', 'Pending', NULL, NULL),
(15, 'Muhammad Fahad', '03708541533', 'Trendy Haircut Styling', 135.00, '2025-08-03 13:29:26', 'Fahad@Hafiz.com', 'Orangi town data nagar karachi pakistan', 'Angela Kwang', 'Pending', NULL, NULL),
(16, 'Muhammad Fahad', '03708541533', 'Royal Beard Facial Care', 60.00, '2025-08-03 13:52:34', 'Fahad@1.com', 'Orangi town data nagar karachi pakistan', 'Alexander Rowland', 'Approved', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pakages_service`
--

CREATE TABLE `pakages_service` (
  `id` int(11) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `service_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pakages_service`
--

INSERT INTO `pakages_service` (`id`, `service_name`, `amount`, `created_at`, `service_id`) VALUES
(3, 'Haircuts', 125.00, '2025-07-29 01:10:59', NULL),
(4, 'Skin Fade', 145.00, '2025-07-29 01:13:33', NULL),
(5, 'Buzz Cut', 85.00, '2025-07-29 01:16:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `qty`, `image`) VALUES
(29, 'Modern Sofa', 'A stylish 3-seater modern sofa with premium fabric and wooden legs.', 45000.00, 10, 'uploads/1757191659_01_Images.jpg'),
(30, 'Dining Table Set', '6-seater solid oak dining table with matching chairs.', 65000.00, 5, 'uploads/1757191644_Kwame-Upholstered-Dining-Chairs-Set-of-6-by-Christopher-Knight-Home.jpg'),
(31, 'Office Chair', 'Ergonomic office chair with lumbar support and adjustable height.', 15000.00, 20, 'uploads/1757191591_0-7-scaled.jpg'),
(32, 'Wardrobe', 'Spacious 3-door wardrobe with mirror and storage drawers.', 55000.00, 7, 'uploads/1757191531_how-to-shop-your-wardrobe-oct-19-th.jpg'),
(33, 'Coffee Table', 'Modern glass-top coffee table with metal base.', 12000.00, 15, 'uploads/1757191503_59427afc0956413865d8b2a763400b9d.jpg'),
(35, 'Bookshelf', '5-tier wooden bookshelf perfect for home and office use.', 18000.00, 12, 'uploads/1757191478_istockphoto-1339845062-612x612.jpg'),
(38, 'Shoe Rack', 'Wooden shoe rack with 4 shelves and a compact design.', 8000.00, 20, 'uploads/1757191425_ac81ff058f86b637992a43a44fd7199d.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `target_type` enum('employee','designer') NOT NULL,
  `target_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `target_type`, `target_id`, `rating`, `comment`, `created_at`) VALUES
(1, 38, 'designer', 1, 2, 'hate it', '2025-09-07 09:42:25'),
(2, 38, 'employee', 13, 1, 'hatr', '2025-09-07 10:02:21'),
(3, 37, 'designer', 1, 1, 'hate it', '2025-09-07 11:02:22'),
(4, 37, 'designer', 1, 1, 'hi', '2025-09-07 11:33:00'),
(5, 37, 'designer', 37, 5, 'satisfied', '2025-09-07 11:44:29');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `amount`) VALUES
(3, 'Haircuts', 125),
(4, 'Skin Fade', 155),
(6, 'Buzz Cut', 75),
(7, 'Chap Haircut', 75),
(8, 'colouring', 325),
(9, 'Head Massage', 60),
(10, 'Fire $ Ice', 325),
(11, 'Stache Trim', 30),
(12, 'line it Up', 40),
(13, 'The Swedish', 275),
(14, 'Express Color', 95),
(15, 'Hair Wash', 55),
(16, 'Scalp Detox', 85),
(17, 'Kids Tirm', 60);

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rating` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `message`, `created_at`, `rating`) VALUES
(1, 'Farrukh Qureshi', 'the loves for me and loves for you', '2025-08-02 16:06:06', 0),
(2, 'Muhammad Fahad', 'bhuytredfghbcnzxbcvnzxcbvcgvcghdagdjhgdajshgds', '2025-08-02 16:06:35', 0),
(3, 'Muhammad Farrukh', 'thats for naw', '2025-08-02 16:18:24', 0),
(4, 'Shabbir Ahmed', 'wow very nice i proud of you', '2025-08-02 16:18:52', 0),
(5, 'Zeeshan Bhai', 'I proud of my brother', '2025-08-02 16:19:23', 0),
(7, 'Muhammad Farrukh', 'Insha Allah ', '2025-08-02 16:28:24', 5),
(8, 'Muhammad Farrukh', 'Thanks for you', '2025-08-02 16:36:14', 3),
(9, 'Farrukh Qureshi', 'dtrggbvbnvhgjjkjkhkuhjjjnmn,mn,m,.,mkjlkjlijijoiklkmm.,m..,m.,  lk;lkk', '2025-08-02 18:34:01', 3),
(10, 'Muhammad Fahad', 'jbvnmbvhghgcfgcvbvcvcjgfjcgfc', '2025-08-02 18:37:08', 2),
(11, 'Muhammad Fahad', 'Thank for yours', '2025-08-02 18:47:43', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','designer','admin') NOT NULL DEFAULT 'user',
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `is_approved`, `created_at`) VALUES
(22, 'farrukh', 'farrokh984@gmail.com', '$2y$10$LeIhESpKhgysVAntEbws9egIqCNh244Dd.w1yLgibjYvR6D6CCvBe', 'user', 1, '2025-07-06 16:32:54'),
(24, 'Muhammad Farrukh', 'farrokh984@aptech.com', '$2y$10$CtwNy3SBSqE3YZql4p/PceeS1syqZE2lrW8dpYVu9wLx5C6TKWWLu', 'user', 1, '2025-07-06 21:23:11'),
(26, 'fahad', 'fahad@Hafiz.com', '$2y$10$2cYOkViFkQcjwnJxJRWNyuTApDc//qKSf9Ytw3oYoXR675YIOtMhm', 'user', 1, '2025-07-06 22:46:28'),
(27, 'Muhammad Farrukh', 'farrokh489@aptech.com', '$2y$10$CeSkdKXpA8A.Z.FywmY2eOYyMCGTS35OUI5sC4Dq.7sLQ9/7mfvN6', 'user', 1, '2025-07-07 20:37:23'),
(28, 'fahad', 'fahad@aptech.com', '$2y$10$Shfo0b2yb1BCJEuEw1tHvOaoicRfke5ynsLM.SinaLGkAZUbTUksm', 'user', 1, '2025-07-07 20:39:45'),
(29, 'Farrokh', 'farrokh@latop.com', '$2y$10$tPphqGJtjklcQHwbD2ikTuxlxONjNce3CtCtTzhEKAPDi1Wggg2Pe', 'user', 1, '2025-07-08 22:06:58'),
(30, 'arman', 'arman@gmail.com', '$2y$10$1WojGzi07EuxXFkBG1H.bub.LKcDLF5py6/FA0wv/I5Vpx7r47D0G', 'user', 1, '2025-07-23 15:03:23'),
(31, 'saim', 'saim@gmail.com', '$2y$10$Ru6uec1zr3Lt6XTGvQ8C2ef/Z2yL3MQtQpcOlyzZ0UJ/2tIUtCSry', 'user', 1, '2025-07-25 13:35:27'),
(32, 'faiz', 'faiz@fun.com', '$2y$10$SppYVNbS.gh.vz8luebaVOoQDPSKJ5tf./BKWz/5ARLiqAsK/6Hji', 'user', 1, '2025-07-27 21:01:42'),
(33, 'fahad', 'Fahad@head.com', '$2y$10$II1BPEOmYmSYLZ3s18hOCuKaKCqYZB90f9CE/AaW5Ul2ALMz4qxcS', 'user', 1, '2025-07-27 21:04:27'),
(34, 'Farrokh', 'farrokh@laptop.com', '$2y$10$KOsWxc8W5eXKlXmjD/MkAeYt145fAEJSJ.PNj1mOsLQXHe6eRuBhu', 'user', 1, '2025-07-27 21:05:47'),
(35, 'Ahmed raza', 'ahmed@raza.com', '$2y$10$/ejKQ8jcOnaD8KBq5zgJFePG7BWX/IWxjz3GTSV8Opom0uNC5VH/a', 'user', 1, '2025-08-03 13:58:13'),
(36, 'asif', 'asifkhan12@gmail.com', '$2y$10$eRx8Cg2rORUuQ2dTlWKc3eLaUEtsyl5UEjC3bXNTHuqPHJnEjW4Wa', 'user', 1, '2025-09-05 12:23:37'),
(37, 'designer', 'designer@gmail.com', '$2y$10$mPMH8BNNjdwpacGj/LMzie9kQLLNBEu9H4Z1O1iqPcGNxt/AayZKS', 'designer', 1, '2025-09-07 06:23:36'),
(38, 'user', 'user@gmail.com', '$2y$10$.AqeZtnw6ydQsW/tlHK4Ce3EFa3fpTWVwkWKdC9ugDiQAvllfNaJC', 'user', 1, '2025-09-07 09:28:18');

-- --------------------------------------------------------

--
-- Table structure for table `users1`
--

CREATE TABLE `users1` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users1`
--

INSERT INTO `users1` (`id`, `name`, `email`, `message`) VALUES
(1, 'Muhammad Farrukh', 'farrokh984@aptech.com', 'Thank You'),
(2, 'Muhammad Farrukh', 'farrokh4@aptech.com', 'Thank You for sales man'),
(3, 'Muhammad Farrukh', 'farrukh@fa.com', 'I leave alone'),
(4, 'farrokh', 'Fahad@Hafiz.com', 'I leave alone from people'),
(5, 'Muhammad Farrukh', 'farrokh@aptech.com', 'best for you'),
(7, 'Muhammad Farrukh', 'farrokh@apt.com', 'fthgfhghgvnbvcv'),
(8, 'Muhammad Farrukh', 'farrokh@a.com', 'gyuyyftf'),
(9, 'Muhammad Farrukh', 'admin@admin.com', 'jioouyguybnbvnbv'),
(10, 'M.Fahad', 'farrokh@a123.com', 'form up on me'),
(13, 'M.Farrukh', 'farrokh@a789.com', 'i want to you'),
(14, 'Muhammad Farrukh', 'farrokh@a7.com', 'Very nice service'),
(15, 'Muhammad Farrukh', 'farrokh@b7.com', 'I dont arest him'),
(17, 'Muhammad Farrukh', 'farrokh@apch.com', 'Muhammad'),
(18, 'M.Farrukh', 'admin@in.com', 'Elegance salone'),
(20, 'M.Farrukh', 'admin@in1.com', 'Iwant to talk you'),
(21, 'M.Farrukh', 'admin@in2.com', 'maire ammi'),
(22, 'M.Farrukh', 'admin@in6.com', 'i work it'),
(23, 'Muhammad Farrukh', 'farrukh@fap.com', 'I leave alone'),
(24, 'Muhammad Farrukh', 'farrukh@fa89.com', 'I leave alone'),
(25, 'Muhammad Farrukh', 'farrukh@fa894.com', 'hjkoiu'),
(26, 'Muhammad Farrukh', 'admin@admin78.com', 'I leave alone'),
(27, 'Muhammad Farrukh', 'farrukh@f.com', 'I leave alone'),
(28, 'Muhammad Farrukh', 'farrokh@aptech123.com', 'Very nice servicelkj'),
(30, 'Muhammad Farrukh', 'farrokh@apte123.com', 'ghjuyt'),
(33, 'Muhammad Fahad', 'Fahad@Hafi', 'Fahad'),
(34, 'Muhammad Fahad', 'Fahad@kl.com', 'Farrokh'),
(35, 'Muhammad Farrukh', 'farro4@.com', 'Ahmed bjhkhvgyvvvvvvhjvg'),
(37, 'Muhammad Farrukh', 'farro41231@.com', 'Qureshi hjhkh');

-- --------------------------------------------------------

--
-- Table structure for table `users2`
--

CREATE TABLE `users2` (
  `id` int(80) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(250) NOT NULL,
  `subject` varchar(460) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments2`
--
ALTER TABLE `appointments2`
  ADD PRIMARY KEY (`id`),
  ADD KEY `designer_id` (`designer_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `designer_profiles`
--
ALTER TABLE `designer_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `designer_reviews`
--
ALTER TABLE `designer_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `designer_id` (`designer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `package_orders`
--
ALTER TABLE `package_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_service` (`service_id`);

--
-- Indexes for table `pakages_service`
--
ALTER TABLE `pakages_service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users1`
--
ALTER TABLE `users1`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users2`
--
ALTER TABLE `users2`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `appointments2`
--
ALTER TABLE `appointments2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `designer_profiles`
--
ALTER TABLE `designer_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `designer_reviews`
--
ALTER TABLE `designer_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `package_orders`
--
ALTER TABLE `package_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pakages_service`
--
ALTER TABLE `pakages_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `users1`
--
ALTER TABLE `users1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users2`
--
ALTER TABLE `users2`
  MODIFY `id` int(80) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments2`
--
ALTER TABLE `appointments2`
  ADD CONSTRAINT `appointments2_ibfk_1` FOREIGN KEY (`designer_id`) REFERENCES `designer_profiles` (`id`),
  ADD CONSTRAINT `appointments2_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `designer_profiles`
--
ALTER TABLE `designer_profiles`
  ADD CONSTRAINT `designer_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `designer_reviews`
--
ALTER TABLE `designer_reviews`
  ADD CONSTRAINT `designer_reviews_ibfk_1` FOREIGN KEY (`designer_id`) REFERENCES `designer_profiles` (`id`),
  ADD CONSTRAINT `designer_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `package_orders`
--
ALTER TABLE `package_orders`
  ADD CONSTRAINT `fk_service` FOREIGN KEY (`service_id`) REFERENCES `pakages_service` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
