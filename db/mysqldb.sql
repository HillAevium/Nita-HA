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
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` VALUES (29,'ABC Law Firm','123 Main St',NULL,'New York','CA','12345','USA','345 Broadway',NULL,'Walla Walla','CA','97450','USA','408-555-6666','408-777-8888','Type 1','1','Bruce Willis'),(30,'ABC Law Firm','123 Main St',NULL,'New York','CA','12345','USA','345 Broadway',NULL,'Walla Walla','CA','97450','USA','408-555-6666','408-777-8888','Type 1','1','Bruce Willis');
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
INSERT INTO `ci_sessions` VALUES ('c2ac0d2edee1367f88a42f4953a547ba','127.0.0.1','Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv',1282117664,'a:2:{s:16:\"auth.credentials\";s:141:\"O:16:\"User_Credentials\":2:{s:4:\"auth\";a:2:{s:6:\"status\";i:1;s:2:\"id\";s:2:\"49\";}s:4:\"user\";a:2:{s:4:\"type\";s:1:\"1\";s:9:\"accountId\";s:2:\"29\";}}\";s:13:\"cart_contents\";a:3:{s:32:\"f16e4897cb08d19799b7cc6f0fbcb77f\";a:6:{s:5:\"rowid\";s:32:\"f16e4897cb08d19799b7cc6f0fbcb77f\";s:2:\"id\";s:36:\"40CED373-C38E-DF11-8D9F-000C2916A1CB\";s:5:\"price\";s:7:\"1495.00\";s:4:\"name\";s:28:\"Deposition Skills: Northwest\";s:3:\"qty\";s:1:\"2\";s:8:\"subtotal\";d:2990;}s:11:\"total_items\";s:1:\"1\";s:10:\"cart_total\";s:4:\"2990\";}}');
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
  `shippingCIty` varchar(255) DEFAULT NULL,
  `shippingState` varchar(255) DEFAULT NULL,
  `shippingZip` varchar(255) DEFAULT NULL,
  `shippingCountry` varchar(255) DEFAULT NULL,
  `requireAccessibility` tinyint(4) DEFAULT NULL,
  `haveScholarship` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact`
--

LOCK TABLES `contact` WRITE;
/*!40000 ALTER TABLE `contact` DISABLE KEYS */;
INSERT INTO `contact` VALUES (49,29,1,'Mr.','Super','B','User','Jr.','Director of Direction','super@test.com','96e79218965eb72c92a549dd5a330112','+1(303)413-0551','7075556666',NULL,'Role Option 1',1,'Doe',NULL,NULL,NULL,'White/Caucasian','Medical Malpractice','345 Broadway',NULL,'Walla Walla','CA','97450','USA','345 Broadway',NULL,'Walla Walla','CA','97450','USA',0,0),(50,30,2,'Mr.','Normal','B','User','Jr.','Director of Direction','normal@test.com','96e79218965eb72c92a549dd5a330112','+1(303)413-0551','7075556666',NULL,'Role Option 1',1,'Doe',NULL,NULL,NULL,'White/Caucasian','Medical Malpractice','345 Broadway',NULL,'Walla Walla','CA','97450','USA','345 Broadway',NULL,'Walla Walla','CA','97450','USA',0,0),(51,29,3,'Mr.','Child','B','User','Jr.','Director of Direction','child@test.com','96e79218965eb72c92a549dd5a330112','+1(303)413-0551','7075556666',NULL,'Role Option 1',1,'Doe',NULL,NULL,NULL,'White/Caucasian','Medical Malpractice','345 Broadway',NULL,'Walla Walla','CA','97450','USA','345 Broadway',NULL,'Walla Walla','CA','97450','USA',0,0);
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
  `date` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contactbarinfo`
--

LOCK TABLES `contactbarinfo` WRITE;
/*!40000 ALTER TABLE `contactbarinfo` DISABLE KEYS */;
INSERT INTO `contactbarinfo` VALUES (49,'1111111','CA','1282160460'),(50,'1111111','CA','1282160460'),(51,'1111111','CA','1282160460');
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

-- Dump completed on 2010-08-18  1:53:07
