-- MySQL dump 10.13  Distrib 8.0.45, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: asesoria
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `asesor_materias`
--

DROP TABLE IF EXISTS `asesor_materias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asesor_materias` (
  `idAsesor` int(11) NOT NULL,
  `idMateria` int(11) NOT NULL,
  PRIMARY KEY (`idAsesor`,`idMateria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asesor_materias`
--

LOCK TABLES `asesor_materias` WRITE;
/*!40000 ALTER TABLE `asesor_materias` DISABLE KEYS */;
INSERT INTO `asesor_materias` VALUES (5,1),(5,2),(5,3),(5,4),(5,5),(6,1),(6,2),(6,5);
/*!40000 ALTER TABLE `asesor_materias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asesores`
--

DROP TABLE IF EXISTS `asesores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asesores` (
  `idAsesor` int(11) NOT NULL AUTO_INCREMENT,
  `correo` varchar(100) DEFAULT NULL,
  `nControl` varchar(45) DEFAULT NULL,
  `contrasena` varchar(45) NOT NULL,
  `carrera` varchar(45) NOT NULL,
  `fechaRegistro` date DEFAULT current_timestamp(),
  `nombre` varchar(60) NOT NULL,
  `apellidos` varchar(60) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `discord` varchar(100) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `facebook` varchar(150) DEFAULT NULL,
  `twitter` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idAsesor`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='ola';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asesores`
--

LOCK TABLES `asesores` WRITE;
/*!40000 ALTER TABLE `asesores` DISABLE KEYS */;
INSERT INTO `asesores` VALUES (5,'l23550747@chihuahua2.tecnm.mx',NULL,'Holamundo_1','IINF','2026-04-19','Juan Pablo','Saenz Lopez','6143583700','642599840110608405','ajusa._','ImMaxmare',''),(6,'l23550737@chihuahua2.tecnm.mx',NULL,'Holamundo_1','IINF','2026-05-07','Yuan Pavolo','Saenzo Lopuz','6143583700','642599840110608405','ajusa._','ImMaxmare',NULL);
/*!40000 ALTER TABLE `asesores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estudiantes`
--

DROP TABLE IF EXISTS `estudiantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estudiantes` (
  `idEstudiante` int(11) NOT NULL AUTO_INCREMENT,
  `correo` varchar(45) DEFAULT NULL,
  `nControl` varchar(45) DEFAULT NULL,
  `contrasena` varchar(45) NOT NULL,
  `carrera` varchar(45) NOT NULL,
  `fechaRegistro` date DEFAULT current_timestamp(),
  `nombre` varchar(60) NOT NULL,
  `apellidos` varchar(60) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `discord` varchar(100) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `facebook` varchar(150) DEFAULT NULL,
  `twitter` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idEstudiante`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudiantes`
--

LOCK TABLES `estudiantes` WRITE;
/*!40000 ALTER TABLE `estudiantes` DISABLE KEYS */;
INSERT INTO `estudiantes` VALUES (1,'l23550738@chihuahua2.tecnm.mx','23550737','5981761028','IINF','2026-04-14','Yuan Pablo','Saenz Lopez','6143583701',NULL,NULL,NULL,NULL),(4,'l23550748@chihuahua2.tecnm.mx','23550701','4263765867','IINF','2026-04-19','Juan Pablo','Saenz Lopez','','','','','');
/*!40000 ALTER TABLE `estudiantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historial_sesiones`
--

DROP TABLE IF EXISTS `historial_sesiones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historial_sesiones` (
  `idSesion` int(11) NOT NULL AUTO_INCREMENT,
  `idAsesor` int(11) NOT NULL,
  `idEstudiante` int(11) NOT NULL,
  `fechaInicio` timestamp NOT NULL DEFAULT current_timestamp(),
  `fechaFin` timestamp NULL DEFAULT NULL,
  `estado` enum('activa','finalizada','cancelada') DEFAULT 'activa',
  PRIMARY KEY (`idSesion`),
  KEY `idAsesor` (`idAsesor`),
  KEY `idEstudiante` (`idEstudiante`),
  CONSTRAINT `historial_sesiones_ibfk_1` FOREIGN KEY (`idAsesor`) REFERENCES `asesores` (`idAsesor`),
  CONSTRAINT `historial_sesiones_ibfk_2` FOREIGN KEY (`idEstudiante`) REFERENCES `estudiantes` (`idEstudiante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial_sesiones`
--

LOCK TABLES `historial_sesiones` WRITE;
/*!40000 ALTER TABLE `historial_sesiones` DISABLE KEYS */;
/*!40000 ALTER TABLE `historial_sesiones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materias`
--

DROP TABLE IF EXISTS `materias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materias` (
  `idMateria` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idMateria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materias`
--

LOCK TABLES `materias` WRITE;
/*!40000 ALTER TABLE `materias` DISABLE KEYS */;
INSERT INTO `materias` VALUES (1,'Fisica'),(2,'Calculo Diferencial'),(3,'Administracion de la Funcion Informatica'),(4,'Calculo Integral'),(5,'Programacion Orientada a Objetos');
/*!40000 ALTER TABLE `materias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sesiones`
--

DROP TABLE IF EXISTS `sesiones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sesiones` (
  `idSolicitud` int(11) NOT NULL AUTO_INCREMENT,
  `idAsesor` int(11) NOT NULL,
  `idEstudiante` int(11) NOT NULL,
  `idMateria` int(11) DEFAULT NULL,
  `estado` enum('pendiente','aceptada','terminada','cancelada') DEFAULT 'pendiente',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idSolicitud`),
  KEY `idAsesor` (`idAsesor`),
  KEY `idEstudiante` (`idEstudiante`),
  KEY `idx_idMateria` (`idMateria`),
  CONSTRAINT `fk_solicitud_materia` FOREIGN KEY (`idMateria`) REFERENCES `materias` (`idMateria`),
  CONSTRAINT `sesiones_ibfk_1` FOREIGN KEY (`idAsesor`) REFERENCES `asesores` (`idAsesor`),
  CONSTRAINT `sesiones_ibfk_2` FOREIGN KEY (`idEstudiante`) REFERENCES `estudiantes` (`idEstudiante`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sesiones`
--

LOCK TABLES `sesiones` WRITE;
/*!40000 ALTER TABLE `sesiones` DISABLE KEYS */;
INSERT INTO `sesiones` VALUES (1,6,1,1,'cancelada','2026-05-14 09:58:00'),(2,6,1,5,'cancelada','2026-05-17 10:29:00'),(3,5,1,2,'terminada','2026-05-14 11:44:00');
/*!40000 ALTER TABLE `sesiones` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-11  5:58:56
