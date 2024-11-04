-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 04, 2024 at 11:20 PM
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
  `user_id` int NOT NULL,
  `student_id` int DEFAULT NULL,
  `time_in` text,
  `time_out` text,
  `date` date NOT NULL,
  PRIMARY KEY (`attendance_id`),
  KEY `student_fk` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `user_id`, `student_id`, `time_in`, `time_out`, `date`) VALUES
(1, 0, 3, '18:45:20', '18:59:42', '2024-10-06'),
(2, 0, 2, '18:47:10', '10:58:35', '2024-10-06'),
(3, 0, 2, '18:58:48', '07:15 PM', '2024-10-06'),
(4, 0, 4, '18:59:12', '18:59:50', '2024-10-06'),
(5, 0, 4, '18:59:55', '19:02:13', '2024-10-06'),
(6, 0, 4, '19:08:53', '07:09 PM', '2024-10-06'),
(7, 0, 4, '07:09 PM', '07:18 PM', '2024-10-06'),
(8, 0, 3, '07:14 PM', '07:15 PM', '2024-10-06'),
(9, 0, 3, '07:15 PM', '07:15 PM', '2024-10-06'),
(10, 0, 3, '07:15 PM', '07:15 PM', '2024-10-06'),
(11, 0, 3, '07:21 PM', '07:23 PM', '2024-10-06'),
(12, 0, 3, '07:29 PM', '08:47 PM', '2024-10-06'),
(13, 0, 2, '07:32 PM', '08:47 PM', '2024-10-06'),
(14, 0, 2, '08:47 PM', '08:47 PM', '2024-10-06'),
(15, 0, 3, '08:49 PM', '08:50 PM', '2024-10-06'),
(16, 0, 2, '08:49 PM', '08:49 PM', '2024-10-06'),
(17, 0, 3, '08:52 PM', NULL, '2024-10-06'),
(18, 0, 3, '08:52 PM', NULL, '2024-10-06'),
(19, 0, 3, '08:52 PM', NULL, '2024-10-06'),
(20, 0, 3, '08:52 PM', NULL, '2024-10-06'),
(21, 0, 3, '08:52 PM', NULL, '2024-10-06'),
(22, 0, 3, '08:52 PM', NULL, '2024-10-06'),
(23, 0, 3, '08:53 PM', NULL, '2024-10-06'),
(24, 0, 3, '08:53 PM', NULL, '2024-10-06'),
(25, 0, 3, '08:54 PM', NULL, '2024-10-06'),
(26, 0, 3, '08:54 PM', NULL, '2024-10-06'),
(27, 0, 3, '08:54 PM', NULL, '2024-10-06'),
(28, 0, 2, '08:53 PM', '08:53 PM', '2024-10-10'),
(29, 0, 2, '08:53 PM', '08:53 PM', '2024-10-10'),
(30, 0, 4, '08:53 PM', '08:54 PM', '2024-10-10'),
(31, 0, 3, '08:53 PM', '08:53 PM', '2024-10-10'),
(32, 0, 4, '08:54 PM', '08:54 PM', '2024-10-10'),
(33, 0, 4, '08:54 PM', '09:24 PM', '2024-10-10'),
(34, 0, 3, '08:54 PM', NULL, '2024-10-10'),
(35, 0, 2, '08:54 PM', NULL, '2024-10-10'),
(36, 0, 1, '08:25 AM', NULL, '2024-10-12'),
(37, 0, 1, '07:24 PM', NULL, '2024-10-14');

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

DROP TABLE IF EXISTS `book`;
CREATE TABLE IF NOT EXISTS `book` (
  `book_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(50) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `publication_year` year DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `copies` int DEFAULT '1',
  `added_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`book_id`),
  UNIQUE KEY `isbn` (`isbn`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`book_id`, `user_id`, `title`, `author`, `isbn`, `publisher`, `publication_year`, `location`, `copies`, `added_at`) VALUES
