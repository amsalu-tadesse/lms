-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 06, 2021 at 08:04 AM
-- Server version: 8.0.25
-- PHP Version: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_level`
--

CREATE TABLE `academic_level` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academic_level`
--

INSERT INTO `academic_level` (`id`, `name`, `description`) VALUES
(1, 'Bachelor', 'dsa'),
(2, 'Masters', 'da'),
(3, 'PhD', 'PhD'),
(5, 'High school graduate', 'grade 12 graduated');

-- --------------------------------------------------------

--
-- Table structure for table `choices`
--

CREATE TABLE `choices` (
  `id` int NOT NULL,
  `question_id` int DEFAULT NULL,
  `letter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` int NOT NULL,
  `chapter_id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `resource` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resource_names` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `chapter_id`, `title`, `content`, `filename`, `video_link`, `description`, `resource`, `resource_names`) VALUES
(17, 11, 'Industrial revolution', NULL, NULL, 'https://youtu.be/pHiMN_gy9mk', NULL, NULL, NULL),
(18, 11, 'emerging technologies', NULL, NULL, 'https://www.youtube.com/watch?v=o81vdCreJtQ', NULL, NULL, NULL),
(19, 12, 'defination', NULL, NULL, 'https://www.youtube.com/watch?v=bAyrObl7TYE', NULL, NULL, NULL),
(20, 13, 'Introduction', NULL, NULL, 'https://www.youtube.com/watch?v=kWmX3pd1f10', NULL, NULL, NULL),
(21, 12, 'Big data video', NULL, 'DORO-6186835067c44.mp4', NULL, NULL, NULL, NULL),
(24, 11, 'five', NULL, NULL, 'https://www.youtube.com/watch?v=pHiMN_gy9mk', NULL, NULL, NULL),
(25, 11, 'mama', NULL, NULL, 'https://www.youtube.com/embed/-zgBTIpBFoo', NULL, NULL, NULL),
(26, 11, 'wrong content', '<p>adhfasdfasdfaf</p>', NULL, NULL, NULL, NULL, NULL),
(27, 14, '<script>alert("kkkkkk")<script>', '<p>management</p>', NULL, NULL, NULL, 'sql-ledger-manual-61ab145383002.docx', 'sql-ledger manual.docx');

-- --------------------------------------------------------

--
-- Table structure for table `content_type`
--

