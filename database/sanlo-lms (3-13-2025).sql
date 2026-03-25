-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 13, 2025 at 03:49 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sanlo-lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE IF NOT EXISTS `attendance` (
  `attendance_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `student_id` int DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`attendance_id`),
  KEY `user_id` (`user_id`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `user_id`, `student_id`, `time_in`, `time_out`, `date`) VALUES
(1, 5, 1, '02:50:00', '02:51:00', '2025-03-12');

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

DROP TABLE IF EXISTS `book`;
CREATE TABLE IF NOT EXISTS `book` (
  `book_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `category_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(50) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `publication_year` year DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `copies` int DEFAULT '1',
  `added_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`book_id`),
  UNIQUE KEY `isbn` (`isbn`),
  KEY `user_id` (`user_id`),
  KEY `fk_category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`book_id`, `user_id`, `category_id`, `title`, `author`, `isbn`, `publisher`, `publication_year`, `location`, `amount`, `copies`, `added_at`) VALUES
(1, 5, 9, 'Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', '978-0062316097', 'Harper', 2011, 'Shelf D4', '1200.00', 2, '2025-03-12 12:57:18'),
(2, 5, 1, 'To Kill a Mockingbird', 'Harper Lee', '978-0061120084', 'HarperCollins', 1960, 'Shelf A1', '600.00', 9, '2025-03-12 14:53:31');

-- --------------------------------------------------------

--
-- Table structure for table `book_borrow`
--

DROP TABLE IF EXISTS `book_borrow`;
CREATE TABLE IF NOT EXISTS `book_borrow` (
  `book_borrow_id` int NOT NULL AUTO_INCREMENT,
  `book_id` int DEFAULT NULL,
  `student_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `status` enum('borrowed','returned') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`book_borrow_id`),
  KEY `fk_book_borrow_book` (`book_id`),
  KEY `fk_book_borrow_student` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_categories`
--

DROP TABLE IF EXISTS `book_categories`;
CREATE TABLE IF NOT EXISTS `book_categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `book_categories`
--

INSERT INTO `book_categories` (`category_id`, `category_name`, `created_at`) VALUES
(1, 'Fiction', '2024-11-05 02:49:02'),
(3, 'Textbook', '2024-11-05 02:49:10'),
(4, 'Reference', '2024-11-05 02:49:14'),
(5, 'Computer Science', '2024-11-05 05:05:15'),
(6, 'Psychology', '2024-11-05 05:05:28'),
(7, 'Physics', '2024-11-05 05:05:38'),
(8, 'Economics', '2024-11-05 05:05:49'),
(9, 'Education', '2024-11-05 05:06:03'),
(10, 'Mathematics', '2024-11-05 05:06:12'),
(11, 'Sociology', '2024-11-05 05:06:23'),
(12, 'Non-Fiction', '2024-11-05 05:21:34');

-- --------------------------------------------------------

--
-- Table structure for table `book_return`
--

