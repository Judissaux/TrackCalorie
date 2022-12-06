-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 28 nov. 2022 à 14:27
-- Version du serveur : 8.0.31-0ubuntu0.22.04.1
-- Version de PHP : 8.1.2-1ubuntu2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tracalorie`
--

-- --------------------------------------------------------

--
-- Structure de la table `suivical`
--

CREATE TABLE `suivical` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `calorie` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `suivical`
--

INSERT INTO `suivical` (`id`, `id_user`, `pseudo`, `date`, `calorie`) VALUES
(57, 1, 'jean', '2022-11-20', 2000),
(58, 1, 'jean', '2022-11-21', 1000),
(59, 1, 'jean', '2022-11-22', 4200),
(61, 1, 'jean', '2022-11-23', 1000),
(62, 1, 'jean', '2022-11-24', 1600),
(64, 1, 'jean', '2022-11-25', 3400),
(65, 1, 'jean', '2022-11-26', 4300),
(66, 1, 'jean', '2022-11-29', 1000),
(67, 1, 'jean', '2022-11-28', 1000),
(68, 1, 'jean', '2022-11-27', 2000);

-- --------------------------------------------------------

--
-- Structure de la table `tcusers`
--

CREATE TABLE `tcusers` (
  `id` int NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `sexe` varchar(50) NOT NULL,
  `taille` int NOT NULL,
  `poids` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `tcusers`
--

INSERT INTO `tcusers` (`id`, `pseudo`, `email`, `password`, `sexe`, `taille`, `poids`) VALUES
(1, 'jean', 'jean@laposte.net', '$argon2id$v=19$m=65536,t=4,p=1$M0pUUDZXN3FIem85aUVjRg$JKlognzVPFU2/bqHZ7Z7Qe8c6EfJr42CrkkNfxoFp98', 'homme', 190, 110),
(2, 'Bernard', 'bernard@laposte.net', '$argon2id$v=19$m=65536,t=4,p=1$SFk4MERLL2sxZUV3OXltVw$t1Bi6Dip2+Tgy+nohFRJwJbujeOx+AIIWpjh0vrV8lI', 'homme', 160, 60),
(3, 'josette', 'josette@laposte.net', '$argon2id$v=19$m=65536,t=4,p=1$REhHUkdwaHE1R0s2R1JnRA$Mh3amGuESz/5f9gc/SCeu5q8QfBwJKkT9PAV+Sg7s1I', 'femme', 160, 70),
(5, 'Justin ', 'justin.dissaux@laposte.net', '$argon2id$v=19$m=65536,t=4,p=1$bXZld1RXSzRCOWguNVZyWg$h8S02+fm1NMQvBQwEKchGyuvoK+dFocRtakNfmD+4Bw', 'homme', 192, 97),
(6, 'hello', 'hello@mail.fr', '$argon2id$v=19$m=65536,t=4,p=1$WDdqUFp0bWFsc2xnbDRsVg$xQcDk3XOSEP5d4ET46Jj9zebZ9KbbazQbqaok6maokY', 'homme', 170, 70),
(7, 'Dolfy', 'lulu1599@hotmail.fr', '$argon2id$v=19$m=65536,t=4,p=1$Vy5vUjk5dDhoeHdhdGp5Sw$UXCJtaIn80+z2BxsqywulhS7LtDw5EGPp8U42dGl4HU', 'femme', 162, 156),
(8, 'Yves', 'yves@yves.com', '$argon2id$v=19$m=65536,t=4,p=1$TW05UDJrT29RcU92Zm1Ydg$jN4CR9I9OLvifDf05a/1gXD0gyNIV7O79Gt0eGfX6nw', 'homme', 85, 65),
(10, 'Michel', 'michel@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$NnNmUHU4dnV5a2ZQMERBbA$gX/E0WDCmjBiYykszW9EJOLypoKXxC3cl3ssCj8Z+sI', 'homme', 198, 123),
(11, 'L.bertaud@hotmail.com', 'L.bertaud@hotmail.com', '$argon2id$v=19$m=65536,t=4,p=1$WmtpNGk1TXhEMmZTNGl4Yw$qS8vB9eO6vjFSHDigx7WlTPa4s2H/aVScmRWlDBbYBo', 'homme', 173, 63),
(12, 'John', 'John@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$ajVLazhvSy8yNUlYSEluNA$tC6mbqZ3za2LZJmdy/jMpRS9eWFsAY9vRiqGbJVs6Eg', 'homme', 180, 80),
(31, 'paul', 'paul@laposte.net', '$argon2id$v=19$m=65536,t=4,p=1$YnpLOEFEQjE3U1lYVU1BVA$jArxfa2VhyKB74nXZXeTIfI/Jb2mpXKkLPPU0yA/yUw', 'homme', 140, 160),
(32, 'Jeannot', 'jeannot@laposte.net', '$argon2id$v=19$m=65536,t=4,p=1$a21vTEE2d3NhcVdMLnp4bA$G70x6aWmqXZq7Bp+jnpGylolEO3ld6PwxFCKzIVlzuo', 'homme', 120, 200);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `suivical`
--
ALTER TABLE `suivical`
  ADD PRIMARY KEY (`id`),
  ADD KEY `suivical_ibfk_1` (`id_user`);

--
-- Index pour la table `tcusers`
--
ALTER TABLE `tcusers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `suivical`
--
ALTER TABLE `suivical`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT pour la table `tcusers`
--
ALTER TABLE `tcusers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
