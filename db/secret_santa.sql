-- phpMyAdmin SQL Dump
-- version 4.5.3.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 23 Janvier 2016 à 17:03
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `secret_santa`
--

-- --------------------------------------------------------

--
-- Structure de la table `santa_instance`
--

CREATE TABLE `santa_instance` (
  `instance_id` int(11) NOT NULL,
  `instance_year` year(4) NOT NULL,
  `instance_name` varchar(255) NOT NULL,
  `instance_hash` varchar(255) NOT NULL,
  `instance_author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `santa_participation`
--

CREATE TABLE `santa_participation` (
  `participation_id` int(11) NOT NULL,
  `participation_instance_id` int(11) NOT NULL,
  `participation_user_id` int(11) NOT NULL,
  `participation_result` tinyint(4) NOT NULL COMMENT '1 is participating, 0 is not participating'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `santa_result`
--

CREATE TABLE `santa_result` (
  `result_id` int(11) NOT NULL,
  `result_instance_id` int(11) NOT NULL,
  `result_sender_user_id` int(11) NOT NULL,
  `result_recipient_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `santa_user`
--

CREATE TABLE `santa_user` (
  `user_id` int(11) NOT NULL,
  `user_login` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_firstname` varchar(255) NOT NULL,
  `user_lastname` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_access` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour la table `santa_instance`
--
ALTER TABLE `santa_instance`
  ADD PRIMARY KEY (`instance_id`);

--
-- Index pour la table `santa_participation`
--
ALTER TABLE `santa_participation`
  ADD PRIMARY KEY (`participation_id`);

--
-- Index pour la table `santa_result`
--
ALTER TABLE `santa_result`
  ADD PRIMARY KEY (`result_id`);

--
-- Index pour la table `santa_user`
--
ALTER TABLE `santa_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `santa_instance`
--
ALTER TABLE `santa_instance`
  MODIFY `instance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT pour la table `santa_participation`
--
ALTER TABLE `santa_participation`
  MODIFY `participation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT pour la table `santa_result`
--
ALTER TABLE `santa_result`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT pour la table `santa_user`
--
ALTER TABLE `santa_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
