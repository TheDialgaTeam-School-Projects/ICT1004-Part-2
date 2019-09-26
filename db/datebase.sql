-- MySQL dump 10.16  Distrib 10.1.36-MariaDB, for Win32 (AMD64)
--
-- Host: 161.117.122.252    Database: group14
-- ------------------------------------------------------
-- Server version	5.7.20-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE = @@TIME_ZONE */;
/*!40103 SET TIME_ZONE = '+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES = @@SQL_NOTES, SQL_NOTES = 0 */;

--
-- Table structure for table `doctor_schedule`
--

DROP TABLE IF EXISTS `doctor_schedule`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctor_schedule`
(
  `doctor_schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `doctor_id`          int(11) NOT NULL,
  `date`               date    NOT NULL,
  `start_time`         time    NOT NULL,
  `end_time`           time    NOT NULL,
  `venue_id`           int(11) NOT NULL,
  PRIMARY KEY (`doctor_schedule_id`),
  KEY `doctor_schedule_user_user_id_fk` (`doctor_id`),
  KEY `doctor_schedule_venue_venue_id_fk` (`venue_id`),
  CONSTRAINT `doctor_schedule_user_user_id_fk` FOREIGN KEY (`doctor_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `doctor_schedule_venue_venue_id_fk` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`venue_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB
  AUTO_INCREMENT = 50
  DEFAULT CHARSET = utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_schedule`
--

LOCK TABLES `doctor_schedule` WRITE;
/*!40000 ALTER TABLE `doctor_schedule`
  DISABLE KEYS */;
INSERT INTO `doctor_schedule`
VALUES (1, 3, '2018-11-22', '10:00:00', '11:00:00', 8),
       (2, 3, '2018-11-16', '13:00:00', '14:00:00', 3),
       (3, 3, '2018-11-22', '12:00:00', '13:00:00', 6),
       (4, 3, '2018-11-22', '11:00:00', '12:00:00', 5),
       (5, 3, '2018-11-21', '00:00:00', '00:00:00', 1),
       (8, 5, '2018-11-28', '13:00:00', '14:00:00', 4),
       (19, 1, '2018-11-25', '09:00:00', '10:00:00', 1),
       (21, 1, '2018-11-30', '09:00:00', '10:00:00', 1),
       (22, 1, '2018-11-28', '10:00:00', '11:00:00', 1),
       (23, 1, '2018-11-28', '11:00:00', '12:00:00', 1),
       (25, 1, '2018-11-28', '13:00:00', '14:00:00', 1),
       (26, 1, '2018-11-28', '14:00:00', '15:00:00', 1),
       (27, 1, '2018-11-28', '15:00:00', '16:00:00', 1),
       (28, 1, '2018-11-28', '16:00:00', '17:00:00', 1),
       (34, 3, '2018-11-28', '12:00:00', '13:00:00', 1),
       (35, 3, '2018-11-28', '09:00:00', '10:00:00', 1),
       (36, 3, '2018-11-28', '11:00:00', '12:00:00', 1),
       (38, 3, '2018-11-29', '09:00:00', '10:00:00', 1),
       (39, 3, '2018-11-30', '12:00:00', '13:00:00', 1),
       (40, 3, '2018-11-29', '13:00:00', '14:00:00', 1),
       (41, 4, '2018-11-30', '16:00:00', '17:00:00', 7),
       (42, 3, '2018-11-30', '09:00:00', '10:00:00', 1),
       (44, 4, '2018-11-30', '12:00:00', '13:00:00', 6),
       (45, 4, '2018-11-30', '15:00:00', '16:00:00', 7),
       (46, 3, '2018-11-30', '13:00:00', '14:00:00', 16),
       (47, 3, '2018-11-29', '12:00:00', '13:00:00', 1),
       (48, 3, '2018-11-29', '10:00:00', '11:00:00', 1),
       (49, 4, '2018-11-30', '13:00:00', '14:00:00', 1);
/*!40000 ALTER TABLE `doctor_schedule`
  ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_appointment`
--

DROP TABLE IF EXISTS `patient_appointment`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_appointment`
(
  `patient_appointment_id` int(11)                     NOT NULL AUTO_INCREMENT,
  `doctor_schedule_id`     int(11)                              DEFAULT NULL,
  `user_id`                int(11)                     NOT NULL,
  `book_time`              timestamp                   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status`                 enum ('booked', 'rejected') NOT NULL DEFAULT 'booked',
  `email_sent`             tinyint(1)                           DEFAULT '0',
  PRIMARY KEY (`patient_appointment_id`),
  KEY `patient_appointment_doctor_schedule_doctor_schedule_id_fk` (`doctor_schedule_id`),
  KEY `patient_appointment_user_user_id_fk` (`user_id`),
  CONSTRAINT `patient_appointment_doctor_schedule_doctor_schedule_id_fk` FOREIGN KEY (`doctor_schedule_id`) REFERENCES `doctor_schedule` (`doctor_schedule_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `patient_appointment_user_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB
  AUTO_INCREMENT = 73
  DEFAULT CHARSET = utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_appointment`
--

LOCK TABLES `patient_appointment` WRITE;
/*!40000 ALTER TABLE `patient_appointment`
  DISABLE KEYS */;
INSERT INTO `patient_appointment`
VALUES (58, 39, 5, '2018-11-28 05:28:25', 'booked', 1),
       (61, 21, 8, '2018-11-28 07:54:05', 'booked', 1),
       (68, 46, 5, '2018-11-28 08:21:40', 'booked', 1),
       (69, 44, 5, '2018-11-28 13:31:38', 'booked', 1),
       (70, 44, 5, '2018-11-28 13:34:26', 'booked', 1),
       (71, 38, 5, '2018-11-28 13:37:06', 'booked', 1),
       (72, 42, 8, '2018-11-29 01:10:46', 'booked', 1);
/*!40000 ALTER TABLE `patient_appointment`
  ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user`
(
  `user_id`           int(11)                    NOT NULL AUTO_INCREMENT,
  `name`              varchar(255)               NOT NULL,
  `email`             varchar(319)               NOT NULL,
  `username`          varchar(50)                NOT NULL,
  `password`          varchar(60)                NOT NULL,
  `type`              enum ('patient', 'doctor') NOT NULL DEFAULT 'patient',
  `dob`               date                                DEFAULT NULL,
  `gender`            enum ('male', 'female')    NOT NULL,
  `verification_hash` varchar(32)                         DEFAULT NULL,
  `verified`          tinyint(1)                 NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_username_uindex` (`username`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 14
  DEFAULT CHARSET = utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user`
  DISABLE KEYS */;
INSERT INTO `user`
VALUES (0, '', '', '', '', '', '0000-00-00', '', '', 0),
       (1, 'Yong Jian Ming', 'jianming1993@gmail.com', 'jianmingyong',
        '$2y$10$q7jxWSX.OgyD3bFWC4woD.OLiu1qQOlR94tZXDkrLJ28iYVhw6dfW', 'patient', '1998-02-24', 'male',
        'c4b97f708efa638002531570f3ba730d', 1),
       (2, 'mervyn', 'mervyn_er@hotmail.com', 'mervyn', '$2y$10$s7iRa1.D.fXd/cIomTJHv.2lYnqncBGl06gxX1WTGTSlat.42dsom',
        'patient', '1996-07-16', 'male', '96bcd04fbdf5358a87be2b29ccc5aaf5', 1),
       (3, 'Roysten', 'ngroysten@gmail.com', 'roystenng',
        '$2y$10$VmIxVsOriANofn/Da6taiu7cfxTXn0HG50wNPUs4ZsTqPtXXO2koS', 'doctor', '1995-04-27', 'male',
        '686a5b6df54b0ab7d24ea114834d79a1', 1),
       (4, 'abner', 'school.abner@gmail.com', 'abner', '$2y$10$mxK.THyrh0EQd3NC8HaMwe7sOsm9LXiXemqQW08EoAO4/Hd.8Zzri',
        'doctor', '0011-11-11', 'male', '70c2ad7075f797034e155cf66c03449a', 1),
       (5, 'Wayne Chua', 'bornincave@gmail.com', 'WayneC',
        '$2y$10$qogb/NHy6eo7Fac1w7.b/O0m8VGCrRuFAzXIpHgKUkdhgYqIvp61u', 'patient', '1996-10-16', 'male',
        '5694c4b35561c23251026a43612de1dd', 1),
       (8, 'pabner', 'abner.otaku@gmail.com', 'pabner', '$2y$10$bW6tQzTAk4eEdPvrkfMsgec5BdpRGyW9BK/iO4dQ7OJg6j.rdeTKG',
        'patient', '1994-10-01', 'male', 'a0c51bc02493c94df40c6ef0b7622184', 1),
       (11, 'test_patient', 'test.patient.ict04@gmail.com', 'test_patient',
        '$2y$10$XMfM1Bqs5CleLGfSzOKS1.sLDCv//v2vVIfyc8iGratud95Kfv/rW', 'patient', '2018-01-01', 'male',
        '81f35cb0c3d0e2847a80c04c187cf5db', 1),
       (12, 'test_doctor', 'Ict1004001@gmail.com', 'test_doctor',
        '$2y$10$vog7hSpbbMVr5Rbxs65odOKEYW4wWHMAIjf/hhCeqojpEGR/jOt9y', 'doctor', '2018-01-01', 'male',
        'bad56fe32399ed35383434fa312ad192', 1),
       (13, 'admin', 'admin@admin.admin', 'admin', '$2y$10$/BNlUyHVMnJ.QFFkl3yAu.UJKV9csTMI3yzhnt26w4oue3e7OtyOG',
        'patient', '1920-11-11', 'male', '53e81befa30774fb73e11a33c4284ea3', 0);
/*!40000 ALTER TABLE `user`
  ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venue`
--

DROP TABLE IF EXISTS `venue`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `venue`
(
  `venue_id` int(11)      NOT NULL AUTO_INCREMENT,
  `name`     varchar(255) NOT NULL,
  PRIMARY KEY (`venue_id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 23
  DEFAULT CHARSET = utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venue`
--

LOCK TABLES `venue` WRITE;
/*!40000 ALTER TABLE `venue`
  DISABLE KEYS */;
INSERT INTO `venue`
VALUES (1, 'Alexandra Hospital'),
       (2, 'Changi General Hospital'),
       (3, 'KK Women\'s and Children\'s Hospital'),
       (4, 'Khoo Teck Puat Hospital'),
       (5, 'National University Hospital'),
       (6, 'Ng Teng Fong General Hospital'),
       (7, 'Sengkang General Hospital'),
       (8, 'Singapore General Hospital'),
       (9, 'Tan Tock Seng Hospital'),
       (10, 'Institute of Mental Health'),
       (11, 'Bright Vision Hospital'),
       (12, 'Jurong Community Hospital'),
       (13, 'Sengkang Community Hospital'),
       (14, 'Yishun Community Hospital'),
       (15, 'National Cancer Centre Singapore'),
       (16, 'National Dental Centre Singapore'),
       (17, 'National Heart Centre Singapore'),
       (18, 'National Neuroscience Institute'),
       (19, 'National Skin Centre'),
       (20, 'National University Cancer Institute, Singapore'),
       (21, 'National University Heart Centre, Singapore'),
       (22, 'Singapore National Eye Centre');
/*!40000 ALTER TABLE `venue`
  ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE = @OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE = @OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES = @OLD_SQL_NOTES */;

-- Dump completed on 2018-11-29 14:12:35
