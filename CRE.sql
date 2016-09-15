-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 14, 2016 at 07:41 PM
-- Server version: 5.5.47-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `CRE`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `votes` int(11) NOT NULL DEFAULT '0',
  `n_votes` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` tinytext NOT NULL,
  `ip` varchar(15) NOT NULL,
  `allow_vote` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `meta`
--

CREATE TABLE `meta` (
  `id` int(11) NOT NULL,
  `meta_name` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `meta`
--

INSERT INTO `meta` (`id`, `meta_name`, `meta_value`) VALUES
(1, 'admin_pass', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `vote_string` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meta`
--
ALTER TABLE `meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `meta`
--
ALTER TABLE `meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
