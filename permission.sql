-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 03, 2021 at 10:48 PM
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
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `id` int NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
(69, 'student_quiz_list', 'student_quiz_list', NULL),
(70, 'student_quiz_create', 'student_quiz create', NULL),
(71, 'student_quiz_edit', 'student_quiz edit', NULL),
(72, 'student_quiz_delete', 'student_quiz delete', NULL),
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
(89, 'user_type_list', 'user_type_list', NULL),
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
(109, 'qa_ask', 'Q&A Ask', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
