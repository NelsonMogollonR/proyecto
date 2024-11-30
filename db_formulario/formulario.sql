-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-11-2024 a las 21:48:36
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
-- Base de datos: `formulario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id_carrito` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `estado` enum('pendiente','completado') DEFAULT 'pendiente',
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos`
--

CREATE TABLE `datos` (
  `id_cliente` int(20) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `tipoDocumento` varchar(50) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `direccion` varchar(40) NOT NULL,
  `telefono` int(15) NOT NULL,
  `email` varchar(40) NOT NULL,
  `contraseña` varchar(20) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `datos`
--

INSERT INTO `datos` (`id_cliente`, `nombres`, `apellidos`, `tipoDocumento`, `documento`, `direccion`, `telefono`, `email`, `contraseña`, `fecha`) VALUES
(2, 'nelson', 'mogollon', 'Cédula', '14395952', 'calle 17d 115-49 fontibon', 2147483647, '@admin', '123', '2020-11-24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id_producto` int(11) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  `en_oferta` enum('si','no') DEFAULT 'no',
  `descuento` int(11) DEFAULT 0,
  `precio_descuento` decimal(10,2) DEFAULT NULL,
  `cantidad_vendida` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id_producto`, `categoria`, `nombre`, `descripcion`, `precio`, `stock`, `imagen_url`, `en_oferta`, `descuento`, `precio_descuento`, `cantidad_vendida`) VALUES
(6, 'Decoracion', 'caudro', '3 perros', 150000.00, 184, 'imagenes/galeria/67419de5cac6e_cuadro3perros.jpg', 'no', 0, NULL, 6),
(7, 'Decoracion', 'lampara colgante', 'lampara colgante  de techo negra', 80000.00, 199, 'imagenes/galeria/674202fb4f05e_lampara_colgante.jpg', 'no', 0, NULL, 0),
(8, 'Decoracion', 'lampara set x 4', 'juego de 4 lamparas colgantes, ideal para decorar mesasa de comedor', 120000.00, 198, 'imagenes/galeria/674205592dd92_lamparax4.jpg', 'no', 0, NULL, 1),
(9, 'muebles', 'Sofa cama gris 3 puestos', 'sofacama comodo para 3 personas', 1500000.00, 98, 'imagenes/galeria/67452cb0bb5a4_sofa1.jpg', 'no', 0, NULL, 1),
(10, 'muebles', 'Sofa en cuero', 'Sofa en cuero  gris para 2 personas', 1300000.00, 100, 'imagenes/galeria/67452d1c2bf39_sofa2.jpg', 'no', 0, NULL, 0),
(11, 'muebles', 'sofa en L', 'sofa en L blanco elegante', 3500000.00, 100, 'imagenes/galeria/67452e60b38b9_sofaModerno.png', 'no', 0, NULL, 0),
(14, 'decoracion', 'caudro pug', 'imagen de perrito pug', 100000.00, 189, 'imagenes/galeria/67490671c4357_descarga.jpg', 'si', 20, 80000.00, 11),
(15, 'decoracion', 'caudro husky', 'imagen de 3 huskys', 10000.00, 192, 'imagenes/galeria/67490d20ac758_huskys.jpg', 'si', 20, 8000.00, 7),
(16, 'Decoracion', 'El Michi', 'imagen de bebe gatico', 50000.00, 198, 'imagenes/galeria/67492abd6d18e_michi.jpg', 'si', 10, 45000.00, 2),
(17, 'muebles', 'Silla Moderna', 'silla moderna con diseño de celosía y patas de madera anguladas.', 130000.00, 200, 'imagenes/galeria/674b44b0b8538_SillasComedor.png', 'no', 0, 0.00, 0),
(18, 'muebles', 'sofa', 'sofá moderno con cojines rectangulares en gris oscuro y un cojín amarillo brillante', 600000.00, 200, 'imagenes/galeria/674b46e30845e_sofa4.jpg', 'no', 0, 0.00, 0),
(19, 'decoracion', 'Mesa Centro Vidrio', 'Mesa de centro moderna con tapa y lados de vidrio, y estante de madera debajo', 150000.00, 50, 'imagenes/galeria/674b4820b2d22_mesacentro3.jpg', 'no', 0, 0.00, 0),
(20, 'muebles', 'Sala', 'Sala estilo  moderno con cojines rectangulares en gris oscuro y un cojín amarillo brillante.', 650000.00, 20, 'imagenes/galeria/674b48e09128b_sofa.jpg', 'no', 0, 0.00, 0),
(21, 'muebles', 'mesa centro rustica', 'Mesa de centro moderna de madera clara con patas en forma de X', 120000.00, 30, 'imagenes/galeria/674b4c3485608_Mesacentro2.jpg', 'no', 0, 0.00, 0),
(22, 'muebles', 'mesa auxiliar', 'Mesa auxiliar moderna con diseño rectangular en madera clara y base blanca', 150000.00, 30, 'imagenes/galeria/674b4d4b79391_mesacentro5.jpg', 'no', 0, 0.00, 0),
(23, 'escritorio', 'escritorio blanco', 'escritorio de madera de balso color blanca con silla', 280000.00, 5, 'imagenes/galeria/674b4eb30d7e3_tocador.jpg', 'no', 0, 0.00, 0),
(24, 'decoracion', 'mueble dona', 'nueble ovalado relleno en espuma  ortopedica con  cojines de colores', 150000.00, 5, 'imagenes/galeria/674b4f6588374_silla ovalada.jpg', 'no', 0, 0.00, 0),
(25, 'Decoracion', 'Bonsai', 'Bonsai en maceta rectangular de cerámica, con tronco grueso y ramas con hojas verdes, acompañado de una roca blanca.', 80000.00, 100, 'imagenes/galeria/674b50205c85f_bonsai.jpg', 'no', 0, 0.00, 0),
(26, 'decoracion', 'Alfombra tejida', 'Alfombra con un diseño de rayas horizontales y patrones geométricos en tonos neutros de blanco y gris.', 160000.00, 60, 'imagenes/galeria/674b514d842c5_tapete.jpg', 'no', 0, 0.00, 0),
(27, 'decoracion', 'Tapete Azul', 'Tapete detallado y ornamentado con una combinación de colores azul y blanco, con patrones intrincados y un diseño central de medallón floral y paisley.', 280000.00, 10, 'imagenes/galeria/674b5208e7f67_tapete2.jpg', 'no', 0, 0.00, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes`
--

