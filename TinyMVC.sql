-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 31 mai 2021 à 18:48
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
-- Base de données : `miniprojet`
--

-- --------------------------------------------------------

--
-- Structure de la table `avisglobal`
--

DROP TABLE IF EXISTS `avisglobal`;
CREATE TABLE IF NOT EXISTS `avisglobal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_film` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `avis` varchar(255) DEFAULT NULL,
  `type_media` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`,`id_film`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `avisglobal`
--

INSERT INTO `avisglobal` (`id`, `id_film`, `id_user`, `avis`, `type_media`) VALUES
(5, 299534, 1, 'J\'ai bien aimé', 'movie'),
(4, 299534, 1, 'Lol', 'movie'),
(6, 2316, 1, 'J\'aime bien ', 'tv'),
(7, 2316, 1, 'Je like', 'tv'),
(8, 2316, 1, 'J\'ai mis 5 étoiles !!!', 'tv'),
(9, 299534, 4, 'Bof', 'movie'),
(10, 2316, 4, 'j\'avoue pareil', 'tv'),
(11, 2316, 1, 'YES', 'movie'),
(12, 691179, 1, 'J\'ai hate', 'movie'),
(13, 1668, 1, 'J\'ai bien aimé', 'tv'),
(14, 1668, 1, 'J\'ai bien aimé', 'tv'),
(15, 632357, 1, 'edfrgthyujk', 'movie'),
(16, 24428, 1, 'J\'aime bien ', 'movie'),
(17, 299534, 6, 'test', 'movie');

-- --------------------------------------------------------

--
-- Structure de la table `avismoment`
--

DROP TABLE IF EXISTS `avismoment`;
CREATE TABLE IF NOT EXISTS `avismoment` (
  `id_avis` int(11) NOT NULL AUTO_INCREMENT,
  `id_moment` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `avis` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_avis`,`id_moment`),
  KEY `id_moment` (`id_moment`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

DROP TABLE IF EXISTS `favoris`;
CREATE TABLE IF NOT EXISTS `favoris` (
  `id_user` int(11) NOT NULL,
  `id_film` int(11) NOT NULL,
  `type_media` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_user`,`id_film`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `favoris`
--

INSERT INTO `favoris` (`id_user`, `id_film`, `type_media`) VALUES
(6, 440249, 'movie'),
(1, 299534, 'movie'),
(6, 299534, 'movie'),
(1, 283995, 'movie'),
(1, 1668, 'tv'),
(1, 635302, 'movie'),
(1, 65930, 'tv'),
(1, 2316, 'tv'),
(1, 118340, 'movie');

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_moment` int(11) NOT NULL,
  PRIMARY KEY (`id`,`id_moment`),
  KEY `id_moment` (`id_moment`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `moments`
--

DROP TABLE IF EXISTS `moments`;
CREATE TABLE IF NOT EXISTS `moments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_film` int(11) NOT NULL,
  `offset` int(11) DEFAULT NULL COMMENT 'Timecode en minutes',
  `label` varchar(255) DEFAULT NULL,
  `resume` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`,`id_film`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `id_film` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `note` float DEFAULT NULL,
  `type_media` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_film`,`id_user`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `notes`
--

INSERT INTO `notes` (`id_film`, `id_user`, `note`, `type_media`) VALUES
(2316, 1, 5, 'tv'),
(2316, 4, 2, 'tv'),
(2316, 5, 4, 'tv'),
(299534, 1, 5, 'movie'),
(2004, 1, 4, 'tv'),
(1668, 1, 4, 'tv'),
(632357, 1, 1, 'movie'),
(299534, 6, 5, 'movie');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) DEFAULT NULL,
  `motdepasse` varchar(255) DEFAULT NULL,
  `private` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `motdepasse`, `private`) VALUES
(1, 'Lampe', 'motdepasse', 1),
(3, 'Test', 'motdepasse', 1),
(4, 'QuelquunDautre', 'mdp', 1),
(5, 'Utilisateur', 'mdp', 1),
(6, 'Thomas', 'fdsfsdf', 1);

-- --------------------------------------------------------

--
-- Structure de la table `visionne`
--

DROP TABLE IF EXISTS `visionne`;
CREATE TABLE IF NOT EXISTS `visionne` (
  `id_user` int(11) NOT NULL,
  `id_film` int(11) NOT NULL,
  `type_media` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_user`,`id_film`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `visionne`
--

INSERT INTO `visionne` (`id_user`, `id_film`, `type_media`) VALUES
(1, 2004, 'tv'),
(1, 135397, 'movie'),
(1, 118340, 'movie'),
(1, 1668, 'tv'),
(1, 283995, 'movie'),
(1, 299534, 'movie'),
(1, 85937, 'tv'),
(1, 65930, 'tv'),
(1, 60625, 'tv'),
(1, 2316, 'tv'),
(1, 24428, 'movie'),
(1, 635302, 'movie'),
(1, 632357, 'movie'),
(6, 440249, 'movie'),
(6, 299534, 'movie');

-- --------------------------------------------------------

--
-- Structure de la table `watchlist`
--

DROP TABLE IF EXISTS `watchlist`;
CREATE TABLE IF NOT EXISTS `watchlist` (
  `id_user` int(11) NOT NULL,
  `id_film` int(11) NOT NULL,
  `type_media` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_user`,`id_film`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
