-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2024 at 11:54 PM
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
-- Database: `online_tutoring1`
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
(105, 13, 11, 'hello', '2024-11-02 20:07:50', NULL, NULL),
(106, 13, 11, 'is it easy?', '2024-11-02 20:13:50', 105, NULL),
(107, 13, 11, 'go this way', '2024-11-02 20:14:08', 105, '../image/67268810375a1_Screenshot 2024-10-27 114740.png');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `l_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `liked_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(13, 1, 2, '3434fdfdfdfdfdfdftrt trhtrrrtrt', 'adminjj@gmail.com', '250 343 422 34355', 'solved', '2024-10-20 18:52:14'),
(14, 11, 3, 'hello my name is amani fazo', 'a.fadhiliprojects@gmail.com', '250 784 424 423', 'pending', '2024-11-02 20:12:15');

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
(18, 13, '../image/Screenshot 2024-02-23 152202.png', '2024-10-20 18:52:14'),
(19, 14, '../image/Screenshot 2024-10-27 034509.png', '2024-11-02 20:12:15'),
(20, 14, '../image/Screenshot 2024-10-27 114300.png', '2024-11-02 20:12:15'),
(21, 14, '../image/Screenshot 2024-10-27 114651.png', '2024-11-02 20:12:15'),
(22, 14, '../image/Screenshot 2024-10-27 114740.png', '2024-11-02 20:12:15'),
(23, 14, '../image/Screenshot 2024-10-27 190430.png', '2024-11-02 20:12:15'),
(24, 14, '../image/Screenshot 2024-10-27 191903.png', '2024-11-02 20:12:15'),
(25, 14, '../image/Screenshot 2024-10-27 194033.png', '2024-11-02 20:12:15'),
(26, 14, '../image/Screenshot 2024-10-27 205703.png', '2024-11-02 20:12:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `email` varchar(110) NOT NULL,
  `password` varchar(110) NOT NULL,
  `profile_image` text DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `email`, `password`, `profile_image`, `role`) VALUES
(1, 'amani fadhili b', 'adminjj@gmail.com', '$2y$10$Wbl9EfRuAPbbZDsWsplNS.RNBfOVE0OF7aJvhxhYSesH4t39VPFNW', 'p2.jpg', 'user'),
(2, 'Jane Smith', 'jane@example.com', 'password123', 'IMG-20230727-WA00121.jpg', 'user'),
(4, 'Bob Williams', 'bob@example.com', 'password123', '670702c242e18_IMG-20230727-WA0012.jpg', 'admin'),
(8, 'amanifadhili', 'admin@gmail.com', '$2y$10$9W6/T60pEg.PZWWRpykWwODOJUSgSpLjqPGIaMnmXoSqM2fTVN6DC\n\n', 'IMG-20230727-WA00121.jpg', 'user'),
(9, 'jules', 'jules@gmail.com', '$2y$10$6VT.XTP.OiNTiAiU28whI.nB1cDgMJEec7.WrK2YXQist.fJtD./O', 'WIN_20241010_11_10_42_Pro.jpg', 'user'),
(10, 'admin', 'fadhiliamani200@gmail.com', '$2y$10$6VT.XTP.OiNTiAiU28whI.nB1cDgMJEec7.WrK2YXQist.fJtD./O', 'images.png', 'admin'),
(11, 'fadhili amani', 'a.fadhiliprojects@gmail.com', '$2y$10$p.5EPsFmvE3GPlQ8rwJhp.Hr.lBafbuhTm1XC/aZTyftgyWCGqeQ.', 'Screenshot 2024-10-27 194033.png', 'user');

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
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`l_id`),
  ADD KEY `userWhoLikes` (`user_id`),
  ADD KEY `commentReplyLiked` (`comment_id`);

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
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `l_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `problems`
--
ALTER TABLE `problems`
  MODIFY `problem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `problem_categories`
--
ALTER TABLE `problem_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `problem_images`
--
ALTER TABLE `problem_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `commentReplyLiked` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userWhoLikes` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
