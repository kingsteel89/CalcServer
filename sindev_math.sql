-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 02, 2019 at 09:49 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sindev_math`
--

-- --------------------------------------------------------

--
-- Table structure for table `re_project_function`
--

CREATE TABLE `re_project_function` (
  `id` int(11) NOT NULL,
  `id_project` int(11) NOT NULL,
  `id_function` int(11) NOT NULL,
  `regdate` datetime NOT NULL,
  `other` varchar(511) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_comments`
--

CREATE TABLE `tb_comments` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `comment` varchar(511) NOT NULL,
  `payed` varchar(31) NOT NULL,
  `regdate` datetime NOT NULL,
  `other` varchar(511) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_functions`
--

CREATE TABLE `tb_functions` (
  `id` int(11) NOT NULL,
  `name` varchar(127) NOT NULL,
  `title` varchar(127) NOT NULL,
  `content` varchar(1023) NOT NULL,
  `id_user` int(11) NOT NULL,
  `regdate` datetime NOT NULL,
  `other` varchar(511) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_params`
--

CREATE TABLE `tb_params` (
  `id` int(11) NOT NULL,
  `name` varchar(63) NOT NULL,
  `description` varchar(255) NOT NULL,
  `id_func` int(11) NOT NULL,
  `regdate` datetime NOT NULL,
  `other` varchar(511) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_project`
--

CREATE TABLE `tb_project` (
  `id` int(11) NOT NULL,
  `name` varchar(63) NOT NULL,
  `id_user` int(11) NOT NULL,
  `regdate` datetime NOT NULL,
  `other` varchar(511) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_reports`
--

CREATE TABLE `tb_reports` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `name` varchar(63) NOT NULL,
  `description` varchar(127) NOT NULL,
  `content` varchar(1023) NOT NULL,
  `regdate` datetime NOT NULL,
  `other` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_users`
--

CREATE TABLE `tb_users` (
  `id` int(11) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `nickname` varchar(127) NOT NULL,
  `headurl` varchar(255) NOT NULL,
  `classname` varchar(127) NOT NULL,
  `function` int(11) NOT NULL,
  `restfunc` int(11) NOT NULL,
  `payed` int(11) NOT NULL,
  `regdate` datetime NOT NULL,
  `other` varchar(511) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `re_project_function`
--
ALTER TABLE `re_project_function`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_comments`
--
ALTER TABLE `tb_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_functions`
--
ALTER TABLE `tb_functions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_params`
--
ALTER TABLE `tb_params`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_project`
--
ALTER TABLE `tb_project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_reports`
--
ALTER TABLE `tb_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `re_project_function`
--
ALTER TABLE `re_project_function`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_comments`
--
ALTER TABLE `tb_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_functions`
--
ALTER TABLE `tb_functions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_params`
--
ALTER TABLE `tb_params`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_project`
--
ALTER TABLE `tb_project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_reports`
--
ALTER TABLE `tb_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_users`
--
ALTER TABLE `tb_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
