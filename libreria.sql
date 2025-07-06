-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 06-07-2025 a las 04:37:28
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10
CREATE DATABASE IF NOT EXISTS libreria;
USE libreria;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `libreria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int NOT NULL,
  `usuario_id` int DEFAULT NULL,
  `estado` enum('pendiente','preparado','listo','entregado') DEFAULT 'pendiente',
  `total` decimal(10,2) DEFAULT '0.00',
  `fecha_pedido` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `estado`, `total`, `fecha_pedido`) VALUES
(1, 1, 'pendiente', 47.80, '2025-07-05 23:35:01'),
(2, 1, 'preparado', 20.00, '2025-07-05 23:35:20'),
(3, 1, 'listo', 34.00, '2025-07-05 23:35:27'),
(4, 1, 'entregado', 20.00, '2025-07-05 23:35:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalle`
--

CREATE TABLE `pedido_detalle` (
  `id` int NOT NULL,
  `pedido_id` int DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `pedido_detalle`
--

INSERT INTO `pedido_detalle` (`id`, `pedido_id`, `titulo`, `precio`) VALUES
(1, 1, 'A Study in Scarlet', 20.00),
(2, 1, 'La Tregua', 27.80),
(3, 2, 'A Christmas Carol', 20.00),
(4, 3, 'El Amor en los Tiempos del Cólera', 34.00),
(5, 4, 'Candide', 20.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `descripcion` text,
  `autor` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `autor`, `precio`, `stock`, `imagen_url`) VALUES
(1, 'El Gran Gatsby', 'Un clásico de la literatura estadounidense.', 'F. Scott Fitzgerald', 35.50, 10, 'https://covers.openlibrary.org/b/id/10523333-L.jpg'),
(2, 'Cien Años de Soledad', 'La obra maestra del realismo mágico.', 'Gabriel García Márquez', 42.00, 8, 'https://covers.openlibrary.org/b/id/11121219-L.jpg'),
(3, 'Don Quijote de la Mancha', 'La novela más influyente de la literatura española.', 'Miguel de Cervantes', 29.99, 12, 'https://covers.openlibrary.org/b/id/240727-L.jpg'),
(4, '1984', 'Una distopía sobre el totalitarismo.', 'George Orwell', 31.25, 15, 'https://covers.openlibrary.org/b/id/7222246-L.jpg'),
(5, 'Matar a un Ruiseñor', 'Un relato sobre la injusticia racial.', 'Harper Lee', 28.40, 7, 'https://covers.openlibrary.org/b/id/8228691-L.jpg'),
(6, 'Orgullo y Prejuicio', 'Una de las historias de amor más famosas.', 'Jane Austen', 27.80, 9, 'https://covers.openlibrary.org/b/id/8081536-L.jpg'),
(7, 'Harry Potter y la Piedra Filosofal', 'El inicio de una saga mágica.', 'J.K. Rowling', 45.00, 20, 'https://covers.openlibrary.org/b/id/7984916-L.jpg'),
(8, 'El Hobbit', 'La aventura que precede a El Señor de los Anillos.', 'J.R.R. Tolkien', 33.00, 14, 'https://covers.openlibrary.org/b/id/6979861-L.jpg'),
(9, 'Los Juegos del Hambre', 'Un mundo distópico de supervivencia.', 'Suzanne Collins', 30.50, 11, 'https://covers.openlibrary.org/b/id/7261955-L.jpg'),
(10, 'Crónica de una Muerte Anunciada', 'Una novela breve y poderosa.', 'Gabriel García Márquez', 26.75, 10, 'https://covers.openlibrary.org/b/id/9553667-L.jpg'),
(11, 'La Sombra del Viento', 'Un thriller literario en la Barcelona de posguerra.', 'Carlos Ruiz Zafón', 39.90, 13, 'https://covers.openlibrary.org/b/id/5949421-L.jpg'),
(12, 'La Chica del Tren', 'Un thriller psicológico moderno.', 'Paula Hawkins', 32.40, 8, 'https://covers.openlibrary.org/b/id/8159550-L.jpg'),
(13, 'El Código Da Vinci', 'Un misterio lleno de símbolos.', 'Dan Brown', 35.20, 12, 'https://covers.openlibrary.org/b/id/240726-L.jpg'),
(14, 'Inferno', 'Otra aventura simbólica de Robert Langdon.', 'Dan Brown', 34.50, 9, 'https://covers.openlibrary.org/b/id/10582314-L.jpg'),
(15, 'It', 'Una historia de terror inolvidable.', 'Stephen King', 46.00, 7, 'https://covers.openlibrary.org/b/id/8231855-L.jpg'),
(16, 'Carrie', 'La primera novela de Stephen King.', 'Stephen King', 28.90, 10, 'https://covers.openlibrary.org/b/id/8091011-L.jpg'),
(17, 'Los Pilares de la Tierra', 'Una novela histórica épica.', 'Ken Follett', 44.60, 11, 'https://covers.openlibrary.org/b/id/10582416-L.jpg'),
(18, 'La Catedral del Mar', 'La construcción de una iglesia en la Barcelona medieval.', 'Ildefonso Falcones', 36.30, 9, 'https://covers.openlibrary.org/b/id/6939353-L.jpg'),
(19, 'Ready Player One', 'Un viaje al futuro de la realidad virtual.', 'Ernest Cline', 31.70, 14, 'https://covers.openlibrary.org/b/id/7572913-L.jpg'),
(20, 'Dune', 'Una epopeya de ciencia ficción.', 'Frank Herbert', 39.80, 8, 'https://covers.openlibrary.org/b/id/10523338-L.jpg'),
(21, 'Fahrenheit 451', 'Un futuro donde los libros están prohibidos.', 'Ray Bradbury', 25.50, 10, 'https://covers.openlibrary.org/b/id/11053970-L.jpg'),
(22, 'La Isla del Tesoro', 'Una clásica aventura de piratas.', 'Robert Louis Stevenson', 27.30, 12, 'https://covers.openlibrary.org/b/id/8231974-L.jpg'),
(23, 'La Odisea', 'La épica travesía de Ulises.', 'Homero', 29.00, 10, 'https://covers.openlibrary.org/b/id/7222245-L.jpg'),
(24, 'Hamlet', 'Una tragedia shakesperiana.', 'William Shakespeare', 26.00, 9, 'https://covers.openlibrary.org/b/id/10524518-L.jpg'),
(25, 'Romeo y Julieta', 'La historia de amor más famosa.', 'William Shakespeare', 25.00, 8, 'https://covers.openlibrary.org/b/id/10022509-L.jpg'),
(26, 'Macbeth', 'La ambición y sus consecuencias.', 'William Shakespeare', 24.50, 7, 'https://covers.openlibrary.org/b/id/10524768-L.jpg'),
(27, 'La Iliada', 'La epopeya de la guerra de Troya.', 'Homero', 30.00, 10, 'https://covers.openlibrary.org/b/id/8228757-L.jpg'),
(28, 'El Principito', 'Una historia universal sobre la infancia.', 'Antoine de Saint-Exupéry', 22.90, 15, 'https://covers.openlibrary.org/b/id/10523613-L.jpg'),
(29, 'El Alquimista', 'Un viaje de autodescubrimiento.', 'Paulo Coelho', 26.50, 13, 'https://covers.openlibrary.org/b/id/8315122-L.jpg'),
(30, 'Sapiens', 'Una breve historia de la humanidad.', 'Yuval Noah Harari', 35.80, 11, 'https://covers.openlibrary.org/b/id/10523601-L.jpg'),
(31, 'Homo Deus', 'El futuro de la humanidad.', 'Yuval Noah Harari', 37.20, 10, 'https://covers.openlibrary.org/b/id/10523606-L.jpg'),
(32, 'El Hombre en Busca de Sentido', 'La experiencia de un sobreviviente de Auschwitz.', 'Viktor Frankl', 29.90, 9, 'https://covers.openlibrary.org/b/id/10523820-L.jpg'),
(33, 'El Arte de la Guerra', 'El clásico tratado de estrategia.', 'Sun Tzu', 21.50, 14, 'https://covers.openlibrary.org/b/id/10523867-L.jpg'),
(34, 'Meditaciones', 'Los pensamientos de un emperador estoico.', 'Marco Aurelio', 23.40, 13, 'https://covers.openlibrary.org/b/id/10523895-L.jpg'),
(35, 'Los Hermanos Karamázov', 'Una novela filosófica sobre la moral.', 'Fiódor Dostoievski', 38.00, 8, 'https://covers.openlibrary.org/b/id/8232142-L.jpg'),
(36, 'Crimen y Castigo', 'La lucha de un hombre con su conciencia.', 'Fiódor Dostoievski', 36.50, 7, 'https://covers.openlibrary.org/b/id/8228748-L.jpg'),
(37, 'Anna Karenina', 'Una tragedia sobre el amor y la sociedad.', 'León Tolstói', 34.00, 10, 'https://covers.openlibrary.org/b/id/10523928-L.jpg'),
(38, 'Guerra y Paz', 'Una novela monumental sobre Rusia.', 'León Tolstói', 42.00, 6, 'https://covers.openlibrary.org/b/id/10523965-L.jpg'),
(39, 'La Metamorfosis', 'La historia de un hombre que despierta como insecto.', 'Franz Kafka', 24.00, 12, 'https://covers.openlibrary.org/b/id/10524014-L.jpg'),
(40, 'Ulises', 'Una odisea moderna por Dublín.', 'James Joyce', 40.00, 5, 'https://covers.openlibrary.org/b/id/10524105-L.jpg'),
(41, 'En Busca del Tiempo Perdido', 'Una obra monumental de introspección.', 'Marcel Proust', 45.00, 4, 'https://covers.openlibrary.org/b/id/10524134-L.jpg'),
(42, 'Lolita', 'Una novela controvertida sobre obsesión.', 'Vladimir Nabokov', 32.00, 7, 'https://covers.openlibrary.org/b/id/10524177-L.jpg'),
(43, 'El Nombre de la Rosa', 'Un misterio medieval en un monasterio.', 'Umberto Eco', 35.00, 8, 'https://covers.openlibrary.org/b/id/10524203-L.jpg'),
(44, 'Rayuela', 'Una novela experimental e icónica.', 'Julio Cortázar', 33.00, 9, 'https://covers.openlibrary.org/b/id/10524245-L.jpg'),
(45, 'Pedro Páramo', 'Una obra maestra del realismo mágico.', 'Juan Rulfo', 28.00, 10, 'https://covers.openlibrary.org/b/id/10524284-L.jpg'),
(46, 'El Amor en los Tiempos del Cólera', 'Una historia de amor que desafía el tiempo.', 'Gabriel García Márquez', 34.00, 8, 'https://covers.openlibrary.org/b/id/10524321-L.jpg'),
(47, 'La Casa de los Espíritus', 'Una saga familiar marcada por la magia.', 'Isabel Allende', 31.00, 11, 'https://covers.openlibrary.org/b/id/10524355-L.jpg'),
(48, 'Los Detectives Salvajes', 'Una novela sobre la juventud y la literatura en América Latina.', 'Roberto Bolaño', 36.00, 9, 'https://covers.openlibrary.org/b/id/10524388-L.jpg'),
(49, '2666', 'Una obra compleja y ambiciosa sobre el mal y la literatura.', 'Roberto Bolaño', 44.50, 1, ''),
(50, 'Ensayo sobre la Ceguera', 'Una alegoría sobre la sociedad y la pérdida de valores.', 'José Saramago', 30.70, 2, ''),
(51, 'La Tregua', 'Un diario íntimo que narra una historia de amor y soledad.', 'Mario Benedetti', 27.80, 3, ''),
(52, 'La Ciudad y los Perros', 'Una crítica a la sociedad militar y educativa del Perú.', 'Mario Vargas Llosa', 34.20, 2, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `tipo` enum('administrador','empleado','cliente') DEFAULT 'cliente',
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `contrasena`, `tipo`, `fecha_registro`) VALUES
(1, 'Cliente', 'cliente@libreria.com', '$2y$10$YzGI6scCcv2F8HSRw5iN7eY.d/IJTAHCQGeUnEyjVOgEuOTpZTzhi', 'cliente', '2025-07-05 23:30:29'),
(2, 'Empleado', 'empleado@libreria.com', '$2y$10$fv70z5zKq79F/PCq6fyU/uUnTeBso8W3856k16VHihV5aHNIem6BC', 'empleado', '2025-07-05 23:30:43'),
(3, 'Administrador', 'admin@libreria.com', '$2y$10$8xSy6C4sfnV4yHlzoIIwM.1nvMirC2ZETL4CZ7CSqeTeVQSFnCbUC', 'administrador', '2025-07-05 23:30:55');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedidos_ibfk_1` (`usuario_id`);

--
-- Indices de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  ADD CONSTRAINT `pedido_detalle_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
