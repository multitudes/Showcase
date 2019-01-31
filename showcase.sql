-- MySQL dump 10.13  Distrib 5.7.23, for osx10.9 (x86_64)
--
-- Host: localhost    Database: showcase
-- ------------------------------------------------------
-- Server version	5.7.23

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
-- Table structure for table `hash`
--

DROP TABLE IF EXISTS `hash`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hash` (
  `id_users` int(11) NOT NULL,
  `hash` varchar(255) NOT NULL,
  UNIQUE KEY `id_users` (`id_users`),
  UNIQUE KEY `id_users_2` (`id_users`,`hash`),
  KEY `hash` (`hash`(20)),
  CONSTRAINT `hash_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hash`
--

LOCK TABLES `hash` WRITE;
/*!40000 ALTER TABLE `hash` DISABLE KEYS */;
INSERT INTO `hash` VALUES (1,'$2y$10$5kKVS3ur8kLIhlcv4LEQROtQIPI6m0RZH8.AiuSKTU/roZcxrjmxy'),(46,'$2y$10$m/5dMQTPYS4nV3yMxg7KCe9l..SlF0eIeDV5x48JNqa/HeA00pMIO'),(1694,'$2y$10$KKSk99mhG8NLyj.QY4haMuKekGw0qHWJipuL8cC.K1AeO.igzIs6m'),(1825,'$2y$10$tXuxZbm9zekmDcFTzJ1JOekojTIFg07SwNtkWa4FBVEQL.3/79Va2'),(2131,'$2y$10$qbN7lUXTAX7Lqgcpa4m6K.4sEgdCvN7UVuAJjaeW22xH3ciBA/MSy'),(2177,'$2y$10$OUQ7b4FG7CQ18fK1tnqLtOrQ6ZZvHK8yJ6oTauiP4dzTD4fK8/KvO');
/*!40000 ALTER TABLE `hash` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `is_admin`
--

DROP TABLE IF EXISTS `is_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `is_admin` (
  `id_users` int(11) NOT NULL,
  `admin` varchar(3) NOT NULL,
  UNIQUE KEY `id_users` (`id_users`),
  CONSTRAINT `is_admin_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `is_admin`
--

LOCK TABLES `is_admin` WRITE;
/*!40000 ALTER TABLE `is_admin` DISABLE KEYS */;
INSERT INTO `is_admin` VALUES (1,'yes'),(46,'no'),(1694,'no'),(1825,'no'),(2131,'no'),(2177,'no');
/*!40000 ALTER TABLE `is_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `id_users` int(11) NOT NULL,
  `logged_in` datetime DEFAULT NULL,
  `logged_out` datetime DEFAULT NULL,
  `tracks_downloaded` varchar(2000) DEFAULT NULL,
  `tracks_listened` varchar(2000) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`id`),
  KEY `message` (`message`(20)),
  KEY `id_users` (`id_users`),
  CONSTRAINT `log_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log`
--

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
INSERT INTO `log` VALUES (1,2131,'2019-01-31 10:49:19','2019-01-31 10:49:39',NULL,NULL,NULL);
/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `playlists`
--

