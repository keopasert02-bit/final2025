-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 25, 2025 at 10:28 AM
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
-- Database: `monk_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `monks`
--

CREATE TABLE `monks` (
  `monk_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `village` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `ordination_date` date DEFAULT NULL,
  `temple_id` int(11) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `move_in_date` date DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monks`
--

INSERT INTO `monks` (`monk_id`, `first_name`, `last_name`, `birth_date`, `village`, `district`, `province`, `ordination_date`, `temple_id`, `phone`, `type`, `move_in_date`, `image_path`, `created_at`, `updated_at`) VALUES
(12, 'ບຸນຊູ', 'ແກ້ວປະເສີດ', '2002-05-10', 'ລົບປາດີກາງ', 'ໂຂງ', 'ນະຄອນຫຼວງ', '2022-05-26', 1, '020 967 12 678', 'ພຣະ', '2022-05-17', 'monk_682ac39f37d236.87430754.jpg', '2025-05-19 03:51:00', '2025-08-06 01:48:42'),
(28, 'ພອນແກ້ວ', 'ຈະເລີນສິນ', '2007-02-06', 'ສະພັງໝໍ້', 'ໄຊເຊດຖາ', 'ນະຄອນຫຼວງວຽງຈັນ', '2022-08-14', 1, '020 967 96 325', 'ສາມະເນນ', '2022-08-04', 'monk_6892b2db8c07d2.47481143.jpg', '2025-08-06 01:41:47', '2025-08-12 01:40:50');

-- --------------------------------------------------------

--
-- Table structure for table `monk_transfers`
--

CREATE TABLE `monk_transfers` (
  `transfer_id` int(11) NOT NULL,
  `monk_id` int(11) NOT NULL,
  `temple_id` int(11) NOT NULL,
  `transfer_type` enum('ຍ້າຍເຂົ້າ','ຍ້າຍອອກ') NOT NULL,
  `transfer_date` date NOT NULL,
  `goto_temple` varchar(255) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monk_transfers`
--

INSERT INTO `monk_transfers` (`transfer_id`, `monk_id`, `temple_id`, `transfer_type`, `transfer_date`, `goto_temple`, `reason`, `created_at`, `updated_at`) VALUES
(4, 12, 1, 'ຍ້າຍອອກ', '2025-05-02', 'ວັດລົບປາດີກາງ', 'ສຶກສາຕໍ່', '2025-05-25 06:32:56', '2025-08-16 10:10:44'),
(11, 28, 1, 'ຍ້າຍເຂົ້າ', '2025-08-04', '', '', '2025-08-06 01:42:34', '2025-08-06 01:42:34');

-- --------------------------------------------------------

--
-- Table structure for table `monk_work`
--

