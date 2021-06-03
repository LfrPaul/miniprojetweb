-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 03 juin 2021 à 22:03
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `holliiwood`
--

-- --------------------------------------------------------

--
-- Structure de la table `avisglobal`
--

DROP TABLE IF EXISTS `avisglobal`;
CREATE TABLE IF NOT EXISTS `avisglobal` (
  `id_avis` int(11) NOT NULL AUTO_INCREMENT,
  `id_film` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `avis` varchar(255) DEFAULT NULL,
  `type_media` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_avis`,`id_film`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `avisglobal`
--

INSERT INTO `avisglobal` (`id_avis`, `id_film`, `id_user`, `avis`, `type_media`) VALUES
(31, 299534, 1, 'f', 'movie'),
(6, 2316, 1, 'J\'aime bien ', 'tv'),
(7, 2316, 1, 'Je like', 'tv'),
(8, 2316, 1, 'J\'ai mis 5 étoiles !!!', 'tv'),
(10, 2316, 4, 'j\'avoue pareil', 'tv'),
(11, 2316, 1, 'YES', 'movie'),
(12, 691179, 1, 'J\'ai hate', 'movie'),
(13, 1668, 1, 'J\'ai bien aimé', 'tv'),
(14, 1668, 1, 'J\'ai bien aimé', 'tv'),
(15, 632357, 1, 'edfrgthyujk', 'movie'),
(16, 24428, 1, 'J\'aime bien ', 'movie'),
(30, 299534, 6, 'fdsf', 'movie');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
