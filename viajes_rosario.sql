SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- CREACIÓN DE LA BASE DE DATOS
-- --------------------------------------------------------

DROP DATABASE IF EXISTS `viajes_rosario`;
CREATE DATABASE IF NOT EXISTS `viajes_rosario` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `viajes_rosario`;

-- --------------------------------------------------------
-- ESTRUCTURA DE TABLAS
-- --------------------------------------------------------

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefono_whatsapp` varchar(30) DEFAULT NULL,
  `rol` enum('chofer','pasajero') NOT NULL DEFAULT 'pasajero',
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_modificado` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_borrado` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`),
  KEY `idx_rol` (`rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `vehiculos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_chofer` int(11) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `anio` int(11) NOT NULL,
  `color` varchar(30) DEFAULT NULL,
  `capacidad_pasajeros` int(11) NOT NULL,
  `tiene_seguro` tinyint(1) DEFAULT 0,
  `aseguradora` varchar(50) DEFAULT NULL,
  `vencimiento_seguro` date DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_modificado` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_borrado` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_chofer` (`id_chofer`),
  CONSTRAINT `vehiculos_ibfk_1` FOREIGN KEY (`id_chofer`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `viajes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_chofer` int(11) NOT NULL,
  `id_vehiculo` int(11) NOT NULL,
  `origen` varchar(60) NOT NULL,
  `destino` varchar(60) NOT NULL,
  `tipo` enum('diario','eventual') NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora_salida` time NOT NULL,
  `capacidad_pasajeros` int(11) NOT NULL,
  `precio_base` decimal(10,2) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_modificado` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_borrado` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_chofer` (`id_chofer`),
  KEY `id_vehiculo` (`id_vehiculo`),
  CONSTRAINT `viajes_ibfk_1` FOREIGN KEY (`id_chofer`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `viajes_ibfk_2` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `viaje_dias` (
  `id_viaje` int(11) NOT NULL,
  `dia_semana` varchar(15) NOT NULL,
  KEY `id_viaje` (`id_viaje`),
  CONSTRAINT `viaje_dias_ibfk_1` FOREIGN KEY (`id_viaje`) REFERENCES `viajes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `zonas` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_chofer` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_modificado` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_borrado` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `viaje_zonas` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_viaje` int(10) UNSIGNED NOT NULL,
  `id_zona` int(10) UNSIGNED NOT NULL,
  `recargo` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `consultas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_viaje` int(11) NOT NULL,
  `id_chofer` int(11) NOT NULL,
  `id_pasajero` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_borrado` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_viaje` (`id_viaje`),
  KEY `id_chofer` (`id_chofer`),
  KEY `id_pasajero` (`id_pasajero`),
  CONSTRAINT `consultas_ibfk_1` FOREIGN KEY (`id_viaje`) REFERENCES `viajes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `consultas_ibfk_2` FOREIGN KEY (`id_chofer`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `consultas_ibfk_3` FOREIGN KEY (`id_pasajero`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `auditoria_viajes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_viaje` int(11) NOT NULL,
  `id_chofer` int(11) NOT NULL,
  `accion` varchar(50) NOT NULL,
  `detalles` text DEFAULT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- DATOS DE PRUEBA (1 Chofer, 3 Pasajeros, 4 Viajes, 6 Consultas)
-- --------------------------------------------------------

-- 1. Usuarios
INSERT INTO `usuarios` (`id`, `usuario`, `clave`, `nombre`, `apellido`, `email`, `telefono_whatsapp`, `rol`) VALUES
(1, 'chofer', '$2y$10$dXSLJ.UNju5V23iMlEjBA.b/SnANIu.IAgFfVZdUrP6M1nluwG9xi', 'Juan', 'Gómez', 'chofer@ejemplo.com', '5493436000001', 'chofer'), -- Contraseña chofer prueba: Chofer123
(2, 'pasajero', '$2y$10$9tBCiolGMzXvuLxeNZe/3epGaPW3sJKBErKfLvI9KPdpEK/ADYNqu', 'Martín', 'Sosa', 'pasajero@ejemplo.com', '5493436000002', 'pasajero'), -- Contraseña pasajero prueba: Pasajero123
(3, 'pasajero2', '$2y$10$9tBCiolGMzXvuLxeNZe/3epGaPW3sJKBErKfLvI9KPdpEK/ADYNqu', 'Lucía', 'Fernández', 'lucia@ejemplo.com', '5493436000003', 'pasajero'),
(4, 'pasajero3', '$2y$10$9tBCiolGMzXvuLxeNZe/3epGaPW3sJKBErKfLvI9KPdpEK/ADYNqu', 'Carlos', 'Benítez', 'carlos@ejemplo.com', '5493436000004', 'pasajero');

-- 2. Vehículos del chofer
INSERT INTO `vehiculos` (`id`, `id_chofer`, `marca`, `modelo`, `anio`, `color`, `capacidad_pasajeros`, `tiene_seguro`, `aseguradora`, `vencimiento_seguro`) VALUES
(1, 1, 'Renault', 'Megane', 2015, 'Gris', 4, 1, 'La Segunda', '2027-06-30'),
(2, 1, 'Peugeot', '206', 2008, 'Negro', 4, 1, 'Triunfo Seguros', '2027-05-20');

-- 3. Zonas del chofer
INSERT INTO `zonas` (`id`, `id_chofer`, `nombre`) VALUES
(1, 1, 'Zona Sur'),
(2, 1, 'Granadero Baigorria'),
(3, 1, 'Fisherton');

-- 4. Viajes del chofer (3 diarios + 1 eventual)
INSERT INTO `viajes` (`id`, `id_chofer`, `id_vehiculo`, `origen`, `destino`, `tipo`, `fecha`, `hora_salida`, `capacidad_pasajeros`, `precio_base`, `observaciones`) VALUES
(1, 1, 1, 'Victoria', 'Rosario', 'diario', NULL, '07:30:00', 4, 16500.00, 'Salida temprana desde la terminal'),
(2, 1, 1, 'Victoria', 'Rosario', 'diario', NULL, '10:00:00', 4, 17000.00, 'Voy a zona sur, acepto equipaje de mano'),
(3, 1, 1, 'Rosario', 'Victoria', 'diario', NULL, '14:00:00', 4, 16500.00, 'Regreso mediodía desde terminal Mariano Moreno'),
(4, 1, 2, 'Victoria', 'Rosario', 'eventual', '2026-11-28', '18:30:00', 3, 18000.00, 'Viaje especial turno tarde-noche');

-- 5. Días de los viajes diarios
INSERT INTO `viaje_dias` (`id_viaje`, `dia_semana`) VALUES
(1, 'Lunes'),
(1, 'Miércoles'),
(1, 'Viernes'),
(2, 'Martes'),
(2, 'Jueves'),
(2, 'Sábado'),
(3, 'Lunes'),
(3, 'Miércoles'),
(3, 'Viernes');

-- 6. Zonas asociadas a los viajes
INSERT INTO `viaje_zonas` (`id`, `id_viaje`, `id_zona`, `recargo`) VALUES
(1, 1, 3, 2500.00),
(2, 2, 1, 2000.00),
(3, 4, 2, 1500.00);

-- 7. Consultas (2 por cada uno de los 3 pasajeros, agrupadas para reflejar picos de demanda en Reportes)
INSERT INTO `consultas` (`id`, `id_viaje`, `id_chofer`, `id_pasajero`, `fecha`) VALUES
(1, 1, 1, 2, '2026-11-13 08:15:00'), -- Viernes (Pasajero 1)
(2, 3, 1, 2, '2026-11-16 11:30:00'), -- Lunes   (Pasajero 1)
(3, 2, 1, 3, '2026-11-13 14:45:00'), -- Viernes (Pasajero 2)
(4, 4, 1, 3, '2026-11-18 17:20:00'), -- Miércoles (Pasajero 2)
(5, 1, 1, 4, '2026-11-13 19:05:00'), -- Viernes (Pasajero 3)
(6, 2, 1, 4, '2026-11-16 15:40:00'); -- Lunes   (Pasajero 3)

-- 8. Auditoría de alta de los 4 viajes
INSERT INTO `auditoria_viajes` (`id`, `id_viaje`, `id_chofer`, `accion`, `detalles`, `fecha_hora`) VALUES
(1, 1, 1, 'INSERTAR', 'Publicó viaje de Victoria a Rosario ($16500)', '2026-11-10 18:00:00'),
(2, 2, 1, 'INSERTAR', 'Publicó viaje de Victoria a Rosario ($17000)', '2026-11-10 18:05:00'),
(3, 3, 1, 'INSERTAR', 'Publicó viaje de Rosario a Victoria ($16500)', '2026-11-10 18:10:00'),
(4, 4, 1, 'INSERTAR', 'Publicó viaje de Victoria a Rosario ($18000)', '2026-11-10 18:15:00');

-- --------------------------------------------------------
-- SCRIPT DE LIMPIEZA por si se desea reiniciar la base de datos a su estado inicial
-- --------------------------------------------------------
/*
USE `viajes_rosario`;

DELETE FROM `auditoria_viajes`;
DELETE FROM `consultas`;
DELETE FROM `viaje_zonas`;
DELETE FROM `viaje_dias`;
DELETE FROM `viajes`;
DELETE FROM `zonas`;
DELETE FROM `vehiculos`;
DELETE FROM `usuarios`;

ALTER TABLE `auditoria_viajes` AUTO_INCREMENT = 1;
ALTER TABLE `consultas` AUTO_INCREMENT = 1;
ALTER TABLE `viaje_zonas` AUTO_INCREMENT = 1;
ALTER TABLE `viajes` AUTO_INCREMENT = 1;
ALTER TABLE `zonas` AUTO_INCREMENT = 1;
ALTER TABLE `vehiculos` AUTO_INCREMENT = 1;
ALTER TABLE `usuarios` AUTO_INCREMENT = 1;
*/

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;