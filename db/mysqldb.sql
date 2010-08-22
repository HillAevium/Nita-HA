-- MySQL dump 10.13  Distrib 5.1.46, for Win32 (ia32)
--
-- Host: localhost    Database: nita
-- ------------------------------------------------------
-- Server version	5.1.46-community

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `billingAddress1` varchar(255) DEFAULT NULL,
  `billingAddress2` varchar(255) DEFAULT NULL,
  `billingCity` varchar(255) DEFAULT NULL,
  `billingState` varchar(255) DEFAULT NULL,
  `billingZip` varchar(255) DEFAULT NULL,
  `billingCountry` varchar(255) DEFAULT NULL,
  `shippingAddress1` varchar(255) DEFAULT NULL,
  `shippingAddress2` varchar(255) DEFAULT NULL,
  `shippingCity` varchar(255) DEFAULT NULL,
  `shippingState` varchar(255) DEFAULT NULL,
  `shippingZip` varchar(255) DEFAULT NULL,
  `shippingCountry` varchar(255) DEFAULT NULL,
  `phone1` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `practiceType` varchar(255) DEFAULT NULL,
  `firmSize` varchar(255) DEFAULT NULL,
  `trainingDirector` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
/*!40000 ALTER TABLE `account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ci_sessions`
--

LOCK TABLES `ci_sessions` WRITE;
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `accountId` int(11) DEFAULT NULL,
  `userType` int(11) DEFAULT NULL,
  `salutation` varchar(30) DEFAULT NULL,
  `firstName` varchar(50) NOT NULL,
  `middleInitial` varchar(10) DEFAULT NULL,
  `lastName` varchar(50) NOT NULL,
  `suffix` varchar(30) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `phone2` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `isAttendingClasses` tinyint(4) NOT NULL DEFAULT '1',
  `badgeName` varchar(255) DEFAULT NULL,
  `companyName` varchar(255) DEFAULT NULL,
  `typeOfPractice` varchar(255) DEFAULT NULL,
  `lawSchoolAttended` varchar(255) DEFAULT NULL,
  `ethnicity` varchar(255) DEFAULT NULL,
  `lawInterests` varchar(255) DEFAULT NULL,
  `billingAddress1` varchar(255) DEFAULT NULL,
  `billingAddress2` varchar(100) DEFAULT NULL,
  `billingCity` varchar(255) DEFAULT NULL,
  `billingState` varchar(255) DEFAULT NULL,
  `billingZip` varchar(255) DEFAULT NULL,
  `billingCountry` varchar(255) DEFAULT NULL,
  `shippingAddress1` varchar(255) DEFAULT NULL,
  `shippingAddress2` varchar(100) DEFAULT NULL,
  `shippingCity` varchar(255) DEFAULT NULL,
  `shippingState` varchar(255) DEFAULT NULL,
  `shippingZip` varchar(255) DEFAULT NULL,
  `shippingCountry` varchar(255) DEFAULT NULL,
  `requireAccessibility` tinyint(4) DEFAULT NULL,
  `haveScholarship` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact`
--

LOCK TABLES `contact` WRITE;
/*!40000 ALTER TABLE `contact` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contactbarinfo`
--

DROP TABLE IF EXISTS `contactbarinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contactbarinfo` (
  `userId` int(11) DEFAULT NULL,
  `barId` varchar(255) NOT NULL,
  `state` varchar(255) DEFAULT NULL,
  `cle` varchar(255) DEFAULT '0',
  `month` varchar(255) DEFAULT NULL,
  `year` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contactbarinfo`
--

LOCK TABLES `contactbarinfo` WRITE;
/*!40000 ALTER TABLE `contactbarinfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `contactbarinfo` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-08-21 19:26:04