DROP TABLE IF EXISTS `book_return`;
CREATE TABLE IF NOT EXISTS `book_return` (
  `return_book_id` int NOT NULL AUTO_INCREMENT,
  `book_borrow_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `status` enum('returned','damaged','lost') NOT NULL,
  `returned_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`return_book_id`),
  KEY `book_borrow_id` (`book_borrow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipment_borrow`
--

DROP TABLE IF EXISTS `equipment_borrow`;
CREATE TABLE IF NOT EXISTS `equipment_borrow` (
  `equipment_id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `equipment` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`equipment_id`),
  KEY `fk_student` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `equipment_borrow`
--

INSERT INTO `equipment_borrow` (`equipment_id`, `student_id`, `equipment`, `status`, `created_at`) VALUES
(11, 5, 'test', 'returned', '2024-11-05 06:40:07'),
(12, 2, 'Microphone', 'returned', '2024-11-05 07:00:07'),
(13, 4, 'Speakers', 'borrowed', '2024-11-05 07:07:27'),
(14, 2, 'microphone', 'returned', '2025-03-06 18:07:07'),
(15, 1, 'Microphone', 'returned', '2025-03-12 06:56:35');

-- --------------------------------------------------------

--
-- Table structure for table `purchased_books`
--

DROP TABLE IF EXISTS `purchased_books`;
CREATE TABLE IF NOT EXISTS `purchased_books` (
  `purchase_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `student_id` int DEFAULT NULL,
  `book_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `cash` decimal(10,2) NOT NULL,
  `money_change` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`purchase_id`),
  KEY `user_id` (`user_id`),
  KEY `student_id` (`student_id`),
  KEY `book_id` (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `purchased_books`
--

INSERT INTO `purchased_books` (`purchase_id`, `user_id`, `student_id`, `book_id`, `quantity`, `total_amount`, `cash`, `money_change`, `created_at`) VALUES
(10, 5, 1, NULL, 0, '1200.00', '1500.00', '300.00', '2025-03-11 20:59:45'),
(11, 5, 1, NULL, 0, '600.00', '1000.00', '400.00', '2025-03-11 22:58:24');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_details`
--

DROP TABLE IF EXISTS `purchase_details`;
CREATE TABLE IF NOT EXISTS `purchase_details` (
  `detail_id` int NOT NULL AUTO_INCREMENT,
  `purchase_id` int NOT NULL,
  `book_id` int NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`detail_id`),
  KEY `purchase_id` (`purchase_id`),
  KEY `book_id` (`book_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `purchase_details`
--

INSERT INTO `purchase_details` (`detail_id`, `purchase_id`, `book_id`, `quantity`) VALUES
(1, 1, 11, 1),
(2, 1, 4, 1),
(3, 8, 6, 2),
(4, 9, 7, 1),
(5, 10, 1, 1),
(6, 11, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `return_equipment`
--

DROP TABLE IF EXISTS `return_equipment`;
CREATE TABLE IF NOT EXISTS `return_equipment` (
  `return_equipment_id` int NOT NULL AUTO_INCREMENT,
  `equipment_id` int NOT NULL,
  `quantity` int NOT NULL,
  `status` enum('returned','damaged','lost') NOT NULL,
  `returned_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`return_equipment_id`),
  KEY `fk_equipment` (`equipment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `school_year`
--

DROP TABLE IF EXISTS `school_year`;
CREATE TABLE IF NOT EXISTS `school_year` (
  `sy_id` int NOT NULL AUTO_INCREMENT,
  `school_year` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'inactive',
  PRIMARY KEY (`sy_id`),
  UNIQUE KEY `sy` (`school_year`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `school_year`
--

INSERT INTO `school_year` (`sy_id`, `school_year`, `created_at`, `status`) VALUES
(4, '2023-2024', '2025-03-10 11:22:30', 'inactive'),
(5, '2024-2025', '2025-03-10 11:45:20', 'active'),
(6, '2022-2023', '2025-03-10 11:55:46', 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `student_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `rfid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `gradelevel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `strand` varchar(50) NOT NULL,
  `section` varchar(50) NOT NULL,
  `picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `sy_id` int DEFAULT NULL,
  PRIMARY KEY (`student_id`),
  KEY `fk_student_sy` (`sy_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `user_id`, `rfid`, `firstname`, `middlename`, `lastname`, `gradelevel`, `strand`, `section`, `picture`, `created_at`, `sy_id`) VALUES
(1, 5, '833433603', 'Juan', 'C.', 'Dela Cruz', 'Grade 10', '', 'Narra', '', '2025-03-12 20:37:46', 5),
(2, 5, '638537313', 'John', 'D.', 'Doe', 'Grade 12', 'HUMSS', 'Edward', '', '2025-03-12 20:37:46', 5),
(3, 5, '448095792', 'Kristian Jay', 'C.', 'Paculanan', 'Grade 11', 'HUMSS', 'Erickson', '', '2025-03-12 21:06:13', 5),
(4, 5, 'Franklyn', '448095792', 'Kristian Jay', 'C.', 'Paculanan', 'Grade 12', 'STEM', '', '2025-03-12 21:16:45', 5);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `userrole` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `rfid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `profile_picture` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `updated_at` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `email`, `userrole`, `rfid`, `profile_picture`, `status`, `created_at`, `updated_at`) VALUES
(5, 'Kristian Jay', 'Capuz', 'Paculanan', 'kristian', '17f5b76487c6d6b9a59a612c132e4cedfb871443b483d63b32c77c28c13f04ee', 'paculanankj@gmail.com', 'librarian', '0448095792', 'aweraw.jpg', '', '', '');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `book_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `fk_category_id` FOREIGN KEY (`category_id`) REFERENCES `book_categories` (`category_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `book_borrow`
--
ALTER TABLE `book_borrow`
  ADD CONSTRAINT `book_borrow_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `book_return`
--
ALTER TABLE `book_return`
  ADD CONSTRAINT `book_return_ibfk_1` FOREIGN KEY (`book_borrow_id`) REFERENCES `book_borrow` (`book_borrow_id`);

--
-- Constraints for table `equipment_borrow`
--
ALTER TABLE `equipment_borrow`
  ADD CONSTRAINT `fk_student` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `purchased_books`
--
ALTER TABLE `purchased_books`
  ADD CONSTRAINT `purchased_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`),
  ADD CONSTRAINT `purchased_books_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`),
  ADD CONSTRAINT `purchased_books_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `return_equipment`
--
ALTER TABLE `return_equipment`
  ADD CONSTRAINT `return_equipment_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment_borrow` (`equipment_id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `fk_student_sy` FOREIGN KEY (`sy_id`) REFERENCES `school_year` (`sy_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
