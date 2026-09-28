-- ==========================================================
-- PSP Student Portal Database Script
-- Database: student_portal
-- Includes: students, lecturers, and grades tables with Foreign Keys
-- ==========================================================

CREATE DATABASE IF NOT EXISTS `student_portal` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `student_portal`;

-- Drop existing tables to ensure clean reset
DROP TABLE IF EXISTS `grades`;
DROP TABLE IF EXISTS `lecturers`;
DROP TABLE IF EXISTS `students`;

-- --------------------------------------------------------
-- Table structure for `students`
-- --------------------------------------------------------
CREATE TABLE `students` (
  `student_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `nric` VARCHAR(20) NOT NULL UNIQUE,
  `program` VARCHAR(100) NOT NULL,
  `profile_picture` VARCHAR(255) DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `lecturers`
-- --------------------------------------------------------
CREATE TABLE `lecturers` (
  `lecturer_id` INT(11) NOT NULL AUTO_INCREMENT,
  `staff_id` VARCHAR(20) NOT NULL UNIQUE,
  `name` VARCHAR(100) NOT NULL,
  `department` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`lecturer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `grades` (Relational child table)
-- --------------------------------------------------------
CREATE TABLE `grades` (
  `grade_id` INT(11) NOT NULL AUTO_INCREMENT,
  `student_id` INT(11) NOT NULL,
  `course_code` VARCHAR(20) NOT NULL,
  `course_name` VARCHAR(100) NOT NULL,
  `marks` DECIMAL(5,2) NOT NULL,
  `grade_letter` VARCHAR(5) NOT NULL,
  `status` ENUM('Pass','Fail') NOT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`grade_id`),
  KEY `fk_grades_students_idx` (`student_id`),
  CONSTRAINT `fk_grades_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Seed Data for `students` (Password: password123)
-- --------------------------------------------------------
INSERT INTO `students` (`student_id`, `name`, `nric`, `program`, `password`) VALUES
(1, 'Ahmad Bin Ali', '040101-07-1234', 'Diploma Teknologi Maklumat (Teknologi Digital)', '$2y$10$vC17iSvdJbThaqgZQGNH1OOBJAUGhxWb/e0kWRBTDUQ1o0RLO5py2'),
(2, 'Siti Nurhaliza Binti Rosli', '040512-08-5678', 'Diploma Teknologi Maklumat (Keselamatan Maklumat)', '$2y$10$vC17iSvdJbThaqgZQGNH1OOBJAUGhxWb/e0kWRBTDUQ1o0RLO5py2'),
(3, 'Tan Wei Ming', '031120-07-3345', 'Diploma Kejuruteraan Elektronik (Komputer)', '$2y$10$vC17iSvdJbThaqgZQGNH1OOBJAUGhxWb/e0kWRBTDUQ1o0RLO5py2'),
(4, 'Priya a/p Suresh', '040819-02-7890', 'Diploma Teknologi Maklumat (Teknologi Digital)', '$2y$10$vC17iSvdJbThaqgZQGNH1OOBJAUGhxWb/e0kWRBTDUQ1o0RLO5py2'),
(5, 'Muhammad Faiz Bin Zakaria', '030903-03-4411', 'Diploma Kejuruteraan Mekanikal', '$2y$10$vC17iSvdJbThaqgZQGNH1OOBJAUGhxWb/e0kWRBTDUQ1o0RLO5py2');

-- --------------------------------------------------------
-- Seed Data for `lecturers` (Password: password123)
-- --------------------------------------------------------
INSERT INTO `lecturers` (`lecturer_id`, `staff_id`, `name`, `department`, `email`, `password`) VALUES
(1, 'L001', 'Dr. Mohd Azman Bin Ismail', 'Jabatan Teknologi Maklumat & Komunikasi', 'azman@psp.edu.my', '$2y$10$vC17iSvdJbThaqgZQGNH1OOBJAUGhxWb/e0kWRBTDUQ1o0RLO5py2'),
(2, 'L002', 'Pn. Noraini Binti Hassan', 'Jabatan Kejuruteraan Elektrik', 'noraini@psp.edu.my', '$2y$10$vC17iSvdJbThaqgZQGNH1OOBJAUGhxWb/e0kWRBTDUQ1o0RLO5py2');

-- --------------------------------------------------------
-- Seed Data for `grades`
-- --------------------------------------------------------
INSERT INTO `grades` (`grade_id`, `student_id`, `course_code`, `course_name`, `marks`, `grade_letter`, `status`) VALUES
(1, 1, 'DFP50193', 'Web Application Development', 88.50, 'A', 'Pass'),
(2, 2, 'DFP50193', 'Web Application Development', 92.00, 'A+', 'Pass'),
(3, 3, 'DEP50083', 'Embedded Robotic Systems', 74.00, 'B+', 'Pass'),
(4, 4, 'DFP50193', 'Web Application Development', 65.50, 'B-', 'Pass'),
(5, 5, 'DJJ50113', 'Fluid Mechanics', 42.00, 'F', 'Fail');
