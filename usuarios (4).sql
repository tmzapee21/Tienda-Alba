-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-07-2024 a las 08:26:07
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
-- Base de datos: `usuarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boleta`
--

CREATE TABLE `boleta` (
  `ID` int(11) NOT NULL,
  `Correo` varchar(255) NOT NULL,
  `Direccion` varchar(255) NOT NULL,
  `Telefono` varchar(20) NOT NULL,
  `Pago` varchar(50) NOT NULL,
  `Usuario` varchar(255) NOT NULL,
  `Estado` varchar(50) NOT NULL,
  `EstadoB` varchar(50) NOT NULL,
  `Rut_Cliente` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `boleta`
--

INSERT INTO `boleta` (`ID`, `Correo`, `Direccion`, `Telefono`, `Pago`, `Usuario`, `Estado`, `EstadoB`, `Rut_Cliente`) VALUES
(1, 'manu@gmail.com', 'El Labrador 0801', '949265050', 'Presencial', 'Manu', 'Recien Creada', 'Por Entregar', '20958817-K');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_factura`
--

CREATE TABLE `detalle_factura` (
  `ID_Detalle` int(11) NOT NULL,
  `ID_Boleta` int(11) NOT NULL,
  `Nombre_Producto` varchar(255) NOT NULL,
  `Descripcion_Producto` text DEFAULT NULL,
  `Cantidad` int(11) NOT NULL,
  `Precio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_factura`
--

INSERT INTO `detalle_factura` (`ID_Detalle`, `ID_Boleta`, `Nombre_Producto`, `Descripcion_Producto`, `Cantidad`, `Precio`) VALUES
(3, 147, 'Balon 4', 'Balon Colo Colo 2024', 123, 123),
(4, 147, '12331', '13313', 123514, 321313);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrega`
--

CREATE TABLE `entrega` (
  `ID_Entrega` int(11) NOT NULL,
  `ID_Boleta` int(11) NOT NULL,
  `EstadoB` varchar(255) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Comprobante` longblob DEFAULT NULL,
  `Rut_cliente` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entrega`
--

INSERT INTO `entrega` (`ID_Entrega`, `ID_Boleta`, `EstadoB`, `Descripcion`, `Comprobante`, `Rut_cliente`) VALUES
(11, 75, 'Rechazado', '5446645', NULL, '20958817-K'),
(12, 142, 'Entregado', '', NULL, NULL),
(13, 147, 'Rechazado', '414234', NULL, '20958817-K');

--
-- Disparadores `entrega`
--
DELIMITER $$
CREATE TRIGGER `insertar_en_intentos_entrega_desde_entrega` AFTER UPDATE ON `entrega` FOR EACH ROW BEGIN
    IF NEW.EstadoB = 'Rechazado' THEN
        INSERT INTO intentos_entrega (id_boleta, estado, descripcion, fecha)
        VALUES (NEW.ID_Boleta, NEW.EstadoB, NEW.Descripcion, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `ID_Boleta` int(11) NOT NULL,
  `Nombre_Empresa` varchar(255) DEFAULT NULL,
  `Nombre_Producto` varchar(255) DEFAULT NULL,
  `Descripcion_Producto` text DEFAULT NULL,
  `Cantidad` int(11) DEFAULT NULL,
  `Precio` int(11) DEFAULT NULL,
  `Correo` varchar(255) DEFAULT NULL,
  `Direccion` varchar(255) DEFAULT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `Pago` varchar(255) DEFAULT NULL,
  `usuario` varchar(255) DEFAULT NULL,
  `Estado` varchar(255) DEFAULT NULL,
  `EstadoB` varchar(255) DEFAULT NULL,
  `Rut_cliente` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`ID_Boleta`, `Nombre_Empresa`, `Nombre_Producto`, `Descripcion_Producto`, `Cantidad`, `Precio`, `Correo`, `Direccion`, `Telefono`, `Pago`, `usuario`, `Estado`, `EstadoB`, `Rut_cliente`) VALUES
(75, 'Tienda Alba', 'Balon 4', 'Balon Colo Colo 2024', 21, 20000, 'manu@gmail.com', '0', '949265050', 'Presencial', 'Manu', 'Anulada', 'Rechazado', '20958817-K'),
(142, 'Tienda Alba', 'Balon 4124', '123', 123, 123, '0', '0', '949265050', 'Transferencia', 'Manu', 'Rectificada', 'Entregado', '20958817-6'),
(145, 'Tienda Alba', 'Balon 46', 'Balon Colo Colo 2026', 23, 200, '0', 'El Labrador 0801', '949265050', 'Transferencia', 'Manu', 'Anulada', 'Por Entregar', '20958817-K'),
(146, 'Tienda Alba', 'Balon 4', 'Balon Colo Colo 20245', 123, 123, '0', 'El Labrador 0801', '949265050', 'Transferencia', 'Manu', 'Rectificada', 'Por Entregar', '20958817-K'),
(147, 'Tienda Alba', 'Balon 4', 'Balon Colo Colo 2024', 123, 123, '0', 'El Labrador 0801', '949265050', 'Transferencia', 'Manu', 'Rectificada', 'Por Entregar', '20958817-K');

--
-- Disparadores `factura`
--
DELIMITER $$
CREATE TRIGGER `insertar_en_entrega_desde_factura` AFTER INSERT ON `factura` FOR EACH ROW BEGIN
    INSERT INTO entrega (ID_Boleta) VALUES (NEW.ID_Boleta);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intentos_entrega`
--

CREATE TABLE `intentos_entrega` (
  `id` int(11) NOT NULL,
  `id_boleta` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `intentos_entrega`
--

INSERT INTO `intentos_entrega` (`id`, `id_boleta`, `estado`, `descripcion`, `fecha`) VALUES
(1, 75, 'Rechazado', '5446645', '2024-07-17 03:54:10'),
(2, 147, 'Rechazado', '414234', '2024-07-17 06:24:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `rut` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `correo`, `username`, `rut`, `contrasena`) VALUES
(1, 'albo@campeon.com', 'Albo213', '241442', 'sfsf'),
(4, 'manu@gmail.com', 'Manu', '123', '123'),
(6, 'claudio@colocolo.com', 'Flojo123', '1234', '123'),
(7, 'javier@chuncho.cl', 'JavierNoctulo', '4321', '123'),
(8, 'hola@hola', 'hola', '1233', '123'),
(9, 'manu2@gmail.com', 'Manu', '123456', '123456'),
(10, 'ma.munozz@duocuc.cl', 'manhu', '123456789', '123'),
(11, 'joselocura@gmail.com', 'JosepValo', '43215', '123'),
(12, 'jose123@gmail.com', 'josenew123', '1234567890', '123');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `boleta`
--
ALTER TABLE `boleta`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD PRIMARY KEY (`ID_Detalle`),
  ADD KEY `ID_Boleta` (`ID_Boleta`);

--
-- Indices de la tabla `entrega`
--
ALTER TABLE `entrega`
  ADD PRIMARY KEY (`ID_Entrega`),
  ADD KEY `ID_Boleta` (`ID_Boleta`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`ID_Boleta`);

--
-- Indices de la tabla `intentos_entrega`
--
ALTER TABLE `intentos_entrega`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_boleta` (`id_boleta`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `boleta`
--
ALTER TABLE `boleta`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  MODIFY `ID_Detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `entrega`
--
ALTER TABLE `entrega`
  MODIFY `ID_Entrega` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `ID_Boleta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT de la tabla `intentos_entrega`
--
ALTER TABLE `intentos_entrega`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD CONSTRAINT `detalle_factura_ibfk_1` FOREIGN KEY (`ID_Boleta`) REFERENCES `factura` (`ID_Boleta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `entrega`
--
ALTER TABLE `entrega`
  ADD CONSTRAINT `entrega_ibfk_1` FOREIGN KEY (`ID_Boleta`) REFERENCES `factura` (`ID_Boleta`);

--
-- Filtros para la tabla `intentos_entrega`
--
ALTER TABLE `intentos_entrega`
  ADD CONSTRAINT `intentos_entrega_ibfk_1` FOREIGN KEY (`id_boleta`) REFERENCES `factura` (`ID_Boleta`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