CREATE TABLE `content_type` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `name`, `description`) VALUES
(1, 'asdf', 'asdf'),
(2, 'xxxxxxxxxxxx', 'xxxxxxxxxxxxxxxxxxxxxxx');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `category_id`, `name`, `code`, `credit`, `description`, `status`) VALUES
(8, 6, 'Introduction à l’intelligence artificielle et à la Science de données', 'MIASD1001', '4', NULL, 1),
(9, 6, 'La Data Science dans la recherche et le Business', 'MIASD1002', '3', NULL, 1),
(10, 6, 'Algèbre linéaire et Optimisation', 'MIASD1003', '4', NULL, 1),
(12, 5, 'History of AI and foundational concepts', 'HisAI101', NULL, NULL, 1),
(13, 5, 'The Ethics of AI, data security and data privacy', 'EthAI101', NULL, NULL, 1),
(14, 5, 'Introduction to Algorithms and Machine Learning', 'IntroAI101', NULL, NULL, 1),
(16, 5, 'Introduction to emerging technologies', 'IET101', '3', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `course_category`
--

CREATE TABLE `course_category` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_category`
--

INSERT INTO `course_category` (`id`, `name`, `description`) VALUES
(5, 'Capacity Building Training', NULL),
(6, 'Masters Program', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE `exam` (
  `id` int NOT NULL,
  `instructor_course_id` int DEFAULT NULL,
  `instruction` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `percentage` double DEFAULT NULL,
  `passvalue` double DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

CREATE TABLE `instructor` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `academic_level_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructor`
--

INSERT INTO `instructor` (`id`, `user_id`, `academic_level_id`) VALUES
(4, 57, NULL),
(5, 58, NULL),
(6, 59, NULL),
(8, 68, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `instructor_course`
--

CREATE TABLE `instructor_course` (
  `id` int NOT NULL,
  `course_id` int NOT NULL,
  `instructor_id` int DEFAULT NULL,
  `status_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `duration` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructor_course`
--

INSERT INTO `instructor_course` (`id`, `course_id`, `instructor_id`, `status_id`, `created_at`, `active`, `duration`) VALUES
(11, 8, 4, NULL, '2021-11-05 12:12:07', 1, NULL),
(12, 9, 5, NULL, '2021-11-05 12:12:46', 1, NULL),
(13, 10, 6, NULL, '2021-11-05 12:13:17', 1, NULL),
(15, 12, 4, NULL, '2021-11-05 12:24:19', 1, NULL),
(16, 13, 5, NULL, '2021-11-05 12:24:50', 1, NULL),
(17, 14, 6, NULL, '2021-11-05 12:25:30', 1, NULL),
(19, 16, 8, NULL, '2021-11-06 13:16:17', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `instructor_course_chapter`
--

CREATE TABLE `instructor_course_chapter` (
  `id` int NOT NULL,
  `instructor_course_id` int NOT NULL,
  `chapter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `topic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructor_course_chapter`
--

INSERT INTO `instructor_course_chapter` (`id`, `instructor_course_id`, `chapter`, `created_at`, `topic`) VALUES
(9, 13, NULL, '2021-11-06 04:12:04', 'Chapter2: Magnetic Induction'),
(10, 13, NULL, '2021-11-06 04:12:04', 'Chapter3: rererern'),
(11, 19, NULL, '2021-11-06 15:02:19', 'Chapter 1 Introduction'),
(12, 19, NULL, '2021-11-06 15:02:42', 'Chapter 2 Big datq'),
(13, 19, NULL, '2021-11-06 15:03:00', 'Chapter 3 AI'),
(14, 11, NULL, '2021-12-04 09:06:28', 'Chapter 11: Amazing Topics');

-- --------------------------------------------------------

--
-- Table structure for table `instructor_course_status`
--

CREATE TABLE `instructor_course_status` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `id` int NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `code`, `name`, `description`) VALUES
(1, 'academic_level_list', 'academic_level_list', NULL),
(2, 'academic_level_create', 'academic_level create', NULL),
(3, 'academic_level_edit', 'academic_level edit', NULL),
(4, 'academic_level_delete', 'academic_level delete', NULL),
(5, 'content_list', 'content_list', NULL),
(6, 'content_create', 'content create', NULL),
(7, 'content_edit', 'content edit', NULL),
(8, 'content_delete', 'content delete', NULL),
(17, 'course_list', 'course_list', NULL),
(18, 'course_create', 'course create', NULL),
(19, 'course_edit', 'course edit', NULL),
(20, 'course_delete', 'course delete', NULL),
(21, 'course_category_list', 'course_category_list', NULL),
(22, 'course_category_create', 'course_category create', NULL),
(23, 'course_category_edit', 'course_category edit', NULL),
(24, 'course_category_delete', 'course_category delete', NULL),
(29, 'instructor_list', 'instructor_list', NULL),
(30, 'instructor_create', 'instructor create', NULL),
(31, 'instructor_edit', 'instructor edit', NULL),
(32, 'instructor_delete', 'instructor delete', NULL),
(33, 'instructor_course_list', 'assigned course list', NULL),
(34, 'instructor_course_create', 'assign courses to instructor', NULL),
(35, 'instructor_course_edit', 'edit course assignement', NULL),
(36, 'instructor_course_delete', 'delete assigned course', NULL),
(37, 'chapter_list', 'chapter_list', NULL),
(38, 'chapter_create', 'chapter create', NULL),
(39, 'chapter_edit', 'chapter edit', NULL),
(40, 'chapter_delete', 'chapter delete', NULL),
(41, 'question_list', 'question_list', NULL),
(42, 'question_create', 'question create', NULL),
(43, 'question_edit', 'question edit', NULL),
(44, 'question_delete', 'question delete', NULL),
(45, 'quiz_list', 'quiz_list', NULL),
(46, 'quiz_create', 'quiz create', NULL),
(47, 'quiz_edit', 'quiz edit', NULL),
(48, 'quiz_delete', 'quiz delete', NULL),
(53, 'student_list', 'student_list', NULL),
(54, 'student_create', 'student create', NULL),
(55, 'student_edit', 'student edit', NULL),
(56, 'student_delete', 'student delete', NULL),
(61, 'student_course_list', 'student_course_list', NULL),
(62, 'student_course_create', 'student_course create', NULL),
(63, 'student_course_edit', 'student_course edit', NULL),
(64, 'student_course_delete', 'student_course delete', NULL),
(73, 'system_setting_list', 'system_setting_list', NULL),
(74, 'system_setting_create', 'system_setting create', NULL),
(75, 'system_setting_edit', 'system_setting edit', NULL),
(76, 'system_setting_delete', 'system_setting delete', NULL),
(77, 'user_list', 'user_list', NULL),
(78, 'user_create', 'user create', NULL),
(79, 'user_edit', 'user edit', NULL),
(80, 'user_delete', 'user delete', NULL),
(81, 'user_group_list', 'user_group_list', NULL),
(82, 'user_group_create', 'user_group create', NULL),
(83, 'user_group_edit', 'user_group edit', NULL),
(84, 'user_group_delete', 'user_group delete', NULL),
(89, 'user_type_list', 'user_type list', NULL),
(90, 'user_type_create', 'user_type create', NULL),
(91, 'user_type_edit', 'user_type edit', NULL),
(92, 'user_type_delete', 'user_type delete', NULL),
(93, 'choices_list', 'choices_list', NULL),
(94, 'choices_create', 'choices create', NULL),
(95, 'choices_edit', 'choices edit', NULL),
(96, 'choices_delete', 'choices delete', NULL),
(97, 'new_request_list', 'new request list', NULL),
(98, 'rejected_request_list', 'rejected request list', NULL),
(99, 'my_course', 'see own course', NULL),
(100, 'manage_users', 'manage users', NULL),
(102, 'approve_course_request', 'approve course request', NULL),
(108, 'qa_answer', 'Q&A answer', NULL),
(109, 'qa_ask', 'Q&A Ask', NULL),
(110, 'instructor_course_assign', 'assigned course assign', NULL),
(111, 'instructor_course_deactivate', 'delete assigned deactivate', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE `person` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_student` tinyint(1) NOT NULL,
  `history` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `id` int NOT NULL,
  `exam_id` int DEFAULT NULL,
  `question` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question_answer`
--

CREATE TABLE `question_answer` (
  `id` int NOT NULL,
  `student_id` int DEFAULT NULL,
  `instructor_id` int DEFAULT NULL,
  `quation_answer_id` int DEFAULT NULL,
  `course_id` int NOT NULL,
  `question` varchar(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notification` tinyint(1) NOT NULL,
  `answer` varchar(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `answered_at` datetime DEFAULT NULL,
  `video_answer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question_answer`
--

INSERT INTO `question_answer` (`id`, `student_id`, `instructor_id`, `quation_answer_id`, `course_id`, `question`, `notification`, `answer`, `created_at`, `answered_at`, `video_answer`) VALUES
(9, 20, 6, NULL, 13, '<p>what the hell is this course for??</p>', 1, '<p>grere</p>', '2021-12-05 10:15:06', '2021-12-05 10:20:17', 'record-61ac764152c73.webm'),
(10, 20, NULL, NULL, 13, '<p>what the hell is this course for??</p>', 0, NULL, '2021-12-05 10:16:31', NULL, NULL),
(11, 20, NULL, NULL, 13, '<p>what the hell is this course for??</p>', 0, NULL, '2021-12-05 10:19:45', NULL, NULL),
(12, 20, NULL, NULL, 13, '<p>what the hell is this course for??</p>', 0, NULL, '2021-12-05 10:20:26', NULL, NULL),
(13, 20, NULL, NULL, 13, '<p>what the hell is this course for??</p>', 0, NULL, '2021-12-05 10:24:55', NULL, NULL),
(14, 20, NULL, NULL, 13, '<p>what the hell is this course for??&nbsp;&nbsp;</p>', 0, NULL, '2021-12-05 10:25:42', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `id` int NOT NULL,
  `instructor_course_chapter_id` int NOT NULL,
  `instruction` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `percentage` double DEFAULT NULL,
  `passvalue` double DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL,
  `active_questions` int NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `no_of_retake_allowed` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`id`, `instructor_course_chapter_id`, `instruction`, `percentage`, `passvalue`, `name`, `duration`, `is_mandatory`, `active_questions`, `active`, `no_of_retake_allowed`) VALUES
(6, 9, 'iinst', 10, 10, 'quiz 1', 10, 1, 20, 1, 0),
(7, 11, 'choice', 3, 2, 'chapter 1 quiz', 4, 1, 3, 1, 2),
(8, 12, 'choice', 2, 2, 'quiz for chapter 2', 5, 0, 2, 1, 1),
(9, 13, 'Instruction', 10, 2, 'chapter 3 q', 10, 1, 2, 1, 1),
(10, 14, 'choose one', 10, 20, 'quiz for chapter 1', 3, 1, 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_choices`
--

CREATE TABLE `quiz_choices` (
  `id` int NOT NULL,
  `letter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_choices`
--

INSERT INTO `quiz_choices` (`id`, `letter`, `description`, `question_id`) VALUES
(27, 'A', 'asfas', 7),
(28, 'B', 'asdfasdf', 7),
(29, 'C', 'asdfasdfasdf', 7),
(30, 'D', 'fasdfasdf', 7),
(31, 'A', 'new technology', 8),
(32, 'B', 'developing technology', 8),
(33, 'C', 'A and B', 8),
(34, 'D', 'None', 8),
(35, 'A', 'industrial revolution', 9),
(36, 'B', 'index revolution', 9),
(37, 'C', 'irreversible random numbers', 9),
(38, 'D', 'None', 9),
(39, 'A', '1st ', 10),
(40, 'B', '2nd', 10),
(41, 'C', '3rd', 10),
(42, 'D', 'ALL', 10),
(43, 'A', 'IoT', 11),
(44, 'B', 'AI', 11),
(45, 'C', 'AR,VR', 11),
(46, 'D', 'all', 11),
(47, 'A', 'video', 12),
(48, 'B', 'audio', 12),
(49, 'C', 'image', 12),
(50, 'D', 'all', 12),
(51, 'A', 'veracity', 13),
(52, 'B', 'volume', 13),
(53, 'C', 'velocity', 13),
(54, 'D', 'all', 13),
(55, 'A', 'structured ', 14),
(56, 'B', 'semi structured', 14),
(57, 'C', 'unstructured ', 14),
(58, 'D', 'ALL', 14),
(59, 'A', 'artifical intelligence', 15),
(60, 'B', 'artificial investigation', 15),
(61, 'A', 'machine', 16),
(62, 'B', 'language', 16),
(63, 'A', 'true', 17),
(64, 'B', 'select * from users where id = 5;', 17),
(65, 'C', 'sfsf', 17),
(66, 'D', 'sdf', 17);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int NOT NULL,
  `quiz_id` int DEFAULT NULL,
  `question` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `quiz_id`, `question`, `answer`) VALUES
(7, 6, '<p>question 1</p>', 'B'),
(8, 7, '<p>what is emerging technology?</p>', 'C'),
(9, 7, '<p>what is IR?</p>', 'A'),
(10, 7, '<p>examples of IR?</p>', 'D'),
(11, 7, '<p>examples of emerging technologies</p>', 'D'),
(12, 8, '<p>what is BD?</p>', 'D'),
(13, 8, '<p>The 4 Vs of BD?</p>', 'D'),
(14, 8, '<p>types of BD?</p>', 'D'),
(15, 9, '<p>what is ai?</p>', 'A'),
(16, 9, '<p>What is computer</p>', 'A'),
(17, 10, '<p>&lt;script&gt;alert(&#39;whatt??&#39;)&lt;/script&gt;</p>', 'B');

-- --------------------------------------------------------

--
-- Table structure for table `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `selector` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `academic_level_id` int DEFAULT NULL,
  `student_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_updated` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `user_id`, `academic_level_id`, `student_id`, `profile_picture`, `profile_updated`) VALUES
(16, 62, 1, '', NULL, 0),
(17, 63, 1, '', NULL, 0),
(18, 64, 2, '', NULL, 0),
(20, 71, 5, '', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `student_answer`
--

CREATE TABLE `student_answer` (
  `id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_chapter`
--

CREATE TABLE `student_chapter` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `chapter_id` int NOT NULL,
  `pages_completed` int NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_chapter`
--

INSERT INTO `student_chapter` (`id`, `student_id`, `chapter_id`, `pages_completed`, `updated_at`) VALUES
(2, 20, 11, 5, '2021-11-06 15:51:59'),
(3, 20, 12, 2, '2021-11-06 16:10:25'),
(4, 20, 13, 1, '2021-11-06 16:11:35');

-- --------------------------------------------------------

--
-- Table structure for table `student_content_reaction`
--

CREATE TABLE `student_content_reaction` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `content_id` int DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_course`
--

CREATE TABLE `student_course` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `instructor_course_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` smallint DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `is_at_page` int DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `teacher_notification` tinyint(1) NOT NULL,
  `director_notification` tinyint(1) NOT NULL,
  `qr_code` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_course`
--

INSERT INTO `student_course` (`id`, `student_id`, `instructor_course_id`, `created_at`, `status`, `active`, `is_at_page`, `completed_at`, `teacher_notification`, `director_notification`, `qr_code`) VALUES
(12, 16, 15, '2021-11-05 12:50:29', 1, 0, NULL, NULL, 0, 1, NULL),
(13, 16, 11, '2021-11-05 12:50:29', 1, 0, NULL, NULL, 0, 1, NULL),
(14, 17, 16, '2021-11-05 12:53:03', 1, 0, NULL, NULL, 0, 1, NULL),
(15, 17, 12, '2021-11-05 12:53:03', 1, 0, NULL, NULL, 0, 1, NULL),
(16, 18, 17, '2021-11-05 12:58:37', 1, 0, NULL, NULL, 0, 1, NULL),
(17, 18, 13, '2021-11-05 12:58:37', 1, 1, NULL, NULL, 0, 1, NULL),
(18, 20, 19, '2021-11-06 14:06:50', 5, 1, NULL, NULL, 0, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_question`
--

CREATE TABLE `student_question` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `question_id` int NOT NULL,
  `answer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answered_at` datetime NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_question`
--

INSERT INTO `student_question` (`id`, `student_id`, `question_id`, `answer`, `answered_at`, `active`) VALUES
(1, 20, 9, 'A', '2021-11-06 15:59:14', 0),
(2, 20, 10, 'C', '2021-11-06 15:59:25', 0),
(3, 20, 11, 'A', '2021-11-06 15:59:33', 0),
(4, 20, 8, 'C', '2021-11-06 15:59:59', 1),
(5, 20, 9, 'A', '2021-11-06 16:00:05', 1),
(6, 20, 10, 'D', '2021-11-06 16:00:10', 1),
(7, 20, 12, 'A', '2021-11-06 16:10:34', 0),
(8, 20, 14, 'B', '2021-11-06 16:10:37', 0),
(9, 20, 12, 'C', '2021-11-06 16:11:18', 1),
(10, 20, 13, 'C', '2021-11-06 16:11:24', 1),
(11, 20, 15, 'A', '2021-11-06 16:14:56', 1),
(12, 20, 16, 'A', '2021-11-06 16:14:59', 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_quiz`
--

CREATE TABLE `student_quiz` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `result` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trial` int NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_quiz`
--

INSERT INTO `student_quiz` (`id`, `student_id`, `quiz_id`, `created_at`, `end_time`, `result`, `trial`, `active`) VALUES
(1, 20, 7, '2021-11-06 15:59:07', '2021-11-06 16:03:07', '1', 0, 0),
(2, 20, 7, '2021-11-06 15:59:46', '2021-11-06 16:03:46', '3', 1, 1),
(3, 20, 8, '2021-11-06 16:10:30', '2021-11-06 16:15:30', '0', 0, 0),
(4, 20, 8, '2021-11-06 16:11:15', '2021-11-06 16:16:15', '0', 1, 1),
(5, 20, 9, '2021-11-06 16:14:43', '2021-11-06 16:24:43', '10', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `system_setting`
--

CREATE TABLE `system_setting` (
  `id` int NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_setting`
--

INSERT INTO `system_setting` (`id`, `code`, `value`, `description`) VALUES
(1, 'upload_size', '100', 'Upload size in MB'),
(2, 'show_un_answered_questions', '0', 'if this row is 1, at the end of a quiz, a student will see the list of questions that he didn\'t see because of time.'),
(4, 'terms', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'Terms of the Organization'),
(5, 'conditions', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 'Conditions of the Organization');

-- --------------------------------------------------------

--
-- Table structure for table `termsandconditions`
--

CREATE TABLE `termsandconditions` (
  `id` int NOT NULL,
  `terms` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `conditions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `termsandconditions`
--

INSERT INTO `termsandconditions` (`id`, `terms`, `conditions`) VALUES
(1, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `user_type_id` int DEFAULT NULL,
  `username` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `locale` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirm_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user_type_id`, `username`, `roles`, `password`, `first_name`, `middle_name`, `last_name`, `email`, `is_active`, `last_login`, `locale`, `confirm_token`, `role`, `is_verified`, `created_at`, `updated_at`) VALUES
(32, 1, 'admin.admin', '["ROLE_ADMIN"]', '$2y$13$04HmczTjehS/I/1RJYNTX.y58tdZrkwHqud5me3cA6aB0nLO6U5Ee', 'admin', 'admin', 'admin', 'admin@gmail.com', 1, '2021-12-06 08:03:47', NULL, NULL, NULL, 0, '2021-12-06 00:00:00', NULL),
(57, 3, 'instructor.instructor', '["ROLE_INSTRUCTOR"]', '$2y$13$sVqNOJCNg3xsRQO/SrN6E.1GDdFylXDEX.6n14Kh8.932qAskQfzC', 'instructor', 'instructor', 'instructor', 'yonas.tesfaye1@aastu.edu.et', 1, '2021-12-04 09:04:35', NULL, NULL, NULL, 0, '2021-12-06 00:00:00', NULL),
(58, 3, 'instructorB.instructorB', '["ROLE_INSTRUCTOR"]', '$2y$13$QZzsFBJ1mlolTzyYQvVReehQ6Imn5Xm54Uw6rErFpLHWlsvQfZ0Y.', 'instructorB', 'instructorB', 'instructorB', 'kebe2003@gmail.com', 1, '2021-11-06 16:41:20', NULL, NULL, NULL, 0, '2021-12-06 00:00:00', NULL),
(59, 3, 'instructorC.instructorC', '["ROLE_INSTRUCTOR"]', '$2y$13$QZzsFBJ1mlolTzyYQvVReehQ6Imn5Xm54Uw6rErFpLHWlsvQfZ0Y.', 'instructorC', 'instructorC', 'instructorC', 'bereket.walle1@aastu.edu.et', 1, '2021-12-05 09:45:59', NULL, NULL, NULL, 0, '2021-12-06 00:00:00', NULL),
(62, 4, 'studentA.studentA', '["ROLE_STUDENT"]', '$argon2id$v=19$m=65536,t=4,p=1$IIywvjUGMzhzSRLCCXei0A$U+88mtTMdintx/rPUYkl4Ld2ofn5Ud51T4IjoNyfzB4', 'studentA', 'studentA', 'studentA', 'lioulnebiye1@gmail.com', 1, NULL, NULL, NULL, NULL, 1, '2021-12-06 00:00:00', NULL),
(63, 4, 'studentB.studentB', '["ROLE_STUDENT"]', '$argon2id$v=19$m=65536,t=4,p=1$VviWr7rdo3LvzKtShTlcTA$JUPeFSBVE1ACmpECaukT1Xfa0KEfvXkz6UbNwQkSt6Y', 'studentB', 'studentB', 'studentB', 'lioulnebiye2@gmail.com', 1, NULL, NULL, NULL, NULL, 1, '2021-12-06 00:00:00', NULL),
(64, 4, 'studentC.studentC', '["ROLE_STUDENT"]', '$2y$13$8hZerp5cc/Wz9kpyTQx49u6LQEWFmPfUdapduRAcO2o3h2KL.Ei.O', 'studentC', 'studentC', 'studentC', 'tadesseamsalu1@gmail.com', 1, '2021-11-05 13:03:13', NULL, NULL, NULL, 1, '2021-12-06 00:00:00', NULL),
(68, 3, 'instructorD.instructorD', '["ROLE_INSTRUCTOR"]', '$2y$13$ekG00KdgO8YL2ZnVrowG1OAz4NeciZNanvJUVawfmVt39.gws2nae', 'instructorD', 'instructorD', 'instructorD', 'yonytesfaye1@gmail.com', 1, '2021-11-06 16:17:12', NULL, NULL, NULL, 0, '2021-12-06 00:00:00', NULL),
(69, 2, 'directorA.directorA', '["ROLE_DIRECTOR"]', '$2y$13$sOOmdCf8WP30G1ihuAA/jOtdN2iLVmZPnW20aTHcVg8BEUNEhrQ9u', 'directorA', 'directorA', 'directorA', 'yonytesfaye@gmail.com1', 1, '2021-11-06 16:19:24', NULL, NULL, NULL, 0, '2021-12-06 00:00:00', NULL),
(70, 2, 'directorB.directorB', '["ROLE_DIRECTOR"]', '$argon2id$v=19$m=65536,t=4,p=1$6rEhn40MYSzkTQsInaARbg$rvKDorL84JN8WguJflC+IJ71rnoLkuW00kuhPXpZpYM', 'directorB', 'directorB', 'directorB', 'yonas.tesfaye1@aastsdfsdfu.edu.et', 1, '2021-11-06 13:39:18', NULL, NULL, NULL, 0, '2021-12-06 00:00:00', NULL),
(71, 4, 'studentD.studentD', '["ROLE_STUDENT"]', '$2y$13$sOOmdCf8WP30G1ihuAA/jOtdN2iLVmZPnW20aTHcVg8BEUNEhrQ9u', 'studentD', 'studentD', 'studentD', 'yonytesfaye@gmail.com', 1, '2021-12-05 10:11:20', NULL, NULL, NULL, 1, '2021-12-06 00:00:00', NULL),
(72, 2, 'testuser.testuser', '["ROLE_DIRECTOR"]', '$2y$13$sVqNOJCNg3xsRQO/SrN6E.1GDdFylXDEX.6n14Kh8.932qAskQfzC', 'testuser', 'testuser', 'testuser', 'tadesseamsalu@gmail.com', 1, '2021-12-04 08:44:43', NULL, NULL, NULL, 0, '2021-12-04 06:59:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `id` int NOT NULL,
  `registered_by_id` int NOT NULL,
  `updated_by_id` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` (`id`, `registered_by_id`, `updated_by_id`, `name`, `description`, `created_at`, `updated_at`, `is_active`) VALUES
(1, 32, 32, 'Admin', 'Administrators', '2021-10-26 17:25:02', '2021-11-06 09:05:07', 1),
(2, 32, NULL, 'Director', 'Directors', '2021-10-30 09:26:05', NULL, 1),
(3, 32, NULL, 'Instructor', 'Instructors', '2021-11-03 14:24:59', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_group_permission`
--

CREATE TABLE `user_group_permission` (
  `user_group_id` int NOT NULL,
  `permission_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_group_permission`
--

INSERT INTO `user_group_permission` (`user_group_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24),
(1, 29),
(1, 30),
(1, 31),
(1, 32),
(1, 33),
(1, 34),
(1, 35),
(1, 36),
(1, 37),
(1, 38),
(1, 39),
(1, 40),
(1, 41),
(1, 42),
(1, 43),
(1, 44),
(1, 45),
(1, 46),
(1, 47),
(1, 48),
(1, 53),
(1, 54),
(1, 55),
(1, 56),
(1, 61),
(1, 62),
(1, 63),
(1, 64),
(1, 73),
(1, 74),
(1, 75),
(1, 76),
(1, 77),
(1, 78),
(1, 79),
(1, 80),
(1, 81),
(1, 82),
(1, 83),
(1, 84),
(1, 89),
(1, 90),
(1, 91),
(1, 92),
(1, 93),
(1, 94),
(1, 95),
(1, 96),
(1, 97),
(1, 98),
(1, 99),
(1, 100),
(1, 102),
(1, 108),
(1, 109),
(1, 110),
(1, 111),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 17),
(2, 18),
(2, 19),
(2, 20),
(2, 21),
(2, 22),
(2, 23),
(2, 24),
(2, 29),
(2, 30),
(2, 31),
(2, 32),
(2, 33),
(2, 34),
(2, 35),
(2, 36),
(2, 53),
(2, 54),
(2, 55),
(2, 56),
(2, 61),
(2, 62),
(2, 63),
(2, 64),
(2, 97),
(2, 98),
(2, 102),
(3, 5),
(3, 6),
(3, 7),
(3, 8),
(3, 37),
(3, 38),
(3, 39),
(3, 40),
(3, 41),
(3, 42),
(3, 43),
(3, 44),
(3, 45),
(3, 46),
(3, 47),
(3, 48),
(3, 93),
(3, 94),
(3, 95),
(3, 96),
(3, 99),
(3, 108),
(3, 109);

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `id` int NOT NULL,
  `country_id` int DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `academic_level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`id`, `name`, `description`) VALUES
(1, 'Admin', NULL),
(2, 'Director', NULL),
(3, 'Instructor', NULL),
(4, 'Student', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_user_group`
--

CREATE TABLE `user_user_group` (
  `user_id` int NOT NULL,
  `user_group_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_user_group`
--

INSERT INTO `user_user_group` (`user_id`, `user_group_id`) VALUES
(32, 1),
(57, 3),
(58, 3),
(59, 3),
(68, 3),
(69, 2),
(70, 2),
(72, 2);

-- --------------------------------------------------------

--
-- Table structure for table `verification`
--

CREATE TABLE `verification` (
  `id` int NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `verification_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `verification_expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `verification`
--

INSERT INTO `verification` (`id`, `email`, `verification_code`, `verification_expiry`) VALUES
(17, 'tadesseamsalu@gmail.com', '63727', '2021-11-05 20:35:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_level`
--
ALTER TABLE `academic_level`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `choices`
--
ALTER TABLE `choices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5CE96391E27F6BF` (`question_id`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FEC530A9579F4768` (`chapter_id`);

--
-- Indexes for table `content_type`
--
ALTER TABLE `content_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_169E6FB912469DE2` (`category_id`);

--
-- Indexes for table `course_category`
--
ALTER TABLE `course_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam`
--
ALTER TABLE `exam`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_38BBA6C6FFA0FC57` (`instructor_course_id`);

--
-- Indexes for table `instructor`
--
ALTER TABLE `instructor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_31FC43DDA76ED395` (`user_id`),
  ADD KEY `IDX_31FC43DD6081C3B0` (`academic_level_id`);

--
-- Indexes for table `instructor_course`
--
ALTER TABLE `instructor_course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6926B0E5591CC992` (`course_id`),
  ADD KEY `IDX_6926B0E58C4FC193` (`instructor_id`),
  ADD KEY `IDX_6926B0E56BF700BD` (`status_id`);

--
-- Indexes for table `instructor_course_chapter`
--
ALTER TABLE `instructor_course_chapter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_235BC3E1FFA0FC57` (`instructor_course_id`);

--
-- Indexes for table `instructor_course_status`
--
ALTER TABLE `instructor_course_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B6F7494E578D5E91` (`exam_id`);

--
-- Indexes for table `question_answer`
--
ALTER TABLE `question_answer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_DD80652D52E3274A` (`quation_answer_id`),
  ADD KEY `IDX_DD80652DCB944F1A` (`student_id`),
  ADD KEY `IDX_DD80652D8C4FC193` (`instructor_id`),
  ADD KEY `IDX_DD80652D591CC992` (`course_id`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A412FA92D20F7375` (`instructor_course_chapter_id`);

--
-- Indexes for table `quiz_choices`
--
ALTER TABLE `quiz_choices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_17943B991E27F6BF` (`question_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8CBC2533853CD175` (`quiz_id`);

--
-- Indexes for table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CE748AA76ED395` (`user_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B723AF33A76ED395` (`user_id`),
  ADD KEY `IDX_B723AF336081C3B0` (`academic_level_id`);

--
-- Indexes for table `student_answer`
--
ALTER TABLE `student_answer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_chapter`
--
ALTER TABLE `student_chapter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_14B700D6CB944F1A` (`student_id`),
  ADD KEY `IDX_14B700D6579F4768` (`chapter_id`);

--
-- Indexes for table `student_content_reaction`
--
ALTER TABLE `student_content_reaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2431CC6DCB944F1A` (`student_id`),
  ADD KEY `IDX_2431CC6D84A0A3ED` (`content_id`);

--
-- Indexes for table `student_course`
--
ALTER TABLE `student_course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_98A8B739CB944F1A` (`student_id`),
  ADD KEY `IDX_98A8B739FFA0FC57` (`instructor_course_id`);

--
-- Indexes for table `student_question`
--
ALTER TABLE `student_question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_57C05D5CB944F1A` (`student_id`),
  ADD KEY `IDX_57C05D51E27F6BF` (`question_id`);

--
-- Indexes for table `student_quiz`
--
ALTER TABLE `student_quiz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9B31814ACB944F1A` (`student_id`),
  ADD KEY `IDX_9B31814A853CD175` (`quiz_id`);

--
-- Indexes for table `system_setting`
--
ALTER TABLE `system_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `termsandconditions`
--
ALTER TABLE `termsandconditions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD KEY `IDX_8D93D6499D419299` (`user_type_id`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8F02BF9D27E92E18` (`registered_by_id`),
  ADD KEY `IDX_8F02BF9D896DBBDE` (`updated_by_id`);

--
-- Indexes for table `user_group_permission`
--
ALTER TABLE `user_group_permission`
  ADD PRIMARY KEY (`user_group_id`,`permission_id`),
  ADD KEY `IDX_4A91B1C51ED93D47` (`user_group_id`),
  ADD KEY `IDX_4A91B1C5FED90CCA` (`permission_id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D95AB405F92F3E70` (`country_id`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_user_group`
--
ALTER TABLE `user_user_group`
  ADD PRIMARY KEY (`user_id`,`user_group_id`),
  ADD KEY `IDX_28657971A76ED395` (`user_id`),
  ADD KEY `IDX_286579711ED93D47` (`user_group_id`);

--
-- Indexes for table `verification`
--
ALTER TABLE `verification`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_level`
--
ALTER TABLE `academic_level`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `choices`
--
ALTER TABLE `choices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `content_type`
--
ALTER TABLE `content_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `course_category`
--
ALTER TABLE `course_category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `exam`
--
ALTER TABLE `exam`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `instructor`
--
ALTER TABLE `instructor`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `instructor_course`
--
ALTER TABLE `instructor_course`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `instructor_course_chapter`
--
ALTER TABLE `instructor_course_chapter`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `instructor_course_status`
--
ALTER TABLE `instructor_course_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;
--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `question_answer`
--
ALTER TABLE `question_answer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `quiz_choices`
--
ALTER TABLE `quiz_choices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;
--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `student_answer`
--
ALTER TABLE `student_answer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `student_chapter`
--
ALTER TABLE `student_chapter`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `student_content_reaction`
--
ALTER TABLE `student_content_reaction`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `student_course`
--
ALTER TABLE `student_course`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `student_question`
--
ALTER TABLE `student_question`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `student_quiz`
--
ALTER TABLE `student_quiz`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `system_setting`
--
ALTER TABLE `system_setting`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `termsandconditions`
--
ALTER TABLE `termsandconditions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `verification`
--
ALTER TABLE `verification`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `choices`
--
ALTER TABLE `choices`
  ADD CONSTRAINT `FK_5CE96391E27F6BF` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`);

--
-- Constraints for table `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `FK_FEC530A9579F4768` FOREIGN KEY (`chapter_id`) REFERENCES `instructor_course_chapter` (`id`);

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `FK_169E6FB912469DE2` FOREIGN KEY (`category_id`) REFERENCES `course_category` (`id`);

--
-- Constraints for table `exam`
--
ALTER TABLE `exam`
  ADD CONSTRAINT `FK_38BBA6C6FFA0FC57` FOREIGN KEY (`instructor_course_id`) REFERENCES `instructor_course` (`id`);

--
-- Constraints for table `instructor`
--
ALTER TABLE `instructor`
  ADD CONSTRAINT `FK_31FC43DD6081C3B0` FOREIGN KEY (`academic_level_id`) REFERENCES `academic_level` (`id`),
  ADD CONSTRAINT `FK_31FC43DDA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `instructor_course`
--
ALTER TABLE `instructor_course`
  ADD CONSTRAINT `FK_6926B0E5591CC992` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `FK_6926B0E56BF700BD` FOREIGN KEY (`status_id`) REFERENCES `instructor_course_status` (`id`),
  ADD CONSTRAINT `FK_6926B0E58C4FC193` FOREIGN KEY (`instructor_id`) REFERENCES `instructor` (`id`);

--
-- Constraints for table `instructor_course_chapter`
--
ALTER TABLE `instructor_course_chapter`
  ADD CONSTRAINT `FK_235BC3E1FFA0FC57` FOREIGN KEY (`instructor_course_id`) REFERENCES `instructor_course` (`id`);

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `FK_B6F7494E578D5E91` FOREIGN KEY (`exam_id`) REFERENCES `exam` (`id`);

--
-- Constraints for table `question_answer`
--
ALTER TABLE `question_answer`
  ADD CONSTRAINT `FK_DD80652D52E3274A` FOREIGN KEY (`quation_answer_id`) REFERENCES `question_answer` (`id`),
  ADD CONSTRAINT `FK_DD80652D591CC992` FOREIGN KEY (`course_id`) REFERENCES `instructor_course` (`id`),
  ADD CONSTRAINT `FK_DD80652D8C4FC193` FOREIGN KEY (`instructor_id`) REFERENCES `instructor` (`id`),
  ADD CONSTRAINT `FK_DD80652DCB944F1A` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`);

--
-- Constraints for table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `FK_A412FA92D20F7375` FOREIGN KEY (`instructor_course_chapter_id`) REFERENCES `instructor_course_chapter` (`id`);

--
-- Constraints for table `quiz_choices`
--
ALTER TABLE `quiz_choices`
  ADD CONSTRAINT `FK_17943B991E27F6BF` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`id`);

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `FK_8CBC2533853CD175` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`);

--
-- Constraints for table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `FK_B723AF336081C3B0` FOREIGN KEY (`academic_level_id`) REFERENCES `academic_level` (`id`),
  ADD CONSTRAINT `FK_B723AF33A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `student_chapter`
--
ALTER TABLE `student_chapter`
  ADD CONSTRAINT `FK_14B700D6579F4768` FOREIGN KEY (`chapter_id`) REFERENCES `instructor_course_chapter` (`id`),
  ADD CONSTRAINT `FK_14B700D6CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`);

--
-- Constraints for table `student_content_reaction`
--
ALTER TABLE `student_content_reaction`
  ADD CONSTRAINT `FK_2431CC6D84A0A3ED` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`),
  ADD CONSTRAINT `FK_2431CC6DCB944F1A` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`);

--
-- Constraints for table `student_course`
--
ALTER TABLE `student_course`
  ADD CONSTRAINT `FK_98A8B739CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`),
  ADD CONSTRAINT `FK_98A8B739FFA0FC57` FOREIGN KEY (`instructor_course_id`) REFERENCES `instructor_course` (`id`);

--
-- Constraints for table `student_question`
--
ALTER TABLE `student_question`
  ADD CONSTRAINT `FK_57C05D51E27F6BF` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`id`),
  ADD CONSTRAINT `FK_57C05D5CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`);

--
-- Constraints for table `student_quiz`
--
ALTER TABLE `student_quiz`
  ADD CONSTRAINT `FK_9B31814A853CD175` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`),
  ADD CONSTRAINT `FK_9B31814ACB944F1A` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D6499D419299` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`id`);

--
-- Constraints for table `user_group`
--
ALTER TABLE `user_group`
  ADD CONSTRAINT `FK_8F02BF9D27E92E18` FOREIGN KEY (`registered_by_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_8F02BF9D896DBBDE` FOREIGN KEY (`updated_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_group_permission`
--
ALTER TABLE `user_group_permission`
  ADD CONSTRAINT `FK_4A91B1C51ED93D47` FOREIGN KEY (`user_group_id`) REFERENCES `user_group` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_4A91B1C5FED90CCA` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `FK_D95AB405F92F3E70` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`);

--
-- Constraints for table `user_user_group`
--
ALTER TABLE `user_user_group`
  ADD CONSTRAINT `FK_286579711ED93D47` FOREIGN KEY (`user_group_id`) REFERENCES `user_group` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_28657971A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
