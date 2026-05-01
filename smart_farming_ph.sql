-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2025 at 02:47 AM
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
-- Database: `smart_farming_ph`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','content_manager') DEFAULT 'content_manager',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `first_name`, `last_name`, `email`, `phone_number`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Super', 'Admin', 'superadmin@smartfarming.ph', '09171234567', 'admin123', 'super_admin', '2025-10-30 22:43:08', '2025-10-30 22:43:08'),
(2, 'Content', 'Manager', 'content@smartfarming.ph', '09179876543', 'admin123', 'content_manager', '2025-10-30 22:43:08', '2025-10-30 22:43:08'),
(3, 'Regional', 'Coordinator', 'regional@smartfarming.ph', '09171122334', 'admin123', 'content_manager', '2025-10-30 22:43:08', '2025-10-30 22:43:08');

-- --------------------------------------------------------

--
-- Table structure for table `crops`
--

CREATE TABLE `crops` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `season_id` int(11) DEFAULT NULL,
  `planting_guide` text DEFAULT NULL,
  `water_requirements` enum('low','medium','high') DEFAULT NULL,
  `sunlight_requirements` enum('full_sun','partial_sun','shade') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crops`
--

INSERT INTO `crops` (`id`, `name`, `description`, `season_id`, `planting_guide`, `water_requirements`, `sunlight_requirements`, `created_at`) VALUES
(1, 'Tomatoes', 'High yield, drought-resistant varieties', 1, 'Plant in well-drained soil with plenty of sunlight. Water regularly but avoid overwatering.', NULL, NULL, '2025-10-30 22:43:08'),
(2, 'Bell Peppers', 'Requires well-drained soil', 1, 'Needs full sun and warm temperatures. Harvest when fruits are firm and fully colored.', NULL, NULL, '2025-10-30 22:43:08'),
(3, 'Eggplant', 'Tolerant to heat and dry conditions', 1, 'Plant in rich, well-drained soil. Requires consistent moisture.', NULL, NULL, '2025-10-30 22:43:08'),
(4, 'Rice', 'Staple crop, requires abundant water', 2, 'Needs flooded fields or consistent moisture. Best planted at start of rainy season.', NULL, NULL, '2025-10-30 22:43:08'),
(5, 'Corn', 'Adapts well to rainy conditions', 2, 'Plant in rows with good spacing. Requires nitrogen-rich soil.', NULL, NULL, '2025-10-30 22:43:08'),
(6, 'Watermelon', 'Thrives in warm, wet conditions', 2, 'Needs plenty of space to spread. Harvest when tendrils near fruit turn brown.', NULL, NULL, '2025-10-30 22:43:08'),
(7, 'Sweet Potatoes1', 'Root crop, versatile and nutritious', 3, 'Plant in loose, well-drained soil. Drought tolerant once established.', '', '', '2025-10-30 22:43:08'),
(8, 'Kangkong2', 'Leafy vegetable, grows quickly', 3, 'Can grow in water or moist soil. Harvest young leaves for best flavor.', '', '', '2025-10-30 22:43:08'),
(10, 'Rice', 'Staple crop, requires abundant water', 2, 'Needs flooded fields or consistent moisture. Best planted at start of rainy season.', 'high', 'full_sun', '2025-11-25 00:17:12'),
(11, 'Corn', 'Adapts well to rainy conditions', 2, 'Plant in rows with good spacing. Requires nitrogen-rich soil.', 'medium', 'full_sun', '2025-11-25 00:17:12'),
(12, 'Vegetables', 'Various vegetable crops', 1, 'Plant in well-drained soil with proper spacing.', 'medium', 'full_sun', '2025-11-25 00:17:12'),
(13, 'Fruits', 'Various fruit crops', 1, 'Requires proper pruning and care for optimal yield.', 'medium', 'full_sun', '2025-11-25 00:17:12'),
(14, 'Root Crops', 'Crops grown for their roots', 3, 'Plant in loose, well-drained soil.', 'low', 'partial_sun', '2025-11-25 00:17:12'),
(15, 'Others', 'Other types of crops', NULL, 'Varies by specific crop type.', 'medium', 'full_sun', '2025-11-25 00:17:12');

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `farm_name` varchar(100) DEFAULT NULL,
  `farm_location` varchar(100) DEFAULT NULL,
  `farm_size` decimal(10,2) DEFAULT NULL,
  `experience_years` int(11) DEFAULT 0,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`id`, `first_name`, `last_name`, `email`, `phone_number`, `gender`, `date_of_birth`, `password`, `farm_name`, `farm_location`, `farm_size`, `experience_years`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Juan', 'Dela Cruz', 'juan@farmer.ph', '09170000001', 'Male', NULL, 'farmer123', 'Dela Cruz Family 1 Farm', '0', 2.00, 1, NULL, '2025-10-30 22:43:08', '2025-11-25 01:16:11'),
(2, 'Maria', 'Santos', 'maria@farmer.ph', '09170000002', 'Female', NULL, 'farmer123', 'Santos Organic Farm', 'CALABARZON', 3.20, 7, NULL, '2025-10-30 22:43:08', '2025-11-25 00:17:12'),
(3, 'Pedro', 'Gonzales', 'pedro@farmer.ph', '09170000003', 'Male', NULL, 'farmer123', 'Gonzales Rice Fields', 'Bicol Region', 12.00, 15, NULL, '2025-10-30 22:43:08', '2025-11-25 00:17:12');

-- --------------------------------------------------------

--
-- Table structure for table `farmer_crops`
--

CREATE TABLE `farmer_crops` (
  `id` int(11) NOT NULL,
  `farmer_id` int(11) DEFAULT NULL,
  `crop_type` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmer_crops`
