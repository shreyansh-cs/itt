-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 14, 2025 at 07:56 AM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u760896062_itt_education`
--

-- --------------------------------------------------------

--
-- Table structure for table `chapters`
--

CREATE TABLE `chapters` (
  `ID` int(2) NOT NULL,
  `NAME` varchar(200) NOT NULL,
  `DETAILS` varchar(200) NOT NULL,
  `SUBJECT_ID` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chapters`
--

INSERT INTO `chapters` (`ID`, `NAME`, `DETAILS`, `SUBJECT_ID`) VALUES
(6, 'Parimeya sankhya', '', 77),
(7, 'linear equation', '', 77);

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `ID` int(2) NOT NULL,
  `NAME` varchar(200) NOT NULL,
  `IS_COMPUTER` tinyint(1) NOT NULL,
  `IS_COMPETITION` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`ID`, `NAME`, `IS_COMPUTER`, `IS_COMPETITION`) VALUES
(1, 'CLASS 1', 0, 0),
(2, 'CLASS 2', 0, 0),
(3, 'CLASS 3', 0, 0),
(4, 'CLASS 4', 0, 0),
(5, 'CLASS 5', 0, 0),
(6, 'CLASS 6', 0, 0),
(7, 'CLASS 7', 0, 0),
(8, 'CLASS 8', 0, 0),
(9, 'CLASS 9', 0, 0),
(10, 'CLASS 10', 0, 0),
(11, 'CLASS 11', 0, 0),
(12, 'CLASS 12', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `ID` int(10) NOT NULL,
  `NAME` varchar(200) NOT NULL,
  `DETAILS` varchar(200) NOT NULL,
  `PDF` varchar(500) DEFAULT NULL,
  `TEXT` text DEFAULT NULL,
  `CHAPTER_ID` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`ID`, `NAME`, `DETAILS`, `PDF`, `TEXT`, `CHAPTER_ID`) VALUES
(5, 'new notes', '', '../../uploads/notes/17410512859286.pdf', 'notes text', 6);

-- --------------------------------------------------------

--
-- Table structure for table `streams`
--

