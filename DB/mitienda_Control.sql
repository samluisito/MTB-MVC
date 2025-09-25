-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 28-03-2025 a las 10:54:46
-- Versión del servidor: 5.7.44
-- Versión de PHP: 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mitienda_Control`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `idcte` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_empresa` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `db_host` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `db_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `db_user` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `db_password` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `db_charset` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `regionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`idcte`, `nombre`, `url_empresa`, `db_host`, `db_name`, `db_user`, `db_password`, `db_charset`, `regionid`) VALUES
(1, 'mitiendabit', 'mitiendabit', '127.0.0.1:3306', 'mitienda_dbcli_1', 'mitienda_prod', 'mitienda031282', 'utf8mb4', 1),
(2, 'Karina Cure Integral', 'kcintegral', '127.0.0.1:3306', 'mitienda_dbcli_2', 'mitienda_prod', 'mitienda031282', 'utf8mb4', 1),
(3, 'Autopartes ReCu', 'autopartesrecu', '127.0.0.1:3306', 'mitienda_dbcli_3', 'mitienda_prod', 'mitienda031282', 'utf8mb4', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config_regional`
--

CREATE TABLE `config_regional` (
  `idregion` int(11) NOT NULL,
  `region` varchar(100) DEFAULT NULL,
  `region_abrev` varchar(50) DEFAULT NULL,
  `idioma` varchar(50) DEFAULT NULL,
  `timezone` varchar(50) DEFAULT NULL,
  `moneda` varchar(50) DEFAULT NULL,
  `moneda_formato` varchar(50) DEFAULT NULL,
  `moneda_simbolo` varchar(10) DEFAULT NULL,
  `moneda_separador_miles` varchar(10) DEFAULT NULL,
  `moneda_separador_decimales` varchar(10) DEFAULT NULL,
  `zona_horaria` varchar(50) DEFAULT NULL,
  `fecha_formato` varchar(50) DEFAULT NULL,
  `img` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `config_regional`
--

INSERT INTO `config_regional` (`idregion`, `region`, `region_abrev`, `idioma`, `timezone`, `moneda`, `moneda_formato`, `moneda_simbolo`, `moneda_separador_miles`, `moneda_separador_decimales`, `zona_horaria`, `fecha_formato`, `img`) VALUES
(1, 'Argentina', 'AR', 'es', 'America/Argentina/Buenos_Aires', 'Peso argentino', '0.00', '$', '.', ',', 'UTC-3', 'dd/mm/yyyy', NULL),
(2, 'Venezuela', 'VE', 'es', 'America/Caracas', 'Bolívar venezolano', '0,00', 'Bs.', '.', ',', 'UTC-4', 'dd/mm/yyyy', NULL),
(3, 'Chile', 'CL', 'es', 'America/Santiago', 'Peso chileno', '0.00', '$', '.', ',', 'UTC-4', 'dd/mm/yyyy', NULL),
(4, 'Colombia', 'CO', 'es', 'America/Bogota', 'Peso colombiano', '0.00', '$', '.', ',', 'UTC-5', 'dd/mm/yyyy', NULL),
(5, 'Panama', 'PA', 'es', 'America/Panama', 'Balboa panameño', '0.00', 'B/.', '.', ',', 'UTC-5', 'dd/mm/yyyy', NULL),
(6, 'Estados Unidos (Este)', 'US-E', 'en', 'America/New_York', 'Dólar estadounidense', '0.00', '$', ',', '.', 'UTC-4', 'mm/dd/yyyy', NULL),
(7, 'Estados Unidos (Centro)', 'US-C', 'en', 'America/Chicago', 'Dólar estadounidense', '0.00', '$', ',', '.', 'UTC-5', 'mm/dd/yyyy', NULL),
(8, 'Estados Unidos (Oeste)', 'US-W', 'en', 'America/Los_Angeles', 'Dólar estadounidense', '0.00', '$', ',', '.', 'UTC-7', 'mm/dd/yyyy', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idcte`),
  ADD KEY `nombre` (`nombre`),
  ADD KEY `regionid` (`regionid`);

--
-- Indices de la tabla `config_regional`
--
ALTER TABLE `config_regional`
  ADD PRIMARY KEY (`idregion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idcte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `config_regional`
--
ALTER TABLE `config_regional`
  MODIFY `idregion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`regionid`) REFERENCES `config_regional` (`idregion`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
