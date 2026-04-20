-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 20, 2026 at 08:17 PM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `herbits_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `blockeduser`
--

CREATE TABLE `blockeduser` (
  `id` int(11) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `emailAddress` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `blockeduser`
--

INSERT INTO `blockeduser` (`id`, `firstName`, `lastName`, `emailAddress`) VALUES
(1, 'test', 'blocked', 'test@gmail.com'),
(2, 'Hanan', 'Alharbi', 'hanan@test.com'),
(3, 'Mona', 'Saleh', 'mona@test.com');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `recipeID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `comment` text,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `recipeID`, `userID`, `comment`, `date`) VALUES
(2, 4, 3, 'Nice recipe!', '2026-04-20 23:11:50'),
(3, 5, 4, 'Looks good', '2026-04-20 23:11:50'),
(4, 6, 9, 'I want to try this', '2026-04-20 23:11:50');

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE `favourites` (
  `userID` int(11) NOT NULL,
  `recipeID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `favourites`
--

INSERT INTO `favourites` (`userID`, `recipeID`) VALUES
(3, 4),
(4, 5),
(9, 6);

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `userID` int(11) NOT NULL,
  `recipeID` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`userID`, `recipeID`, `id`) VALUES
(3, 4, 1),
(3, 5, 2),
(4, 6, 3);

-- --------------------------------------------------------

--
-- Table structure for table `recipe`
--

CREATE TABLE `recipe` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text,
  `photoFileName` varchar(255) DEFAULT NULL,
  `videoFilePath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `recipe`
--

INSERT INTO `recipe` (`id`, `userID`, `categoryID`, `name`, `description`, `photoFileName`, `videoFilePath`) VALUES
(4, 4, 2, 'Skin Boost Nut Mix', 'recipe', 'nut mix.jpeg', NULL),
(5, 4, 1, 'Iron Boost Spinach Salad', 'recipe', 'spinach salad.jpeg', NULL),
(6, 4, 3, 'Apple Cinnamon Snack Bites', 'recipe', 'apple cinnamon.jpeg', NULL),
(8, 4, 3, 'Peanut Butter Energy Balls', 'recipe', 'peanut butter balls.jpeg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `recipecategory`
--

CREATE TABLE `recipecategory` (
  `id` int(11) NOT NULL,
  `categoryName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `recipecategory`
--

INSERT INTO `recipecategory` (`id`, `categoryName`) VALUES
(1, 'Inner Glow'),
(2, 'Outer Glow'),
(3, 'Healthy Snacks');

-- --------------------------------------------------------

--
-- Table structure for table `recipeingredient`
--

CREATE TABLE `recipeingredient` (
  `id` int(11) NOT NULL,
  `recipeID` int(11) NOT NULL,
  `ingredientName` varchar(150) NOT NULL,
  `quantity` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `recipeingredient`
--

INSERT INTO `recipeingredient` (`id`, `recipeID`, `ingredientName`, `quantity`) VALUES
(13, 4, 'Mixed nuts', '1 cup'),
(14, 4, 'Pumpkin seeds', '2 tbsp'),
(15, 4, 'Sunflower seeds', '2 tbsp'),
(16, 5, 'Spinach', '2 cups'),
(17, 5, 'Pomegranate seeds', '1/4 cup'),
(18, 5, 'Feta cheese', '2 tbsp'),
(19, 6, 'Apple', '1 diced'),
(20, 6, 'Cinnamon', '1 tsp'),
(21, 6, 'Oats', '1/2 cup'),
(25, 8, 'Peanut butter', '1/2 cup'),
(26, 8, 'Oats', '1 cup'),
(27, 8, 'Honey', '1 tbsp');

-- --------------------------------------------------------

--
-- Table structure for table `recipeinstruction`
--

CREATE TABLE `recipeinstruction` (
  `id` int(11) NOT NULL,
  `recipeID` int(11) NOT NULL,
  `stepNumber` int(11) NOT NULL,
  `instructionText` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `recipeinstruction`
--

INSERT INTO `recipeinstruction` (`id`, `recipeID`, `stepNumber`, `instructionText`) VALUES
(11, 4, 1, 'Mix all nuts and seeds together.'),
(12, 4, 2, 'Serve immediately or store in a sealed jar.'),
(13, 5, 1, 'Wash the spinach well.'),
(14, 5, 2, 'Mix spinach with the remaining ingredients.'),
(15, 5, 3, 'Serve fresh.'),
(16, 6, 1, 'Mix diced apple with oats and cinnamon.'),
(17, 6, 2, 'Shape into bite-sized pieces.'),
(18, 6, 3, 'Chill before serving.'),
(22, 8, 1, 'Mix peanut butter, oats, and honey.'),
(23, 8, 2, 'Roll into small balls.'),
(24, 8, 3, 'Refrigerate until firm.');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `id` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `recipeID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`id`, `userID`, `recipeID`) VALUES
(3, 4, 4),
(4, 3, 5),
(5, 4, 4),
(6, 3, 5),
(7, 9, 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `userType` enum('user','admin') NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photoFileName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userType`, `firstName`, `lastName`, `emailAddress`, `password`, `photoFileName`) VALUES
(1, 'admin', 'Admin', 'User', 'admin@herbite.com', '$2y$10$3ukJu3ykLTx92hPA3o63e.jGGC9wfm8DlEyYniZOXxFJ5GzJlujbS', 'admin.png'),
(3, 'user', 'Sara', 'Ahmed', 'sara@test.com', '$2y$10$wdRXOY/zWCICx0iqU/62L.OISQS46v/xi9INeEaY4VbzcrYs5Ecoa', 'default.png'),
(4, 'user', 'Noor', 'Ali', 'noor@test.com', '$2y$10$8ijqm7pPaxfsfQXRaadPiuxmAlWR7r5rk5RdHqzLtwTx.8Jn4syFu', 'blonde-girl.png'),
(9, 'user', 'Khaled', 'Ahmed', 'khaled@gmail.com', '', ''),
(10, 'user', 'Fahad', 'Ali', 'fahad@gmail.com', '', ''),
(11, 'user', 'ra', 'an', '445202375@student.ksu.edu.sa', '$2y$10$SDdob7IrLz8.ytaiOPRVHOSlhPOStqnOOOq8h1uz18WBe6lcElUhu', 'default.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blockeduser`
--
ALTER TABLE `blockeduser`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favourites`
--
ALTER TABLE `favourites`
  ADD PRIMARY KEY (`userID`,`recipeID`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipe`
--
ALTER TABLE `recipe`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipeingredient`
--
ALTER TABLE `recipeingredient`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipeinstruction`
--
ALTER TABLE `recipeinstruction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blockeduser`
--
ALTER TABLE `blockeduser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `recipe`
--
ALTER TABLE `recipe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `recipeingredient`
--
ALTER TABLE `recipeingredient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `recipeinstruction`
--
ALTER TABLE `recipeinstruction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
