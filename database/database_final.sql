-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 12, 2022 at 07:56 PM
-- Server version: 5.7.33
-- PHP Version: 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
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
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL
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
  `id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `letter` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `resource` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resource_names` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `chapter_id`, `title`, `content`, `filename`, `video_link`, `description`, `resource`, `resource_names`) VALUES
(1, 1, 'introduction', NULL, NULL, 'https://www.youtube.com/embed/3vcgOkTOgaU', NULL, NULL, NULL),
(2, 2, 'introduction', NULL, NULL, 'https://www.youtube.com/embed/ad79nYk2keg', NULL, NULL, NULL),
(3, 3, 'Introduction to AI', NULL, NULL, NULL, NULL, 'History-of-AI-and-Foundational-Concepts-1-61de7995955cc.pptx', 'History of AI and Foundational Concepts_1.pptx'),
(4, 4, 'introduction', NULL, NULL, NULL, NULL, 'History-of-AI-and-Foundational-Concepts-2-61de79b26d5ba.pptx', 'History of AI and Foundational Concepts_2.pptx'),
(5, 5, 'Basics of Intelligent Systems', NULL, NULL, NULL, NULL, 'History-of-AI-and-Foundational-Concepts-3-61de79dd3b1fd.pptx', 'History of AI and Foundational Concepts_3.pptx'),
(6, 6, 'Knowledge Representation', NULL, NULL, NULL, NULL, 'History-of-AI-and-Foundational-Concepts-4-61de79fbad61a.pptx', 'History of AI and Foundational Concepts_4.pptx'),
(7, 7, 'Review', NULL, NULL, NULL, NULL, 'Module-1-review-61de7acb6d103.docx', 'Module 1_review.docx'),
(8, 8, 'Summary of key issues', NULL, NULL, NULL, NULL, 'The-Ethics-of-AI-Data-Security-and-Privacy-1-61de7c0a087b4.pptx', 'The Ethics of AI, Data Security and Privacy_1.pptx'),
(9, 9, 'Bias in datasets', NULL, NULL, NULL, NULL, 'The-Ethics-of-AI-Data-Security-and-Privacy-2-61de7c2263131.pptx', 'The Ethics of AI, Data Security and Privacy_2.pptx'),
(10, 10, 'Implication of data security', NULL, NULL, NULL, NULL, 'The-Ethics-of-AI-Data-Security-and-Privacy-3-61de7c3dc1a1b.pptx', 'The Ethics of AI, Data Security and Privacy_3.pptx'),
(11, 11, 'Importance of data privacy', NULL, NULL, NULL, NULL, 'The-Ethics-of-AI-Data-Security-and-Privacy-4-61de7c6001996.pptx', 'The Ethics of AI, Data Security and Privacy_4.pptx'),
(12, 12, 'Module review', NULL, NULL, NULL, NULL, 'Module2-review-61de7cb9e02fd.docx', 'Module2_review.docx'),
(13, 13, 'Rule based systems and machine learning', NULL, NULL, NULL, NULL, 'Algorithms-and-Machine-Learning-1-61de7db5c641f.pptx', 'Algorithms and Machine Learning_1.pptx'),
(14, 14, 'General problem-solving approaches in AI', NULL, NULL, NULL, NULL, 'Algorithms-and-Machine-Learning-2-61de7dda12efe.pptx', 'Algorithms and Machine Learning_2.pptx'),
(15, 15, 'Machine learning problems', NULL, NULL, NULL, NULL, 'Algorithms-and-Machine-Learning-3-61de7e0d01b29.pptx', 'Algorithms and Machine Learning_3.pptx'),
(16, 16, 'Programming languages and architecture for AI', NULL, NULL, NULL, NULL, 'Algorithms-and-Machine-Learning-4-61de7e332e21b.pptx', 'Algorithms and Machine Learning_4.pptx'),
(17, 17, 'Module review', NULL, NULL, NULL, NULL, 'Module-3-review-61de7ee035830.docx', 'Module 3_review.docx'),
(18, 18, 'Unsupervised learning and reinforcement learning techniques', NULL, NULL, NULL, NULL, 'Neural-Networks-and-Deep-Learning-1-61de8132ae8e0.pptx', 'Neural Networks and Deep Learning_1.pptx'),
(19, 19, 'Constitutional neural networks in computer vision', NULL, NULL, NULL, NULL, 'Neural-Networks-and-Deep-Learning-2-61de815465d20.pptx', 'Neural Networks and Deep Learning_2.pptx'),
(20, 20, 'recurrent neural networks in natural language processing', NULL, NULL, NULL, NULL, 'Neural-Networks-and-Deep-Learning-3-61de81738154d.pptx', 'Neural Networks and Deep Learning_3.pptx'),
(21, 21, 'Trends and recent developments', NULL, NULL, NULL, NULL, 'Neural-Networks-and-Deep-Learning-4-61de818f6df06.pptx', 'Neural Networks and Deep Learning_4.pptx'),
(22, 22, 'Module review', NULL, NULL, NULL, NULL, 'Module-4-review-61de81e31e42c.docx', 'Module 4_review.docx'),
(23, 23, 'Probability and its application in AI', NULL, NULL, NULL, NULL, 'Data-Analysis-and-Visualization-1-61de8c114ee9d.pptx', 'Data Analysis and Visualization_1.pptx'),
(24, 24, 'Common approach in data science', NULL, NULL, NULL, NULL, 'Data-Analysis-and-Visualization-2-61de8c2932e55.pptx', 'Data Analysis and Visualization_2.pptx'),
(25, 25, 'Data analysis using R programming language', NULL, NULL, NULL, NULL, 'Data-Analysis-and-Visualization-3-61de8c4677136.pptx', 'Data Analysis and Visualization_3.pptx'),
(26, 26, 'Exploration of data visualization tools and techniques', NULL, NULL, NULL, NULL, 'Data-Analysis-and-Visualization-4-61de8c6cd7635.pptx', 'Data Analysis and Visualization_4.pptx'),
(27, 27, 'Module review', NULL, NULL, NULL, NULL, 'Module-5-review-61de8ce4049c5.docx', 'Module 5_review.docx'),
(28, 28, 'Practical AI applications', NULL, NULL, NULL, NULL, 'Case-Studies-and-Practical-Applications-of-AI-1-61de8d8dd9852.pptx', 'Case Studies and Practical Applications of AI_1.pptx'),
(29, 29, 'Practical appilications of AI in different sectors', NULL, NULL, NULL, NULL, 'Case-Studies-and-Practical-Applications-of-AI-2-61de8dac31bac.pptx', 'Case Studies and Practical Applications of AI_2.pptx'),
(30, 30, 'Module review', NULL, NULL, NULL, NULL, 'Module-6-review-61de8df6699e4.docx', 'Module 6_review.docx');