CREATE TABLE `streams` (
  `ID` int(2) NOT NULL,
  `NAME` varchar(200) NOT NULL,
  `DETAILS` varchar(200) NOT NULL,
  `CLASS_ID` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `streams`
--

INSERT INTO `streams` (`ID`, `NAME`, `DETAILS`, `CLASS_ID`) VALUES
(11, 'MATHEMATICS (5)', '', 5),
(12, 'SCIENCE (5)', '', 5),
(13, 'SOCIAL SCIENCE (5)', '', 5),
(14, 'HINDI (5)', '', 5),
(15, 'ENGLISH (5)', '', 5),
(16, 'SANSKRIT (5)', '', 5),
(17, 'MATHEMATICS (6)', '', 6),
(18, 'SCIENCE (6)', '', 6),
(19, 'SOCIAL SCIENCE (6)', '', 6),
(20, 'HINDI (6)', '', 6),
(21, 'ENGLISH (6)', '', 6),
(22, 'SANSKRIT (6)', '', 6),
(23, 'MATHEMATICS (7)', '', 7),
(24, 'SCIENCE (7)', '', 7),
(25, 'SOCIAL SCIENCE (7)', '', 7),
(26, 'HINDI (7)', '', 7),
(27, 'ENGLISH (7)', '', 7),
(28, 'SANSKRIT (7)', '', 7),
(29, 'MATHEMATICS (8)', '', 8),
(30, 'SCIENCE (8)', '', 8),
(31, 'SOCIAL SCIENCE (8)', '', 8),
(32, 'HINDI (8)', '', 8),
(33, 'ENGLISH (8)', '', 8),
(34, 'SANSKRIT (8)', '', 8),
(35, 'MATHEMATICS (9)', '', 9),
(36, 'SCIENCE (9)', '', 9),
(37, 'SOCIAL SCIENCE (9)', '', 9),
(38, 'HINDI (9)', '', 9),
(39, 'ENGLISH (9)', '', 9),
(40, 'SANSKRIT (9)', '', 9),
(41, 'MATHEMATICS (10)', '', 10),
(42, 'SCIENCE (10)', '', 10),
(43, 'SOCIAL SCIENCE (10)', '', 10),
(44, 'HINDI (10)', '', 10),
(45, 'ENGLISH (10)', '', 10),
(46, 'SANSKRIT (10)', '', 10),
(47, 'SCEINCE (11)', '', 11),
(48, 'ARTS (11)', '', 11),
(49, 'SCEINCE (12)', '', 12),
(50, 'ARTS (12)', '', 12);

-- --------------------------------------------------------

--
-- Table structure for table `streamubjectmap`
--

CREATE TABLE `streamubjectmap` (
  `STREAM_ID` int(2) NOT NULL,
  `SUBJECT_ID` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `streamubjectmap`
--

INSERT INTO `streamubjectmap` (`STREAM_ID`, `SUBJECT_ID`) VALUES
(31, 68),
(31, 69),
(31, 70),
(29, 77),
(30, 67);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `ID` int(2) NOT NULL,
  `NAME` varchar(200) NOT NULL,
  `CLASS_ID` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`ID`, `NAME`, `CLASS_ID`) VALUES
(25, 'MATHEMATICS (5)', 5),
(26, 'SCIENCE (5)', 5),
(27, 'ITIHAS (5)', 5),
(28, 'BHUGOL (5)', 5),
(29, 'NAGRIK SASTRA (5)', 5),
(30, 'HINDI (5)', 5),
(31, 'HINDI GRAMMER (5)', 5),
(32, 'ENGLISH (5)', 5),
(33, 'ENGLISH GRAMMER (5)', 5),
(34, 'SANSKRIT (5)', 5),
(35, 'SANSKRIT GRAMMER (5)', 5),
(36, 'MATHEMATICS (6)', 6),
(37, 'SCIENCE (6)', 6),
(38, 'HISTORY (6)', 6),
(39, 'BHUGOL (6)', 6),
(40, 'POLITY  (6)', 6),
(41, 'HINDI  (6)', 6),
(42, 'HINDI GRAMER  (6)', 6),
(43, 'ENGLISH  (6)', 6),
(44, 'ENGLISH GRAMMER (6)', 6),
(45, 'SANSKRIT (6)', 6),
(46, 'SANSKRIT GRAMMER (6)', 6),
(47, 'MATHEMATICS (7)', 7),
(48, 'SCIENCE (7)', 7),
(49, 'HISTORY (7)', 7),
(50, 'GEOGRAPHY (7)', 7),
(51, 'POLITY (7)', 7),
(52, 'HINDI (7)', 7),
(53, 'HINDI GRAMMER (7)', 7),
(54, 'ENGLISH (7)', 7),
(55, 'ENGLISH GRAMMER (7)', 7),
(56, 'SANSKRIT (7)', 7),
(57, 'SANSKRIT GRAMMER (7)', 7),
(67, 'SCIENCE (8)', 8),
(68, 'HISTORY (8)', 8),
(69, 'GEOGRAPHY (8)', 8),
(70, 'POLITY (8)', 8),
(71, 'HINDI (8)', 8),
(72, 'HINDI GRAMMER (8)', 8),
(73, 'ENGLISH (8)', 8),
(74, 'ENGLISH GRAMMER (8)', 8),
(75, 'SANSKRIT (8)', 8),
(76, 'SANSKRIT GRAMMER (8)', 8),
(77, 'MATHEMATICS (8)', 8),
(78, 'MATHEMATICS (9)', 9),
(79, 'PHYSICS (9)', 9),
(80, 'CHEMISTRY (9)', 9),
(81, 'BIOLOGY (9)', 9),
(82, 'HISTORY (9)', 9),
(83, 'GEOGRAPHY (9)', 9),
(84, 'POLITY (9)', 9),
(85, 'HINDI (9)', 9),
(86, 'HINDI GRAMMER (9)', 9),
(87, 'ENGLISH (9)', 9),
(88, 'ENGLISH GRAMMER (9)', 9),
(89, 'SANSKRIT (9)', 9),
(90, 'SANSKRIT GRAMMER (9)', 9),
(91, 'MATHEMATICS (10)', 10),
(92, 'PHYSICS (10)', 10),
(93, 'CHEMISTRY (10)', 10),
(94, 'BIOLOGY (10)', 10),
(95, 'HISTORY (10)', 10),
(96, 'GEOGRAPHY (10)', 10),
(97, 'POLITY (10)', 10),
(98, 'HINDI (10)', 10),
(99, 'HINDI GRAMMER (10)', 10),
(100, 'ENGLISH (10)', 10),
(101, 'ENGLISH GRAMMER (10)', 10),
(102, 'SANSKRIT (10)', 10),
(103, 'SANSKRIT GRAMMER (10)', 10),
(104, 'ECONOMICS (10)', 10),
(105, 'ECONOMICS (9)', 9),
(122, 'HINDI (11)', 11),
(123, 'HINDI GRAMER (11)', 11),
(124, 'ENGLISH (11)', 11),
(125, 'ENGLISH GRAMER (11)', 11),
(126, 'MATHEMATICS (11)', 11),
(127, 'PHYSICS (11)', 11),
(128, 'CHEMISTRY (11)', 11),
(129, 'BIOLOGY (11)', 11),
(130, 'HISTORY (11)', 11),
(131, 'GROGRAPHY (11)', 11),
(132, 'POLITY (11)', 11),
(133, 'MUSIC (11)', 11),
(134, 'HOME SCIENCE (11)', 11),
(135, 'PSYCHOLOGY (11)', 11),
(136, 'ECONOMICS (11)', 11),
(137, 'SOCIOLOGY (11)', 11),
(138, 'HINDI (12)', 12),
(139, 'HINDI GRAMER (12)', 12),
(140, 'ENGLISH (12)', 12),
(141, 'ENGLISH GRAMER (12)', 12),
(142, 'MATHEMATICS (12)', 12),
(143, 'PHYSICS (12)', 12),
(144, 'CHEMISTRY (12)', 12),
(145, 'BIOLOGY (12)', 12),
(146, 'HISTORY (12)', 12),
(147, 'GROGRAPHY (12)', 12),
(148, 'POLITY (12)', 12),
(149, 'MUSIC (12)', 12),
(150, 'HOME SCIENCE (12)', 12),
(151, 'PSYCHOLOGY (12)', 12),
(152, 'ECONOMICS (12)', 12),
(153, 'SOCIOLOGY (12)', 12);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `father_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `dob` date NOT NULL,
  `photo` varchar(500) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(50) NOT NULL,
  `user_class` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `full_name`, `father_name`, `email`, `phone`, `dob`, `photo`, `password`, `user_type`, `user_class`) VALUES
(9, 'shreyansh kumar', 'hridyanand tiwari', 'shreyansh.cs@gmail.com', '9650777075', '1986-08-30', '../../uploads/images/_image.jpg', '1234', 'admin', '5'),
(10, 'Anish kumar', 'Manoj Prasad ', 'anishkushwaha8541@gmail.com', '7479648528', '2009-01-01', '../../uploads/images/IMG_20241203_073124_144.jpg', 'anishku123', 'student', '12'),
(11, 'Ravi kumar ', 'Brij Kishore pandit ', 'bp187029@gmail.com', '9801494319', '2008-03-11', '../../uploads/images/IMG_20250301_201950_110.webp', 'kumar112008', 'student', '12'),
(12, 'Prince kumar', 'Umashankar prasad', 'prince@123gmail.com', '9973576247', '2000-02-28', '../../uploads/images/Screenshot_2024-11-25-18-37-47-809_com.whatsapp.jpg', 'prince@123', 'student', '12'),
(13, 'ABHISHEK KUMAR ', 'Sharma ram', 'abhishek364674@gmail.com', '8877735212', '2009-01-01', '../../uploads/images/b42d224670ba0ef7228e1bb1d8613d85.jpg', 'abhi8877', 'student', '12'),
(14, 'Suraj kushwaha', 'Munna prasad', 'surajrazz9669@gmail.com', '9835954518', '2010-01-23', '../../uploads/images/1000137777.jpg', '7703939664suraj', 'student', '12'),
(15, 'Aryan Singh ', 'Manoj Kumar Mukund ', 'rinkusingh0428@gmail.com', '6200823754', '2009-05-16', '../../uploads/images/1000024364.webp', 'Aryan999', 'student', '12'),
(16, 'Aryan Singh ', 'Manoj Kumar Mukund ', 'aryansinghr837@gmail.com', '9708206287', '2009-05-16', '../../uploads/images/1000024364.webp', 'Aryqn999', 'student', '12'),
(17, 'Satya Kumari ', 'Mukesh kumar prasad', 'nikhilprasad7765@gmail.com', '7061116577', '2007-08-02', '../../uploads/images/IMG_20250227_082234.jpg', 'satyakumari', 'student', '12'),
(18, 'Shiwam singh ', 'Om Pratap Singh ', 'shiwamkum11@gmail.com', '9693392903', '2008-06-07', '../../uploads/images/IMG20250302135348.jpg', '9973443768', 'student', '12'),
(19, 'Dipsi kumari', 'Ramkeshwar prasad', 'rohitkumat5393@gmail.com', '9905748994', '2008-05-01', '../../uploads/images/IMG_20250304_14585801.jpg', 'Rohit Kumar', 'student', '12'),
(20, 'Xyx devi', 'Fulmatiya deva', 'abc@gmail.com', '9876543210', '1906-03-08', '../../uploads/images/1000048116.jpg', 'unic.123', 'student', '10'),
(21, 'Anshu kumar ', 'Brijmohan kumar ray', 'anshukumarray000@gmail.com', '8651836818', '2010-01-04', '../../uploads/images/Image_Editor.png', 'anshu12345', 'student', '12'),
(22, 'ASHUTOSH KUMAR KUSHAWAHA', 'UMASHANKAR PRASAD', 'ashutoshsir1997@gmail.com', '7290003122', '1997-10-15', '../../uploads/images/1678544537646468-0.jpg', 'ashutosh@123', 'student', '12'),
(23, 'Rahul kumar ', 'Rajkishor Prasad ', 'krritik841408@gmail.com', '8651766082', '2008-02-13', '../../uploads/images/1000019108.jpg', 'Rahul@##7352', 'student', '12'),
(24, 'Shiwam Kumar', 'Dilip prasad', 'shiwamkumar2833@gmail.com', '7250434241', '2010-11-03', '../../uploads/images/20250304_201912.jpg', 'Shiwam7250', 'student', '10'),
(25, 'VISHAWAJIT KUMAR ', 'SANTOSH PANDIT', 'santoshpanditpandit821@gmail.com', '8084480870', '2010-07-24', '../../uploads/images/1711425875562.jpg', 'VISHAWAJIT@123', 'student', '10'),
(26, 'Prince Sharma', 'SANTOSH SHARMA ', 'f76521806@gmail.com', '9229772914', '2025-03-10', '../../uploads/images/1000037722.webp', 'prince4415', 'student', '12'),
(27, 'Prince Sharma', 'Santosh sharma ', 'priyanshu7755z@gmail.com', '8651681214', '2007-09-30', '../../uploads/images/1000039032.jpg', 'prince4415_', 'student', '12');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `ID` int(10) NOT NULL,
  `NAME` varchar(200) NOT NULL,
  `DETAILS` text NOT NULL,
  `LINK` varchar(200) NOT NULL,
  `CHAPTER_ID` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chapters`
--
ALTER TABLE `chapters`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_subject_id` (`SUBJECT_ID`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CHAPTER_ID` (`CHAPTER_ID`);

--
-- Indexes for table `streams`
--
ALTER TABLE `streams`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_class_id` (`CLASS_ID`);

--
-- Indexes for table `streamubjectmap`
--
ALTER TABLE `streamubjectmap`
  ADD KEY `STREAM_ID` (`STREAM_ID`),
  ADD KEY `SUBJECT_ID` (`SUBJECT_ID`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CLASS_ID` (`CLASS_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CHAPTER_ID` (`CHAPTER_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chapters`
--
ALTER TABLE `chapters`
  MODIFY `ID` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `ID` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `streams`
--
ALTER TABLE `streams`
  MODIFY `ID` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `ID` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chapters`
--
ALTER TABLE `chapters`
  ADD CONSTRAINT `fk_subject_id` FOREIGN KEY (`SUBJECT_ID`) REFERENCES `subjects` (`ID`);

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`CHAPTER_ID`) REFERENCES `chapters` (`ID`);

--
-- Constraints for table `streams`
--
ALTER TABLE `streams`
  ADD CONSTRAINT `fk_class_id` FOREIGN KEY (`CLASS_ID`) REFERENCES `classes` (`ID`);

--
-- Constraints for table `streamubjectmap`
--
ALTER TABLE `streamubjectmap`
  ADD CONSTRAINT `streamubjectmap_ibfk_1` FOREIGN KEY (`STREAM_ID`) REFERENCES `streams` (`ID`),
  ADD CONSTRAINT `streamubjectmap_ibfk_2` FOREIGN KEY (`SUBJECT_ID`) REFERENCES `subjects` (`ID`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`CLASS_ID`) REFERENCES `classes` (`ID`);

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `fk_chapter_id` FOREIGN KEY (`CHAPTER_ID`) REFERENCES `chapters` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`CHAPTER_ID`) REFERENCES `chapters` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