CREATE TABLE `ordenes` (
  `id_orden` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `ciudad` varchar(255) NOT NULL,
  `codigo_postal` varchar(20) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `total_compra` decimal(10,2) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_detalles`
--

CREATE TABLE `orden_detalles` (
  `id_detalle` int(11) NOT NULL,
  `id_orden` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  `en_oferta` enum('si','no') DEFAULT 'no',
  `descuento` int(11) NOT NULL,
  `precio_descuento` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `cliente_id` (`id_cliente`),
  ADD KEY `producto_id` (`id_producto`);

--
-- Indices de la tabla `datos`
--
ALTER TABLE `datos`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD PRIMARY KEY (`id_orden`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `orden_detalles`
--
ALTER TABLE `orden_detalles`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_orden` (`id_orden`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT de la tabla `datos`
--
ALTER TABLE `datos`
  MODIFY `id_cliente` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  MODIFY `id_orden` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `orden_detalles`
--
ALTER TABLE `orden_detalles`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `datos` (`id_cliente`),
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`);

--
-- Filtros para la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD CONSTRAINT `ordenes_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `datos` (`id_cliente`);

--
-- Filtros para la tabla `orden_detalles`
--
ALTER TABLE `orden_detalles`
  ADD CONSTRAINT `orden_detalles_ibfk_1` FOREIGN KEY (`id_orden`) REFERENCES `ordenes` (`id_orden`),
  ADD CONSTRAINT `orden_detalles_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
