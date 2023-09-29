-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 29 sep. 2023 à 19:38
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog`
--
CREATE DATABASE IF NOT EXISTS `blog` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `blog`;

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `contenu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dateCreation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateModification` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `idUser` int NOT NULL,
  `idPost` int NOT NULL,
  `statut` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `foreignKeyPost` (`idPost`),
  KEY `foreignKeyUserComments` (`idUser`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `contenu`, `dateCreation`, `dateModification`, `idUser`, `idPost`, `statut`) VALUES
(1, 'Faire du sport 3 à 4 fois par jour ce serait pas mal. Moi qui pratique la musculation je pense que c\'est un bon sport. Le football, la course. Il y a plein de choses à faire mais c\'est important de se sentir à l\'aise dans le sport qu\'on pratique', '2023-09-29 20:41:40', '2023-09-29 20:42:19', 1, 3, 1),
(2, 'Jardin du Luxembourg, la Tour Eiffel, Les Champs Elysées', '2023-09-29 20:42:02', '2023-09-29 20:42:18', 1, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `chapo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contenu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dateCreation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateModification` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `idUser` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `foreignKeyUser` (`idUser`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `titre`, `chapo`, `contenu`, `dateCreation`, `dateModification`, `idUser`) VALUES
(2, 'Les plus beaux endroits à visiter à Paris', 'Tourisme', 'Vous pouvez poster ici les plus beaux endroits à visiter sur Paris.', '2023-09-29 20:32:14', '2023-09-29 20:32:14', 2),
(3, 'L\'importance du sport', 'Sport et Santé', 'Donnez-nous vos meilleurs conseils, quel que soit le sport, pour que nos utilisateurs bénéficient des conseils les plus pertinents.', '2023-09-29 20:33:57', '2023-09-29 20:34:09', 2);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lastname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pwd` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'user',
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `expireAt` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `pwd`, `createdAt`, `updatedAt`, `role`, `token`, `expireAt`) VALUES
(1, 'Axel', 'Chasseloup', 'axel@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$Qzdvd0o3bkxYcnRZbllRSw$oaIT6Z1SiT8FjhZObODbNMHLkfHAWSwoBUmsp9nftwM', '2023-09-29 20:24:44', '2023-09-29 18:40:17', 'user', NULL, '0000-00-00 00:00:00'),
(2, 'Admin', 'Blog', 'admin@blog.com', '$argon2id$v=19$m=65536,t=4,p=1$d3pjb1o3eXcwMU10T0J1eg$7d7y4VDugRan1DfTOtSnIOFrnhu/j85nkpx2MXq4WM4', '2023-09-29 20:26:30', '2023-09-29 18:42:12', 'admin', NULL, '2023-09-29 18:26:49');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `foreignKeyPost` FOREIGN KEY (`idPost`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `foreignKeyUserComments` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `foreignKeyUser` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
