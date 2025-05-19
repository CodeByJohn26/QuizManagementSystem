-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2025 at 04:38 AM
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
-- Database: `quiz_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `choices`
--

CREATE TABLE `choices` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `choice_label` char(1) NOT NULL,
  `choice_text` varchar(255) NOT NULL,
  `is_correct` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `enrolled_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `user_id`, `subject_id`, `enrolled_at`) VALUES
(1, 38, 2, '2025-05-16 17:26:10'),
(2, 38, 3, '2025-05-16 17:26:19'),
(3, 38, 4, '2025-05-16 17:26:28'),
(4, 38, 1, '2025-05-16 17:27:03'),
(5, 38, 5, '2025-05-16 17:27:17'),
(6, 38, 6, '2025-05-16 17:27:29'),
(7, 38, 7, '2025-05-16 17:27:39'),
(8, 38, 8, '2025-05-16 17:27:50'),
(9, 39, 2, '2025-05-16 17:31:31'),
(10, 39, 3, '2025-05-16 17:31:42'),
(11, 39, 4, '2025-05-16 17:31:54'),
(12, 39, 1, '2025-05-16 17:32:02'),
(13, 39, 5, '2025-05-16 17:32:11'),
(14, 39, 6, '2025-05-16 17:32:19'),
(15, 39, 7, '2025-05-16 17:32:28'),
(16, 39, 8, '2025-05-16 17:32:40'),
(17, 40, 2, '2025-05-16 17:36:04'),
(18, 40, 3, '2025-05-16 17:36:07'),
(19, 40, 4, '2025-05-16 17:36:10'),
(20, 40, 1, '2025-05-16 17:36:13'),
(21, 40, 5, '2025-05-16 17:36:15'),
(22, 40, 6, '2025-05-16 17:36:38'),
(23, 40, 7, '2025-05-16 17:36:48'),
(24, 40, 8, '2025-05-16 17:36:56'),
(25, 41, 2, '2025-05-16 17:39:08'),
(26, 41, 3, '2025-05-16 17:39:10'),
(27, 41, 4, '2025-05-16 17:39:12'),
(28, 41, 1, '2025-05-16 17:39:13'),
(29, 41, 5, '2025-05-16 17:39:16'),
(30, 41, 6, '2025-05-16 17:39:18'),
(31, 41, 7, '2025-05-16 17:39:26'),
(32, 41, 8, '2025-05-16 17:39:34'),
(33, 42, 2, '2025-05-16 17:42:53'),
(34, 42, 3, '2025-05-16 17:42:55'),
(35, 42, 4, '2025-05-16 17:42:57'),
(36, 42, 1, '2025-05-16 17:42:58'),
(37, 42, 5, '2025-05-16 17:43:00'),
(38, 42, 6, '2025-05-16 17:43:03'),
(39, 42, 7, '2025-05-16 17:43:17'),
(40, 42, 8, '2025-05-16 17:43:28'),
(41, 43, 2, '2025-05-16 17:49:28'),
(42, 43, 3, '2025-05-16 17:49:31'),
(43, 43, 4, '2025-05-16 17:49:33'),
(44, 43, 1, '2025-05-16 17:49:42'),
(45, 43, 5, '2025-05-16 17:49:46'),
(46, 43, 6, '2025-05-16 17:49:48'),
(47, 43, 7, '2025-05-16 17:49:56'),
(48, 43, 8, '2025-05-16 17:50:07'),
(49, 44, 2, '2025-05-16 17:52:36'),
(50, 44, 3, '2025-05-16 17:52:38'),
(51, 44, 4, '2025-05-16 17:52:40'),
(52, 44, 1, '2025-05-16 17:52:42'),
(53, 44, 5, '2025-05-16 17:52:45'),
(54, 44, 6, '2025-05-16 17:52:47'),
(55, 44, 7, '2025-05-16 17:52:56'),
(56, 44, 8, '2025-05-16 17:53:04'),
(57, 45, 2, '2025-05-16 17:54:24'),
(58, 45, 3, '2025-05-16 17:54:26'),
(59, 45, 4, '2025-05-16 17:54:28'),
(60, 45, 1, '2025-05-16 17:54:29'),
(61, 45, 5, '2025-05-16 17:54:33'),
(62, 45, 6, '2025-05-16 17:54:35'),
(63, 45, 7, '2025-05-16 17:54:52'),
(64, 45, 8, '2025-05-16 17:54:58');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `student_id`, `teacher_id`, `subject_id`, `message`, `created_at`) VALUES
(1, 40, 30, 2, 'The quiz provided a helpful review of the key concepts covered in the lesson. I found the instructions clear and the format easy to follow, especially with the mix of multiple choice and true or false questions. The level of difficulty was generally considered fair, though a few students mentioned that certain questions—such as identifying types of malware or understanding database keys—required more in-depth explanation. Overall, the quiz was seen as a useful tool for reinforcing learning and checking understanding.', '2025-05-16 10:50:10'),
(2, 42, 34, 1, 'The quiz was helpful in reviewing key RDBMS concepts like tables, primary keys, and SQL. Most questions were clear, but some students found topics like foreign keys and normalization a bit challenging. Overall, the quiz supported learning, though a few areas could use more examples for better understanding.', '2025-05-16 10:57:14'),
(3, 44, 30, 2, 'The quiz was effective in reinforcing basic cybersecurity concepts such as strong passwords, phishing, and malware. Most students found the questions clear and relevant. However, a few participants suggested including more real-life examples to better understand threats like phishing. Overall, the quiz helped raise awareness and tested essential knowledge well.', '2025-05-16 11:02:50'),
(4, 45, 34, 1, 'The RDBMS quiz gave a clear overview of key concepts like data tables, primary and foreign keys, and the role of SQL. Students appreciated the structure of the quiz, though some needed more clarification on normalization and relationships between tables. It was a good check of understanding and highlighted areas for review.', '2025-05-16 11:05:23');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `created_at`) VALUES
(1, 38, 'You have successfully enrolled in Professional Elective 3.', '2025-05-16 09:26:10'),
(2, 38, 'You have successfully enrolled in Environmental Science.', '2025-05-16 09:26:19'),
(3, 38, 'You have successfully enrolled in Character Building.', '2025-05-16 09:26:28'),
(4, 38, 'You have successfully enrolled in Aviation Information Management 1.', '2025-05-16 09:27:03'),
(5, 38, 'You have successfully enrolled in Computer Networking 3 (Switching and VOIP).', '2025-05-16 09:27:17'),
(6, 38, 'You have successfully enrolled in Aviation Secure Web Development.', '2025-05-16 09:27:29'),
(7, 38, 'You have successfully enrolled in Aviation System Requirement Analysis, Design and Quality Assurance.', '2025-05-16 09:27:39'),
(8, 38, 'You have successfully enrolled in Physical Education 4.', '2025-05-16 09:27:50'),
(9, 39, 'You have successfully enrolled in Professional Elective 3.', '2025-05-16 09:31:31'),
(10, 39, 'You have successfully enrolled in Environmental Science.', '2025-05-16 09:31:42'),
(11, 39, 'You have successfully enrolled in Character Building.', '2025-05-16 09:31:54'),
(12, 39, 'You have successfully enrolled in Aviation Information Management 1.', '2025-05-16 09:32:02'),
(13, 39, 'You have successfully enrolled in Computer Networking 3 (Switching and VOIP).', '2025-05-16 09:32:11'),
(14, 39, 'You have successfully enrolled in Aviation Secure Web Development.', '2025-05-16 09:32:19'),
(15, 39, 'You have successfully enrolled in Aviation System Requirement Analysis, Design and Quality Assurance.', '2025-05-16 09:32:28'),
(16, 39, 'You have successfully enrolled in Physical Education 4.', '2025-05-16 09:32:40'),
(17, 40, 'You have successfully enrolled in Professional Elective 3.', '2025-05-16 09:36:04'),
(18, 40, 'You have successfully enrolled in Environmental Science.', '2025-05-16 09:36:07'),
(19, 40, 'You have successfully enrolled in Character Building.', '2025-05-16 09:36:10'),
(20, 40, 'You have successfully enrolled in Aviation Information Management 1.', '2025-05-16 09:36:13'),
(21, 40, 'You have successfully enrolled in Computer Networking 3 (Switching and VOIP).', '2025-05-16 09:36:15'),
(22, 40, 'You have successfully enrolled in Aviation Secure Web Development.', '2025-05-16 09:36:38'),
(23, 40, 'You have successfully enrolled in Aviation System Requirement Analysis, Design and Quality Assurance.', '2025-05-16 09:36:48'),
(24, 40, 'You have successfully enrolled in Physical Education 4.', '2025-05-16 09:36:56'),
(25, 41, 'You have successfully enrolled in Professional Elective 3.', '2025-05-16 09:39:08'),
(26, 41, 'You have successfully enrolled in Environmental Science.', '2025-05-16 09:39:10'),
(27, 41, 'You have successfully enrolled in Character Building.', '2025-05-16 09:39:12'),
(28, 41, 'You have successfully enrolled in Aviation Information Management 1.', '2025-05-16 09:39:13'),
(29, 41, 'You have successfully enrolled in Computer Networking 3 (Switching and VOIP).', '2025-05-16 09:39:16'),
(30, 41, 'You have successfully enrolled in Aviation Secure Web Development.', '2025-05-16 09:39:18'),
(31, 41, 'You have successfully enrolled in Aviation System Requirement Analysis, Design and Quality Assurance.', '2025-05-16 09:39:26'),
(32, 41, 'You have successfully enrolled in Physical Education 4.', '2025-05-16 09:39:34'),
(33, 42, 'You have successfully enrolled in Professional Elective 3.', '2025-05-16 09:42:53'),
(34, 42, 'You have successfully enrolled in Environmental Science.', '2025-05-16 09:42:55'),
(35, 42, 'You have successfully enrolled in Character Building.', '2025-05-16 09:42:57'),
(36, 42, 'You have successfully enrolled in Aviation Information Management 1.', '2025-05-16 09:42:58'),
(37, 42, 'You have successfully enrolled in Computer Networking 3 (Switching and VOIP).', '2025-05-16 09:43:00'),
(38, 42, 'You have successfully enrolled in Aviation Secure Web Development.', '2025-05-16 09:43:03'),
(39, 42, 'You have successfully enrolled in Aviation System Requirement Analysis, Design and Quality Assurance.', '2025-05-16 09:43:17'),
(40, 42, 'You have successfully enrolled in Physical Education 4.', '2025-05-16 09:43:28'),
(41, 43, 'You have successfully enrolled in Professional Elective 3.', '2025-05-16 09:49:28'),
(42, 43, 'You have successfully enrolled in Environmental Science.', '2025-05-16 09:49:31'),
(43, 43, 'You have successfully enrolled in Character Building.', '2025-05-16 09:49:33'),
(44, 43, 'You have successfully enrolled in Aviation Information Management 1.', '2025-05-16 09:49:42'),
(45, 43, 'You have successfully enrolled in Computer Networking 3 (Switching and VOIP).', '2025-05-16 09:49:46'),
(46, 43, 'You have successfully enrolled in Aviation Secure Web Development.', '2025-05-16 09:49:48'),
(47, 43, 'You have successfully enrolled in Aviation System Requirement Analysis, Design and Quality Assurance.', '2025-05-16 09:49:56'),
(48, 43, 'You have successfully enrolled in Physical Education 4.', '2025-05-16 09:50:07'),
(49, 44, 'You have successfully enrolled in Professional Elective 3.', '2025-05-16 09:52:36'),
(50, 44, 'You have successfully enrolled in Environmental Science.', '2025-05-16 09:52:38'),
(51, 44, 'You have successfully enrolled in Character Building.', '2025-05-16 09:52:40'),
(52, 44, 'You have successfully enrolled in Aviation Information Management 1.', '2025-05-16 09:52:42'),
(53, 44, 'You have successfully enrolled in Computer Networking 3 (Switching and VOIP).', '2025-05-16 09:52:45'),
(54, 44, 'You have successfully enrolled in Aviation Secure Web Development.', '2025-05-16 09:52:47'),
(55, 44, 'You have successfully enrolled in Aviation System Requirement Analysis, Design and Quality Assurance.', '2025-05-16 09:52:56'),
(56, 44, 'You have successfully enrolled in Physical Education 4.', '2025-05-16 09:53:04'),
(57, 45, 'You have successfully enrolled in Professional Elective 3.', '2025-05-16 09:54:24'),
(58, 45, 'You have successfully enrolled in Environmental Science.', '2025-05-16 09:54:26'),
(59, 45, 'You have successfully enrolled in Character Building.', '2025-05-16 09:54:28'),
(60, 45, 'You have successfully enrolled in Aviation Information Management 1.', '2025-05-16 09:54:29'),
(61, 45, 'You have successfully enrolled in Computer Networking 3 (Switching and VOIP).', '2025-05-16 09:54:33'),
(62, 45, 'You have successfully enrolled in Aviation Secure Web Development.', '2025-05-16 09:54:35'),
(63, 45, 'You have successfully enrolled in Aviation System Requirement Analysis, Design and Quality Assurance.', '2025-05-16 09:54:52'),
(64, 45, 'You have successfully enrolled in Physical Education 4.', '2025-05-16 09:54:58');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('multiple_choice','true_false','essay','identification') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `correct_answer` text DEFAULT NULL,
  `case_sensitive` tinyint(1) DEFAULT 0,
  `max_score` int(11) DEFAULT NULL,
  `choices` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`, `question_type`, `created_at`, `correct_answer`, `case_sensitive`, `max_score`, `choices`) VALUES
(1, 1, '1. What does RDBMS stand for?', 'multiple_choice', '2025-05-16 10:04:07', 'B) Relational Database Management System', 0, NULL, '[\"A) Relational Data Basic Management System\",\"B) Relational Database Management System\",\"C) Random Database Management System\",\"D) None of the above\"]'),
(2, 1, 'Who is considered the father of the relational database model?', 'multiple_choice', '2025-05-16 10:05:26', 'B) Edgar F. Codd', 0, NULL, '[\"A) Charles Babbage\",\"B) Edgar F. Codd\",\"C) Bill Gates\",\"D) Dennis Ritchie\"]'),
(3, 1, 'Which of the following is an example of an RDBMS?', 'multiple_choice', '2025-05-16 10:06:37', 'B) MySQL', 0, NULL, '[\"A) MongoDB\",\"B) MySQL\",\"C) Redis\",\"D) Neo4j\"]'),
(4, 1, 'What is a ‘table’ in RDBMS?', 'multiple_choice', '2025-05-16 10:08:17', 'C) A collection of rows and columns representing data', 0, NULL, '[\"A) A graphical interface\",\"B) A collection of related files\",\"C) A collection of rows and columns representing data\",\"D) A type of database\"]'),
(6, 1, 'What is a primary key in a relational table?', 'multiple_choice', '2025-05-16 10:11:18', 'B) A unique identifier for each record in a table', 0, NULL, '[\"A) A column that stores duplicate values\",\"B) A unique identifier for each record in a table\",\"C) A foreign column\",\"D) A data type\"]'),
(8, 1, 'A foreign key uniquely identifies each record in its own table.', 'multiple_choice', '2025-05-16 10:15:18', 'FALSE', 0, NULL, '[\"TRUE\",\"FALSE\"]'),
(9, 1, 'SQL is the standard language used to communicate with RDBMS.', 'multiple_choice', '2025-05-16 10:16:08', 'TRUE', 0, NULL, '[\"TRUE\",\"FALSE\"]'),
(10, 1, 'In an RDBMS, data is stored in the form of objects and collections.', 'multiple_choice', '2025-05-16 10:16:39', 'FALSE', 0, NULL, '[\"TRUE\",\"FALSE\"]'),
(11, 1, 'One of the main benefits of RDBMS is data integrity and consistency.', 'multiple_choice', '2025-05-16 10:17:10', 'TRUE', 0, NULL, '[\"TRUE\",\"FALSE\"]'),
(12, 1, 'Normalization is a process used to eliminate redundancy in database tables.', 'multiple_choice', '2025-05-16 10:17:44', 'TRUE', 0, NULL, '[\"TRUE\",\"FALSE\"]'),
(15, 2, 'What is the main goal of cybersecurity?', 'multiple_choice', '2025-05-16 10:26:58', 'Protect data and systems from attacks', 0, NULL, '[\" Create computer viruses\",\" Protect data and systems from attacks\",\"Improve computer speed\",\"Monitor social media activity\",\"None of the Above\"]'),
(16, 2, 'Which of the following is an example of malware?', 'multiple_choice', '2025-05-16 10:27:50', 'Trojan horse', 0, NULL, '[\"Firewall\",\"Antivirus\",\"Trojan horse\",\"Password\"]'),
(17, 2, 'What is phishing?', 'multiple_choice', '2025-05-16 10:29:14', 'Tricking users into revealing personal information', 0, NULL, '[\"Sending spam emails for marketing\",\" A technique to repair computer hardware\",\" Tricking users into revealing personal information\",\"Using encryption to protect data\",\"None of the Above\"]'),
(18, 2, 'Cybersecurity only applies to large companies.', 'multiple_choice', '2025-05-16 10:29:58', 'FALSE', 0, NULL, '[\"TRUE\",\"FALSE\"]'),
(19, 2, 'Keeping software updated can help prevent cyber attacks.', 'multiple_choice', '2025-05-16 10:30:37', 'TRUE', 0, NULL, '[\"TRUE\",\"FALSE\"]'),
(20, 3, 'Which of the following best describes the purpose of normalization in a relational database?', 'multiple_choice', '2025-05-17 04:51:50', 'To eliminate data anomalies and redundancy', 0, NULL, '[\"To increase data redundancy\",\"To reduce data integrity\",\"To eliminate data anomalies and redundancy\",\" To make database design more complex\"]'),
(21, 3, 'In Third Normal Form (3NF), a relation must be in Second Normal Form (2NF) and have no transitive dependencies.', 'multiple_choice', '2025-05-17 04:53:33', 'TRUE', 0, NULL, '[\"TRUE\",\"FALSE\"]');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `time_limit` int(11) NOT NULL,
  `scramble_questions` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `max_attempts` int(11) DEFAULT 1,
  `quiz_completed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `title`, `subject_id`, `time_limit`, `scramble_questions`, `created_at`, `max_attempts`, `quiz_completed`) VALUES
