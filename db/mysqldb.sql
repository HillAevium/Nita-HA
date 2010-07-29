-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 29, 2010 at 04:33 AM
-- Server version: 5.1.37
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `nita`
--

-- --------------------------------------------------------

--
-- Table structure for table `Account`
--

CREATE TABLE IF NOT EXISTS `Account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `billingAddress1` varchar(255) NOT NULL,
  `billingAddress2` varchar(255) DEFAULT NULL,
  `billingCity` varchar(255) NOT NULL,
  `billingState` varchar(255) NOT NULL,
  `billingZip` varchar(255) NOT NULL,
  `billingCountry` varchar(255) NOT NULL,
  `shippingAddress1` varchar(255) NOT NULL,
  `shippingAddress2` varchar(255) DEFAULT NULL,
  `shippingCity` varchar(255) NOT NULL,
  `shippingState` varchar(255) NOT NULL,
  `shippingZip` varchar(255) NOT NULL,
  `shippingCountry` varchar(255) NOT NULL,
  `primaryPhone` varchar(255) NOT NULL,
  `primaryFax` varchar(255) NOT NULL,
  `practiceType` varchar(255) NOT NULL,
  `firmSize` varchar(255) NOT NULL,
  `trainingDirector` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Contact`
--

CREATE TABLE IF NOT EXISTS `Contact` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `accountId` int(11) DEFAULT NULL,
  `salutation` varchar(30) DEFAULT NULL,
  `firstName` varchar(50) NOT NULL,
  `middleInitial` varchar(10) DEFAULT NULL,
  `lastName` varchar(50) NOT NULL,
  `suffix` varchar(30) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `phone2` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `role` varchar(30) NOT NULL,
  `isAttendingClasses` tinyint(1) NOT NULL,
  `badgeName` varchar(100) NOT NULL,
  `companyName` varchar(255) DEFAULT NULL,
  `typeOfPractice` varchar(255) DEFAULT NULL,
  `lawSchoolAttended` varchar(255) DEFAULT NULL,
  `firmSize` int(11) DEFAULT NULL,
  `ethnicity` varchar(255) DEFAULT NULL,
  `lawInterests` varchar(255) DEFAULT NULL,
  `trainingDirector` varchar(255) DEFAULT NULL,
  `billingAddress1` varchar(100) NOT NULL,
  `billingAddress2` varchar(100) NOT NULL,
  `billingCity` varchar(50) NOT NULL,
  `billingState` varchar(50) NOT NULL,
  `billingZip` varchar(10) NOT NULL,
  `billingCountry` varchar(50) NOT NULL,
  `shippingAddress1` varchar(50) NOT NULL,
  `shippingAddress2` varchar(50) NOT NULL,
  `shippingCity` varchar(50) NOT NULL,
  `shippingState` varchar(50) NOT NULL,
  `shippingZip` varchar(10) NOT NULL,
  `shippingCountry` varchar(50) NOT NULL,
  `requireAccessibility` tinyint(1) NOT NULL,
  `haveScholarship` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `ContactBarInfo`
--

CREATE TABLE IF NOT EXISTS `ContactBarInfo` (
  `userId` int(11) DEFAULT NULL,
  `barId` int(11) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
