-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Nov 17, 2019 alle 17:44
-- Versione del server: 10.1.41-MariaDB-cll-lve
-- Versione PHP: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hnlzewad_edmdeathplaylistmachine`
--

DELIMITER $$
--
-- Funzioni
--
CREATE DEFINER=`hnlzewad`@`localhost` FUNCTION `LastMonday` () RETURNS DATETIME RETURN DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `added_tracks`
--

CREATE TABLE `added_tracks` (
  `idAdd` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `trackUri` varchar(60) NOT NULL,
  `datetime` datetime NOT NULL,
  `duplicated` tinyint(1) DEFAULT NULL,
  `removed` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `added_tracks`
--

INSERT INTO `added_tracks` (`idAdd`, `idUser`, `trackUri`, `datetime`, `duplicated`, `removed`) VALUES
(73, 5, 'spotify:track:1geovaCdfs5fSa4NNgFPVe', '2019-11-17 13:32:35', NULL, 1),
(74, 5, 'spotify:track:32JaQ9q2fPvR2Uzy1Gcd9c', '2019-11-17 13:32:51', NULL, 1),
(75, 5, 'spotify:track:3lWzVNe1yFZlkeBBzUuZYu', '2019-11-17 13:32:51', NULL, 1),
(76, 5, 'spotify:track:3NwVga1qCZ6TgJ8RVcBbKD', '2019-11-17 13:32:51', NULL, 1),
(77, 5, 'spotify:track:7zn8XbHMzo8JRsxSsh4VaR', '2019-11-17 13:32:51', NULL, 1),
(78, 5, 'spotify:track:1geovaCdfs5fSa4NNgFPVe', '2019-11-17 13:43:55', NULL, 1),
(79, 5, 'spotify:track:32JaQ9q2fPvR2Uzy1Gcd9c', '2019-11-17 13:44:07', NULL, 1),
(80, 5, 'spotify:track:3lWzVNe1yFZlkeBBzUuZYu', '2019-11-17 13:44:07', NULL, 1),
(81, 5, 'spotify:track:3NwVga1qCZ6TgJ8RVcBbKD', '2019-11-17 13:44:07', NULL, 1),
(82, 5, 'spotify:track:7zn8XbHMzo8JRsxSsh4VaR', '2019-11-17 13:44:07', NULL, 1),
(83, 5, 'spotify:track:0H9xfZtaL0ZT96vd3KNIDC', '2019-11-17 14:39:33', NULL, 1),
(84, 5, 'spotify:track:38dmkPe7nIe9lDGZ5Xdewc', '2019-11-17 14:39:33', NULL, 1),
(85, 5, 'spotify:track:32JaQ9q2fPvR2Uzy1Gcd9c', '2019-11-17 14:42:07', NULL, 1),
(86, 5, 'spotify:track:1geovaCdfs5fSa4NNgFPVe', '2019-11-17 15:16:03', NULL, NULL),
(87, 5, 'spotify:track:32JaQ9q2fPvR2Uzy1Gcd9c', '2019-11-17 15:16:03', NULL, 1),
(88, 5, 'spotify:track:3lWzVNe1yFZlkeBBzUuZYu', '2019-11-17 15:16:03', NULL, 1),
(89, 5, 'spotify:track:3NwVga1qCZ6TgJ8RVcBbKD', '2019-11-17 15:16:03', NULL, 1),
(90, 5, 'spotify:track:7zn8XbHMzo8JRsxSsh4VaR', '2019-11-17 15:16:03', NULL, 1),
(91, 5, 'spotify:track:4vJH4sXPuxuXHdaYVJlaIm', '2019-11-17 16:05:11', NULL, NULL),
(92, 5, 'spotify:track:3T2hGU0fZhcNT7EBza1MYh', '2019-11-17 16:58:46', NULL, 1),
(93, 5, 'spotify:track:5yhQS5ce3WebABcdngULA8', '2019-11-17 16:58:46', NULL, 1),
(94, 5, 'spotify:track:5c9YHjSjEZxaCxsF1Gy5Jt', '2019-11-17 16:58:46', NULL, 1),
(95, 5, 'spotify:track:3T2hGU0fZhcNT7EBza1MYh', '2019-11-17 16:59:58', NULL, 1),
(96, 5, 'spotify:track:5yhQS5ce3WebABcdngULA8', '2019-11-17 16:59:58', NULL, 1),
(97, 5, 'spotify:track:5c9YHjSjEZxaCxsF1Gy5Jt', '2019-11-17 16:59:58', NULL, 1),
(98, 5, 'spotify:track:3T2hGU0fZhcNT7EBza1MYh', '2019-11-17 17:02:10', NULL, 1),
(99, 5, 'spotify:track:5yhQS5ce3WebABcdngULA8', '2019-11-17 17:02:10', NULL, NULL),
(100, 5, 'spotify:track:5c9YHjSjEZxaCxsF1Gy5Jt', '2019-11-17 17:02:10', NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE `user` (
  `idUser` int(11) UNSIGNED NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(256) NOT NULL,
  `email` varchar(50) NOT NULL,
  `countryCode` varchar(5) NOT NULL,
  `telephone` varchar(10) NOT NULL,
  `accessToken` varchar(256) DEFAULT NULL,
  `refreshToken` varchar(256) DEFAULT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`idUser`, `username`, `password`, `email`, `countryCode`, `telephone`, `accessToken`, `refreshToken`, `admin`) VALUES
(5, 'Apocalypse480', '{µ@­råô€O&ð6%', 'luca-consoli@live.it', '39', '3312373946', 'BQDN2DLGoMiUlwLbKLcItUckpYJjjsbQDx1Wb0nUa1pstof24-4ACxzuKAxS2nUY4FoXzfjh1CnByGMOcgOZ7JVuvuaZAfnIiZKHUbM07ZnTYO4XrYR1urFLSe6UeVzGekCleQH_5dgSGgG0bxzBdVMBLLjWVw4ICzcstU6ejtn2Ft5830jyXW3c2aW2O11-Csdvy3_riaheAuaIg2dkr4LwSiOuzBMbpfYCHpQPsk0NldfXR79RG1EdP7yBb10O', 'AQCgy6XIzPGOdhnTu8Pv-Eq9NzgIei9b5xiliv5fk14GrEdcp5eGInoWEZXXd1NXxXnc8_AljEEqYs_jcMsKQqoYejXEGXxMO93BJdlZlXzrQqrPpeh0GEbqtZ7ik-bH4Ic', 0),
(6, 'BrokenH', 'fs|é-§›ŠÓî‹!¬($', 'luke.sandrin@gmail.com', '39', '3333162199', 'BQDwoWSLIHE8fPnwU4sBpLRuuHW6E4DBqrHd_KOtt_ppzgN9xVHUSI_h5wpqcJ23lJ9VwBFExa99ex0BEvkY5LUzpSAk9BnLXhHVEbfgmN77P7aiI85wWnO3XGnxWlLJWUmXjOOM_lGUK-EznbSoqyKeuJVDBaKQuLtOElcxaymRJoPcu2o4PGOICa3l2iiAXasLA8TbG41TBK8gGy2l5Dhb-H34rHaGZQjE9u97sgGwQIWTuxgAYJxiwC4BJa1G', 'AQA3vYl1fXyvPa76V_Co-leu1bdIR3XRIv0PjxS_vEJro_f8EgUuBFrv7RyehGhcV3V2m87H_8fHw2cVbKW7pbPWlg7bS6b_M3SRvJVYEaT2YwiTh_-vJFyKUX-V2RfVc0Q', 0);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `added_tracks`
--
ALTER TABLE `added_tracks`
  ADD PRIMARY KEY (`idAdd`);

--
-- Indici per le tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `username` (`username`,`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `added_tracks`
--
ALTER TABLE `added_tracks`
  MODIFY `idAdd` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT per la tabella `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
