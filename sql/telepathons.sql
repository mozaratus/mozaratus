-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 16 août 2022 à 06:19
-- Version du serveur :  8.0.21
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `telepathons`
--

-- --------------------------------------------------------

--
-- Structure de la table `lesip`
--

DROP TABLE IF EXISTS `lesip`;
CREATE TABLE IF NOT EXISTS `lesip` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip` varchar(100) NOT NULL,
  `idUSer` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `resultat`
--

DROP TABLE IF EXISTS `resultat`;
CREATE TABLE IF NOT EXISTS `resultat` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `userId` int NOT NULL,
  `couleur` varchar(50) NOT NULL,
  `rcouleur` tinyint DEFAULT NULL,
  `forme` varchar(50) DEFAULT NULL,
  `rforme` tinyint DEFAULT NULL,
  `nombre` int DEFAULT NULL,
  `rnombre` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `resultat`
--

INSERT INTO `resultat` (`id`, `date`, `userId`, `couleur`, `rcouleur`, `forme`, `rforme`, `nombre`, `rnombre`) VALUES
(19, '2022-08-15', 17, 'ù', 1, '*ù*ùm', 1, 3, 1),
(20, '2022-08-15', 29, '0', NULL, NULL, NULL, NULL, NULL),
(21, '2022-08-15', 30, 'Bleu', 1, 'Carré', 0, 12, 1),
(22, '2022-08-15', 31, 'Bleu', NULL, 'Carré', NULL, NULL, NULL),
(23, '2022-08-15', 32, 'sdfsdf', NULL, NULL, NULL, NULL, NULL),
(24, '2022-08-15', 33, 'ccc', NULL, 'carré', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `email` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pw` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tentatives` int NOT NULL,
  `niveau` int NOT NULL,
  `experience` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom`, `email`, `pw`, `tentatives`, `niveau`, `experience`, `date`) VALUES
(17, 'Boby', 'toto@toto.fr', '202cb962ac59075b964b07152d234b70', 0, 0, '', '2022-08-14 17:14:04'),
(28, 'Boby', 'tuytuyuytoto@toto.fr', '202cb962ac59075b964b07152d234b70', 0, 0, '', '2022-08-14 21:00:52'),
(29, 'Dédé', 'totodd@toto.fr', '202cb962ac59075b964b07152d234b70', 0, 0, '', '2022-08-15 21:18:28'),
(30, 'BobyToto', 'toto258@toto.fr', '202cb962ac59075b964b07152d234b70', 0, 0, '', '2022-08-15 21:21:43'),
(31, 'Boby25', 'toto87@toto.fr', '202cb962ac59075b964b07152d234b70', 0, 0, '', '2022-08-15 21:55:50'),
(32, 'Boby666', 'tot666o@toto.fr', '202cb962ac59075b964b07152d234b70', 0, 0, '', '2022-08-15 21:57:02'),
(33, 'B777oby', '77toto@toto.fr', '202cb962ac59075b964b07152d234b70', 0, 0, '', '2022-08-15 21:58:07'),
(34, '', '', 'd41d8cd98f00b204e9800998ecf8427e', 0, 0, '', '2022-08-16 00:01:28'),
(35, '', '**', 'd41d8cd98f00b204e9800998ecf8427e', 0, 0, '', '2022-08-16 00:01:32');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
