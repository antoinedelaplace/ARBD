-- phpMyAdmin SQL Dump
-- version 4.7.1
-- https://www.phpmyadmin.net/
--
-- Hôte : HAPROXY
-- Généré le :  mar. 31 oct. 2017 à 17:59
-- Version du serveur :  5.7.18-log
-- Version de PHP :  7.0.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `speedbouffe`
--

use speedbouffe;

-- --------------------------------------------------------

--
-- Structure de la table `Client`
--

CREATE TABLE `Client` (
  `id` int(7) NOT NULL,
  `civilite` enum('Monsieur','Madame','Mademoiselle') NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `age` int(3) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Commande`
--

CREATE TABLE `Commande` (
  `id` int(7) NOT NULL,
  `date_creation` date DEFAULT NULL,
  `date_livraison` date NOT NULL,
  `horaire_livraison` time NOT NULL,
  `acheteur_id` int(11) NOT NULL,
  `type_paiement` enum('Espece','Carte','Ticket restaurant') DEFAULT NULL,
  `etat` enum('En attente','En cours','Livraison','Recu') DEFAULT 'En attente',
  `paiement` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Commande_Client`
--

CREATE TABLE `Commande_Client` (
  `id` int(7) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `repas_id` int(11) NOT NULL,
  `tarif` int(7) NOT NULL,
  `entree_id` int(7) NOT NULL,
  `dessert_id` int(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Dessert`
--

CREATE TABLE `Dessert` (
  `id` int(7) NOT NULL,
  `titre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Entree`
--

CREATE TABLE `Entree` (
  `id` int(7) NOT NULL,
  `titre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Repas`
--

CREATE TABLE `Repas` (
  `id` int(7) NOT NULL,
  `titre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Tarif`
--

CREATE TABLE `Tarif` (
  `id` int(7) NOT NULL,
  `titre` varchar(20) NOT NULL,
  `prix` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Tarif`
--

INSERT INTO `Tarif` (`id`, `titre`, `prix`) VALUES
(1, 'Tarif etudiant', 9),
(2, 'Senior', 10),
(3, 'Plein tarif', 13),
(4, 'Tarif ami', 11);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Client`
--
ALTER TABLE `Client`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Commande`
--
ALTER TABLE `Commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Commande_fk1` (`acheteur_id`);

--
-- Index pour la table `Commande_Client`
--
ALTER TABLE `Commande_Client`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Commande_Client_fk0` (`commande_id`),
  ADD KEY `Commande_Client_fk1` (`client_id`),
  ADD KEY `Commande_Client_fk2` (`repas_id`),
  ADD KEY `Commande_Client_fk3` (`tarif`),
  ADD KEY `Commande_client_fk4` (`entree_id`) USING BTREE,
  ADD KEY `Commande_client_fk5` (`dessert_id`) USING BTREE;

--
-- Index pour la table `Dessert`
--
ALTER TABLE `Dessert`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Entree`
--
ALTER TABLE `Entree`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Repas`
--
ALTER TABLE `Repas`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Tarif`
--
ALTER TABLE `Tarif`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Client`
--
ALTER TABLE `Client`
  MODIFY `id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `Commande`
--
ALTER TABLE `Commande`
  MODIFY `id` int(7) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `Commande_Client`
--
ALTER TABLE `Commande_Client`
  MODIFY `id` int(7) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `Dessert`
--
ALTER TABLE `Dessert`
  MODIFY `id` int(7) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `Entree`
--
ALTER TABLE `Entree`
  MODIFY `id` int(7) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `Repas`
--
ALTER TABLE `Repas`
  MODIFY `id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `Tarif`
--
ALTER TABLE `Tarif`
  MODIFY `id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Commande`
--
ALTER TABLE `Commande`
  ADD CONSTRAINT `Commande_fk1` FOREIGN KEY (`acheteur_id`) REFERENCES `Client` (`id`);

--
-- Contraintes pour la table `Commande_Client`
--
ALTER TABLE `Commande_Client`
  ADD CONSTRAINT `Commande_Client_fk0` FOREIGN KEY (`commande_id`) REFERENCES `Commande` (`id`),
  ADD CONSTRAINT `Commande_Client_fk1` FOREIGN KEY (`client_id`) REFERENCES `Client` (`id`),
  ADD CONSTRAINT `Commande_Client_fk2` FOREIGN KEY (`repas_id`) REFERENCES `Repas` (`id`),
  ADD CONSTRAINT `Commande_Client_fk3` FOREIGN KEY (`tarif`) REFERENCES `Tarif` (`id`),
  ADD CONSTRAINT `Repas_fk0` FOREIGN KEY (`entree_id`) REFERENCES `Entree` (`id`),
  ADD CONSTRAINT `Repas_fk1` FOREIGN KEY (`dessert_id`) REFERENCES `Dessert` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