-- --------------------------------------------------------

--
-- Table structure for table `content_type`
--

CREATE TABLE `content_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci
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
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `status` int(11) NOT NULL
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
(16, 5, 'Introduction to emerging technologies', 'IET101', '3', NULL, 1),
(17, 5, 'Neural Networks and Deep Learning', 'NNDL101', '3', NULL, 1),
(18, 5, 'Data Analysis and Visualization', 'DAV101', '3', NULL, 1),
(19, 5, 'Case Studies and Practical Applications of AI', 'PAAI', '3', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `course_category`
--

CREATE TABLE `course_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci
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
  `id` int(11) NOT NULL,
  `instructor_course_id` int(11) DEFAULT NULL,
  `instruction` longtext COLLATE utf8mb4_unicode_ci,
  `percentage` double DEFAULT NULL,
  `passvalue` double DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

CREATE TABLE `instructor` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `academic_level_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructor`
--

INSERT INTO `instructor` (`id`, `user_id`, `academic_level_id`) VALUES
(4, 57, NULL),
(5, 58, NULL),
(6, 59, NULL),
(8, 68, NULL),
(9, 74, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `instructor_course`
--

CREATE TABLE `instructor_course` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `duration` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructor_course`
--

INSERT INTO `instructor_course` (`id`, `course_id`, `instructor_id`, `status_id`, `created_at`, `active`, `duration`) VALUES
(11, 8, 4, NULL, '2021-11-05 12:12:07', 1, NULL),
(12, 9, 5, NULL, '2021-11-05 12:12:46', 1, NULL),
(13, 10, 6, NULL, '2021-11-05 12:13:17', 1, NULL),
(15, 12, 6, NULL, '2021-11-05 12:24:19', 1, NULL),
(16, 13, 6, NULL, '2021-11-05 12:24:50', 1, NULL),
(17, 14, 6, NULL, '2021-11-05 12:25:30', 1, NULL),
(19, 16, 6, NULL, '2021-11-06 13:16:17', 1, NULL),
(20, 17, 5, NULL, '2022-01-12 09:13:21', 1, NULL),
(21, 18, 5, NULL, '2022-01-12 09:13:52', 1, NULL),
(22, 19, 5, NULL, '2022-01-12 09:14:37', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `instructor_course_chapter`
--

CREATE TABLE `instructor_course_chapter` (
  `id` int(11) NOT NULL,
  `instructor_course_id` int(11) NOT NULL,
  `chapter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `topic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructor_course_chapter`
--

INSERT INTO `instructor_course_chapter` (`id`, `instructor_course_id`, `chapter`, `created_at`, `topic`) VALUES
(1, 19, NULL, '2021-12-09 08:43:29', 'chapter1'),
(2, 19, NULL, '2021-12-09 08:43:36', 'chapter 2'),
(3, 15, NULL, '2021-12-17 07:27:49', 'Unit 1: Evolution of Artificial Intelligence and Trends'),
(4, 15, NULL, '2022-01-12 08:44:09', 'Unit 2: General Problem-Solving Approaches'),
(5, 15, NULL, '2022-01-12 08:44:37', 'Unit 3: Basics of Intelligent Systems'),
(6, 15, NULL, '2022-01-12 08:44:56', 'Unit 4: Knowledge Representation'),
(7, 15, NULL, '2022-01-12 08:50:08', 'Unit 4: Review'),
(8, 16, NULL, '2022-01-12 08:56:35', 'Unit 1: Summary of Key Issues'),
(9, 16, NULL, '2022-01-12 08:56:53', 'Unit 2: Bias in Datasets'),
(10, 16, NULL, '2022-01-12 08:57:07', 'Unit 3: Implication on Data Security'),
(11, 16, NULL, '2022-01-12 08:57:26', 'Unit 4: Importance of Data Privacy'),
(12, 16, NULL, '2022-01-12 08:57:39', 'Unit 5: Review'),
(13, 17, NULL, '2022-01-12 09:03:46', 'Unit 1: Rules-Based Systems and Machine Learning'),
(14, 17, NULL, '2022-01-12 09:04:03', 'Unit 2: General Problem-Solving Approaches in AI'),
(15, 17, NULL, '2022-01-12 09:04:21', 'Unit 3: Machine Learning Problems'),
(16, 17, NULL, '2022-01-12 09:04:39', 'Unit 4: Programming Languages and Architecture for AI'),
(17, 17, NULL, '2022-01-12 09:04:51', 'Unit 5: Review'),
(18, 20, NULL, '2022-01-12 09:16:50', 'Unit 1: Unsupervised Learning and Reinforcement Learning Techniques'),
(19, 20, NULL, '2022-01-12 09:18:27', 'Unit 2: Constitutional Neural Networks in Computer Vision'),
(20, 20, NULL, '2022-01-12 09:18:44', 'Unit 3:  Recurrent Neural Networks In Natural Language Processing'),
(21, 20, NULL, '2022-01-12 09:19:15', 'Unit 4: Trends and Recent Developments'),
(22, 20, NULL, '2022-01-12 09:19:25', 'Unit 5: Review'),
(23, 21, NULL, '2022-01-12 10:04:05', 'Unit 1: Probability and its Applications in AI'),
(24, 21, NULL, '2022-01-12 10:04:26', 'Unit 2: Common Approach in Data Science'),
(25, 21, NULL, '2022-01-12 10:05:28', 'Unit 3: Data Analysis using R Programming Language'),
(26, 21, NULL, '2022-01-12 10:05:48', 'Unit 4: Exploration of Data Visualization Tools and Techniques'),
(27, 21, NULL, '2022-01-12 10:05:58', 'Unit 5: Review'),
(28, 22, NULL, '2022-01-12 10:11:27', 'Unit 1: Practical AI Applications and Case Studies Relevant to the SDGs and the African Development Agenda'),
(29, 22, NULL, '2022-01-12 10:11:48', 'Unit 2: Practical Applications of AI in Different Sectors'),
(30, 22, NULL, '2022-01-12 10:12:29', 'Unit 3: Review');

-- --------------------------------------------------------

--
-- Table structure for table `instructor_course_status`
--

CREATE TABLE `instructor_course_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `actor_id` int(11) NOT NULL,
  `original` longtext COLLATE utf8mb4_unicode_ci,
  `modified` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modified_entity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `log`
--

INSERT INTO `log` (`id`, `actor_id`, `original`, `modified`, `created_at`, `action`, `modified_entity`) VALUES
(1, 32, '{\"id\":6,\"name\":\"sdf\",\"description\":\"asdf\"}', '\"\"', '2022-01-12 18:53:04', 'create', 'academic level'),
(2, 32, '{\"id\":6,\"name\":\"sdf\",\"description\":\"asdf\"}', '\"\"', '2022-01-12 18:53:08', 'delete', 'academic level'),
(3, 59, '{\"id\":31,\"title\":\"tes\",\"content\":\"<p>asdfasdf<\\/p>\",\"filename\":null,\"videoLink\":null,\"description\":null,\"resource\":null,\"resourceNames\":null}', '\"\"', '2022-01-12 19:02:54', 'create', 'content');

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci
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
  `id` int(11) NOT NULL,
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
  `id` int(11) NOT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `question` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question_answer`
--

CREATE TABLE `question_answer` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `quation_answer_id` int(11) DEFAULT NULL,
  `course_id` int(11) NOT NULL,
  `question` varchar(5000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notification` tinyint(1) NOT NULL,
  `answer` varchar(5000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `answered_at` datetime DEFAULT NULL,
  `video_answer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question_answer`
--

INSERT INTO `question_answer` (`id`, `student_id`, `instructor_id`, `quation_answer_id`, `course_id`, `question`, `notification`, `answer`, `created_at`, `answered_at`, `video_answer`, `updated_at`) VALUES
(1, 16, 6, NULL, 13, '<p>what is this?</p>', 1, NULL, '2021-12-07 20:25:40', NULL, NULL, NULL),
(2, 18, 5, NULL, 21, '<p>gfgdfgdf</p>', 1, NULL, '2022-01-12 14:13:21', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `id` int(11) NOT NULL,
  `instructor_course_chapter_id` int(11) NOT NULL,
  `instruction` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `percentage` double DEFAULT NULL,
  `passvalue` double DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int(11) DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL,
  `active_questions` int(11) NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `no_of_retake_allowed` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`id`, `instructor_course_chapter_id`, `instruction`, `percentage`, `passvalue`, `name`, `duration`, `is_mandatory`, `active_questions`, `active`, `no_of_retake_allowed`) VALUES
(1, 1, 'choice', 1, 1, 'intro', 5, 1, 1, 1, 2),
(2, 2, 'choice', 1, 1, 'chapt 2 quize', 5, 1, 2, 1, 2),
(3, 7, 'Multiple choice', 100, 51, 'Module review questions', 100, 1, 50, 1, 2),
(4, 12, 'Multiple choice', 100, 51, 'Module review questions', 100, 1, 50, 1, 2),
(5, 17, 'Multiple choice', 100, 51, 'Module review questions', 100, 1, 50, 1, 2),
(6, 22, 'Multiple choice', 100, 51, 'Module review questions', 100, 1, 50, 1, 2),
(7, 27, 'Multiple choice', 100, 51, 'Module review questions', 100, 1, 50, 1, 2),
(8, 30, 'Multiple choices', 100, 51, 'Module review questions', 100, 1, 50, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_choices`
--

CREATE TABLE `quiz_choices` (
  `id` int(11) NOT NULL,
  `letter` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_choices`
--

INSERT INTO `quiz_choices` (`id`, `letter`, `description`, `question_id`) VALUES
(1, 'A', 'new technology', 1),
(2, 'B', 'updated technology', 1),
(3, 'C', 'all', 1),
(4, 'A', 'industrial revolution', 2),
(5, 'B', 'index review', 2),
(6, 'C', 'none', 2),
(7, 'A', 'Artificial intelligence', 3),
(8, 'B', 'Automatic intelligence', 3),
(9, 'A', 'Enstiene', 4),
(10, 'B', 'turing', 4),
(11, 'A', 'Artificial intelligence', 5),
(12, 'B', '\r\nMachine language programming\r\n\r\n', 5),
(13, 'C', 'Programmable memory\r\n', 5),
(14, 'D', 'Machine learning', 5),
(15, 'A', '50', 6),
(16, 'B', '25', 6),
(17, 'C', '45', 6),
(18, 'D', 'noe', 6),
(19, 'A', 'It pursues creating computers or machines as intelligent as human beings.\r\n\r\n', 7),
(20, 'B', 'It is the science and engineering of making intelligent machines, especially intelligent computer programs. \r\n', 7),
(21, 'C', 'It is related to the similar task of using computers to understand human intelligence\r\n', 7),
(22, 'D', 'AI does confine itself to biologically observable methods\r\n', 7),
(23, 'E', 'None', 7),
(24, 'A', 'True\r\n', 8),
(25, 'B', 'False', 8),
(26, 'A', 'True ', 9),
(27, 'B', 'False', 9),
(28, 'A', 'Dartmouth College in New Hampshire\r\n', 10),
(29, 'B', 'MIT in Cambridge, Massachusetts\r\n', 10),
(30, 'C', 'Caltech in Pasadena, California\r\n', 10),
(31, 'D', 'None', 10),
(32, 'A', 'Marvin Minsky\r\n', 11),
(33, 'B', 'Nathaniel Rochester\r\n', 11),
(34, 'C', 'John McCarthy\r\n', 11),
(35, 'D', 'Claude Shanon\r\n', 11),
(36, 'E', 'None', 11),
(37, 'A', 'Natural languages\r\n', 12),
(38, 'B', 'Play games\r\n', 12),
(39, 'C', 'Recognize images of objects and prove mathematical theorems\r\n', 12),
(40, 'D', 'ALL', 12),
(41, 'A', 'True\r\n', 13),
(42, 'B', 'False', 13),
(43, 'A', 'Studying how the human brain thinks\r\n', 14),
(44, 'B', 'Studying how humans learn, decide,\r\n', 14),
(45, 'C', 'Studying how humans work while trying to solve a problem\r\n', 14),
(46, 'D', 'All', 14),
(47, 'A', 'Big data\r\n', 15),
(48, 'B', 'Advancements in computer processing speed and new chip architectures\r\n', 15),
(49, 'C', 'Cloud computing\r\n', 15),
(50, 'D', 'A and C', 15),
(51, 'E', 'All', 15),
(52, 'A', 'True\r\n', 16),
(53, 'B', 'False', 16),
(54, 'A', 'True\r\n', 17),
(55, 'B', 'False', 17),
(56, 'A', 'True\r\n', 18),
(57, 'B', 'False', 18),
(58, 'A', 'the computational part of the ability to achieve goals\r\n', 19),
(59, 'B', 'relates to tasks involving higher mental processes,\r\n', 19),
(60, 'C', 'creativity, solving problems\r\n', 19),
(61, 'D', 'language processing, knowledge\r\n', 19),
(62, 'E', 'C and D\r\n', 19),
(63, 'F', 'None', 19),
(64, 'A', 'True\r\n', 20),
(65, 'B', 'False', 20),
(66, 'A', 'True\r\n', 21),
(67, 'B', 'False', 21),
(68, 'A', 'True\r\n', 22),
(69, 'B', 'False', 22),
(70, 'A', 'Problem-solving,\r\n', 23),
(71, 'B', 'Speech recognition,\r\n', 23),
(72, 'C', 'Natural language processing,\r\n', 23),
(73, 'D', 'Computer vision,\r\n', 23),
(74, 'E', ' Expert systems\r\n', 23),
(75, 'F', 'D and E\r\n', 23),
(76, 'G', 'None', 23),
(77, 'A', 'Noisy data', 24),
(78, 'B', 'Incomplete coverage of the problem domain', 24),
(79, 'C', 'Imperfect models.', 24),
(80, 'D', 'All', 24),
(81, 'A', 'Logic AI\r\n', 25),
(82, 'B', 'Search\r\n', 25),
(83, 'C', 'Pattern recognition\r\n', 25),
(84, 'D', 'Common sense reasoning and knowledge\r\n', 25),
(85, 'E', 'None', 25),
(86, 'A', 'Ontology\r\n', 26),
(87, 'B', 'Epistemology\r\n', 26),
(88, 'C', 'Heuristics\r\n', 26),
(89, 'D', 'Genetic programming\r\n', 26),
(90, 'E', 'None', 26),
(91, 'A', 'True', 27),
(92, 'B', 'False', 27),
(93, 'A', 'Ontology\r\n', 28),
(94, 'B', 'Epistemology\r\n', 28),
(95, 'C', 'Heuristics\r\n', 28),
(96, 'D', 'Genetic programming\r\n', 28),
(97, 'E', 'None', 28),
(98, 'A', 'True\r\n', 29),
(99, 'B', '\r\nFalse', 29),
(100, 'A', 'Ontology\r\n', 30),
(101, 'B', 'Epistemology\r\n', 30),
(102, 'C', 'Heuristics\r\n', 30),
(103, 'D', 'Genetic programming\r\n', 30),
(104, 'E', 'None', 30),
(105, 'A', 'True', 31),
(106, 'B', 'False', 31),
(107, 'A', 'True\r\n', 32),
(108, 'B', 'False', 32),
(109, 'A', 'Proposition', 33),
(110, 'B', 'Random variable', 33),
(111, 'C', 'Atomic event', 33),
(112, 'D', 'Independent event', 33),
(113, 'A', 'True\r\n', 34),
(114, 'B', 'False', 34),
(115, 'E', 'Dependent event', 33);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `question` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `quiz_id`, `question`, `answer`) VALUES
(1, 1, '<p>what is emerging technology</p>', 'C'),
(2, 1, '<p>what is IR</p>', 'A'),
(3, 2, '<p>what is AI</p>', 'A'),
(4, 2, '<p>how contriburte for AI</p>', 'B'),
(5, 4, '<p><span style=\"font-size:11pt\"><span style=\"background-color:white\"><span style=\"font-family:Calibri,&quot;sans-serif&quot;\"><span style=\"font-size:12.0pt\"><span style=\"font-family:&quot;Times New Roman&quot;,&quot;serif&quot;\"><span style=\"color:#555555\">Machines that can do things like speech recognition, visual perception, and decision making are said to have what?</span></span></span></span></span></span></p>', 'A'),
(6, 3, '<p>how old are you&nbsp;</p>', 'A'),
(7, 3, '<p><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">Which one of the following is false about AI?</span></span></span></p>', 'D'),
(8, 3, '<p><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">The History of the various areas of Artificial Intelligence dated back from 1930.</span></span></span></p>', 'A'),
(9, 3, '<p><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">The year 1956 is considered to be the start of the topic of Artificial Intelligence.</span></span></span></p>', 'A'),
(10, 3, '<p><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">The first Artificial Intelligence conference, organized by John McCarthy, Marvin Minsky, Nathaniel Rochester and Claude Shanon at_____________________?</span></span></span></p>', 'A'),
(11, 3, '<p><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">The term AI was proposed by LISP developer named _______________?</span></span></span></p>', 'C'),
(12, 3, '<p><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">The current 5th generation computers can process?</span></span></span></p>', 'D'),
(13, 3, '<p><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">AI is the simulation of human intelligence on a machine, to make the machine efficient to identify and use the right piece of Knowledge at a given step of solving a problem.</span></span></span></p>\r\n\r\n<p>&nbsp;</p>', 'A'),
(14, 3, '<p><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">AI is accomplished by________________?</span></span></span></p>', 'D'),
(15, 3, '<p><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">AI has gained prominence recently due to ________________?</span></span></span></p>', 'E'),
(16, 7, '<p style=\"text-align:justify\"><span style=\"font-size:12.0000pt\"><span style=\"font-family:\'Times New Roman\'\">Statistics is the branch of mathematics that studies the possible outcomes of the given events together with the outcomes&rsquo; relative likelihoods and distributions</span></span></p>', 'B'),
(17, 7, '<p style=\"text-align:justify\"><span style=\"font-size:12.0000pt\"><span style=\"font-family:\'Times New Roman\'\">In common usage, the word &ldquo;probability&rdquo; is used to mean the chance that a particular event (or set of events) will occur expressed on a linear scale from 0 (impossibility) to 1 (certainty), also expressed as a percentage between 0 and 100%.</span></span></p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>', 'A'),
(18, 7, '<p style=\"text-align:justify\"><span style=\"font-size:12.0000pt\"><span style=\"font-family:\'Times New Roman\'\">The analysis of events governed by probability is called statistics.</span></span></p>', 'A'),
(19, 3, '<p><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">Which one of the following is not true about intelligence?</span></span></span></p>', 'F'),
(20, 3, '<p><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">The engineering-based goals of AI are to develop concepts, mechanisms and understand biological intelligent behavior that emphasis understanding intelligent behavior.</span></span></span></p>', 'B'),
(21, 7, '<p style=\"text-align:justify\"><span style=\"font-size:12.0000pt\"><span style=\"font-family:\'Times New Roman\'\">Probability is the branch of mathematics that studies the possible outcomes of the given events together with the outcomes&rsquo; relative likelihoods and distributions</span></span></p>', 'A'),
(22, 3, '<p><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">The science-based goal of AI is to develop concepts, theory, and practice of building intelligent machines that emphasize system building.</span></span></span></p>', 'B'),
(23, 3, '<p><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">Which one of the following is not the application of AI?</span></span></span></p>', 'G'),
(24, 7, '<p style=\"text-align:justify\"><span style=\"font-size:12.0000pt\"><span style=\"font-family:\'Times New Roman\'\">What are the three main sources of uncertainty in AI?</span></span></p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>', 'D'),
(25, 3, '<p><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">Which one of the following is not the branch of AI?</span></span></span></p>', 'E'),
(26, 3, '<p><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">______________ is a study of the kinds of knowledge that are required for solving problems in the world?</span></span></span></p>', 'B'),
(27, 7, '<p style=\"text-align:justify\"><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAZQAAAAiCAYAAAB4IS98AAAACXBIWXMAAA7EAAAOxAGVKw4bAAAILElEQVR4Xu2buY4kRRCGF7QuSJigBRuWB+B4AXgBJGwOn8tEHD6HjWDx2cUHfGCFDcPaHFoXAQ+wxNeqEDGxkXV1dXXXzJ9SqqqzMiMjvz8ys46ZK1eUREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAEREAERGA7BB4zV/+0fM/y+9txe5OeRtYv7TGCOXbmtNnDxcGmirtBRKMqHFNXadhJxMLJAhrzb/b7lVESjq/kwKcsHnPajPeorvmMFbOpcFQ6LIGlWA/ZqeKoalPVOyyB/61X/qzV9xL9sI6seRPW0qri2Kq7xLijjarvpfuYZe/BWa3mNfrEmt22fMvyA5Yf7s5v2HHJAHm8c+/3CW7OaTPWPOLfsUyw5fSvFfyRC/X7IASWYt1np4qjJ2w0xOKvYVRVvb44mQpkyFbfGKb2dVHqt5hVWjHmNXRt+UT/J6nh1RWj4aFOhE+7PgHyTlf2gh3ZcCjbN/1kBq5NNDKnzZguGPPrlv+ynMf2opX9bPnuGEOqsxeBpVgP2ani6Lp5zpNo1D/X64uTqQMfsjU0hqn9XYT6fcyyVj7eQ+va59PJarjmE0prpz8zhdjtAeiJ868s++uxH9N1xIzXmbA8Adzs2uQnno+s/O9g74tgr9WGPujXfaA//Iq+8VqNer909WK/+MOTyauWn7P8j2X3006vPG2ZsfPKD9/I3JHENIbDFB9jHzBgbBw9OQuOOeFL7Mu55GPWKtvx32M4tupkVi2tvK+KNTcxMUUdsf+u5Rgn1O2zU8UR/tMPOnvK9YbiZIpOQ7ZaYzjluAvoJp/G+Ilzk3nrc7GPWdbKHdhX1yFN+3w6dQ0nizS3AYJWiw3lcaH195BsAghHsMdvDX7dJzvXv+vq+rX4/cSDhwWDxAIeF8yqDTZZVNwHX0zxlYwtbHxm+U3LXK/G5+3y9xzvExv4E+3bz10a4jDWx2/MFr49aZm7LeyyyOFTxZ5NMC8wVrR46uPo8RDrVOPAqT4OXI/x4jrAPcaA2/CY8jb072nIThVHlGWeVb1WnMzRqWUrsthy3PkcDNI0T2P8RC05j2tRi1mllXOcq+tYTYd8OlUNm2IsfSFPYrdPeRQ31uOphkkeN5xcP/rJwoDQBIInymjf+vhftan6iH652JR5qsbnfXOMid9sWB7kVfDM4TDkY3Ljvs0av+ITS66/9G8fd+yTRT8+gVSsox9DWjnruKnnBSXbqPQYslPF0ZSyeNOUOec46tMp1422fAxbiTvX4Z4Noi9XN6o+7qyl/44bTItZpR92q/JWWUvX3GfWNF/38Zy0hmu98mKBf94yH+RjAg47Nk8YvGP2ekx+Auh7y3y0fsry3XCd7zD5m4QV7V6d5e8V3JW/Z5k2vJq6TsWQchv3wX2iKkF4zfJZ1w4btPu4++3X8/iqD3c04R0o/n8e7D1i5z90v8dymOJjZ/rcwf8gAD9JL1t2n3L9JSZ3tukcY5+UwcZ9y6yjjTFawRrdvg0NeXXFRI8xF2OKPqMeNB2yk+PI2+R4rOq14sRdnqJTn62txR36sG7wRzyeP7BzciyjTrUeWPFuvkctiWMYxT+SaDGrtMImHPfVdUjTlk8nreFV6KyQqu8nCMskZrLzQZ7k9Z61czaCnCo7sQ4bUVxk/dqXdsJif8Myd8NsYh6AuY33ERcgRGQR4vUWCbFJcdHLCxDXse0LV9dkd8AWd1VskqQcoGM5TPGx6+rcgf7xAz/5SyTGU3GnkU/ucwb2/JEnDTGBNpFNZh27HKMVrGNM+E3Ma52hKqbetmt5wRiyk+MI87kNZVW9Vpz4WKfo1Gdri3HnDOYe89zij2RIfvPGeYtZpRX1l9B1SNOWTyet4VpPKIjKguV3BWwYty3zp8NMbF/cfYEGGokFhg0AuKR8nbuPry1zp+pPCdTh9RZtyHwHIdHHWXfuh6qN94HPJPpg42NR8sUWu3nRYwEiURe7bps+OecRm8XS76qjL4yXjYe+3rKcx9niMMVHM1sm/KBfJlrr6aRsuEAhHIkB5wU7NpA3gu3MOnY7pJWz9nhinNxUoOWtYIj+6ZcjNw20i3oM2aniyMuIeY/Hvno5ToJ7u9MxOrn9ytZljTvXHoasNTBiLSIRb49a9rcPXPN5WmlFmyV1bWna0vGyargTy5O/C4zvQPmHRoQDXE4sqLxDpz5HfscUr+dXWDdTGyay28Jero/d3IYyFrHoQ/QVn9lMKPPk9bGP6J6ok8dRvRv1ev5RmPZDHKb6GNw6d+p28GvN5BydP5z4SzoWfU8V6+xjHwfGhE24ug7+hxbRDmUxPogbfrseY+xUcTS2rIqT1jiHdGrZuihxx/jIY5PHB/MZBmTO41xtMav0o9+qvCpr2XXf++Ze1faiaDhWO9XbIAE2LgJ77VRNjrV92FJ/x9LpUIzmjmfqhnIo/5ewO5fBEn0vYeM+/9f6hrKE87KxLAF/vOdVYHz9s2wvbWv5+0m75uW+cmydlqa/73g+XNqhI9jbl8ERXD7X5db9Pza/C9W/v0bisZ9XO8dK/orgmD4ca+xj+j0Vncb4OqbORRvPmDHnOltnsHX/sx76LQIiIAIiIAIiIAIiIAIiIAIiIAIiIAIiIAIiIAIiIAIiIAIiIAIiIAIiIAIiIAIiIAIiIAIiIAIiIAIiIAIiIAIiIAIiIAIicCQC/wGMy8yhFriYWgAAAABJRU5ErkJggg==\" style=\"height:34px; width:404px\" /></p>', 'A'),
(28, 3, '<p><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">_____________ is the study of the kinds of things that exist?</span></span></span></p>', 'A'),
(29, 7, '<p style=\"text-align:justify\"><span style=\"font-size:12.0000pt\"><span style=\"font-family:\'Times New Roman\'\">The fundamental idea of decision theory is that an agent is rational if and only if it chooses the action that yields the highest expected utility, averaged over all the possible outcomes of the action.</span></span></p>', 'A'),
(30, 3, '<p><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">_____________ is a way of trying to discover something or an idea embedded in a program?</span></span></span></p>\r\n\r\n<p>&nbsp;</p>', 'C'),
(31, 7, '<p style=\"text-align:justify\"><span style=\"font-size:12.0000pt\"><span style=\"font-family:\'Times New Roman\'\">The dependence on experience is reflected in the syntactic distinction between prior probability statements, which apply before any evidence is obtained,</span></span></p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;</p>', 'A'),
(32, 3, '<p><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">Heuristic functions are used in some approaches to search or to measure how far a node in a search tree seems to be from a goal.</span></span></span></p>', 'A'),
(33, 7, '<p style=\"text-align:justify\"><span style=\"font-size:12.0000pt\"><span style=\"font-family:\'Times New Roman\'\">____________ is a complete specification of the state of the world about which the agent is uncertain. It can be thought of as an assignment of particular values to all the variables of which the world is composed?</span></span></p>', 'C'),
(34, 3, '<p style=\"text-align:justify\"><span style=\"font-size:12pt\"><span style=\"font-family:Arial,sans-serif\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\">Genetic programming starts from a high-level statement of &#39;what needs to be done and automatically creates a computer program to solve the problem.</span></span></span></p>', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `academic_level_id` int(11) DEFAULT NULL,
  `student_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_updated` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `user_id`, `academic_level_id`, `student_id`, `profile_picture`, `profile_updated`) VALUES
(16, 62, 1, 'RM-02-21', 'IMG-2577-1-61bc2513a1453.jpg', 1),
(17, 63, 1, 'RM-03-21', NULL, 1),
(18, 64, 2, 'RM-04-21', NULL, 1),
(20, 71, 5, 'RM-05-21', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_answer`
--

CREATE TABLE `student_answer` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_chapter`
--

CREATE TABLE `student_chapter` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `pages_completed` int(11) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_chapter`
--

INSERT INTO `student_chapter` (`id`, `student_id`, `chapter_id`, `pages_completed`, `updated_at`) VALUES
(1, 16, 1, 1, '2021-12-17 07:45:13'),
(2, 16, 2, 1, '2021-12-17 07:45:34');

-- --------------------------------------------------------

--
-- Table structure for table `student_content_reaction`
--

CREATE TABLE `student_content_reaction` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `content_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `message` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_course`
--

CREATE TABLE `student_course` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `instructor_course_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` smallint(6) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `is_at_page` int(11) DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `teacher_notification` tinyint(1) NOT NULL,
  `director_notification` tinyint(1) NOT NULL,
  `qr_code` longtext COLLATE utf8mb4_unicode_ci,
  `qr_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_course`
--

INSERT INTO `student_course` (`id`, `student_id`, `instructor_course_id`, `created_at`, `status`, `active`, `is_at_page`, `completed_at`, `teacher_notification`, `director_notification`, `qr_code`, `qr_url`) VALUES
(1, 16, 15, '2021-12-17 07:33:11', 1, 0, NULL, NULL, 0, 1, NULL, NULL),
(2, 16, 19, '2021-12-17 07:34:40', 5, 1, NULL, '2021-12-17 07:45:48', 0, 1, 'iVBORw0KGgoAAAANSUhEUgAAAJYAAACWAQMAAAAGz+OhAAAABlBMVEX///8AAABVwtN+AAAACXBIWXMAAA7EAAAOxAGVKw4bAAABdUlEQVRIid2WIaLDMAxDzQJ9nbFeqdAsYYG9qmGYvpy22z5cPLSMPTB7lixP5FfeBgyzMgSuDsA/YA8RInQXVSdIszpMapMKcXf4N5jV3gT8fImxRHGFfIWdM0DjROX/XJbYqZHZ7jGCd93W2HysAZ0lXm+VlWalFX43JVeoJ1lpFKhgCJlg2ijFqFEFem8eVS6PJ1gZtdGU0TNmkSTjPGlyqwcceiuUYNzm0vuQ6kpL3nXXmdQ+OIa26yRnkQyLhTaTHcqUUMmzMiJzDjKhNT9h1IMN9sa0im+7NVpnaFECMw30qpFgG73HFa70S+hx5cE6Y8/h5uIxP3/+jmW2caJ90DXc3VAky7ga08+T4MzTDHsY/Uz/UZ6YwjRMhjGrWEMimCMNrrxaZ7PtIaZx3O48SLDI+9Dp8HkwRZIs7lFkwtnv2XOKzXs+ShPx+xplmcVYKfpT8yTjHkqNYx5CZVnMgCY6XucoxUKjCIoA/AuDLPuN9wegqUqEcNIpWgAAAABJRU5ErkJggg==', '10.2.30.81/certificate//2'),
(3, 16, 15, '2021-12-17 08:07:59', 1, 0, NULL, NULL, 0, 1, NULL, NULL),
(4, 16, 16, '2021-12-17 08:09:26', 1, 1, NULL, NULL, 0, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_question`
--

CREATE TABLE `student_question` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answered_at` datetime NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_question`
--

INSERT INTO `student_question` (`id`, `student_id`, `question_id`, `answer`, `answered_at`, `active`) VALUES
(1, 16, 1, 'C', '2021-12-17 07:45:29', 1),
(2, 16, 3, 'A', '2021-12-17 07:45:44', 1),
(3, 16, 4, 'B', '2021-12-17 07:45:48', 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_quiz`
--

CREATE TABLE `student_quiz` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `result` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trial` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_quiz`
--

INSERT INTO `student_quiz` (`id`, `student_id`, `quiz_id`, `created_at`, `end_time`, `result`, `trial`, `active`) VALUES
(1, 16, 1, '2021-12-17 07:45:23', '2021-12-17 07:50:23', '1', 0, 1),
(2, 16, 2, '2021-12-17 07:45:40', '2021-12-17 07:50:40', '1', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `system_setting`
--

CREATE TABLE `system_setting` (
  `id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_setting`
--

INSERT INTO `system_setting` (`id`, `code`, `value`, `description`) VALUES
(1, 'upload_size', '200', 'Upload size in MB'),
(2, 'show_un_answered_questions', '0', 'if this row is 1, at the end of a quiz, a student will see the list of questions that he didn\'t see because of time.'),
(4, 'terms', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'Terms of the Organization'),
(5, 'conditions', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 'Conditions of the Organization'),
(6, 'masters_id_prefix', 'MD', NULL),
(7, 'phd_id_prefix', 'PH', NULL),
(8, 'hd_id_prefix', 'HD', NULL),
(9, 'num_of_digits_student_id', '5', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `termsandconditions`
--

CREATE TABLE `termsandconditions` (
  `id` int(11) NOT NULL,
  `terms` longtext COLLATE utf8mb4_unicode_ci,
  `conditions` longtext COLLATE utf8mb4_unicode_ci
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
  `id` int(11) NOT NULL,
  `user_type_id` int(11) DEFAULT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `locale` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirm_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `sex` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user_type_id`, `username`, `roles`, `password`, `first_name`, `middle_name`, `last_name`, `email`, `is_active`, `last_login`, `locale`, `confirm_token`, `role`, `is_verified`, `created_at`, `updated_at`, `sex`) VALUES
(32, 1, 'admin.admin', '[\"ROLE_ADMIN\"]', '$2y$13$04HmczTjehS/I/1RJYNTX.y58tdZrkwHqud5me3cA6aB0nLO6U5Ee', 'admin', 'admin', 'admin', 'admin@gmail.com', 1, '2022-01-12 18:42:29', NULL, NULL, NULL, 0, '2021-12-06 00:00:00', NULL, 'M'),
(57, 3, 'instructor.instructor', '[\"ROLE_INSTRUCTOR\"]', '$2y$13$sVqNOJCNg3xsRQO/SrN6E.1GDdFylXDEX.6n14Kh8.932qAskQfzC', 'instructor', 'instructor', 'instructor', 'yonas.tesfaye1@aastu.edu.et', 1, '2022-01-12 08:14:18', NULL, NULL, NULL, 0, '2021-12-06 00:00:00', NULL, 'M'),
(58, 3, 'instructorB.instructorB', '[\"ROLE_INSTRUCTOR\"]', '$2y$13$QZzsFBJ1mlolTzyYQvVReehQ6Imn5Xm54Uw6rErFpLHWlsvQfZ0Y.', 'instructorB', 'instructorB', 'instructorB', 'kebe20003@gmail.com', 1, '2022-01-12 19:37:35', NULL, NULL, NULL, 0, '2021-12-06 00:00:00', NULL, 'M'),
(59, 3, 'instructorC.instructorC', '[\"ROLE_INSTRUCTOR\"]', '$2y$13$QZzsFBJ1mlolTzyYQvVReehQ6Imn5Xm54Uw6rErFpLHWlsvQfZ0Y.', 'instructorC', 'instructorC', 'instructorC', 'bereket.walle1@aastu.edu.et', 1, '2022-01-12 19:25:59', NULL, NULL, NULL, 0, '2021-12-06 00:00:00', NULL, 'F'),
(62, 4, 'studentA.studentA', '[\"ROLE_STUDENT\"]', '$2y$13$SChkQdQWRR02f6AR2JXWeubKLrFy74YQzL6NpFHw9siNP7c6Vp8ES', 'studentA', 'studentA', 'studentAA', 'lioulnebiye1@gmail.com', 1, '2022-01-12 18:10:18', NULL, NULL, NULL, 1, '2021-12-06 00:00:00', NULL, 'M'),
(63, 4, 'studentB.studentB', '[\"ROLE_STUDENT\"]', '$argon2id$v=19$m=65536,t=4,p=1$VviWr7rdo3LvzKtShTlcTA$JUPeFSBVE1ACmpECaukT1Xfa0KEfvXkz6UbNwQkSt6Y', 'studentB', 'studentB', 'studentB', 'lioulnebiye2@gmail.com', 1, NULL, NULL, NULL, NULL, 1, '2021-12-06 00:00:00', NULL, 'M'),
(64, 4, 'studentC.studentC', '[\"ROLE_STUDENT\"]', '$2y$13$8hZerp5cc/Wz9kpyTQx49u6LQEWFmPfUdapduRAcO2o3h2KL.Ei.O', 'studentC', 'studentC', 'studentC', 'tadesseamsalu1@gmail.com', 1, '2021-11-05 13:03:13', NULL, NULL, NULL, 1, '2021-12-06 00:00:00', NULL, 'M'),
(68, 3, 'instructorD.instructorD', '[\"ROLE_INSTRUCTOR\"]', '$2y$13$ekG00KdgO8YL2ZnVrowG1OAz4NeciZNanvJUVawfmVt39.gws2nae', 'instructorD', 'instructorD', 'instructorD', 'yonytesfaye1@gmail.com', 1, '2022-01-12 13:46:54', NULL, NULL, NULL, 0, '2021-12-06 00:00:00', NULL, 'F'),
(69, 2, 'directorA.directorA', '[\"ROLE_DIRECTOR\"]', '$2y$13$sOOmdCf8WP30G1ihuAA/jOtdN2iLVmZPnW20aTHcVg8BEUNEhrQ9u', 'directorA', 'directorA', 'directorA', 'yonytesfaye@gmail.com1', 1, '2022-01-12 14:08:41', NULL, NULL, NULL, 0, '2021-12-06 00:00:00', NULL, 'M'),
(70, 2, 'directorB.directorB', '[\"ROLE_DIRECTOR\"]', '$argon2id$v=19$m=65536,t=4,p=1$6rEhn40MYSzkTQsInaARbg$rvKDorL84JN8WguJflC+IJ71rnoLkuW00kuhPXpZpYM', 'directorB', 'directorB', 'directorB', 'yonas.tesfaye1@aastsdfsdfu.edu.et', 1, '2021-11-06 13:39:18', NULL, NULL, NULL, 0, '2021-12-06 00:00:00', NULL, 'F'),
(71, 4, 'studentD.studentD', '[\"ROLE_STUDENT\"]', '$2y$13$sOOmdCf8WP30G1ihuAA/jOtdN2iLVmZPnW20aTHcVg8BEUNEhrQ9u', 'studentD', 'studentD', 'studentD', 'yonytesfaye22@gmail.com', 1, '2022-01-12 19:26:31', NULL, NULL, NULL, 1, '2021-12-06 00:00:00', NULL, 'M'),
(72, 2, 'testuser.testuser', '[\"ROLE_DIRECTOR\"]', '$2y$13$sVqNOJCNg3xsRQO/SrN6E.1GDdFylXDEX.6n14Kh8.932qAskQfzC', 'testuser', 'testuser', 'testuser', 'tadesseamsalu2@gmail.com', 1, '2021-12-04 08:44:43', NULL, NULL, NULL, 0, '2021-12-04 06:59:44', NULL, 'F'),
(73, 4, 'studentz.studentz', '[\"ROLE_STUDENT\"]', '$2y$13$eKj61PirKjD5UWiODObC5eVmWtZ6OTXJ4I.lDS9HUttHU7MQtEGUa', 'studentz', 'studentz', 'studentz', 'tarikezeleke72@gmail.com', 1, '2021-12-09 09:23:25', NULL, NULL, NULL, 1, '2021-12-09 08:26:50', NULL, 'M'),
(74, 3, 'instructorz.instructorz', '[\"ROLE_INSTRUCTOR\"]', '$2y$13$eKj61PirKjD5UWiODObC5eVmWtZ6OTXJ4I.lDS9HUttHU7MQtEGUa', 'instructorz', 'instructorz', 'instructorz', 'yonytesfayeee@gmail.com', 1, '2021-12-09 09:14:32', NULL, NULL, NULL, 0, '2021-12-09 08:35:43', NULL, 'M'),
(75, 4, 'studenty.studenty', '[\"ROLE_STUDENT\"]', '$argon2id$v=19$m=65536,t=4,p=1$sJwb5xSwkQ9Ay9uf8PXr2g$qSboyR2aaPsAkRyCT5VARkp9Rg8oZmPy5VK7EGplA6A', 'studenty', 'studenty', 'studenty', 'yonytesfaye@gmail.com', 1, NULL, NULL, NULL, NULL, 1, '2021-12-09 09:21:14', NULL, 'M'),
(76, 4, 'StudentK.StudentK', '[\"ROLE_STUDENT\"]', '$argon2id$v=19$m=65536,t=4,p=1$54cP4mTmKm7fE4kxEJDONQ$9IsXSPirAf61P8uNHMBQbrF5DrzAMKbm13RH61EYHlk', 'StudentK', 'StudentK', NULL, 'kebe2003@gmail.com', 1, NULL, NULL, NULL, NULL, 1, '2022-01-12 13:01:25', NULL, 'F'),
(77, 4, 'studentp.studentp', '[\"ROLE_STUDENT\"]', '$argon2id$v=19$m=65536,t=4,p=1$/tI4il5UGkRfiPh6ATLdDQ$mBofuGyyvY9qmyfp45gSEsbo2B73eXpXvUjiEHgBW0c', 'studentp', 'studentp', NULL, 'yonas.tesfaye@aastu.edu.et', 1, NULL, NULL, NULL, NULL, 1, '2022-01-12 14:17:29', NULL, 'M'),
(78, 4, 'testuser.testuser1', '[\"ROLE_STUDENT\"]', '$argon2id$v=19$m=65536,t=4,p=1$BKLTmv7sRxjJdbEUV3gTiw$tIHFtwdkUlu2lrLTKEYK5T9kuphoelZwFwpbnp6kHUM', 'testuser', 'testuser', NULL, 'tadesseamsalu@gmail.com', 1, NULL, NULL, NULL, NULL, 1, '2022-01-12 19:50:23', NULL, 'm');

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `id` int(11) NOT NULL,
  `registered_by_id` int(11) NOT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
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
(3, 32, 32, 'Instructor', 'Instructors', '2021-11-03 14:24:59', '2022-01-12 12:49:17', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_group_permission`
--

CREATE TABLE `user_group_permission` (
  `user_group_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
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
  `id` int(11) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `academic_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
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
  `user_id` int(11) NOT NULL,
  `user_group_id` int(11) NOT NULL
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
(72, 2),
(74, 3);

-- --------------------------------------------------------

--
-- Table structure for table `verification`
--

CREATE TABLE `verification` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verification_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verification_expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `verification`
--

INSERT INTO `verification` (`id`, `email`, `verification_code`, `verification_expiry`) VALUES
(26, 'tarike.zeleke@aastu.edu.et', '64767', '2021-12-09 09:29:00');

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
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8F3F68C510DAF24A` (`actor_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `choices`
--
ALTER TABLE `choices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `content_type`
--
ALTER TABLE `content_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `course_category`
--
ALTER TABLE `course_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `exam`
--
ALTER TABLE `exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instructor`
--
ALTER TABLE `instructor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `instructor_course`
--
ALTER TABLE `instructor_course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `instructor_course_chapter`
--
ALTER TABLE `instructor_course_chapter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `instructor_course_status`
--
ALTER TABLE `instructor_course_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `question_answer`
--
ALTER TABLE `question_answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `quiz_choices`
--
ALTER TABLE `quiz_choices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `student_answer`
--
ALTER TABLE `student_answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_chapter`
--
ALTER TABLE `student_chapter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_content_reaction`
--
ALTER TABLE `student_content_reaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_course`
--
ALTER TABLE `student_course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student_question`
--
ALTER TABLE `student_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_quiz`
--
ALTER TABLE `student_quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `system_setting`
--
ALTER TABLE `system_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `termsandconditions`
--
ALTER TABLE `termsandconditions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `verification`
--
ALTER TABLE `verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

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
-- Constraints for table `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `FK_8F3F68C510DAF24A` FOREIGN KEY (`actor_id`) REFERENCES `user` (`id`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
