-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-05-2026 a las 16:24:03
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `asesoria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asesores`
--

CREATE TABLE `asesores` (
  `idAsesor` int(11) NOT NULL,
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
  `twitter` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='ola';

--
-- Volcado de datos para la tabla `asesores`
--

INSERT INTO `asesores` (`idAsesor`, `correo`, `nControl`, `contrasena`, `carrera`, `fechaRegistro`, `nombre`, `apellidos`, `telefono`, `discord`, `instagram`, `facebook`, `twitter`) VALUES
(5, 'l23550747@chihuahua2.tecnm.mx', NULL, 'Holamundo_1', 'IINF', '2026-04-19', 'Juan Pablo', 'Saenz Lopez', '6143583700', '642599840110608405', 'ajusa._', 'ImMaxmare', ''),
(6, 'l23550737@chihuahua2.tecnm.mx', NULL, 'Holamundo_1', 'IINF', '2026-05-07', 'Yuan Pavolo', 'Saenzo Lopuz', '6143583700', '642599840110608405', 'ajusa._', 'ImMaxmare', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asesor_materias`
--

CREATE TABLE `asesor_materias` (
  `idAsesor` int(11) NOT NULL,
  `idMateria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `asesor_materias`
--

INSERT INTO `asesor_materias` (`idAsesor`, `idMateria`) VALUES
(5, 1),
(5, 2),
(5, 3),
(5, 4),
(5, 5),
(6, 1),
(6, 2),
(6, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `idEstudiante` int(11) NOT NULL,
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
  `twitter` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`idEstudiante`, `correo`, `nControl`, `contrasena`, `carrera`, `fechaRegistro`, `nombre`, `apellidos`, `telefono`, `discord`, `instagram`, `facebook`, `twitter`) VALUES
(1, 'l23550738@chihuahua2.tecnm.mx', '23550737', '5981761028', 'IINF', '2026-04-14', 'Yuan Pablo', 'Saenz Lopez', '6143583701', NULL, NULL, NULL, NULL),
(4, 'l23550748@chihuahua2.tecnm.mx', '23550701', '4263765867', 'IINF', '2026-04-19', 'Juan Pablo', 'Saenz Lopez', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_sesiones`
--

CREATE TABLE `historial_sesiones` (
  `idSesion` int(11) NOT NULL,
  `idAsesor` int(11) NOT NULL,
  `idEstudiante` int(11) NOT NULL,
  `fechaInicio` timestamp NOT NULL DEFAULT current_timestamp(),
  `fechaFin` timestamp NULL DEFAULT NULL,
  `estado` enum('activa','finalizada','cancelada') DEFAULT 'activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `idMateria` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`idMateria`, `nombre`) VALUES
(1, 'Fisica'),
(2, 'Calculo Diferencial'),
(3, 'Administracion de la Funcion Informatica'),
(4, 'Calculo Integral'),
(5, 'Programacion Orientada a Objetos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `idReporte` int(11) NOT NULL,
  `idReportante` int(11) NOT NULL,
  `rolReportante` enum('asesor','estudiante') NOT NULL,
  `idReportado` int(11) NOT NULL,
  `rolReportado` enum('asesor','estudiante') NOT NULL,
  `motivo` enum('spam','acoso','contenido_inapropiado','perfil_falso','otro') NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `reportes`
--

INSERT INTO `reportes` (`idReporte`, `idReportante`, `rolReportante`, `idReportado`, `rolReportado`, `motivo`, `descripcion`, `fecha`) VALUES
(4, 1, 'estudiante', 5, 'asesor', 'acoso', 'gdfssgd', '2026-05-13 08:59:27'),
(5, 4, 'estudiante', 6, 'asesor', 'contenido_inapropiado', 'aadsdas', '2026-05-15 13:06:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE `sesiones` (
  `idSolicitud` int(11) NOT NULL,
  `idAsesor` int(11) NOT NULL,
  `idEstudiante` int(11) NOT NULL,
  `idMateria` int(11) DEFAULT NULL,
  `estado` enum('pendiente','aceptada','terminada','cancelada') DEFAULT 'pendiente',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `sesiones`
--

INSERT INTO `sesiones` (`idSolicitud`, `idAsesor`, `idEstudiante`, `idMateria`, `estado`, `fecha`) VALUES
(1, 6, 1, 1, 'cancelada', '2026-05-14 09:58:00'),
(2, 6, 1, 5, 'cancelada', '2026-05-17 10:29:00'),
(3, 5, 1, 2, 'terminada', '2026-05-14 11:44:00'),
(4, 5, 4, 1, 'terminada', '2026-05-13 20:50:00'),
(5, 5, 4, 3, 'cancelada', '2026-05-13 11:29:00'),
(6, 5, 4, 1, 'terminada', '2026-05-23 13:15:00'),
(7, 5, 4, 5, 'terminada', '2026-05-15 10:11:00'),
(8, 5, 1, 4, 'terminada', '2026-05-22 08:14:00'),
(10, 5, 1, 2, 'cancelada', '2026-05-17 08:15:00'),
(11, 5, 1, 2, 'aceptada', '2026-05-01 07:15:00'),
(12, 5, 1, 3, 'cancelada', '2026-05-31 11:15:00'),
(13, 5, 4, 2, 'terminada', '2026-06-21 10:17:00'),
(14, 5, 1, 2, 'cancelada', '2026-05-15 10:37:00'),
(15, 5, 1, 3, 'cancelada', '2026-05-16 14:05:00'),
(16, 6, 1, 2, 'cancelada', '2026-05-16 11:06:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asesores`
--
ALTER TABLE `asesores`
  ADD PRIMARY KEY (`idAsesor`);

--
-- Indices de la tabla `asesor_materias`
--
ALTER TABLE `asesor_materias`
  ADD PRIMARY KEY (`idAsesor`,`idMateria`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`idEstudiante`);

--
-- Indices de la tabla `historial_sesiones`
--
ALTER TABLE `historial_sesiones`
  ADD PRIMARY KEY (`idSesion`),
  ADD KEY `idAsesor` (`idAsesor`),
  ADD KEY `idEstudiante` (`idEstudiante`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`idMateria`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`idReporte`);

--
-- Indices de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD PRIMARY KEY (`idSolicitud`),
  ADD KEY `idAsesor` (`idAsesor`),
  ADD KEY `idEstudiante` (`idEstudiante`),
  ADD KEY `idx_idMateria` (`idMateria`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asesores`
--
ALTER TABLE `asesores`
  MODIFY `idAsesor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `idEstudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `historial_sesiones`
--
ALTER TABLE `historial_sesiones`
  MODIFY `idSesion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `idReporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  MODIFY `idSolicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `historial_sesiones`
--
ALTER TABLE `historial_sesiones`
  ADD CONSTRAINT `historial_sesiones_ibfk_1` FOREIGN KEY (`idAsesor`) REFERENCES `asesores` (`idAsesor`),
  ADD CONSTRAINT `historial_sesiones_ibfk_2` FOREIGN KEY (`idEstudiante`) REFERENCES `estudiantes` (`idEstudiante`);

--
-- Filtros para la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD CONSTRAINT `fk_solicitud_materia` FOREIGN KEY (`idMateria`) REFERENCES `materias` (`idMateria`),
  ADD CONSTRAINT `sesiones_ibfk_1` FOREIGN KEY (`idAsesor`) REFERENCES `asesores` (`idAsesor`),
  ADD CONSTRAINT `sesiones_ibfk_2` FOREIGN KEY (`idEstudiante`) REFERENCES `estudiantes` (`idEstudiante`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
