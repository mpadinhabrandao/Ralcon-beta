-- MySQL dump 10.13  Distrib 5.6.36, for Linux (x86_64)
--
-- Host: localhost    Database: apps
-- ------------------------------------------------------
-- Server version	5.6.36

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
-- Table structure for table `dependencies`
--

DROP TABLE IF EXISTS `dependencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dependencies` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `namespace` varchar(255) NOT NULL DEFAULT '',
  `version` char(36) NOT NULL DEFAULT '',
  `namespace_d` varchar(255) NOT NULL DEFAULT '',
  `version_d` char(36) NOT NULL DEFAULT '',
  `deleted` int(11) DEFAULT '0',
  `last_edit` char(35) DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `namespace` (`namespace`,`version`,`namespace_d`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dependencies`
--

LOCK TABLES `dependencies` WRITE;
/*!40000 ALTER TABLE `dependencies` DISABLE KEYS */;
INSERT INTO `dependencies` VALUES (1,'Shop','v1.0.6','Tag','v1.0.2',0,'2017-04-20 09:19:40'),(2,'Shop','v1.0.6','myApp4','v1.0.7',0,'2017-04-20 13:20:21');
/*!40000 ALTER TABLE `dependencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `installables`
--

DROP TABLE IF EXISTS `installables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `installables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `namespace` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  `last_edit` char(65) DEFAULT '0000-00-00 00:00:00',
  `git_url` varchar(255) DEFAULT NULL,
  `git_key` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `namespace` (`namespace`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `installables`
--

LOCK TABLES `installables` WRITE;
/*!40000 ALTER TABLE `installables` DISABLE KEYS */;
INSERT INTO `installables` VALUES (1,'Shop','App',0,'0000-00-00 00:00:00',NULL,NULL),(3,'teste','App',1,'2017-05-06 19:48:34','git@bitbucket.org:mbrandaobsolus/testegit.git',NULL),(4,'myApp4','App',0,'0000-00-00 00:00:00',NULL,NULL),(5,'my','App',1,'2017-04-18 14:09:49',NULL,NULL),(6,'Tag','App',0,'2017-04-19 23:48:15',NULL,NULL);
/*!40000 ALTER TABLE `installables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `versions`
--

DROP TABLE IF EXISTS `versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `namespace` varchar(255) DEFAULT NULL,
  `version` char(36) NOT NULL DEFAULT 'v',
  `deleted` int(11) DEFAULT '0',
  `last_edit` char(65) DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `namespace_2` (`namespace`,`version`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `versions`
--

LOCK TABLES `versions` WRITE;
/*!40000 ALTER TABLE `versions` DISABLE KEYS */;
INSERT INTO `versions` VALUES (1,'Shop','v1.0.4',1,'2017-04-19 12:59:50'),(23,'Shop','v1.0.5',0,'2017-04-19 13:12:07'),(25,'Shop','v1.0.6',0,'2017-04-19 13:13:09'),(26,'myApp4','v1.0.6',0,'2017-04-19 14:00:09'),(29,'myApp4','v1.0.7',0,'2017-04-19 14:14:48'),(35,'myApp4','v1.0.8',0,'2017-04-19 14:15:42'),(36,'Shop','v0.0.0',0,'2017-04-20 00:33:09'),(37,'Tag','v0.0.0',1,'2017-04-20 00:48:18'),(39,'Tag','v1.0.0',0,'2017-04-20 00:35:14'),(40,'Tag','v1.0.2',0,'2017-04-20 00:42:50'),(41,'teste','v0.0.0',0,'2017-05-06 18:27:16');
/*!40000 ALTER TABLE `versions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-07-19 12:27:45
