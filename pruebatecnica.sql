-- MySQL dump 10.13  Distrib 5.7.34, for Win64 (x86_64)
--
-- Host: localhost    Database: pruebatecnica
-- ------------------------------------------------------
-- Server version	5.7.33

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
-- Table structure for table `alumno`
--

DROP TABLE IF EXISTS `alumno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alumno` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) NOT NULL,
  `apellido_paterno` varchar(20) NOT NULL,
  `apellido_materno` varchar(20) NOT NULL,
  `matricula` varchar(45) NOT NULL,
  `contrasena` varchar(60) NOT NULL,
  `Instituto_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Alumno_Instituto1_idx` (`Instituto_ID`),
  CONSTRAINT `fk_Alumno_Instituto1` FOREIGN KEY (`Instituto_ID`) REFERENCES `instituto` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumno`
--

LOCK TABLES `alumno` WRITE;
/*!40000 ALTER TABLE `alumno` DISABLE KEYS */;
INSERT INTO `alumno` VALUES (1,'Hansel','Sarabia','Valencia','112233','$2y$10$yTt17OlQJclu3/nldoQXB.4CzYcYyKWPEychLzRFbFq2.Ln9vHvfC',1),(2,'Alfonso','Robles','Avila','345678','$2y$10$MdeSFoJGIOE6DCAw4OO51u444Ph9P1KNKI4JrTLissNsCGFNAjx12',1),(3,'Luis','Sanchez','Torres','456789','$2y$10$DhbIrjuo54kfnH/1pv0d6ePMpYScmZqRpasXuGws8/g5TVsAduPBq',1);
/*!40000 ALTER TABLE `alumno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alumno_asignatura`
--

DROP TABLE IF EXISTS `alumno_asignatura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alumno_asignatura` (
  `Alumno_ID` int(11) NOT NULL,
  `Asignatura_ID` int(11) NOT NULL,
  PRIMARY KEY (`Alumno_ID`,`Asignatura_ID`),
  KEY `fk_Alumno_has_Asignatura_Asignatura1_idx` (`Asignatura_ID`),
  KEY `fk_Alumno_has_Asignatura_Alumno1_idx` (`Alumno_ID`),
  CONSTRAINT `fk_Alumno_has_Asignatura_Alumno1` FOREIGN KEY (`Alumno_ID`) REFERENCES `alumno` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_Alumno_has_Asignatura_Asignatura1` FOREIGN KEY (`Asignatura_ID`) REFERENCES `asignatura` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumno_asignatura`
--

LOCK TABLES `alumno_asignatura` WRITE;
/*!40000 ALTER TABLE `alumno_asignatura` DISABLE KEYS */;
INSERT INTO `alumno_asignatura` VALUES (1,1),(3,1),(1,2),(2,2),(1,3),(2,3),(2,4),(3,4);
/*!40000 ALTER TABLE `alumno_asignatura` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asignatura`
--

DROP TABLE IF EXISTS `asignatura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asignatura` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `abreviacion` varchar(15) NOT NULL,
  `Instituto_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Asignatura_Instituto1_idx` (`Instituto_ID`),
  CONSTRAINT `fk_Asignatura_Instituto1` FOREIGN KEY (`Instituto_ID`) REFERENCES `instituto` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asignatura`
--

LOCK TABLES `asignatura` WRITE;
/*!40000 ALTER TABLE `asignatura` DISABLE KEYS */;
INSERT INTO `asignatura` VALUES (1,'Algebra','ALG',1),(2,'Quimica','QUI',1),(3,'Filosofia','FIL',1),(4,'Fisica','FIS',1);
/*!40000 ALTER TABLE `asignatura` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `director`
--

DROP TABLE IF EXISTS `director`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `director` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `numero_empleado` int(11) NOT NULL,
  `contrasena` varchar(60) NOT NULL,
  `Instituto_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Director_Instituto_idx` (`Instituto_ID`),
  CONSTRAINT `fk_Director_Instituto` FOREIGN KEY (`Instituto_ID`) REFERENCES `instituto` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `director`
--

LOCK TABLES `director` WRITE;
/*!40000 ALTER TABLE `director` DISABLE KEYS */;
INSERT INTO `director` VALUES (2,'Javier Rodriguez Valencia',295107,'$2y$10$3nA8kCwzew6tPQaNPBbPX.IXrknflSflobWn3L.9sr78/JCApPMMC',1);
/*!40000 ALTER TABLE `director` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `docente`
--

DROP TABLE IF EXISTS `docente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `docente` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `matricula` varchar(45) NOT NULL,
  `contrasena` varchar(60) NOT NULL,
  `Instituto_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Docente_Instituto1_idx` (`Instituto_ID`),
  CONSTRAINT `fk_Docente_Instituto1` FOREIGN KEY (`Instituto_ID`) REFERENCES `instituto` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `docente`
--

LOCK TABLES `docente` WRITE;
/*!40000 ALTER TABLE `docente` DISABLE KEYS */;
INSERT INTO `docente` VALUES (1,'Aaron Sarabia Valencia','987654','$2y$10$ulDUvq48h5L2UHwHW/iXMuARYoWyRAJ.HCEGjfDEe0iDNCIaDCLue',1),(2,'Alejandro Cardenas Beltran','876543','$2y$10$MvjgbR5AQ48lQz4/9QQyKuCsIVXfH9CZMhIWKmZ81EeALY/wzGmRS',1);
/*!40000 ALTER TABLE `docente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `docente_asignatura`
--

DROP TABLE IF EXISTS `docente_asignatura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `docente_asignatura` (
  `Docente_ID` int(11) NOT NULL,
  `Asignatura_ID` int(11) NOT NULL,
  KEY `fk_Docente_has_Asignatura_Asignatura1_idx` (`Asignatura_ID`),
  KEY `fk_Docente_has_Asignatura_Docente1_idx` (`Docente_ID`),
  CONSTRAINT `fk_Docente_has_Asignatura_Asignatura1` FOREIGN KEY (`Asignatura_ID`) REFERENCES `asignatura` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_Docente_has_Asignatura_Docente1` FOREIGN KEY (`Docente_ID`) REFERENCES `docente` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `docente_asignatura`
--

LOCK TABLES `docente_asignatura` WRITE;
/*!40000 ALTER TABLE `docente_asignatura` DISABLE KEYS */;
INSERT INTO `docente_asignatura` VALUES (1,1),(1,3),(2,2),(2,4);
/*!40000 ALTER TABLE `docente_asignatura` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instituto`
--

DROP TABLE IF EXISTS `instituto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `instituto` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `domicilio` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instituto`
--

LOCK TABLES `instituto` WRITE;
/*!40000 ALTER TABLE `instituto` DISABLE KEYS */;
INSERT INTO `instituto` VALUES (1,'ICBI','Carretera Pachuca-Tulancingo');
/*!40000 ALTER TABLE `instituto` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-06-13 20:29:53
