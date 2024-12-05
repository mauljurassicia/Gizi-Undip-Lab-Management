-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: lab_app
-- ------------------------------------------------------
-- Server version	8.0.39

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `borrowings`
--

DROP TABLE IF EXISTS `borrowings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `borrowings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint unsigned NOT NULL,
  `userable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userable_id` bigint unsigned NOT NULL,
  `activity_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `equipment_id` bigint unsigned NOT NULL,
  `quantity` int unsigned NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `return_quantity` int unsigned DEFAULT NULL,
  `return_date` datetime DEFAULT NULL,
  `report` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected','cancelled','returned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `creator_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `borrowings_userable_type_userable_id_index` (`userable_type`,`userable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `borrowings`
--

LOCK TABLES `borrowings` WRITE;
/*!40000 ALTER TABLE `borrowings` DISABLE KEYS */;
INSERT INTO `borrowings` VALUES (12,1,'App\\User',1,'Penimbangan Bayi Puskesmas Bayuwangi',NULL,5,1,'2024-11-22 00:00:00','2024-11-29 00:00:00','2024-11-22 03:01:54','2024-12-02 08:20:01',1,NULL,NULL,'approved',NULL),(13,1,'App\\User',11,'Masak Ikan Pake Bahan Kimia',NULL,6,1,'2024-11-22 00:00:00','2024-11-30 00:00:00','2024-11-22 10:09:28','2024-12-02 08:19:49',NULL,NULL,NULL,'approved',NULL),(15,1,'App\\User',10,'Maulana Skripsi',NULL,9,12,'2024-11-26 00:00:00','2024-11-29 00:00:00','2024-11-26 08:39:11','2024-11-26 08:40:09',NULL,NULL,NULL,'returned',NULL),(16,1,'App\\Models\\Group',2,'Masak-Masakan Dulu','Pinjam Untuk Memasak Kue Anti Diabetes',8,6,'2024-11-28 00:00:00','2024-12-13 00:00:00','2024-11-28 06:55:03','2024-12-02 08:20:06',NULL,NULL,NULL,'approved',NULL),(17,1,'App\\User',10,'Maulana',NULL,5,2,'2024-11-28 00:00:00','2024-11-28 00:00:00','2024-11-28 09:54:10','2024-12-04 07:22:25',NULL,NULL,NULL,'returned',NULL),(18,1,'App\\User',12,'Pengukuran Lingkar Kepala Bayi',NULL,4,1,'2024-11-29 00:00:00','2024-12-06 00:00:00','2024-11-29 07:53:05','2024-12-02 08:19:45',NULL,NULL,NULL,'approved',NULL),(19,2,'App\\User',10,'Pengukuran Antropometri Di Puskesmas Bulu Lor',NULL,5,2,'2024-12-02 00:00:00','2024-12-06 00:00:00','2024-12-02 03:23:46','2024-12-03 03:38:42',NULL,NULL,NULL,'returned',NULL),(20,1,'App\\Models\\Group',2,'Pengukuran Berat Badan Dan Komposisi Tubuh Siswi SMAN 5 Semarang',NULL,9,6,'2024-12-03 00:00:00','2024-12-27 00:00:00','2024-12-03 03:39:34','2024-12-03 09:21:17',NULL,NULL,NULL,'returned',NULL),(29,1,'App\\User',10,'Maulana',NULL,6,1,'2024-12-04 00:00:00','2024-12-13 00:00:00','2024-12-04 09:50:16','2024-12-05 04:24:01',NULL,NULL,NULL,'approved',NULL);
/*!40000 ALTER TABLE `borrowings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `broken_equipments`
--

DROP TABLE IF EXISTS `broken_equipments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `broken_equipments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `equipment_id` bigint unsigned NOT NULL,
  `quantity` int unsigned NOT NULL,
  `broken_date` datetime NOT NULL,
  `return_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `logbook_id` bigint unsigned DEFAULT NULL,
  `image` text COLLATE utf8mb4_unicode_ci,
  `report` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `broken_equipments`
--

LOCK TABLES `broken_equipments` WRITE;
/*!40000 ALTER TABLE `broken_equipments` DISABLE KEYS */;
INSERT INTO `broken_equipments` VALUES (9,1,10,6,1,'2024-11-29 00:00:00',NULL,'2024-11-29 06:15:47','2024-12-05 06:37:42',NULL,NULL,NULL),(10,1,12,4,1,'2024-11-29 00:00:00',NULL,'2024-11-29 07:55:31','2024-11-29 07:55:31',62,'storage/broken/07565f3c80d340deec5c0351a73f9c43.png',NULL),(11,2,10,5,2,'2024-12-02 00:00:00',NULL,'2024-12-02 07:36:17','2024-12-02 07:36:17',65,'storage/broken/668084883347017f82bb374b951083e9.png',NULL),(12,1,10,9,6,'2024-12-03 00:00:00','2024-12-05 00:00:00','2024-12-03 09:21:17','2024-12-05 06:43:47',73,NULL,NULL);
/*!40000 ALTER TABLE `broken_equipments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courses_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,'Gizi Masyarakat','GM-303','storage/courses/4e737f612ddbf5e119891f62b4b05e6f.jpg',NULL,1,'2024-10-16 10:45:10','2024-10-17 07:30:19'),(2,'Gizi Olahraga','GO-2020','storage/courses/a242a16ca0d4cfd179c41b67e88a25e3.jpg',NULL,1,'2024-10-17 08:15:15','2024-10-17 08:15:15');
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cover_letters`
--

DROP TABLE IF EXISTS `cover_letters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cover_letters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cover_letterable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_letterable_id` bigint unsigned NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cover_letters_cover_letterable_type_cover_letterable_id_index` (`cover_letterable_type`,`cover_letterable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cover_letters`
--

LOCK TABLES `cover_letters` WRITE;
/*!40000 ALTER TABLE `cover_letters` DISABLE KEYS */;
INSERT INTO `cover_letters` VALUES (9,'App\\Models\\Schedule',123,'storage/coverLetter/750cdc8695f39c08b044f9e2ea0fa15b.pdf','2024-12-04 07:16:25','2024-12-04 07:16:25'),(10,'App\\Models\\Schedule',124,'storage/coverLetter/750cdc8695f39c08b044f9e2ea0fa15b.pdf','2024-12-04 07:16:25','2024-12-04 07:16:25'),(11,'App\\Models\\Borrowing',29,'storage/coverLetter/7fba52e26ac1d99b3a937771199d4e1a.pdf','2024-12-04 09:50:16','2024-12-04 09:50:16');
/*!40000 ALTER TABLE `cover_letters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipment`
--

DROP TABLE IF EXISTS `equipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipment` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci,
  `price` int unsigned NOT NULL,
  `status` tinyint NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('chemical','electronic','breakable','consumables','utility','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_type` enum('gram','kg','unit','pcs','liter','boxes') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unit',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipment`
--

LOCK TABLES `equipment` WRITE;
/*!40000 ALTER TABLE `equipment` DISABLE KEYS */;
INSERT INTO `equipment` VALUES (1,'Sempak Firauan','storage/equipment/859f4ccee36f1aa53ef46f377891e5c5.png',250000,1,NULL,'chemical','unit','2024-10-10 15:55:09','2024-10-10 16:07:56','2024-10-10 16:07:56'),(2,'Namo',NULL,10000,1,NULL,'chemical','unit','2024-10-11 00:11:14','2024-10-11 00:11:14',NULL),(3,'Kapal Laut','storage/equipment/0d074f29a106e888cbe839ce0823be81.jpg',20000,1,NULL,'chemical','kg','2024-10-11 06:56:20','2024-10-11 06:56:20',NULL),(4,'Meteran','storage/equipment/50dc9fbd70287a9ea9aede39ae7459ba.jpg',75000,1,'<p><strong>Meteran Manusia</strong></p>\r\n<p>Meteran ini digunakan untuk mengukur tinggi badan manusia. Dapat mengukur tinggi hingga maksimal 2m.<br /></p>','utility','unit','2024-10-11 07:27:14','2024-10-11 07:27:14',NULL),(5,'Timbangan Bayi','storage/equipment/96bd89c0f432b33c188f8e9b395c0919.jpeg',250000,1,NULL,'utility','unit','2024-10-11 07:28:18','2024-10-11 07:28:18',NULL),(6,'Tabung Erlenmeyer','storage/equipment/f507a45cec5ec8e6ee3b32411820b7a4.jpg',20000,1,NULL,'breakable','unit','2024-10-11 07:30:55','2024-10-11 07:30:55',NULL),(7,'Oven','storage/equipment/0c91703d737a3247a36dca7a43eebae6.jpg',1000000,1,NULL,'electronic','unit','2024-10-11 07:32:11','2024-10-11 07:32:11',NULL),(8,'Labu ukur','storage/equipment/c2fac2a3bd17764a91070b2b1afeaea9.webp',60000,1,NULL,'breakable','unit','2024-10-11 07:44:32','2024-10-11 07:44:32',NULL),(9,'U310 Body Composition Analyzer','storage/equipment/2d7c17ace79ac972b3b49d20fa0f7731.jpg',3000000,1,NULL,'electronic','unit','2024-10-11 09:41:39','2024-10-11 09:41:39',NULL);
/*!40000 ALTER TABLE `equipment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipment_rooms`
--

DROP TABLE IF EXISTS `equipment_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipment_rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `equipment_id` bigint unsigned NOT NULL,
  `room_id` bigint unsigned NOT NULL,
  `quantity` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipment_rooms`
--

LOCK TABLES `equipment_rooms` WRITE;
/*!40000 ALTER TABLE `equipment_rooms` DISABLE KEYS */;
INSERT INTO `equipment_rooms` VALUES (6,4,1,1,NULL,NULL),(10,9,2,3,NULL,NULL),(14,6,2,10,NULL,NULL),(15,5,2,2,NULL,NULL),(37,6,1,1,NULL,NULL),(40,5,1,3,NULL,NULL),(41,9,1,12,NULL,NULL),(42,2,1,2,NULL,NULL),(43,8,1,20,NULL,NULL);
/*!40000 ALTER TABLE `equipment_rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_id` bigint unsigned DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `banner` text COLLATE utf8mb4_unicode_ci,
  `thumbnail` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'5 Ma',1,NULL,NULL,'storage/group/655dbc5321bd4ee81c0924393d1d0719.jpg','2024-10-21 05:44:46','2024-10-25 08:42:12','active'),(2,'Praktikum Masak-Masak',1,NULL,NULL,NULL,'2024-11-22 06:51:33','2024-11-22 07:07:48','active');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logbooks`
--

DROP TABLE IF EXISTS `logbooks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logbooks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('in','out') COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` datetime NOT NULL,
  `report` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `logbookable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logbookable_id` bigint unsigned NOT NULL,
  `userable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userable_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `logbooks_logbookable_type_logbookable_id_index` (`logbookable_type`,`logbookable_id`),
  KEY `logbooks_userable_type_userable_id_index` (`userable_type`,`userable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logbooks`
--

LOCK TABLES `logbooks` WRITE;
/*!40000 ALTER TABLE `logbooks` DISABLE KEYS */;
INSERT INTO `logbooks` VALUES (67,'in','2024-12-02 15:32:00',NULL,'2024-12-02 08:32:40','2024-12-02 08:32:40','App\\Models\\Borrowing',19,'App\\User',10),(68,'in','2024-12-02 15:32:00',NULL,'2024-12-02 08:32:49','2024-12-02 08:32:49','App\\Models\\Borrowing',17,'App\\User',10),(70,'out','2024-12-03 10:38:00',NULL,'2024-12-03 03:38:42','2024-12-03 03:38:42','App\\Models\\Borrowing',19,'App\\User',10),(72,'in','2024-12-03 16:20:00',NULL,'2024-12-03 09:21:00','2024-12-03 09:21:00','App\\Models\\Borrowing',20,'App\\Models\\Group',2),(73,'out','2024-12-03 16:21:00',NULL,'2024-12-03 09:21:17','2024-12-03 09:21:17','App\\Models\\Borrowing',20,'App\\Models\\Group',2),(74,'out','2024-12-04 14:22:00',NULL,'2024-12-04 07:22:25','2024-12-04 07:22:25','App\\Models\\Borrowing',17,'App\\User',10);
/*!40000 ALTER TABLE `logbooks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2023_06_01_000000_create_failed_jobs_table',1),(2,'2023_06_01_000000_create_pages_table',1),(3,'2023_06_01_000000_create_password_resets_table',1),(4,'2023_06_01_000000_create_permission_tables',1),(8,'2023_06_01_000000_create_settings_table',2),(9,'2023_06_01_000000_create_users_table',2),(10,'2024_10_09_025943_create_equipment_table',3),(11,'2024_10_09_030330_create_rooms_table',4),(12,'2024_10_09_031228_create_appointments_table',5),(13,'2024_10_10_091636_create_courses_table',5),(14,'2024_10_10_101136_create_groups_table',5),(15,'2024_10_10_103347_create_users_groups_table',5),(16,'2024_10_10_124659_update_rooms_table',6),(17,'2024_10_10_170904_update_users_table',7),(18,'2024_10_10_171221_update_rooms_table',8),(19,'2024_10_11_001138_update_equipments_table',9),(21,'2024_10_11_002632_update_rooms_table',10),(22,'2024_10_11_031319_create_equipments_rooms_table',11),(23,'2024_10_11_035838_update_rooms_table',12),(24,'2024_10_13_063713_update_appointments_table',13),(25,'2024_10_13_064031_update_appointments_table',14),(27,'2024_10_17_033708_alter_users_table',15),(28,'2024_10_17_062242_alter_courses_table',16),(36,'2024_10_17_064056_alter_courses_table',17),(37,'2024_10_17_073940_alter_rooms_table',18),(38,'2024_10_18_031942_create_schedules_table',19),(41,'2024_10_18_032040_create_routines_table',20),(42,'2024_10_18_032137_delete_appointments_table',20),(43,'2024_10_18_042042_create_borrowings_table',21),(44,'2024_10_18_042247_create_logbooks_table',22),(45,'2024_10_18_042541_alter_borrowings_table',23),(46,'2024_10_18_043119_create_broken_equipments_table',24),(47,'2024_10_18_044320_create_return_reports_table',25),(48,'2024_11_14_074652_alter_schedules_table',26),(49,'2024_11_14_074730_create_schedule_user_table',26),(50,'2024_11_14_075043_alter_schedule_user_table',27),(51,'2024_11_14_075905_delete_schdule_user_table',27),(52,'2024_11_14_080240_create_scheduleables_table',28),(55,'2024_11_14_080525_alter_rooms_table',29),(56,'2024_11_14_080613_alter_schedules_table',30),(57,'2024_11_14_090226_alter_schedules_table',31),(58,'2024_11_15_043939_alter_logbooks_table',32),(59,'2024_11_18_024218_alter_schedules_table',32),(60,'2024_11_18_045220_alter_schedules_table',33),(61,'2024_11_18_084432_alter_scheduleables_table',34),(62,'2024_11_20_040416_alter_borrowings_table',35),(63,'2024_11_20_121455_alter_logbooks_table',36),(64,'2024_11_21_011429_alter_logbooks_table',37),(65,'2024_11_22_030325_alter_broken_equipments_table',38),(66,'2024_11_22_070031_alter_groups_tabel',39),(67,'2024_11_28_072727_alter_broken_equipments_table',40),(68,'2024_11_29_044223_alter_return_reports_table',41),(69,'2024_11_29_063304_alter_return_reports_table',42),(71,'2024_11_29_063733_alter_return_reports_table',43),(72,'2024_12_03_031230_create_cover_letters_table',44),(73,'2024_12_03_033131_alter_schedules_table',45),(74,'2024_12_03_033151_alter_borrowings_table',45);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\User',1),(2,'App\\User',2),(5,'App\\User',4),(4,'App\\User',7),(5,'App\\User',9),(5,'App\\User',10),(5,'App\\User',11),(5,'App\\User',12);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions_label_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'permissiongroup-create','web',1,'2024-10-09 02:51:06',NULL,NULL),(2,'permissiongroup-show','web',1,'2024-10-09 02:51:06',NULL,NULL),(3,'permissiongroup-edit','web',1,'2024-10-09 02:51:06',NULL,NULL),(4,'permissiongroup-update','web',1,'2024-10-09 02:51:06',NULL,NULL),(5,'permissiongroup-delete','web',1,'2024-10-09 02:51:06',NULL,NULL),(6,'permissiongroup-store','web',1,'2024-10-09 02:51:06',NULL,NULL),(7,'permission-create','web',2,'2024-10-09 02:51:06',NULL,NULL),(8,'permission-show','web',2,'2024-10-09 02:51:06',NULL,NULL),(9,'permission-edit','web',2,'2024-10-09 02:51:06',NULL,NULL),(10,'permission-update','web',2,'2024-10-09 02:51:06',NULL,NULL),(11,'permission-delete','web',2,'2024-10-09 02:51:06',NULL,NULL),(12,'permission-store','web',2,'2024-10-09 02:51:06',NULL,NULL),(13,'role-create','web',3,'2024-10-09 02:51:06',NULL,NULL),(14,'role-show','web',3,'2024-10-09 02:51:06',NULL,NULL),(15,'role-edit','web',3,'2024-10-09 02:51:06',NULL,NULL),(16,'role-update','web',3,'2024-10-09 02:51:06',NULL,NULL),(17,'role-delete','web',3,'2024-10-09 02:51:06',NULL,NULL),(18,'role-store','web',3,'2024-10-09 02:51:06',NULL,NULL),(19,'user-create','web',4,'2024-10-09 02:51:06',NULL,NULL),(20,'user-show','web',4,'2024-10-09 02:51:06',NULL,NULL),(21,'user-edit','web',4,'2024-10-09 02:51:06',NULL,NULL),(22,'user-update','web',4,'2024-10-09 02:51:06',NULL,NULL),(23,'user-delete','web',4,'2024-10-09 02:51:06',NULL,NULL),(24,'user-store','web',4,'2024-10-09 02:51:06',NULL,NULL),(25,'equipment-create','web',5,'2024-10-09 02:59:43','2024-10-09 02:59:43',NULL),(26,'equipment-show','web',5,'2024-10-09 02:59:43','2024-10-09 02:59:43',NULL),(27,'equipment-edit','web',5,'2024-10-09 02:59:43','2024-10-09 02:59:43',NULL),(28,'equipment-update','web',5,'2024-10-09 02:59:43','2024-10-09 02:59:43',NULL),(29,'equipment-delete','web',5,'2024-10-09 02:59:43','2024-10-09 02:59:43',NULL),(30,'equipment-store','web',5,'2024-10-09 02:59:43','2024-10-09 02:59:43',NULL),(31,'room-create','web',6,'2024-10-09 03:03:30','2024-10-09 03:03:30',NULL),(32,'room-show','web',6,'2024-10-09 03:03:30','2024-10-09 03:03:30',NULL),(33,'room-edit','web',6,'2024-10-09 03:03:30','2024-10-09 03:03:30',NULL),(34,'room-update','web',6,'2024-10-09 03:03:30','2024-10-09 03:03:30',NULL),(35,'room-delete','web',6,'2024-10-09 03:03:30','2024-10-09 03:03:30',NULL),(36,'room-store','web',6,'2024-10-09 03:03:30','2024-10-09 03:03:30',NULL),(49,'appointment-create','web',9,'2024-10-09 03:12:28','2024-10-09 03:12:28',NULL),(50,'appointment-show','web',9,'2024-10-09 03:12:28','2024-10-09 03:12:28',NULL),(51,'appointment-edit','web',9,'2024-10-09 03:12:28','2024-10-09 03:12:28',NULL),(52,'appointment-update','web',9,'2024-10-09 03:12:28','2024-10-09 03:12:28',NULL),(53,'appointment-delete','web',9,'2024-10-09 03:12:28','2024-10-09 03:12:28',NULL),(54,'appointment-store','web',9,'2024-10-09 03:12:28','2024-10-09 03:12:28',NULL),(55,'course-create','web',10,'2024-10-13 03:38:55','2024-10-13 03:38:55',NULL),(56,'course-show','web',10,'2024-10-13 03:38:55','2024-10-13 03:38:55',NULL),(57,'course-edit','web',10,'2024-10-13 03:38:55','2024-10-13 03:38:55',NULL),(58,'course-update','web',10,'2024-10-13 03:38:55','2024-10-13 03:38:55',NULL),(59,'course-delete','web',10,'2024-10-13 03:38:55','2024-10-13 03:38:55',NULL),(60,'course-store','web',10,'2024-10-13 03:38:55','2024-10-13 03:38:55',NULL),(61,'teacher-show','web',11,NULL,NULL,NULL),(62,'teacher-create','web',11,NULL,NULL,NULL),(63,'teacher-store','web',11,NULL,NULL,NULL),(64,'teacher-edit','web',11,NULL,NULL,NULL),(65,'teacher-update','web',11,NULL,NULL,NULL),(66,'teacher-delete','web',11,NULL,NULL,NULL),(67,'student-create','web',12,NULL,NULL,NULL),(68,'student-store','web',12,NULL,NULL,NULL),(69,'student-show','web',12,NULL,NULL,NULL),(70,'student-edit','web',12,NULL,NULL,NULL),(71,'student-update','web',12,NULL,NULL,NULL),(72,'student-delete','web',12,NULL,NULL,NULL),(73,'guest-show','web',13,NULL,NULL,NULL),(74,'guest-create','web',13,NULL,NULL,NULL),(75,'guest-store','web',13,NULL,NULL,NULL),(76,'guest-edit','web',13,NULL,NULL,NULL),(77,'guest-update','web',13,NULL,NULL,NULL),(78,'guest-delete','web',13,NULL,NULL,NULL),(79,'group-create','web',14,NULL,NULL,NULL),(80,'group-store','web',14,NULL,NULL,NULL),(81,'group-show','web',14,NULL,NULL,NULL),(82,'group-edit','web',14,NULL,NULL,NULL),(83,'group-update','web',14,NULL,NULL,NULL),(84,'group-delete','web',14,NULL,NULL,NULL),(85,'schedule-create','web',15,'2024-10-18 03:42:31','2024-10-18 03:42:31',NULL),(86,'schedule-show','web',15,'2024-10-18 03:42:31','2024-10-18 03:42:31',NULL),(87,'schedule-edit','web',15,'2024-10-18 03:42:31','2024-10-18 03:42:31',NULL),(88,'schedule-update','web',15,'2024-10-18 03:42:31','2024-10-18 03:42:31',NULL),(89,'schedule-delete','web',15,'2024-10-18 03:42:31','2024-10-18 03:42:31',NULL),(90,'schedule-store','web',15,'2024-10-18 03:42:31','2024-10-18 03:42:31',NULL),(91,'laborant-create','web',16,'2024-10-18 03:47:30','2024-10-18 03:47:30',NULL),(92,'laborant-show','web',16,'2024-10-18 03:47:30','2024-10-18 03:47:30',NULL),(93,'laborant-edit','web',16,'2024-10-18 03:47:30','2024-10-18 03:47:30',NULL),(94,'laborant-update','web',16,'2024-10-18 03:47:30','2024-10-18 03:47:30',NULL),(95,'laborant-delete','web',16,'2024-10-18 03:47:30','2024-10-18 03:47:30',NULL),(96,'laborant-store','web',16,'2024-10-18 03:47:30','2024-10-18 03:47:30',NULL),(97,'logBook-create','web',17,'2024-10-18 06:36:57','2024-10-18 06:36:57',NULL),(98,'logBook-show','web',17,'2024-10-18 06:36:57','2024-10-18 06:36:57',NULL),(99,'logBook-edit','web',17,'2024-10-18 06:36:57','2024-10-18 06:36:57',NULL),(100,'logBook-update','web',17,'2024-10-18 06:36:57','2024-10-18 06:36:57',NULL),(101,'logBook-delete','web',17,'2024-10-18 06:36:57','2024-10-18 06:36:57',NULL),(102,'logBook-store','web',17,'2024-10-18 06:36:57','2024-10-18 06:36:57',NULL),(103,'borrowing-create','web',18,'2024-11-15 04:37:56','2024-11-15 04:37:56',NULL),(104,'borrowing-show','web',18,'2024-11-15 04:37:56','2024-11-15 04:37:56',NULL),(105,'borrowing-edit','web',18,'2024-11-15 04:37:56','2024-11-15 04:37:56',NULL),(106,'borrowing-update','web',18,'2024-11-15 04:37:56','2024-11-15 04:37:56',NULL),(107,'borrowing-delete','web',18,'2024-11-15 04:37:56','2024-11-15 04:37:56',NULL),(108,'borrowing-store','web',18,'2024-11-15 04:37:56','2024-11-15 04:37:56',NULL),(109,'brokenEquipment-create','web',19,'2024-11-15 04:42:22','2024-11-15 04:42:22',NULL),(110,'brokenEquipment-show','web',19,'2024-11-15 04:42:22','2024-11-15 04:42:22',NULL),(111,'brokenEquipment-edit','web',19,'2024-11-15 04:42:22','2024-11-15 04:42:22',NULL),(112,'brokenEquipment-update','web',19,'2024-11-15 04:42:22','2024-11-15 04:42:22',NULL),(113,'brokenEquipment-delete','web',19,'2024-11-15 04:42:22','2024-11-15 04:42:22',NULL),(114,'brokenEquipment-store','web',19,'2024-11-15 04:42:22','2024-11-15 04:42:22',NULL),(115,'returnReport-create','web',20,'2024-11-15 04:42:55','2024-11-15 04:42:55',NULL),(116,'returnReport-show','web',20,'2024-11-15 04:42:55','2024-11-15 04:42:55',NULL),(117,'returnReport-edit','web',20,'2024-11-15 04:42:55','2024-11-15 04:42:55',NULL),(118,'returnReport-update','web',20,'2024-11-15 04:42:55','2024-11-15 04:42:55',NULL),(119,'returnReport-delete','web',20,'2024-11-15 04:42:55','2024-11-15 04:42:55',NULL),(120,'returnReport-store','web',20,'2024-11-15 04:42:55','2024-11-15 04:42:55',NULL);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions_group`
--

DROP TABLE IF EXISTS `permissions_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions_group` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_group_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions_group`
--

LOCK TABLES `permissions_group` WRITE;
/*!40000 ALTER TABLE `permissions_group` DISABLE KEYS */;
INSERT INTO `permissions_group` VALUES (1,'User Management','2024-10-09 02:51:06',NULL,NULL),(2,'Website','2024-10-09 02:51:06',NULL,NULL);
/*!40000 ALTER TABLE `permissions_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions_label`
--

DROP TABLE IF EXISTS `permissions_label`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions_label` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission_group_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_label_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions_label`
--

LOCK TABLES `permissions_label` WRITE;
/*!40000 ALTER TABLE `permissions_label` DISABLE KEYS */;
INSERT INTO `permissions_label` VALUES (1,'Permission Group',1,'2024-10-09 02:51:06',NULL,NULL),(2,'Permission',1,'2024-10-09 02:51:06',NULL,NULL),(3,'Roles',1,'2024-10-09 02:51:06',NULL,NULL),(4,'Users',1,'2024-10-09 02:51:06',NULL,NULL),(5,'Equipment',2,'2024-10-09 02:59:43','2024-10-09 02:59:43',NULL),(6,'Room',2,'2024-10-09 03:03:30','2024-10-09 03:03:30',NULL),(7,'TypeEquipment',2,'2024-10-09 03:05:27','2024-10-17 03:04:25','2024-10-17 03:04:25'),(8,'TypeRoom',2,'2024-10-09 03:06:24','2024-10-17 03:04:22','2024-10-17 03:04:22'),(9,'Appointment',2,'2024-10-09 03:12:28','2024-10-09 03:12:28',NULL),(10,'Course',2,'2024-10-13 03:38:55','2024-10-13 03:38:55',NULL),(11,'Teacher',2,'2024-10-17 03:01:33','2024-10-17 03:02:40',NULL),(12,'Student',2,'2024-10-17 03:04:03','2024-10-17 03:04:03',NULL),(13,'Guest',2,'2024-10-17 03:05:22','2024-10-17 03:05:22',NULL),(14,'Group',2,'2024-10-17 03:07:05','2024-10-17 03:07:05',NULL),(15,'Schedule',2,'2024-10-18 03:42:31','2024-10-18 03:42:31',NULL),(16,'Laborant',2,'2024-10-18 03:47:30','2024-10-18 03:47:30',NULL),(17,'LogBook',2,'2024-10-18 06:36:57','2024-10-18 06:36:57',NULL),(18,'Borrowing',2,'2024-11-15 04:37:56','2024-11-15 04:37:56',NULL),(19,'BrokenEquipment',2,'2024-11-15 04:42:22','2024-11-15 04:42:22',NULL),(20,'ReturnReport',2,'2024-11-15 04:42:55','2024-11-15 04:42:55',NULL);
/*!40000 ALTER TABLE `permissions_label` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `return_reports`
--

DROP TABLE IF EXISTS `return_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `return_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `quantity` int unsigned DEFAULT NULL,
  `return_date` datetime NOT NULL,
  `additional` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `broken_equipment_id` bigint unsigned NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `image` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(20,2) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `return_reports`
--

LOCK TABLES `return_reports` WRITE;
/*!40000 ALTER TABLE `return_reports` DISABLE KEYS */;
INSERT INTO `return_reports` VALUES (4,2,'2024-12-05 00:00:00',NULL,'2024-12-05 06:43:47','2024-12-05 06:43:47',12,'approved','storage/returnReport/2cd4098352c1d6e19b135782af4546d8.jpg',NULL);
/*!40000 ALTER TABLE `return_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1),(25,1),(26,1),(27,1),(28,1),(29,1),(30,1),(31,1),(32,1),(33,1),(34,1),(35,1),(36,1),(49,1),(50,1),(51,1),(52,1),(53,1),(54,1),(55,1),(56,1),(57,1),(58,1),(59,1),(60,1),(61,1),(62,1),(63,1),(64,1),(65,1),(66,1),(67,1),(68,1),(69,1),(70,1),(71,1),(72,1),(73,1),(74,1),(75,1),(76,1),(77,1),(78,1),(79,1),(80,1),(81,1),(82,1),(83,1),(84,1),(85,1),(86,1),(87,1),(88,1),(89,1),(90,1),(91,1),(92,1),(93,1),(94,1),(95,1),(96,1),(97,1),(98,1),(99,1),(100,1),(101,1),(102,1),(103,1),(104,1),(105,1),(106,1),(107,1),(108,1),(109,1),(110,1),(111,1),(112,1),(113,1),(114,1),(115,1),(116,1),(117,1),(118,1),(119,1),(120,1),(25,2),(26,2),(27,2),(28,2),(29,2),(30,2),(31,2),(32,2),(33,2),(34,2),(35,2),(36,2),(49,2),(50,2),(51,2),(52,2),(53,2),(54,2),(55,2),(56,2),(57,2),(58,2),(59,2),(60,2),(67,2),(68,2),(69,2),(70,2),(71,2),(72,2),(73,2),(74,2),(75,2),(76,2),(77,2),(78,2),(79,2),(80,2),(81,2),(82,2),(83,2),(84,2),(85,2),(86,2),(87,2),(88,2),(89,2),(90,2),(91,2),(92,2),(93,2),(94,2),(95,2),(96,2),(97,2),(98,2),(99,2),(100,2),(101,2),(102,2),(103,2),(104,2),(105,2),(106,2),(107,2),(108,2),(26,3),(32,3),(73,3),(74,3),(75,3),(76,3),(77,3),(78,3),(79,3),(80,3),(81,3),(82,3),(83,3),(84,3),(85,3),(86,3),(87,3),(88,3),(89,3),(90,3),(97,3),(98,3),(99,3),(100,3),(101,3),(102,3),(103,3),(104,3),(105,3),(106,3),(107,3),(108,3),(25,4),(26,4),(27,4),(28,4),(29,4),(30,4),(31,4),(32,4),(33,4),(34,4),(35,4),(36,4),(49,4),(50,4),(51,4),(52,4),(53,4),(54,4),(55,4),(56,4),(57,4),(58,4),(59,4),(60,4),(61,4),(62,4),(63,4),(64,4),(65,4),(66,4),(67,4),(68,4),(69,4),(70,4),(71,4),(72,4),(73,4),(74,4),(75,4),(76,4),(77,4),(78,4),(79,4),(80,4),(81,4),(82,4),(83,4),(84,4),(85,4),(86,4),(87,4),(88,4),(89,4),(90,4),(97,4),(98,4),(99,4),(100,4),(101,4),(102,4),(103,4),(104,4),(105,4),(106,4),(107,4),(108,4),(49,5),(50,5),(51,5),(52,5),(53,5),(54,5),(56,5),(79,5),(80,5),(81,5),(82,5),(83,5),(84,5),(85,5),(86,5),(87,5),(88,5),(89,5),(90,5),(97,5),(98,5),(99,5),(100,5),(101,5),(102,5),(103,5),(104,5),(105,5),(106,5),(107,5),(108,5),(109,5),(110,5),(111,5),(112,5),(113,5),(114,5),(115,5),(116,5),(117,5),(118,5),(119,5),(120,5);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'administrator','web','2024-10-09 02:51:06',NULL,NULL),(2,'laborant','web','2024-10-10 17:02:38','2024-10-10 17:02:38',NULL),(3,'guest','web','2024-10-10 17:03:06','2024-10-10 17:03:06',NULL),(4,'teacher','web','2024-10-17 02:41:27','2024-10-17 02:41:27',NULL),(5,'student','web','2024-10-17 02:46:16','2024-10-17 02:46:16',NULL);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `floor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `volume` int unsigned NOT NULL,
  `operational_days` json DEFAULT NULL,
  `status` tinyint NOT NULL,
  `pic_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `type` enum('kitchen','medical','chemical','food-science','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kitchen',
  PRIMARY KEY (`id`),
  KEY `rooms_pic_id_foreign` (`pic_id`),
  CONSTRAINT `rooms_pic_id_foreign` FOREIGN KEY (`pic_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rooms`
--

LOCK TABLES `rooms` WRITE;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` VALUES (1,'Ruang Pengolahan Produk','1','101','storage/rooms/ff24fc67b351e16fbc245a9a96836d22.jpg',NULL,20,'\"{\\\"senin\\\":{\\\"start\\\":\\\"00:00\\\",\\\"end\\\":\\\"23:59\\\"},\\\"selasa\\\":{\\\"start\\\":\\\"08:00\\\",\\\"end\\\":\\\"18:00\\\"},\\\"rabu\\\":{\\\"start\\\":\\\"08:30\\\",\\\"end\\\":\\\"15:00\\\"},\\\"kamis\\\":{\\\"start\\\":\\\"08:20\\\",\\\"end\\\":\\\"17:00\\\"},\\\"jumat\\\":{\\\"start\\\":\\\"08:20\\\",\\\"end\\\":\\\"17:00\\\"},\\\"sabtu\\\":{\\\"start\\\":\\\"08:20\\\",\\\"end\\\":\\\"15:00\\\"}}\"',1,2,'2024-10-10 17:15:49','2024-12-02 08:08:10',NULL,'kitchen'),(2,'Ruang Lab Sukses Ababdi','1','3.01','storage/rooms/d77e03025d5318244606fbf873312fbc.jpg',NULL,30,'\"{\\\"senin\\\":{\\\"start\\\":\\\"08:00\\\",\\\"end\\\":\\\"17:00\\\"},\\\"selasa\\\":{\\\"start\\\":\\\"08:00\\\",\\\"end\\\":\\\"17:00\\\"},\\\"rabu\\\":{\\\"start\\\":\\\"08:00\\\",\\\"end\\\":\\\"17:00\\\"},\\\"kamis\\\":{\\\"start\\\":\\\"08:00\\\",\\\"end\\\":\\\"17:00\\\"},\\\"jumat\\\":{\\\"start\\\":\\\"13:00\\\",\\\"end\\\":\\\"17:00\\\"}}\"',1,2,'2024-10-11 01:33:29','2024-10-22 09:51:27',NULL,'kitchen');
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `routines`
--

DROP TABLE IF EXISTS `routines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `routines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint unsigned NOT NULL,
  `userable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` int unsigned NOT NULL DEFAULT '0',
  `end_time` int unsigned NOT NULL DEFAULT '0',
  `course_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `routines_userable_type_userable_id_index` (`userable_type`,`userable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `routines`
--

LOCK TABLES `routines` WRITE;
/*!40000 ALTER TABLE `routines` DISABLE KEYS */;
/*!40000 ALTER TABLE `routines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scheduleables`
--

DROP TABLE IF EXISTS `scheduleables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `scheduleables` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `schedule_id` bigint unsigned NOT NULL,
  `scheduleable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheduleable_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scheduleables_scheduleable_type_scheduleable_id_index` (`scheduleable_type`,`scheduleable_id`),
  KEY `scheduleables_schedule_id_index` (`schedule_id`),
  CONSTRAINT `scheduleables_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scheduleables`
--

LOCK TABLES `scheduleables` WRITE;
/*!40000 ALTER TABLE `scheduleables` DISABLE KEYS */;
INSERT INTO `scheduleables` VALUES (148,119,'App\\User',11,NULL,NULL),(149,120,'App\\User',11,NULL,NULL),(150,121,'App\\User',11,NULL,NULL),(152,123,'App\\User',10,NULL,NULL),(153,124,'App\\User',10,NULL,NULL);
/*!40000 ALTER TABLE `scheduleables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedules`
--

DROP TABLE IF EXISTS `schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_schedule` datetime NOT NULL,
  `end_schedule` datetime NOT NULL,
  `status` enum('approved','rejected','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `course_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `associated_info` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grouped_schedule_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schedule_type` enum('weekly','monthly','onetime','series') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'onetime',
  `creator_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `schedules_course_id_foreign` (`course_id`),
  KEY `schedules_room_id_foreign` (`room_id`),
  CONSTRAINT `schedules_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `schedules_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedules`
--

LOCK TABLES `schedules` WRITE;
/*!40000 ALTER TABLE `schedules` DISABLE KEYS */;
INSERT INTO `schedules` VALUES (119,1,'Makan Nasi','2024-12-04 08:30:00','2024-12-04 11:00:00','approved',2,'2024-12-04 03:51:51','2024-12-04 03:51:51',NULL,'64EBF3E','weekly',1),(120,1,'Makan Nasi','2024-12-11 08:30:00','2024-12-11 11:00:00','approved',2,'2024-12-04 03:51:51','2024-12-04 03:51:51',NULL,'64EBF3E','weekly',1),(121,1,'Makan Nasi','2024-12-18 08:30:00','2024-12-18 11:00:00','approved',2,'2024-12-04 03:51:51','2024-12-04 03:51:51',NULL,'64EBF3E','weekly',1),(123,1,'Makan Ikan Manusia','2024-12-20 08:30:00','2024-12-20 15:00:00','pending',1,'2024-12-04 07:16:25','2024-12-04 07:16:25',NULL,'1EA89F5','series',10),(124,1,'Makan Ikan Manusia','2024-12-12 08:30:00','2024-12-12 15:00:00','pending',1,'2024-12-04 07:16:25','2024-12-04 07:16:25',NULL,'1EA89F5','series',10);
/*!40000 ALTER TABLE `schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` text COLLATE utf8mb4_unicode_ci,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `identity_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin@redtech.co.id','2024-10-10 12:23:45','Arashi-oven-elektrik-20-liter-M20A-4.jpg','$2y$10$i7NvQMEkAGAwL4bBJorF4uEKza.7/2YVyVTy0nXcpg8fVvvLpeMiu','Wnc8mNSB0qaMzLOFJSt575X9va90bGphxycILZenFxQqt4E7mEyx6E6NPrQ7','2024-10-10 12:23:45','2024-10-16 09:33:58',NULL,NULL),(2,'Mba Mutia','Meiracle@gmail.com',NULL,'storage/laborants./f1421edea43ce188a8f3548063f9013b.jpg','$2y$10$FV/KUEgu0lPCikHVoXi6WegRpiAyXi37wYUZLxePzXug6/9r3iDvS',NULL,'2024-10-10 17:10:00','2024-10-21 02:53:33',NULL,'2345678908765432'),(4,'STudent 1','student33@gmail.com',NULL,'storage/students/b03e8d39adc1f17d4a2a9ea244009c57.jpg','$2y$10$C.4AcUy4223Ixi0WCguKG.FCQKAJsCpn9vacxN7QhF0hltT4/F9nG',NULL,'2024-10-17 02:46:54','2024-10-17 05:47:27',NULL,'22030119130054'),(7,'Pak Budiman','budisehat88@gmail.com',NULL,'storage/teachers/26bd2471e018e075fc8954eee051cfc8.jpg','$2y$10$t/WksepEH.MA.vcZx.IF0.MX/xmfVQuXZFKGr9Q9b6/WSpU4A46W.',NULL,'2024-10-17 05:40:59','2024-10-17 05:40:59',NULL,'22030119130054'),(10,'Muhamad Maulana Ihsan','smamalik33@gmail.com',NULL,NULL,'$2y$10$2YjlayTWDmUOWVYnKLk1kOkWjMqR2FMWqBLo7HJyaAHUnlEHxdl2S',NULL,'2024-11-22 06:38:43','2024-11-22 06:38:43',NULL,'22030119130054'),(11,'Diponegoro Student','llannagrock@gmail.com',NULL,NULL,'$2y$10$/RvtKDaXSUnBGzgzyGWRVeAqJq2rFPNTBUV4IdXRQVEXBMHqAV3N.',NULL,'2024-11-22 07:47:22','2024-11-22 07:47:22',NULL,'22030119130054'),(12,'Muhamad Maulana Malik','grocksaja@gmail.com',NULL,NULL,'$2y$10$s6vLvJQFNz0LgWFh.WtEnui6qZl3AB7WeIqPJcyKRqD0/XlzKsFr.',NULL,'2024-11-29 07:38:01','2024-11-29 07:38:01',NULL,'22030119130054');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_groups`
--

DROP TABLE IF EXISTS `users_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_groups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `role` enum('admin','creator','member') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'member',
  `group_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_groups_user_id_foreign` (`user_id`),
  KEY `users_groups_group_id_foreign` (`group_id`),
  CONSTRAINT `users_groups_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_groups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_groups`
--

LOCK TABLES `users_groups` WRITE;
/*!40000 ALTER TABLE `users_groups` DISABLE KEYS */;
INSERT INTO `users_groups` VALUES (2,7,'member',1,NULL,NULL),(3,2,'member',1,NULL,NULL),(5,10,'member',2,NULL,NULL),(6,2,'member',2,NULL,NULL);
/*!40000 ALTER TABLE `users_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'lab_app'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-05 15:35:45