--

INSERT INTO `farmer_crops` (`id`, `farmer_id`, `crop_type`, `created_at`) VALUES
(3, 2, 'fruits', '2025-10-30 22:43:08'),
(4, 2, 'vegetables', '2025-10-30 22:43:08'),
(5, 3, 'rice', '2025-10-30 22:43:08'),
(6, 3, 'corn', '2025-10-30 22:43:08'),
(14, 1, 'fruits', '2025-11-25 01:16:11');

-- --------------------------------------------------------

--
-- Table structure for table `learning_materials`
--

CREATE TABLE `learning_materials` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_type` enum('pdf','video','article') NOT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  `file_size` varchar(50) DEFAULT NULL,
  `duration` varchar(20) DEFAULT NULL,
  `download_count` int(11) DEFAULT 0,
  `uploaded_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learning_materials`
--

INSERT INTO `learning_materials` (`id`, `title`, `description`, `file_type`, `file_path`, `file_size`, `duration`, `download_count`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(6, 'qweryu', 'qwertyu', 'pdf', 'uploads/materials/690424409f48b_testing1.pdf', '5845354', '0', 8, 1, '2025-10-31 02:51:44', '2025-10-31 03:25:16');

-- --------------------------------------------------------

--
-- Table structure for table `material_categories`
--

CREATE TABLE `material_categories` (
  `id` int(11) NOT NULL,
  `material_id` int(11) DEFAULT NULL,
  `category_name` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `material_categories`
--

INSERT INTO `material_categories` (`id`, `material_id`, `category_name`, `created_at`) VALUES
(19, 6, 'crop_management', '2025-10-31 02:51:44'),
(20, 6, 'pest_control', '2025-10-31 02:51:44');

-- --------------------------------------------------------

--
-- Table structure for table `seasons`
--

CREATE TABLE `seasons` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `start_month` int(11) DEFAULT NULL,
  `end_month` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seasons`
--

INSERT INTO `seasons` (`id`, `name`, `description`, `start_month`, `end_month`, `created_at`) VALUES
(1, 'Dry Season', 'November to April - Ideal for crops that require less water', 11, 4, '2025-10-30 22:43:08'),
(2, 'Wet Season', 'May to October - Best for rice and other water-intensive crops', 5, 10, '2025-10-30 22:43:08'),
(3, 'Hybrid Season 1', 'Transition periods between dry and wet seasons', 3, 5, '2025-10-30 22:43:08');

-- --------------------------------------------------------

--
-- Table structure for table `weather_data`
--

CREATE TABLE `weather_data` (
  `id` int(11) NOT NULL,
  `location` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `temperature_max` decimal(5,2) DEFAULT NULL,
  `temperature_min` decimal(5,2) DEFAULT NULL,
  `condition` varchar(50) DEFAULT NULL,
  `precipitation` decimal(5,2) DEFAULT NULL,
  `humidity` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_number` (`phone_number`);

--
-- Indexes for table `crops`
--
ALTER TABLE `crops`
  ADD PRIMARY KEY (`id`),
  ADD KEY `season_id` (`season_id`);

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_number` (`phone_number`),
  ADD KEY `idx_gender` (`gender`),
  ADD KEY `idx_farm_location` (`farm_location`);

--
-- Indexes for table `farmer_crops`
--
ALTER TABLE `farmer_crops`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmer_id` (`farmer_id`);

--
-- Indexes for table `learning_materials`
--
ALTER TABLE `learning_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `material_categories`
--
ALTER TABLE `material_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `seasons`
--
ALTER TABLE `seasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weather_data`
--
ALTER TABLE `weather_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `crops`
--
ALTER TABLE `crops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `farmer_crops`
--
ALTER TABLE `farmer_crops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `learning_materials`
--
ALTER TABLE `learning_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `material_categories`
--
ALTER TABLE `material_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `seasons`
--
ALTER TABLE `seasons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `weather_data`
--
ALTER TABLE `weather_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `crops`
--
ALTER TABLE `crops`
  ADD CONSTRAINT `crops_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `farmer_crops`
--
ALTER TABLE `farmer_crops`
  ADD CONSTRAINT `farmer_crops_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `learning_materials`
--
ALTER TABLE `learning_materials`
  ADD CONSTRAINT `learning_materials_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `material_categories`
--
ALTER TABLE `material_categories`
  ADD CONSTRAINT `material_categories_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `learning_materials` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