CREATE TABLE `monk_work` (
  `work_id` int(11) NOT NULL,
  `work_name` varchar(255) NOT NULL,
  `work_description` text DEFAULT NULL,
  `work_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monk_work`
--

INSERT INTO `monk_work` (`work_id`, `work_name`, `work_description`, `work_order`, `created_at`, `updated_at`) VALUES
(1, 'ເຮັດວຽກປະຈຳ', 'ປັດສາລາ ລ້າງຖ້ວຍ', 1, '2025-05-20 16:25:02', '2025-05-21 02:01:07'),
(6, 'ປັດເດີນວັດ', 'ທຸກມື້ຕອນແລງ', 2, '2025-08-12 01:11:53', '2025-08-12 01:11:53');

-- --------------------------------------------------------

--
-- Table structure for table `monk_work_members`
--

CREATE TABLE `monk_work_members` (
  `id` int(11) NOT NULL,
  `work_id` int(11) NOT NULL,
  `monk_id` int(11) NOT NULL,
  `sequence` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monk_work_members`
--

INSERT INTO `monk_work_members` (`id`, `work_id`, `monk_id`, `sequence`, `created_at`, `updated_at`) VALUES
(8, 1, 12, 1, '2025-05-25 06:32:23', '2025-06-18 01:59:28');

-- --------------------------------------------------------

--
-- Table structure for table `temples`
--

CREATE TABLE `temples` (
  `temple_id` int(11) NOT NULL,
  `temple_name` varchar(150) NOT NULL,
  `village` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `temples`
--

INSERT INTO `temples` (`temple_id`, `temple_name`, `village`, `district`, `province`, `created_at`, `updated_at`) VALUES
(1, 'ວັດສະພັງໝໍ້', 'ບ້ານສະພັງໝໍ້ ', 'ເມືອງໄຊເຊດຖາ', ' ນະຄອນຫຼວງວຽງຈັນ', '2025-05-17 20:03:54', '2025-08-12 02:39:52');

-- --------------------------------------------------------

--
-- Table structure for table `temple_events`
--

CREATE TABLE `temple_events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `temple_events`
--

INSERT INTO `temple_events` (`id`, `title`, `event_date`, `description`, `created_at`, `updated_at`) VALUES
(8, 'ບຸນເຂົ້າພັນສາ', '2025-10-07', 'ບຸນເຂົ້າພັນສາ ເປັນໜຶ່ງງານບຸນປະເພນີ ທີ່ມີຄວາມໝາຍ-ຄວາມສຳຄັນທາງສາສະໜາຂອງຊາວພຸດທັງເປັນບຸນທີ່ຢູ່ໃນຮີດ 12 ຄອງ 14 ປະເພນີລາວ ທີ່ຊາວພຸດເຄີຍສືບທອດປະຕິບັດກັນມາຮອດປັດຈຸບັນ. ປີນີ້ແມ່ນກົງກັບມື້ຂຶ້ນ 15 ຄໍ່າ ເດືອນ 8 ພ.ສ 2568 ລາວ ຫຼື ກົງກັບວັນພະຫັດ ວັນທີ 10 ກໍລະກົດ 2025.', '2025-07-05 11:09:46', '2025-07-05 11:09:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(18, 'bounxou', 'bounxou@gmail.com', '$2y$10$Jd9WOlaVqfmDu8Fj9WDwqujywBCUpfT8RrreFbmtGb/2gLPzVllqa', 'user', '2025-08-15 12:03:48', '2025-08-15 12:35:56'),
(21, 'adminxou', 'soukeopasert@gmail.com', '$2y$10$AgVY84so4qgZiqx.mOpWWeVfRzVvK7V1RfOertI0qSoQFuWql3fY2', 'admin', '2025-08-15 12:37:18', '2025-08-15 13:13:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `monks`
--
ALTER TABLE `monks`
  ADD PRIMARY KEY (`monk_id`),
  ADD KEY `temple_id` (`temple_id`);

--
-- Indexes for table `monk_transfers`
--
ALTER TABLE `monk_transfers`
  ADD PRIMARY KEY (`transfer_id`),
  ADD KEY `monk_id` (`monk_id`),
  ADD KEY `temple_id` (`temple_id`);

--
-- Indexes for table `monk_work`
--
ALTER TABLE `monk_work`
  ADD PRIMARY KEY (`work_id`);

--
-- Indexes for table `monk_work_members`
--
ALTER TABLE `monk_work_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `work_id` (`work_id`),
  ADD KEY `monk_id` (`monk_id`);

--
-- Indexes for table `temples`
--
ALTER TABLE `temples`
  ADD PRIMARY KEY (`temple_id`);

--
-- Indexes for table `temple_events`
--
ALTER TABLE `temple_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `monks`
--
ALTER TABLE `monks`
  MODIFY `monk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `monk_transfers`
--
ALTER TABLE `monk_transfers`
  MODIFY `transfer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `monk_work`
--
ALTER TABLE `monk_work`
  MODIFY `work_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `monk_work_members`
--
ALTER TABLE `monk_work_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `temples`
--
ALTER TABLE `temples`
  MODIFY `temple_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `temple_events`
--
ALTER TABLE `temple_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `monks`
--
ALTER TABLE `monks`
  ADD CONSTRAINT `monks_ibfk_1` FOREIGN KEY (`temple_id`) REFERENCES `temples` (`temple_id`);

--
-- Constraints for table `monk_transfers`
--
ALTER TABLE `monk_transfers`
  ADD CONSTRAINT `monk_transfers_ibfk_1` FOREIGN KEY (`monk_id`) REFERENCES `monks` (`monk_id`),
  ADD CONSTRAINT `monk_transfers_ibfk_2` FOREIGN KEY (`temple_id`) REFERENCES `temples` (`temple_id`);

--
-- Constraints for table `monk_work_members`
--
ALTER TABLE `monk_work_members`
  ADD CONSTRAINT `monk_work_members_ibfk_1` FOREIGN KEY (`work_id`) REFERENCES `monk_work` (`work_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `monk_work_members_ibfk_2` FOREIGN KEY (`monk_id`) REFERENCES `monks` (`monk_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
