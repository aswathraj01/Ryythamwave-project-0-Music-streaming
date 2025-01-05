-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2025 at 12:36 PM
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
-- Database: `ryythmwave`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(32) NOT NULL,
  `signUpDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `profilePic` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `firstName`, `lastName`, `email`, `password`, `signUpDate`, `profilePic`) VALUES
(1, 'admin', 'aswath', '', 'testmail@gmail.com', 'admin', '2024-05-10 21:35:00', ''),
(2, 'mainuser', 'Aswath', 'Raj', 'test@gmail.com', 'bdc87b9c894da5168059e00ebffb9077', '2024-05-11 00:53:13', 'assets/images/profile-pics/head_emerald.png'),
(3, 'ak12', 'Akshay', '', 'akshay@gmail.com', '50977d6b4d7d7b056427ae04b90b251d', '2024-09-17 22:01:51', ''),
(4, 'abhimanyu', 'Abhimanyu', 'Vijayakumar', 'Abhimanyu@gmail.com', '482c811da5d5b4bc6d497ffa98491e38', '2024-11-28 18:30:00', 'assets/images/profile-pics/head_emerald.png'),
(33, '234234', 'sdfdsaf', 'dsfdsfds', 'dxfcvsdf@dfgd.cdfs', '$2y$10$j7/wQ4Xbexm/zYyIzlmy..6Uf', '0000-00-00 00:00:00', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
