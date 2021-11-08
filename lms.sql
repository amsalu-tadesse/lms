-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 19, 2021 at 12:52 PM
-- Server version: 8.0.26-0ubuntu0.20.04.2
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `choices`
--

CREATE TABLE `choices` (
  `id` int NOT NULL,
  `question_id` int DEFAULT NULL,
  `letter` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chapter_id` int NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `resource` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `title`, `content`, `filename`, `video_link`, `chapter_id`, `description`, `resource`) VALUES
(3, 'kkk', NULL, NULL, 'https://www.youtube.com/embed/_Iew7b9FyJk', 1, NULL, ''),
(4, 'dffff', NULL, 'video.mp4', NULL, 1, 'rtr', ''),
(5, 'content', '<p>sample text</p>', NULL, NULL, 1, 'dfdfdfd', ''),
(6, 'fff', 'fff', NULL, NULL, 2, 'ff', ''),
(7, 'another content', '<h2>this is another content</h2>\r\n<p>hello content</p>', NULL, NULL, 1, NULL, ''),
(8, 'another youtube video', NULL, NULL, 'https://www.youtube.com/embed/XZowIDdqnLA', 1, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `content_type`
--

CREATE TABLE `content_type` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `category_id`, `name`, `code`, `credit`, `description`, `status`) VALUES
(1, 1, 'business adminstartion', 'dd', NULL, 'Business professionals generally need at least a basic understanding of accounting, finance, marketing, human resources and information technology, and they often specialize in a practice area Business professionals generally need at least a basic understanding of accounting, finance, marketing, human resources and information technology, and they often specialize in a practice area', 1),
(2, 1, 'management\r\n', NULL, NULL, 'this is management desccription', 1),
(3, 1, 'software engineering', NULL, NULL, 'Definition: Software engineering is a detailed study of engineering to the design, development and maintenance of software. Software engineering was introduced to address the issues of low-quality software projects. Problems arise when a software generally exceeds timelines, budgets, and reduced levels of quality.\r\n', 1),
(4, 1, 'mechanical engineering', NULL, NULL, 'Mechanical engineering is an engineering branch that combines engineering physics and mathematics principles with materials science to design, analyze, manufacture, and maintain mechanical systems. ... It is the branch of engineering that involves the design, production, and operation of machinery.', 1),
(5, 2, 'foundations of economics', NULL, NULL, 'An incentive is something that motivates individuals to do something. Economic incentives provide good value for money while also contributing to the societal organization. Restaurants, for example, offer discounts and buy one, get one promotion to persuade customers to dine at their establishments.\r\n\r\nA tradeoff occurs when one element or quality of something is traded in exchange for another aspect or quality of the same thing. In economics, a tradeoff is defined as the loss of the most desirable alternative, or the opportunity cost of a given option. The best illustration of a trade-off to choosing the most favored alternative is the relationship between unemployment and inflation.\r\n\r\nOpportunity cost is what everyone must give up acquiring something, and the benefit or value that was given up might pertain to decisions made in personal life, in the economy, or at the level of government. ', 1),
(6, 2, 'intro to management', NULL, NULL, 'no description', 1),
(7, 2, 'marketing', NULL, NULL, 'marketing technologies', 1),
(8, 2, 'urbanizations', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `course_category`
--

CREATE TABLE `course_category` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_category`
--

INSERT INTO `course_category` (`id`, `name`, `description`) VALUES
(1, 'techonology', 'desc'),
(2, 'business', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE `exam` (
  `id` int NOT NULL,
  `instructor_course_id` int DEFAULT NULL,
  `instruction` longtext COLLATE utf8mb4_unicode_ci,
  `percentage` double DEFAULT NULL,
  `passvalue` double DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exam`
--

INSERT INTO `exam` (`id`, `instructor_course_id`, `instruction`, `percentage`, `passvalue`, `active`) VALUES
(1, NULL, 'kkjh', 10, 5, 1),
(3, NULL, 'test', 11, 1, 1);

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
(2, 3, NULL);

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
(1, 1, 2, NULL, '2021-10-06 10:46:25', 1, NULL),
(2, 2, 2, NULL, NULL, 1, NULL),
(3, 4, 2, NULL, NULL, 1, NULL),
(4, 6, 2, NULL, NULL, 1, NULL),
(5, 3, 2, NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `instructor_course_chapter`
--

CREATE TABLE `instructor_course_chapter` (
  `id` int NOT NULL,
  `instructor_course_id` int NOT NULL,
  `chapter` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `topic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructor_course_chapter`
--

INSERT INTO `instructor_course_chapter` (`id`, `instructor_course_id`, `chapter`, `created_at`, `topic`) VALUES
(1, 1, 'chapter 4 - basics of economics', '2021-09-02 00:00:00', ''),
(2, 1, 'chapter 5 - subfields of economics', '2021-09-04 00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `instructor_course_status`
--

CREATE TABLE `instructor_course_status` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `name`, `code`, `description`) VALUES
(1, 'Approver1', 'approver1', NULL),
(2, 'Approver2', 'Approver2', NULL),
(3, 'Approver3', 'Approver3', NULL),
(4, 'manage college', 'manage_college', NULL),
(5, 'manage department', 'manage_department', NULL),
(6, 'manage store', 'manage_store', NULL),
(7, 'manage catagory', 'manage_catagory', NULL),
(8, 'manage brand', 'manag_brand', NULL),
(9, 'manage usage type', 'manage_usage_type', NULL),
(10, 'manage product type', 'manage_product_type', NULL),
(11, 'manage unit of measure', 'manage_unit_of_measure', NULL),
(12, 'manage request', 'manage_request', NULL),
(13, 'manage stock', 'manage_stock', NULL),
(14, 'manage company', 'manage_company', NULL),
(15, 'manage report', 'manage_report', NULL),
(16, 'manage user', 'manage_user', NULL),
(17, 'manage permission and usergroup', 'manage_permision_and_usergroup', NULL),
(18, 'view issued request', 'issued_request', NULL),
(20, 'view balance', 'balance', NULL),
(21, 'view my items', 'my_items', NULL),
(22, 'read only stock in out', 'property', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `id` int NOT NULL,
  `exam_id` int DEFAULT NULL,
  `question` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reset_password_request`
--

INSERT INTO `reset_password_request` (`id`, `user_id`, `selector`, `hashed_token`, `requested_at`, `expires_at`) VALUES
(8, 3, 'ZHWOOgIr6SpTciKfnRR9', 'dRAVfjdwXZg0GpdPiOsm/9NCAKWO9SQKDbepmP1iCeo=', '2021-10-17 12:32:23', '2021-10-17 13:32:23');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `academic_level_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `user_id`, `academic_level_id`) VALUES
(1, 3, NULL),
(2, 19, NULL);

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
(2, 1, 1, 1, '2021-10-07 16:37:32');

-- --------------------------------------------------------

--
-- Table structure for table `student_content_reaction`
--

CREATE TABLE `student_content_reaction` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `content_id` int DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `message` longtext COLLATE utf8mb4_unicode_ci
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
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_course`
--

INSERT INTO `student_course` (`id`, `student_id`, `instructor_course_id`, `created_at`, `status`, `active`, `is_at_page`, `completed_at`) VALUES
(4, 1, 1, '2021-10-06 10:46:25', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `user_type_id` int DEFAULT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `locale` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirm_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user_type_id`, `username`, `roles`, `password`, `first_name`, `middle_name`, `last_name`, `email`, `is_active`, `last_login`, `locale`, `confirm_token`, `role`, `is_verified`) VALUES
(1, 1, 'admin', '[\"ROLE_ADMIN\"]', '$2y$13$raJxMazDR0h64re5G7fmlecZdGdUmiUnFSLgmioAZllnJYspH5cLi', 'user 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(2, 1, 'stud1', '[]', '$2y$13$pUyMB8cdzMVvRY1kD3.Ujuo.lssOA8pb8rPBeYcistdjzf/CYtNWu', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(3, 1, 'qq', '[\"ROLE_STUDENT\"]', '$2y$13$jAJlZw6Kzkv7zjd4KY/.GezpyfGH4S0asV7eD94Xn9kdN9KF4Kwbq', 'qqq', 'qq', 'qqq', 'lioulnebiye@gmail.com', 1, '2021-10-19 12:50:12', '1', NULL, NULL, 1),
(4, 1, 'neba', '[\"ROLE_USER\"]', '$argon2id$v=19$m=65536,t=4,p=1$ZwMuAJV0BGzJZYe/jda15w$TRdfAn9s197SVnc09dvgrc2SocoNbDZVedia4zu9LDE', 'qqq', 'qq', 'qqq', 'lioulnebiye@gmail.com', 1, NULL, NULL, NULL, NULL, 0),
(19, NULL, 'neva1', '[\"ROLE_STUDENT\"]', '$2y$13$IyRs0N9UaFrV2MxbI9qrm.9QbVP3O418T98.fwRSSkrXwZpz9GnT.', 'Nebiye', 'Lioul', 'Temesgen', 'lioulnebiye@gmail.com', 1, '2021-10-17 12:58:54', NULL, NULL, NULL, 1),
(20, NULL, 'neva2', '[\"ROLE_STUDENT\"]', '$argon2id$v=19$m=65536,t=4,p=1$OL2JYgAEXt6cFQ9mS5Dusg$Q8x0zpx9ZENfMBnJnMqm1RiA4HqXH9oJKp/OGPI1QXU', 'Nebiye', 'Lioul', 'Temesgen', 'lioulnebiye@gmail.com', 1, NULL, NULL, NULL, NULL, 0),
(21, NULL, 'neva3', '[\"ROLE_STUDENT\"]', '$2y$13$VGz.3ZT.wAbOFAgQSp595.Npu2Wua6NkPNn1DzjkFa3K72v9RE.JK', 'Nebiye', 'Lioul', 'Temesgen', 'lioulnebiye@gmail.com', 1, '2021-10-17 12:58:26', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `id` int NOT NULL,
  `registered_by_id` int NOT NULL,
  `updated_by_id` int DEFAULT NULL,
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
(1, 1, NULL, 'admin', 'this is admin', '2021-09-29 10:00:19', NULL, 1);

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
(1, 3),
(1, 4),
(1, 5),
(1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `id` int NOT NULL,
  `country_id` int DEFAULT NULL,
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
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`id`, `name`, `description`) VALUES
(1, 'admin', 'this is admin\r\n');

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
(1, 1);

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
  ADD KEY `IDX_31FC43DD6081C3B0` (`academic_level_id`),
  ADD KEY `IDX_31FC43DDA76ED395` (`user_id`);

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
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B6F7494E578D5E91` (`exam_id`);

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
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_level`
--
ALTER TABLE `academic_level`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `choices`
--
ALTER TABLE `choices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `content_type`
--
ALTER TABLE `content_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `course_category`
--
ALTER TABLE `course_category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `exam`
--
ALTER TABLE `exam`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `instructor`
--
ALTER TABLE `instructor`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `instructor_course`
--
ALTER TABLE `instructor_course`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `instructor_course_chapter`
--
ALTER TABLE `instructor_course_chapter`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `instructor_course_status`
--
ALTER TABLE `instructor_course_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_chapter`
--
ALTER TABLE `student_chapter`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_content_reaction`
--
ALTER TABLE `student_content_reaction`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_course`
--
ALTER TABLE `student_course`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
