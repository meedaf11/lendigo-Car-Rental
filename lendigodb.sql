-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 23 mai 2025 à 14:03
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `lendigodb`
--

-- --------------------------------------------------------

--
-- Structure de la table `agency`
--

CREATE TABLE `agency` (
  `agency_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `contact_email` varchar(150) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `agency_city` varchar(150) NOT NULL,
  `location` varchar(255) NOT NULL,
  `rating` decimal(3,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `agency_review`
--

CREATE TABLE `agency_review` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `rating` decimal(3,2) NOT NULL CHECK (`rating` >= 0 and `rating` <= 5),
  `review_text` text NOT NULL,
  `created_at` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `status` varchar(15) DEFAULT NULL CHECK (`status` in ('waiting','reserved','canceled','completed')),
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `car`
--

CREATE TABLE `car` (
  `car_id` int(11) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `model` varchar(100) NOT NULL,
  `places` int(11) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `car_type` varchar(150) NOT NULL,
  `availability_status` enum('available','booked') DEFAULT 'available',
  `image_url` varchar(255) NOT NULL,
  `car_fuel` enum('diesel','gasoline') NOT NULL,
  `kilometers` int(11) NOT NULL,
  `isAutomatic` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `car_review`
--

CREATE TABLE `car_review` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `rating` decimal(3,2) NOT NULL CHECK (`rating` >= 0 and `rating` <= 5),
  `review_text` text NOT NULL,
  `created_at` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `registration_date` date DEFAULT curdate(),
  `user_type` enum('customer','admin') NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agency`
--
ALTER TABLE `agency`
  ADD PRIMARY KEY (`agency_id`);

--
-- Index pour la table `agency_review`
--
ALTER TABLE `agency_review`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `fk_agencyreview_user` (`user_id`),
  ADD KEY `fk_agencyreview_agency` (`agency_id`);

--
-- Index pour la table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Index pour la table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`car_id`),
  ADD KEY `agency_id` (`agency_id`);

--
-- Index pour la table `car_review`
--
ALTER TABLE `car_review`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `fk_carreview_user` (`user_id`),
  ADD KEY `fk_carreview_car` (`car_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `agency`
--
ALTER TABLE `agency`
  MODIFY `agency_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `agency_review`
--
ALTER TABLE `agency_review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `car`
--
ALTER TABLE `car`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `car_review`
--
ALTER TABLE `car_review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `agency_review`
--
ALTER TABLE `agency_review`
  ADD CONSTRAINT `fk_agencyreview_agency` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`agency_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_agencyreview_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `car_id` FOREIGN KEY (`car_id`) REFERENCES `car` (`car_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `car`
--
ALTER TABLE `car`
  ADD CONSTRAINT `agency_id` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`agency_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `car_review`
--
ALTER TABLE `car_review`
  ADD CONSTRAINT `fk_carreview_car` FOREIGN KEY (`car_id`) REFERENCES `car` (`car_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_carreview_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
