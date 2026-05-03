-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 28, 2026 at 12:21 PM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agence_imm`
--

-- --------------------------------------------------------

--
-- Table structure for table `bien_imm`
--

DROP TABLE IF EXISTS `bien_imm`;
CREATE TABLE IF NOT EXISTS `bien_imm` (
  `IdBien` int NOT NULL AUTO_INCREMENT,
  `Type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Superficie` int NOT NULL,
  `Adresse` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Prix_jour` int NOT NULL,
  `nbre_pieces` int NOT NULL,
  `idUser` int NOT NULL,
  `statut` enum('libre','en reservation') COLLATE utf8mb4_unicode_ci DEFAULT 'libre',
  PRIMARY KEY (`IdBien`),
  KEY `idUser` (`idUser`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bien_imm`
--

INSERT INTO `bien_imm` (`IdBien`, `Type`, `Superficie`, `Adresse`, `Description`, `Prix_jour`, `nbre_pieces`, `idUser`, `statut`) VALUES
(2, 'app', 1200, 'Colobane', 'Appartement haut de gamme     ', 15000, 12, 5, 'libre'),
(5, 'app', 1200, 'ksajdnms ', 'dakjlms,asdalsam,.d        ', 123, 12, 5, 'libre');

-- --------------------------------------------------------

--
-- Table structure for table `favoris`
--

DROP TABLE IF EXISTS `favoris`;
CREATE TABLE IF NOT EXISTS `favoris` (
  `idUser` int NOT NULL,
  `idBien` int NOT NULL,
  PRIMARY KEY (`idUser`,`idBien`),
  KEY `fk_favoris_bien` (`idBien`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `historique`
--

DROP TABLE IF EXISTS `historique`;
CREATE TABLE IF NOT EXISTS `historique` (
  `idHistorique` int NOT NULL AUTO_INCREMENT,
  `idUser` int NOT NULL,
  `idLoc` int NOT NULL,
  PRIMARY KEY (`idHistorique`),
  KEY `fk_hist_user` (`idUser`),
  KEY `fk_hist_loc` (`idLoc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
CREATE TABLE IF NOT EXISTS `location` (
  `idLoc` int NOT NULL AUTO_INCREMENT,
  `idBien` int NOT NULL,
  `idUser` int NOT NULL,
  `duree` int NOT NULL,
  `dateDebut` date NOT NULL,
  `prix` int NOT NULL,
  `is_validated` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`idLoc`),
  KEY `fk_location_user` (`idUser`),
  KEY `fk_loc_bien` (`idBien`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

DROP TABLE IF EXISTS `photos`;
CREATE TABLE IF NOT EXISTS `photos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idBien` int NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_photos_bien` (`idBien`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `idBien`, `url`) VALUES
(1, 5, 'photos/19042026_214408_paris.jpg'),
(2, 5, 'photos/19042026_214408_pngtree-computer-setup-and-hardware-image_2660042.jpg'),
(3, 5, 'photos/19042026_214408_pp.jpg'),
(4, 5, 'photos/19042026_214408_recipisse.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `IdUser` int NOT NULL AUTO_INCREMENT,
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `adresse` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_activated` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`IdUser`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`IdUser`, `type`, `nom`, `date`, `adresse`, `mail`, `password`, `is_activated`) VALUES
(1, 'Commercial', 'Ahmadou Bamba Diante', '2006-03-31', 'Keur Massar', 'ahmadoubambadiante@gmail.com', 'olkadsnmas', '1'),
(4, 'Commercial', 'Khadidiatou Ly', '2026-04-29', 'Pikine', 'khadijaly@gmail.com', 'AmadouDiallo', '1'),
(5, 'Commercial', 'Fatoumata DIante', '2026-04-22', 'Colobane', 'FatouDIante@gmail.com', 'FatouDiante', '1');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bien_imm`
--
ALTER TABLE `bien_imm`
  ADD CONSTRAINT `bien_imm_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `users` (`IdUser`);

--
-- Constraints for table `favoris`
--
ALTER TABLE `favoris`
  ADD CONSTRAINT `fk_favoris_bien` FOREIGN KEY (`idBien`) REFERENCES `bien_imm` (`IdBien`),
  ADD CONSTRAINT `fk_favoris_user` FOREIGN KEY (`idUser`) REFERENCES `users` (`IdUser`);

--
-- Constraints for table `historique`
--
ALTER TABLE `historique`
  ADD CONSTRAINT `fk_hist_loc` FOREIGN KEY (`idLoc`) REFERENCES `location` (`idLoc`),
  ADD CONSTRAINT `fk_hist_user` FOREIGN KEY (`idUser`) REFERENCES `users` (`IdUser`);

--
-- Constraints for table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `fk_loc_bien` FOREIGN KEY (`idBien`) REFERENCES `bien_imm` (`IdBien`),
  ADD CONSTRAINT `fk_location_bien` FOREIGN KEY (`idBien`) REFERENCES `bien_imm` (`IdBien`),
  ADD CONSTRAINT `fk_location_user` FOREIGN KEY (`idUser`) REFERENCES `users` (`IdUser`);

--
-- Constraints for table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `fk_photos_bien` FOREIGN KEY (`idBien`) REFERENCES `bien_imm` (`IdBien`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