(4, 5, 'Introduction to Algorithms', 'Thomas H. Cormen', '9780262033848', 'MIT Press', 2009, 'A1-01', 13, '2024-10-18 21:05:34'),
(6, 5, 'Introduction to Psychology', 'James W. Kalat', '978-1305271555', 'Cengage Learning', 2016, 'Shelf PSY-01', 9, '2024-10-19 02:18:53'),
(7, 5, 'Physics for Scientists and Engineers', 'Raymond A. Serway, John Jewett', '978-1337553292', 'Cengage Learning', 2018, 'Shelf PHY-02', 10, '2024-10-19 02:20:02'),
(8, 5, 'Fundamentals of Database Systems', 'Ramez Elmasri, Shamkant Navathe', '978-0133970777', '	Pearson Education', 2016, 'Shelf CS-03', 6, '2024-10-19 02:20:44'),
(9, 5, 'Principles of Microeconomics', 'N. Gregory Mankiw', '978-1337096223', 'Cengage Learning', 2018, 'Shelf ECO-04', 4, '2024-10-19 02:21:47'),
(10, 5, 'Educational Psychology: Theory and Practice', 'Robert E. Slavin', '978-0134522096', 'Pearson Education', 2017, 'Shelf EDU-01', 3, '2024-10-19 02:22:45'),
(11, 5, 'Calculus: Early Transcendentals', 'James Stewart', '978-1337613924', 'Cengage Learning', 2018, 'Shelf MATH-01', 7, '2024-10-19 08:06:09'),
(12, 5, 'Introduction to Sociology', 'Anthony Giddens et al.', '978-0393639452', 'W.W. Norton & Company', 2018, 'Shelf SOC-02', 8, '2024-10-19 08:06:44'),
(13, 5, 'Essentials of Human Anatomy & Physiology', 'Elaine N. Marieb', '978-0134580579', 'Pearson Education', 2017, 'Shelf BIO-03', 4, '2024-10-19 08:07:25'),
(14, 5, 'Artificial Intelligence: A Modern Approach', 'Stuart Russell, Peter Norvig', '978-0134610993', 'Pearson Education', 2016, 'Shelf CS-04', 8, '2024-10-19 08:07:58'),
(15, 5, 'Research Methods in Education', 'Louis Cohen, Lawrence Manion, Keith Morrison', '978-1138209886', 'Routledge', 2018, 'Shelf EDU-02', 9, '2024-10-19 08:08:46');

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `book_borrow`
--

INSERT INTO `book_borrow` (`book_borrow_id`, `book_id`, `student_id`, `quantity`, `status`, `created_at`) VALUES
(1, 7, 3, 1, 'borrowed', '2024-11-03 06:46:13'),
(2, 6, 2, 0, 'borrowed', '2024-11-03 06:46:47'),
(3, 7, 2, 0, 'borrowed', '2024-11-03 06:56:31'),
(4, 12, 2, 0, 'borrowed', '2024-11-03 10:41:13'),
(5, 4, 2, 1, 'borrowed', '2024-11-04 02:41:01');

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `book_return`
--

INSERT INTO `book_return` (`return_book_id`, `book_borrow_id`, `quantity`, `status`, `returned_at`) VALUES
(4, 3, 3, 'returned', '2024-11-03 18:01:16'),
(3, 2, 1, 'returned', '2024-11-03 15:55:18'),
(5, 4, 1, 'lost', '2024-11-03 18:41:30');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchased_book`
--

DROP TABLE IF EXISTS `purchased_book`;
CREATE TABLE IF NOT EXISTS `purchased_book` (
  `purchased_id` int NOT NULL,
  `user_id` int NOT NULL,
  `student_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `grade_level` varchar(255) NOT NULL,
  `book` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `total` varchar(255) NOT NULL,
  `total_change` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `report_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `report_type` varchar(50) DEFAULT NULL,
  `report_data` json DEFAULT NULL,
  `generated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`report_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `address` varchar(255) NOT NULL,
  `picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `user_id`, `rfid`, `firstname`, `middlename`, `lastname`, `gradelevel`, `address`, `picture`, `created_at`) VALUES
(2, 5, '0638537313', 'Juan', 'D', 'Cruz', 'Grade 12', 'Tufi', '../uploads/671f81dadea4f_weaewa.jpg', '2024-10-28 20:21:46'),
(3, 5, '0833433603', 'Kristian Jay', 'Capuz', 'Paculanan', 'Grade 11', 'Polomolok', '../uploads/671f81eeae5d3_avrwev.jpg', '2024-10-28 20:22:06');

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
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `student_fk` FOREIGN KEY (`student_id`) REFERENCES `attendance` (`attendance_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `book_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `equipment_borrow`
--
ALTER TABLE `equipment_borrow`
  ADD CONSTRAINT `fk_student` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