DROP TABLE IF EXISTS `playlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `playlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playlist_name` varchar(100) DEFAULT NULL,
  `customized_page_header` varchar(1000) DEFAULT NULL,
  `customized_text` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `playlist_name` (`playlist_name`),
  KEY `playlist_name_2` (`playlist_name`(20)),
  KEY `customized_text` (`customized_text`(20))
) ENGINE=InnoDB AUTO_INCREMENT=2150 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `playlists`
--

LOCK TABLES `playlists` WRITE;
/*!40000 ALTER TABLE `playlists` DISABLE KEYS */;
INSERT INTO `playlists` VALUES (1,'electro','Electro Playlist','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero dolorum quisquam culpa quae voluptatibus saepe a ad excepturi quam, sunt ex ullam officia porro architecto, nam et esse minus reiciendis? Enim aliquid accusantium repellat, maiores magnam quidem rem cumque officia omnis officiis. Omnis doloremque error, maxime vitae aspernatur nemo eos.<br>\r\n        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero dolorum quisquam culpa quae voluptatibus saepe a ad excepturi quam, sunt ex ullam officia porro architecto, nam et esse minus reiciendis? Enim aliquid accusantium repellat, maiores magnam quidem rem cumque officia omnis officiis. Omnis doloremque error, maxime vitae aspernatur nemo eos.'),(1599,'test2','test2','test22222222222'),(1757,'Piano','Piano Playlist','I selected these tracks for you!'),(2068,'Electronic','Electronic','Electronic music is music that employs electronic musical instruments, digital instruments and circuitry-based music technology. In general, a distinction can be made between sound produced using electromechanical means (electroacoustic music), and that produced using electronics only.'),(2124,'Amin','Amin\'s Playlist','Here is a playlist for you');
/*!40000 ALTER TABLE `playlists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salt`
--

DROP TABLE IF EXISTS `salt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salt` (
  `id_users` int(11) NOT NULL,
  `salt` varchar(16) NOT NULL,
  UNIQUE KEY `id_users` (`id_users`),
  UNIQUE KEY `id_users_2` (`id_users`,`salt`),
  CONSTRAINT `salt_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salt`
--

LOCK TABLES `salt` WRITE;
/*!40000 ALTER TABLE `salt` DISABLE KEYS */;
INSERT INTO `salt` VALUES (1,'3c4060a9e619dfeb'),(46,'1d7cb24913a2484c'),(1694,'4c2c271df261c29d'),(1825,'6d428e0db52673e9'),(2131,'be564d5f797661ac'),(2177,'ed6e91c8067c50f4');
/*!40000 ALTER TABLE `salt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id_tracks` int(11) DEFAULT NULL,
  `tag` varchar(100) DEFAULT NULL,
  UNIQUE KEY `id_tracks` (`id_tracks`),
  UNIQUE KEY `id_tracks_2` (`id_tracks`,`tag`),
  CONSTRAINT `tags_ibfk_1` FOREIGN KEY (`id_tracks`) REFERENCES `tracks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'corporate'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'electronica'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'emotional'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'happy'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'inspirational'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'orchestral'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'piano'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad'),(NULL,'sad');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `track_format`
--

DROP TABLE IF EXISTS `track_format`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `track_format` (
  `id_tracks` int(11) NOT NULL,
  `format` varchar(5) NOT NULL,
  UNIQUE KEY `id_tracks` (`id_tracks`),
  UNIQUE KEY `id_tracks_2` (`id_tracks`,`format`),
  CONSTRAINT `track_format_ibfk_1` FOREIGN KEY (`id_tracks`) REFERENCES `tracks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `track_format`
--

LOCK TABLES `track_format` WRITE;
/*!40000 ALTER TABLE `track_format` DISABLE KEYS */;
/*!40000 ALTER TABLE `track_format` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tracks`
--

DROP TABLE IF EXISTS `tracks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tracks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `track_name` varchar(100) NOT NULL,
  `soundcloud_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `track_name` (`track_name`),
  UNIQUE KEY `soundcloud_id` (`soundcloud_id`),
  KEY `track_name_2` (`track_name`(20))
) ENGINE=InnoDB AUTO_INCREMENT=6977 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tracks`
--

LOCK TABLES `tracks` WRITE;
/*!40000 ALTER TABLE `tracks` DISABLE KEYS */;
INSERT INTO `tracks` VALUES (1,'Power To Inspire',342002845),(2,'Silence I',166769769),(2389,'Eye Opener (for Choir & Piano)',525633687),(2425,'Nocturne No.2',534269169),(5663,'Strange Night In A Strange Town (The Last Rehearsal Mix)',220259722),(5664,'Eye Opener (The Last Rehearsal Mix)',220260092),(5665,'Be The Change',492263835),(5666,'MOOD I',515601660),(5667,'Twilight Sky',455073204),(5669,'Abandoned Loyalty',441531300),(5670,'Window III',213975126),(5671,'Silent Wings (Slow Down)',121058300),(5672,'The Big Picture',488029833);
/*!40000 ALTER TABLE `tracks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tracks_in_playlist`
--

DROP TABLE IF EXISTS `tracks_in_playlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tracks_in_playlist` (
  `id_playlist` int(11) NOT NULL,
  `id_tracks` int(11) NOT NULL,
  UNIQUE KEY `id_playlist` (`id_playlist`,`id_tracks`),
  KEY `id_tracks` (`id_tracks`),
  CONSTRAINT `tracks_in_playlist_ibfk_1` FOREIGN KEY (`id_playlist`) REFERENCES `playlists` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tracks_in_playlist_ibfk_2` FOREIGN KEY (`id_tracks`) REFERENCES `tracks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tracks_in_playlist`
