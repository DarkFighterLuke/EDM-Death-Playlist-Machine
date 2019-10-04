-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Ott 04, 2019 alle 19:00
-- Versione del server: 10.4.6-MariaDB
-- Versione PHP: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_edmdeathplaylistmachine`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `added_tracks`
--

CREATE TABLE `added_tracks` (
  `idAddition` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `tracks` text NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(5, 'Apocalypse480', '{µ@­råô€O&ð6%', 'luca-consoli@live.it', '39', '3312373946', NULL, NULL, 0);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `added_tracks`
--
ALTER TABLE `added_tracks`
  ADD PRIMARY KEY (`idAddition`);

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
  MODIFY `idAddition` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
