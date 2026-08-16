-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 16, 2026 at 07:04 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eduxamido`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_data`
--

DROP TABLE IF EXISTS `admin_data`;
CREATE TABLE IF NOT EXISTS `admin_data` (
  `Admin_id` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `Admin_email` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `Admin_name` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `Admin_password` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`Admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assignment`
--

DROP TABLE IF EXISTS `assignment`;
CREATE TABLE IF NOT EXISTS `assignment` (
  `assignment_id` int NOT NULL AUTO_INCREMENT,
  `ClassID` int NOT NULL,
  `inv_id` int NOT NULL,
  `a_exam_date` date NOT NULL,
  `session` enum('AM','PM') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`assignment_id`),
  KEY `ClassID` (`ClassID`),
  KEY `inv_id` (`inv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classroom`
--

DROP TABLE IF EXISTS `classroom`;
CREATE TABLE IF NOT EXISTS `classroom` (
  `ClassID` int NOT NULL AUTO_INCREMENT,
  `ClassName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `ClassRows` int NOT NULL,
  `ClassColumns` int NOT NULL,
  PRIMARY KEY (`ClassID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `course_id` int NOT NULL AUTO_INCREMENT,
  `scheme_id` int NOT NULL,
  `department_id` int NOT NULL,
  `course_code` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `course_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `credits` int NOT NULL,
  `semester` int NOT NULL,
  `course_type` enum('Core','Elective','Lab','Project','OpenCourse') COLLATE utf8mb4_general_ci DEFAULT 'Core',
  PRIMARY KEY (`course_id`),
  KEY `department_id` (`department_id`),
  KEY `courses_ibfk_2` (`scheme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `department_id` int NOT NULL AUTO_INCREMENT,
  `department_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `department_scode` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `examination`
--

DROP TABLE IF EXISTS `examination`;
CREATE TABLE IF NOT EXISTS `examination` (
  `ExamID` int NOT NULL AUTO_INCREMENT,
  `ExaminationName` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `scheme_id` int NOT NULL,
  `AcademicYear` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `Status` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`ExamID`),
  KEY `scheme_id` (`scheme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `examsubjects`
--

DROP TABLE IF EXISTS `examsubjects`;
CREATE TABLE IF NOT EXISTS `examsubjects` (
  `examsubjectsID` int NOT NULL AUTO_INCREMENT,
  `ExamID` int NOT NULL,
  `course_id` int NOT NULL,
  `examHour` int NOT NULL,
  `Qp_code` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `ExamDate` date NOT NULL,
  `ExamStatus` int NOT NULL,
  `session` enum('AM','PM') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`examsubjectsID`),
  KEY `ExamID` (`ExamID`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_students`
--

DROP TABLE IF EXISTS `exam_students`;
CREATE TABLE IF NOT EXISTS `exam_students` (
  `exam_sub_stu_ID` int NOT NULL AUTO_INCREMENT,
  `RollNo` bigint NOT NULL,
  `examsubjectsID` int NOT NULL,
  `exam_status` int NOT NULL,
  PRIMARY KEY (`exam_sub_stu_ID`),
  KEY `examsubjectsID` (`examsubjectsID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_stu_seating`
--

DROP TABLE IF EXISTS `exam_stu_seating`;
CREATE TABLE IF NOT EXISTS `exam_stu_seating` (
  `exam_sub_stu_ID` int NOT NULL,
  `ClassID` int NOT NULL,
  `class_row` int NOT NULL,
  `class_col` int NOT NULL,
  PRIMARY KEY (`exam_sub_stu_ID`,`ClassID`),
  KEY `ClassID` (`ClassID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invigilators`
--

DROP TABLE IF EXISTS `invigilators`;
CREATE TABLE IF NOT EXISTS `invigilators` (
  `invid` int NOT NULL AUTO_INCREMENT,
  `invi_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `invi_duty_count` int NOT NULL,
  `inviemail` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `invi_address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `invi_highest_qualification` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `invi_post` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `invi_pass` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `invi_status` int NOT NULL,
  PRIMARY KEY (`invid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programmes`
--

DROP TABLE IF EXISTS `programmes`;
CREATE TABLE IF NOT EXISTS `programmes` (
  `programmes_id` int NOT NULL AUTO_INCREMENT,
  `scheme_id` int NOT NULL,
  `programmes_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `programmes_scode` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `department_id` int DEFAULT NULL,
  PRIMARY KEY (`programmes_id`),
  KEY `department_id` (`department_id`),
  KEY `programmes_ibfk_2` (`scheme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schemes`
--

DROP TABLE IF EXISTS `schemes`;
CREATE TABLE IF NOT EXISTS `schemes` (
  `scheme_id` int NOT NULL AUTO_INCREMENT,
  `scheme_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `start_year` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`scheme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students_details`
--

DROP TABLE IF EXISTS `students_details`;
CREATE TABLE IF NOT EXISTS `students_details` (
  `RollNo` bigint NOT NULL,
  `Name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `programmes_id` int NOT NULL,
  `AcademicYear` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `stud_dob` date NOT NULL,
  PRIMARY KEY (`RollNo`),
  KEY `department_id` (`programmes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_for_programmes`
--

DROP TABLE IF EXISTS `subject_for_programmes`;
CREATE TABLE IF NOT EXISTS `subject_for_programmes` (
  `subject_for_programmesID` int NOT NULL AUTO_INCREMENT,
  `examsubjectsID` int NOT NULL,
  `programmes_id` int NOT NULL,
  PRIMARY KEY (`subject_for_programmesID`),
  KEY `examsubjectsID` (`examsubjectsID`),
  KEY `programmes_id` (`programmes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignment`
--
ALTER TABLE `assignment`
  ADD CONSTRAINT `assignment_ibfk_1` FOREIGN KEY (`ClassID`) REFERENCES `classroom` (`ClassID`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignment_ibfk_2` FOREIGN KEY (`inv_id`) REFERENCES `invigilators` (`invid`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `courses_ibfk_2` FOREIGN KEY (`scheme_id`) REFERENCES `schemes` (`scheme_id`);

--
-- Constraints for table `examination`
--
ALTER TABLE `examination`
  ADD CONSTRAINT `examination_ibfk_1` FOREIGN KEY (`scheme_id`) REFERENCES `schemes` (`scheme_id`);

--
-- Constraints for table `examsubjects`
--
ALTER TABLE `examsubjects`
  ADD CONSTRAINT `examsubjects_ibfk_1` FOREIGN KEY (`ExamID`) REFERENCES `examination` (`ExamID`) ON DELETE CASCADE,
  ADD CONSTRAINT `examsubjects_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `exam_students`
--
ALTER TABLE `exam_students`
  ADD CONSTRAINT `exam_students_ibfk_1` FOREIGN KEY (`examsubjectsID`) REFERENCES `examsubjects` (`examsubjectsID`) ON DELETE CASCADE;

--
-- Constraints for table `exam_stu_seating`
--
ALTER TABLE `exam_stu_seating`
  ADD CONSTRAINT `exam_stu_seating_ibfk_1` FOREIGN KEY (`exam_sub_stu_ID`) REFERENCES `exam_students` (`exam_sub_stu_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_stu_seating_ibfk_2` FOREIGN KEY (`ClassID`) REFERENCES `classroom` (`ClassID`) ON DELETE CASCADE;

--
-- Constraints for table `programmes`
--
ALTER TABLE `programmes`
  ADD CONSTRAINT `programmes_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `programmes_ibfk_2` FOREIGN KEY (`scheme_id`) REFERENCES `schemes` (`scheme_id`);

--
-- Constraints for table `students_details`
--
ALTER TABLE `students_details`
  ADD CONSTRAINT `students_details_ibfk_1` FOREIGN KEY (`programmes_id`) REFERENCES `programmes` (`programmes_id`);

--
-- Constraints for table `subject_for_programmes`
--
ALTER TABLE `subject_for_programmes`
  ADD CONSTRAINT `subject_for_programmes_ibfk_1` FOREIGN KEY (`examsubjectsID`) REFERENCES `examsubjects` (`examsubjectsID`),
  ADD CONSTRAINT `subject_for_programmes_ibfk_2` FOREIGN KEY (`programmes_id`) REFERENCES `programmes` (`programmes_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