--

LOCK TABLES `tracks_in_playlist` WRITE;
/*!40000 ALTER TABLE `tracks_in_playlist` DISABLE KEYS */;
INSERT INTO `tracks_in_playlist` VALUES (1,1),(2068,1),(1,2),(1,2389),(1599,2389),(1757,2389),(2068,2389),(2124,2389),(1,2425),(1757,2425),(2068,5663),(2124,5663),(2068,5665),(2068,5667),(2068,5669),(2124,5669),(2068,5670),(2068,5671);
/*!40000 ALTER TABLE `tracks_in_playlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_details`
--

DROP TABLE IF EXISTS `user_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_details` (
  `id_users` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `comments` text,
  UNIQUE KEY `id_users` (`id_users`),
  KEY `comments` (`comments`(20)),
  KEY `email` (`email`(10)),
  KEY `last_name` (`last_name`(10)),
  KEY `first_name` (`first_name`(10)),
  CONSTRAINT `user_details_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_details`
--

LOCK TABLES `user_details` WRITE;
/*!40000 ALTER TABLE `user_details` DISABLE KEYS */;
INSERT INTO `user_details` VALUES (1,'seba','mora','sebmo@seba.com','the boss!! The admin'),(46,'','','',''),(1825,NULL,NULL,NULL,'The magic link is:  localhost:8890/php/authenticate.php?id=1825&token=$2y$10$tXuxZbm9zekmDcFTzJ1JOekojTIFg07SwNtkWa4FBVEQL.3/79Va2'),(2131,'','','','The magic link is:  localhost:8890/php/authenticate.php?id=2131&token=$2y$10$qbN7lUXTAX7Lqgcpa4m6K.4sEgdCvN7UVuAJjaeW22xH3ciBA/MSy'),(2177,NULL,NULL,NULL,'The magic link is:  localhost:8890/php/authenticate.php?id=2177&token=$2y$10$OUQ7b4FG7CQ18fK1tnqLtOrQ6ZZvHK8yJ6oTauiP4dzTD4fK8/KvO');
/*!40000 ALTER TABLE `user_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `username_2` (`username`(20))
) ENGINE=InnoDB AUTO_INCREMENT=2192 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2177,'Amin'),(2131,'Electronic'),(1825,'Piano'),(1,'seba'),(1694,'test2'),(46,'Tim ');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_playlist`
--

DROP TABLE IF EXISTS `users_playlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_playlist` (
  `id_users` int(11) NOT NULL,
  `id_playlists` int(11) NOT NULL,
  UNIQUE KEY `id_playlists` (`id_playlists`,`id_users`),
  KEY `id_users` (`id_users`),
  CONSTRAINT `users_playlist_ibfk_1` FOREIGN KEY (`id_playlists`) REFERENCES `playlists` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_playlist_ibfk_2` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_playlist`
--

LOCK TABLES `users_playlist` WRITE;
/*!40000 ALTER TABLE `users_playlist` DISABLE KEYS */;
INSERT INTO `users_playlist` VALUES (1,1),(1694,1599),(1825,1757),(2131,2068),(2177,2124);
/*!40000 ALTER TABLE `users_playlist` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-01-31 10:50:13
