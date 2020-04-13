-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 13 avr. 2020 à 15:42
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `ecebay`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresse`
--

DROP TABLE IF EXISTS `adresse`;
CREATE TABLE IF NOT EXISTS `adresse` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `OwnerID` int(255) NOT NULL,
  `Ligne1` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `Ligne2` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `Ville` varchar(30) COLLATE utf8mb4_bin NOT NULL,
  `CodePostal` varchar(10) COLLATE utf8mb4_bin NOT NULL,
  `Pays` varchar(30) COLLATE utf8mb4_bin NOT NULL,
  `Telephone` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `adresse`
--

INSERT INTO `adresse` (`ID`, `OwnerID`, `Ligne1`, `Ligne2`, `Ville`, `CodePostal`, `Pays`, `Telephone`) VALUES
(1, 1, '68 rue raspail', '', 'Bois Colombes', '92270', 'France', '+33614012536'),
(2, 1, '54 rue Aristide Bian', 'C est bien le 54', 'Colombes', '92700', 'France', '0123456789'),
(3, 2, '104 rue pierre joigneaux', '', 'Bois Colombes', '92270', 'France', '0648591526'),
(4, 2, '842 rue tartempion', '', 'Paris', '75000', 'France', '0548795616'),
(8, 2, '8718 rue Truc', '', 'Muche', '696969', 'Angleterre', '0789654123');

-- --------------------------------------------------------

--
-- Structure de la table `cartebancaire`
--

DROP TABLE IF EXISTS `cartebancaire`;
CREATE TABLE IF NOT EXISTS `cartebancaire` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `OwnerID` int(255) NOT NULL,
  `TypeCarte` varchar(30) COLLATE utf8mb4_bin NOT NULL,
  `NumeroCarte` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `NomAffiche` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `DatePeremption` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `Cryptogramme` varchar(3) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `cartebancaire`
--

INSERT INTO `cartebancaire` (`ID`, `OwnerID`, `TypeCarte`, `NumeroCarte`, `NomAffiche`, `DatePeremption`, `Cryptogramme`) VALUES
(2, 2, 'Visa', '1234567891011121', 'Cyrille KASYC', '01/25', '888');

-- --------------------------------------------------------

--
-- Structure de la table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `OwnerID` int(255) NOT NULL,
  `Nom` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `DescriptionQualites` varchar(247) COLLATE utf8mb4_bin NOT NULL,
  `DescriptionDefauts` varchar(247) COLLATE utf8mb4_bin NOT NULL,
  `Categorie` varchar(31) COLLATE utf8mb4_bin NOT NULL,
  `EtatVente` int(3) NOT NULL,
  `ModeVente` int(3) NOT NULL,
  `PrixDepart` decimal(65,0) DEFAULT NULL,
  `VenteDirect` int(2) DEFAULT NULL,
  `PrixVenteDirect` decimal(65,0) DEFAULT NULL,
  `dateMiseEnLigne` varchar(30) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `item`
--

INSERT INTO `item` (`ID`, `OwnerID`, `Nom`, `DescriptionQualites`, `DescriptionDefauts`, `Categorie`, `EtatVente`, `ModeVente`, `PrixDepart`, `VenteDirect`, `PrixVenteDirect`, `dateMiseEnLigne`) VALUES
(7, 2, 'drgrt', 'zrgrt', 'ergret', 'ferraille', 1, 1, '10', 0, '0', '1586790529'),
(2, 2, 'Montre', 'Jolie montre plaquée or, toujours brillante', 'Le mécanisme est cassé', 'musee', 1, 2, '25', 1, '50', '1586734575'),
(3, 2, 'iPhone 6', 'Vendu avec boite', 'Ecran cassé', 'VIP', 1, 1, '400', 0, '0', '1586734901'),
(4, 2, 'iPhone 6s', 'Vendu avec protections', 'Bouton home HS', 'VIP', 1, 2, '400', 0, '0', '1586735202'),
(8, 2, 'drgrt', 'zrgrt', 'ergret', 'ferraille', 1, 1, '10', 0, '0', '1586790557'),
(10, 2, 'Cyrille KASYC', 'azda', 'azdazd', 'ferraille', 1, 1, '10', 0, '0', '1586790674');

-- --------------------------------------------------------

--
-- Structure de la table `logintoken`
--

DROP TABLE IF EXISTS `logintoken`;
CREATE TABLE IF NOT EXISTS `logintoken` (
  `Token` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `UserID` int(255) NOT NULL,
  `CreationDate` varchar(30) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`Token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `logintoken`
--

INSERT INTO `logintoken` (`Token`, `UserID`, `CreationDate`) VALUES
('39d598cc652c0baddf38c22791e2d795', 2, '1586785955'),
('b963df20c83ec7641d6a485febb28634', 2, '1586782343'),
('b411710eefdad1a306991a8a55d14450', 2, '1586775965'),
('f754dd37f1f39d59a0d61f1e4c460760', 2, '1586732044'),
('96cce1c9059f7f2f9d74385f6e744836', 1, '1586728433'),
('789617614a17ab8eb2ae6b7749f38fa5', 2, '1586724500'),
('63307734f62af55eabf106d97c0890d3', 2, '1586790483');

-- --------------------------------------------------------

--
-- Structure de la table `medias`
--

DROP TABLE IF EXISTS `medias`;
CREATE TABLE IF NOT EXISTS `medias` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `ItemID` int(255) NOT NULL,
  `Lien` varchar(511) COLLATE utf8mb4_bin NOT NULL,
  `type` int(2) NOT NULL,
  `Ordre` int(10) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `medias`
--

INSERT INTO `medias` (`ID`, `ItemID`, `Lien`, `type`, `Ordre`) VALUES
(6, 10, './uploads/2/item98a3483e52a570f5b36043883b358265_1586790585_Windows.png', 1, 2),
(5, 10, './uploads/2/itemd14ea73992aefe08a0f631cc7fb2734e_1586790585_Film.png', 1, 1),
(4, 10, './uploads/2/itema8c18e0d24e40c7ec4ebc9c8ed9e2c5f_1586790585_Musique.png', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Mail` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `MotDePasse` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `Nom` varchar(30) COLLATE utf8mb4_bin NOT NULL,
  `Prenom` varchar(30) COLLATE utf8mb4_bin NOT NULL,
  `TypeCompte` int(3) NOT NULL,
  `StyleFavoris` int(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Mail` (`Mail`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`ID`, `Mail`, `MotDePasse`, `Nom`, `Prenom`, `TypeCompte`, `StyleFavoris`) VALUES
(1, 'cyrkcyrk99@gmail.com', '43931e6963b120b3b25e198c10232d93', 'KASYC', 'Cyrille', 1, 0),
(2, 'cyrille.kas@gmail.com', 'b42ccf8050e5f1e1e266929374369396', 'Jean', 'Jaque', 1, 0),
(3, 'Cacaboudin', 'd2c6a0dd55e11f98ba654b0942661f73', 'Prout', 'Pipi', 1, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
