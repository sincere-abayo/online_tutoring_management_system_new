-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2024 at 08:12 PM
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
-- Database: `online_tutoring`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `problem_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `parent_comment_id` int(11) DEFAULT NULL,
  `image_url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `problem_id`, `user_id`, `comment`, `created_at`, `parent_comment_id`, `image_url`) VALUES
(2, 2, 4, 'I have experience in machine learning. We can collaborate.', '2024-10-02 13:21:43', NULL, NULL),
(3, 3, 2, 'You should consider using encryption and secure authentication.', '2024-10-02 13:21:43', NULL, NULL),
(23, 3, 1, 'ok thanks', '2024-10-04 12:28:09', 3, NULL),
(24, 1, 1, 'i am expert with four years of experience I can help you', '2024-10-04 12:29:02', NULL, NULL),
(25, 1, 1, 'i like it, how could we meet?', '2024-10-04 12:29:23', 24, NULL),
(26, 1, 1, 'sincere ararwaye', '2024-10-04 12:30:36', NULL, NULL),
(27, 1, 1, 'ntago ari kurya', '2024-10-04 12:30:57', 26, NULL),
(38, 1, 1, 'hello\r\n', '2024-10-04 13:29:47', 24, NULL),
(39, 1, 1, 'jlafn,', '2024-10-04 13:33:09', 24, NULL),
(40, 1, 1, 'jfgvyujg', '2024-10-04 13:33:26', 24, NULL),
(41, 1, 1, 'hello\r\n', '2024-10-08 16:14:50', NULL, NULL),
(42, 1, 1, 'allow from any where', '2024-10-08 16:29:04', NULL, NULL),
(43, 1, 1, 'allow from any where', '2024-10-08 16:29:57', NULL, NULL),
(44, 1, 1, '', '2024-10-08 17:48:13', 24, NULL),
(45, 1, 1, 'amani', '2024-10-08 18:01:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `problems`
--

CREATE TABLE `problems` (
  `problem_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `status` varchar(30) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `problems`
--

INSERT INTO `problems` (`problem_id`, `user_id`, `category_id`, `description`, `email`, `contact`, `status`, `created_at`) VALUES
(1, 1, 1, 'Need help with building a login form in HTML and PHP.', 'john@example.com', '250 784 424 423', 'pending', '2024-10-02 13:21:43'),
(2, 3, 4, 'Looking for help with a machine learning project.', 'alice@example.com', '250 784 424 423', 'solved', '2024-10-02 13:21:43'),
(3, 1, 3, 'How can I improve the security of my web application?', 'john@example.com', '250 784 424 423', 'pending', '2024-10-02 13:21:43');

-- --------------------------------------------------------

--
-- Table structure for table `problem_categories`
--

CREATE TABLE `problem_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `problem_categories`
--

INSERT INTO `problem_categories` (`category_id`, `category_name`) VALUES
(3, 'Cybersecurity'),
(5, 'Data Science'),
(4, 'Machine Learning'),
(2, 'Networking'),
(1, 'Software Development');

-- --------------------------------------------------------

--
-- Table structure for table `problem_images`
--

CREATE TABLE `problem_images` (
  `image_id` int(11) NOT NULL,
  `problem_id` int(11) DEFAULT NULL,
  `image_url` text NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `problem_images`
--

INSERT INTO `problem_images` (`image_id`, `problem_id`, `image_url`, `uploaded_at`) VALUES
(1, 1, 'img1.png', '2024-10-02 13:21:43'),
(2, 3, 'img2.png', '2024-10-02 13:21:43'),
(3, 2, 'img2.png', '2024-10-02 13:21:43'),
(4, 3, 'img2.png', '2024-10-02 13:21:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `email` varchar(110) NOT NULL,
  `password` varchar(110) NOT NULL,
  `profile_image` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `email`, `password`, `profile_image`) VALUES
(1, 'John Doe', 'john@example.com', 'password123', 'profile1.jpg'),
(2, 'Jane Smith', 'jane@example.com', 'password123', 'profile2.jpg'),
(3, 'Alice Johnson', 'alice@example.com', 'password123', 'profile3.jpg'),
(4, 'Bob Williams', 'bob@example.com', 'password123', 'profile4.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `problem_id` (`problem_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_comment_id` (`parent_comment_id`);

--
-- Indexes for table `problems`
--
ALTER TABLE `problems`
  ADD PRIMARY KEY (`problem_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `problem_categories`
--
ALTER TABLE `problem_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `problem_images`
--
ALTER TABLE `problem_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `problem_id` (`problem_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `problems`
--
ALTER TABLE `problems`
  MODIFY `problem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `problem_categories`
--
ALTER TABLE `problem_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `problem_images`
--
ALTER TABLE `problem_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`problem_id`) REFERENCES `problems` (`problem_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`parent_comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE;

--
-- Constraints for table `problems`
--
ALTER TABLE `problems`
  ADD CONSTRAINT `problems_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `problems_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `problem_categories` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `problem_images`
--
ALTER TABLE `problem_images`
  ADD CONSTRAINT `problem_images_ibfk_1` FOREIGN KEY (`problem_id`) REFERENCES `problems` (`problem_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
