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
) ENGINE=INNODB DEFAULT CHARSET=latin1;

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