(1, 'Introduction of RDBMS', 1, 30, 0, '2025-05-16 10:00:22', 1, 0),
(2, 'Cybersecurity Awareness Quiz', 2, 45, 0, '2025-05-16 10:21:21', 1, 0),
(3, 'Normalization in RDBMS', 1, 5, 0, '2025-05-17 04:47:50', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `attempt_date` datetime DEFAULT current_timestamp(),
  `start_time` datetime DEFAULT current_timestamp(),
  `status` enum('in_progress','completed') DEFAULT 'in_progress'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_attempts`
--

INSERT INTO `quiz_attempts` (`id`, `quiz_id`, `student_id`, `attempt_date`, `start_time`, `status`) VALUES
(1, 1, 38, '2025-05-16 18:31:38', '2025-05-16 18:31:38', 'completed'),
(2, 2, 38, '2025-05-16 18:37:25', '2025-05-16 18:37:25', 'completed'),
(3, 1, 39, '2025-05-16 18:40:37', '2025-05-16 18:40:37', 'completed'),
(4, 2, 39, '2025-05-16 18:44:53', '2025-05-16 18:44:53', 'completed'),
(5, 2, 40, '2025-05-16 18:47:31', '2025-05-16 18:47:31', 'completed'),
(6, 1, 40, '2025-05-16 18:50:44', '2025-05-16 18:50:44', 'completed'),
(7, 2, 41, '2025-05-16 18:53:01', '2025-05-16 18:53:01', 'completed'),
(8, 1, 41, '2025-05-16 18:53:28', '2025-05-16 18:53:28', 'completed'),
(9, 2, 42, '2025-05-16 18:55:25', '2025-05-16 18:55:25', 'completed'),
(10, 1, 42, '2025-05-16 18:55:50', '2025-05-16 18:55:50', 'completed'),
(11, 2, 43, '2025-05-16 18:58:49', '2025-05-16 18:58:49', 'completed'),
(12, 1, 43, '2025-05-16 18:59:19', '2025-05-16 18:59:19', 'completed'),
(13, 2, 44, '2025-05-16 19:01:06', '2025-05-16 19:01:06', 'completed'),
(14, 1, 44, '2025-05-16 19:01:41', '2025-05-16 19:01:41', 'completed'),
(15, 2, 45, '2025-05-16 19:03:54', '2025-05-16 19:03:54', 'completed'),
(16, 1, 45, '2025-05-16 19:04:14', '2025-05-16 19:04:14', 'completed'),
(17, 3, 38, '2025-05-17 14:06:40', '2025-05-17 14:06:40', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_results`
--

CREATE TABLE `quiz_results` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completion_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_results`
--

INSERT INTO `quiz_results` (`id`, `student_id`, `quiz_id`, `score`, `total`, `created_at`, `completion_time`) VALUES
(1, 38, 1, 10, 10, '2025-05-16 10:32:06', '2025-05-16 18:32:06'),
(2, 38, 2, 3, 5, '2025-05-16 10:37:43', '2025-05-16 18:37:43'),
(3, 39, 1, 8, 10, '2025-05-16 10:41:02', '2025-05-16 18:41:02'),
(4, 39, 2, 3, 5, '2025-05-16 10:45:09', '2025-05-16 18:45:09'),
(5, 40, 2, 3, 5, '2025-05-16 10:48:12', '2025-05-16 18:48:12'),
(6, 40, 1, 5, 10, '2025-05-16 10:51:06', '2025-05-16 18:51:06'),
(7, 41, 2, 2, 5, '2025-05-16 10:53:17', '2025-05-16 18:53:17'),
(8, 41, 1, 9, 10, '2025-05-16 10:53:58', '2025-05-16 18:53:58'),
(9, 42, 2, 3, 5, '2025-05-16 10:55:37', '2025-05-16 18:55:37'),
(10, 42, 1, 7, 10, '2025-05-16 10:56:14', '2025-05-16 18:56:14'),
(11, 43, 2, 2, 5, '2025-05-16 10:59:05', '2025-05-16 18:59:05'),
(12, 43, 1, 10, 10, '2025-05-16 10:59:48', '2025-05-16 18:59:48'),
(13, 44, 2, 2, 5, '2025-05-16 11:01:32', '2025-05-16 19:01:32'),
(14, 44, 1, 10, 10, '2025-05-16 11:02:10', '2025-05-16 19:02:10'),
(15, 45, 2, 3, 5, '2025-05-16 11:04:05', '2025-05-16 19:04:05'),
(16, 45, 1, 9, 10, '2025-05-16 11:04:40', '2025-05-16 19:04:40'),
(17, 38, 3, 2, 2, '2025-05-17 06:07:15', '2025-05-17 14:07:15');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(50) NOT NULL DEFAULT 'bg-gray-100'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `code`, `teacher_id`, `description`, `color`) VALUES
(1, 'Aviation Information Management 1', 'IT 221', 34, NULL, '#0ed4d8'),
(2, 'Professional Elective 3', 'AIT Elec 3', 30, NULL, '#3b82f6'),
(3, 'Environmental Science', 'GEC11', 31, NULL, '#22c55e'),
(4, 'Character Building', 'GEI 1', 32, NULL, '#ec4899'),
(5, 'Computer Networking 3 (Switching and VOIP)', 'IT 222', 30, NULL, '#f97316'),
(6, 'Aviation Secure Web Development', 'IT 223', 35, NULL, '#ef4444'),
(7, 'Aviation System Requirement Analysis, Design and Quality Assurance', 'IT 224', 36, NULL, '#14b8a6'),
(8, 'Physical Education 4', 'PE 4', 37, NULL, '#6b7280'),
(9, 'Computer Programming 2 (Advance Java)', 'IT 122', 47, NULL, '#8b5cf6');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `role` enum('teacher','student','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `profile_image`, `role`, `created_at`) VALUES
(15, 'Admin', 'lorenzo@gmail.com', '$2y$10$vDj4FaDzclWilTLJYCCq..JNuePLt1h1ISkzWv6/E/s75FmhHxd6i', '1747383115_Office operator with headset talking with clients _ Premium AI-generated image.jpg', 'admin', '2025-04-30 11:47:07'),
(30, 'Aron J. Alojado', 'alojado@gmail.com', '$2y$10$9c2UKv3MqnR0v7ybNfUX5etlrOUu2ScEmxuBtfoqOBqkJniJmHT9y', '1747386553_sirA.jpg', 'teacher', '2025-05-16 08:32:33'),
(31, 'Resa M. Boco', 'resa@gmail.com', '$2y$10$/fjoU4th4XfGBSDm9Ub11..vf.BWNmZYzRDDdnqbxs.oIisTvcec.', '1747386663_Cartoon Character Of Girl Teacher Vector Illustration PNG Images _ EPS Free Download - Pikbest.jpg', 'teacher', '2025-05-16 08:39:57'),
(32, 'Lea V. Lanete', 'lea@gmail.com', '$2y$10$/Ere9a0./b3nOcVRVvKH/.Gxt/OuKiYaJtXO1AZ5RhlQpR2KHDJGS', '1747386698_download.jpg', 'teacher', '2025-05-16 08:40:39'),
(34, 'Ronald T. Lava', 'lavz@gmail.com', '$2y$10$kiLglnKSeMkdf7gLHFhUA.8mx5HFoa.OsDfuMOeRn3.6Za2ywXAX2', '1747386609_sir lavz.jpg', 'teacher', '2025-05-16 08:42:09'),
(35, 'Richard B. Peralta', 'richard@gmail.com', '$2y$10$NA1qbZLa4upFlmNIW4sKFOW0048G/IQxgNOuOPh8D7O0xpqJFsjiu', '1747386806_sirperalts.jpg', 'teacher', '2025-05-16 08:44:47'),
(36, 'Jerum B. Dasalla', 'jerum@gmail.com', '$2y$10$adiXVWZvevm2ha3vHnpPwesQ3rL3rArf3RmheyWAczvu6kqwK9Nq2', '1747387243_sird.jpg', 'teacher', '2025-05-16 08:45:31'),
(37, 'Higenio C. Burce', 'higenio@gmail.com', '$2y$10$JhbcNuDONcPjjDZ.pQw.TuW36n01vObP4bYLJKYSHObYnKHch5Num', '1747387269_SIRBURCE.jpg', 'teacher', '2025-05-16 08:47:04'),
(38, 'Ma. Jessica C. Baltazar', 'jessica@gmail.com', '$2y$10$XzuzRz1IL2P6bZITw0Htgeg5H3oUxk6k9eXO9lWrOMm0RynCEA32q', '1747456637_pic.jpg', 'student', '2025-05-16 09:23:12'),
(39, 'Denise Faith P. Pacete', 'denise@gmail.com', '$2y$10$S8Z8469.35cMIX/0bS25oe356vB2flJCRzYhfdzHRT0vJ9TOQenf2', '1747392247_den.jpg', 'student', '2025-05-16 09:30:54'),
(40, 'Carl Andre L. Espinosa', 'carl@gmail.com', '$2y$10$GlZ8qd2DNkcYyXztJ6abNuBmojAduoifLNxlZVsnP2UPqNrCKg8HO', '1747392443_carl.jpg', 'student', '2025-05-16 09:35:39'),
(41, 'Ray John V. Lorenzo', 'ray@gmail.com', '$2y$10$UEVj93j64ZSio0sPwo7wvOaPNufk7ZWWetfdpcfhBjsQATWDsLWHS', '1747392741_rj.jpg', 'student', '2025-05-16 09:38:40'),
(42, 'Roniel Ronron S. Emasa', 'roniel@gmail.com', '$2y$10$C/byABsyAM.vqBCOiA8kZeFnfe6rtWla66fW1JWNoZSR3U0HnU3sW', '1747392912_ron.jpg', 'student', '2025-05-16 09:42:12'),
(43, 'Marc Shanylle A. Arcega', 'marc@gmail.com', '$2y$10$9wMt7XzK/uZz/hWUJ.WAIujWWBHxpN1X4TwYjll4ERrlzfR9dTbL.', '1747393204_marc.jpg', 'student', '2025-05-16 09:49:14'),
(44, 'Kent Reynald P. Oaman', 'kent@gmail.com', '$2y$10$4YH0l9gHhwwx0RstasnN8ekqqOrMMhtPzQc6ZSaPxvFDlQO.a.HO2', '1747393387_kent.jpg', 'student', '2025-05-16 09:52:03'),
(45, 'Vince Lenin C. Pandaan', 'vince@gmail.com', '$2y$10$XB5cu7Ozw80LHFdzaciZgOyVx/vGxMfGWz/BeItTgFvNVm5VTXEwa', '1747393427_vince.jpg', 'student', '2025-05-16 09:54:08'),
(47, 'Erickson A. Antonio', 'erickson@gmail.com', '$2y$10$p5ByFt9H8/ynYtvnOYeYJ.a8SXDlAQ1PzfkRyVIj03rTjIAmRIqa6', '', 'teacher', '2025-05-17 05:08:46'),
(48, 'Danah Maegan S. Magbagay', 'danah@gmail.com', '$2y$10$7wEbniClMCeNnda2Dx2qMewnzlfAMFgi3FRydKTMgXV80YUnOsSJO', '../uploads/profile_images/default-profile.png', 'student', '2025-05-18 03:59:22'),
(49, 'Alan Lino Silverio J. Agustin', 'alan@gmail.com', '$2y$10$5laeuSDj8GnmQ9bPR4Mu9uLJGrWy6vyPvoAZtgzAQmoXH1AFrgnnK', '', 'teacher', '2025-05-18 04:04:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `choices`
--
ALTER TABLE `choices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `choices`
--
ALTER TABLE `choices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `feedback_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
