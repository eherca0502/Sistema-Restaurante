-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-06-2026 a las 23:57:52
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `restaurante_ermita`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `estado`) VALUES
(1, 'Pizzas', 1),
(2, 'Alitas', 1),
(3, 'Botanas', 1),
(4, 'Bebidas', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comandas`
--

CREATE TABLE `comandas` (
  `id` int(11) NOT NULL,
  `tipo_orden` enum('mesa','llevar','domicilio') DEFAULT NULL,
  `mesa_id` int(11) DEFAULT NULL,
  `mesero_id` int(11) DEFAULT NULL,
  `cliente_nombre` varchar(100) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `estado` enum('abierta','preparando','lista','pagada','entregada') DEFAULT 'abierta',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comandas`
--

INSERT INTO `comandas` (`id`, `tipo_orden`, `mesa_id`, `mesero_id`, `cliente_nombre`, `telefono`, `direccion`, `estado`, `fecha`) VALUES
(1, 'mesa', 1, NULL, NULL, NULL, NULL, 'pagada', '2026-05-25 16:12:33'),
(2, 'mesa', 1, NULL, NULL, NULL, NULL, 'pagada', '2026-05-25 17:23:59'),
(3, 'mesa', 1, NULL, NULL, NULL, NULL, 'pagada', '2026-05-25 17:46:18'),
(4, 'mesa', 3, NULL, NULL, NULL, NULL, 'pagada', '2026-05-25 17:56:50'),
(5, 'mesa', 2, NULL, NULL, NULL, NULL, 'pagada', '2026-05-25 17:57:00'),
(6, 'mesa', 1, NULL, NULL, NULL, NULL, 'pagada', '2026-05-26 19:57:35'),
(7, 'mesa', 3, 4, NULL, NULL, NULL, 'pagada', '2026-05-27 22:32:44'),
(8, 'mesa', 1, 4, NULL, NULL, NULL, 'pagada', '2026-05-29 18:22:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comanda_detalle`
--

CREATE TABLE `comanda_detalle` (
  `id` int(11) NOT NULL,
  `comanda_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `notas` text DEFAULT NULL,
  `estado` enum('pendiente','preparando','lista','entregado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `nombre_restaurante` varchar(150) DEFAULT NULL,
  `rfc` varchar(50) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `mensaje_ticket` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_orden`
--

CREATE TABLE `detalle_orden` (
  `id` int(11) NOT NULL,
  `orden_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `notas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_movimientos`
--

CREATE TABLE `inventario_movimientos` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `tipo` enum('entrada','salida') DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `numero_mesa` int(11) NOT NULL,
  `estado` enum('libre','ocupada','pagando') DEFAULT 'libre'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `numero_mesa`, `estado`) VALUES
(1, 1, 'libre'),
(2, 2, 'libre'),
(3, 3, 'libre'),
(4, 4, 'libre'),
(5, 5, 'libre');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_inventario`
--

CREATE TABLE `movimientos_inventario` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `tipo` enum('entrada','salida') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes`
--

CREATE TABLE `ordenes` (
  `id` int(11) NOT NULL,
  `folio` varchar(20) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `mesa_id` int(11) DEFAULT NULL,
  `tipo_orden` enum('local','llevar') NOT NULL,
  `estado` enum('pendiente','preparando','lista','entregada','cancelada') DEFAULT 'pendiente',
  `subtotal` decimal(10,2) DEFAULT 0.00,
  `descuento` decimal(10,2) DEFAULT 0.00,
  `total` decimal(10,2) DEFAULT 0.00,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `metodo_pago` enum('efectivo','tarjeta') NOT NULL,
  `dinero_recibido` decimal(10,2) DEFAULT NULL,
  `cambio` decimal(10,2) DEFAULT NULL,
  `cliente_nombre` varchar(150) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `estado_cocina` varchar(50) DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordenes`
--

INSERT INTO `ordenes` (`id`, `folio`, `usuario_id`, `mesa_id`, `tipo_orden`, `estado`, `subtotal`, `descuento`, `total`, `fecha`, `metodo_pago`, `dinero_recibido`, `cambio`, `cliente_nombre`, `telefono`, `direccion`, `estado_cocina`) VALUES
(1, 'ORD-1779206176', 2, NULL, 'llevar', 'entregada', 0.00, 0.00, 200.00, '2026-05-19 15:56:16', 'efectivo', 200.00, 0.00, NULL, NULL, NULL, 'entregada'),
(2, 'ORD-1779207549', 2, 1, 'local', 'entregada', 0.00, 0.00, 100.00, '2026-05-19 16:19:09', 'efectivo', 200.00, 100.00, NULL, NULL, NULL, 'entregada'),
(3, 'ORD-1779210477', 2, NULL, 'llevar', 'entregada', 0.00, 0.00, 100.00, '2026-05-19 17:07:57', 'efectivo', 100.00, 0.00, NULL, NULL, NULL, 'entregada'),
(4, 'ORD-1779210614', 2, 1, 'local', 'entregada', 0.00, 0.00, 100.00, '2026-05-19 17:10:14', 'efectivo', 200.00, 100.00, NULL, NULL, NULL, 'entregada'),
(5, 'ORD-1779210770', 2, 1, 'local', 'entregada', 0.00, 0.00, 100.00, '2026-05-19 17:12:50', 'efectivo', 200.00, 100.00, NULL, NULL, NULL, 'entregada'),
(6, 'ORD-1779210791', 2, NULL, 'llevar', 'entregada', 0.00, 0.00, 100.00, '2026-05-19 17:13:11', 'efectivo', 100.00, 0.00, NULL, NULL, NULL, 'entregada'),
(7, 'ORD-1779210826', 2, 5, 'local', 'entregada', 0.00, 0.00, 100.00, '2026-05-19 17:13:46', 'efectivo', 200.00, 100.00, NULL, NULL, NULL, 'entregada'),
(8, 'ORD-1779211827', 2, NULL, 'llevar', 'entregada', 0.00, 0.00, 100.00, '2026-05-19 17:30:27', 'tarjeta', NULL, NULL, NULL, NULL, NULL, 'entregada'),
(9, 'ORD-1779254200', 2, NULL, 'llevar', 'entregada', 0.00, 0.00, 100.00, '2026-05-20 05:16:40', 'tarjeta', NULL, NULL, NULL, NULL, NULL, 'entregada'),
(10, 'ORD-1779301912', 2, NULL, 'local', 'entregada', 0.00, 0.00, 100.00, '2026-05-20 18:31:52', 'efectivo', 200.00, 100.00, NULL, NULL, NULL, 'entregada'),
(11, 'ORD-1779823095', 2, NULL, 'llevar', 'pendiente', 0.00, 0.00, 100.00, '2026-05-26 19:18:15', 'tarjeta', NULL, NULL, NULL, NULL, NULL, 'entregada'),
(12, 'ORD-1779824224', 2, NULL, '', 'pendiente', 0.00, 0.00, 100.00, '2026-05-26 19:37:04', 'efectivo', 500.00, 400.00, 'Eduardo Hdz', '8664079939', 'Hinojosa 114', 'entregada'),
(13, 'ORD-1779824554', 2, NULL, 'llevar', 'pendiente', 0.00, 0.00, 100.00, '2026-05-26 19:42:34', 'efectivo', 100.00, 0.00, NULL, NULL, NULL, 'entregada'),
(14, 'ORD-1779825425', 2, NULL, 'llevar', 'pendiente', 0.00, 0.00, 200.00, '2026-05-26 19:57:05', 'efectivo', 200.00, 0.00, NULL, NULL, NULL, 'entregada'),
(15, 'ORD-1780078881', 2, NULL, 'llevar', 'pendiente', 0.00, 0.00, 100.00, '2026-05-29 18:21:21', 'efectivo', 100.00, 0.00, NULL, NULL, NULL, 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `comanda_id` int(11) NOT NULL,
  `metodo_pago` enum('efectivo','tarjeta') NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `dinero_recibido` decimal(10,2) DEFAULT NULL,
  `cambio` decimal(10,2) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `imagen` varchar(255) DEFAULT NULL,
  `disponible` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `descripcion`, `precio`, `stock`, `imagen`, `disponible`, `fecha_creacion`) VALUES
(1, 2, 'Alitas BBQ', 'Alitas sabor bbq', 100.00, 199, NULL, 1, '2026-05-19 15:51:57'),
(2, 1, 'Pizza Hawaiana', 'pizza', 100.00, 100, NULL, 1, '2026-05-20 18:36:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promociones`
--

CREATE TABLE `promociones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `dias` varchar(100) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','mesero','cocina','caja') DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `qr_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `password`, `rol`, `estado`, `fecha_creacion`, `qr_token`) VALUES
(1, 'Administrador', 'admin', '$2y$10$mZ6aZi/sCQVYGrgL8/J9S.ZbOq/05V0jBVGaTutc.NOjuc0bqxjkG', 'admin', 1, '2026-05-19 04:18:26', 'ADMIN_1'),
(2, 'Cajero', 'cajero', '$2y$10$65OuSilE8DtiEy.rh8T8u.gjbFuYrnELgzpV3K2FL6WNAORI9o9su', 'caja', 1, '2026-05-19 04:18:26', 'CAJERO_2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `tipo_orden` varchar(50) DEFAULT NULL,
  `mesa_id` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas_detalle`
--

CREATE TABLE `ventas_detalle` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `comandas`
--
ALTER TABLE `comandas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `comanda_detalle`
--
ALTER TABLE `comanda_detalle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comanda_id` (`comanda_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_orden`
--
ALTER TABLE `detalle_orden`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orden_id` (`orden_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `inventario_movimientos`
--
ALTER TABLE `inventario_movimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_mesa` (`numero_mesa`);

--
-- Indices de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `folio` (`folio`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `mesa_id` (`mesa_id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comanda_id` (`comanda_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `promociones`
--
ALTER TABLE `promociones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas_detalle`
--
ALTER TABLE `ventas_detalle`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `comandas`
--
ALTER TABLE `comandas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `comanda_detalle`
--
ALTER TABLE `comanda_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_orden`
--
ALTER TABLE `detalle_orden`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventario_movimientos`
--
ALTER TABLE `inventario_movimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `promociones`
--
ALTER TABLE `promociones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ventas_detalle`
--
ALTER TABLE `ventas_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comanda_detalle`
--
ALTER TABLE `comanda_detalle`
  ADD CONSTRAINT `comanda_detalle_ibfk_1` FOREIGN KEY (`comanda_id`) REFERENCES `comandas` (`id`),
  ADD CONSTRAINT `comanda_detalle_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `detalle_orden`
--
ALTER TABLE `detalle_orden`
  ADD CONSTRAINT `detalle_orden_ibfk_1` FOREIGN KEY (`orden_id`) REFERENCES `ordenes` (`id`),
  ADD CONSTRAINT `detalle_orden_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `inventario_movimientos`
--
ALTER TABLE `inventario_movimientos`
  ADD CONSTRAINT `inventario_movimientos_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD CONSTRAINT `movimientos_inventario_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD CONSTRAINT `ordenes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `ordenes_ibfk_2` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`comanda_id`) REFERENCES `comandas` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
