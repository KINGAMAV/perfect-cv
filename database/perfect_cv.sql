-- Script d'initialisation de la base de données Perfect CV
-- À importer dans phpMyAdmin (http://localhost/phpmyadmin)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Création de la base de données si elle n'existe pas
CREATE DATABASE IF NOT EXISTS `perfect_cv` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `perfect_cv`;

-- --------------------------------------------------------

-- Table Utilisateur
CREATE TABLE IF NOT EXISTS `Utilisateur` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `mot_de_passe` VARCHAR(255) NOT NULL,
    `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table CV
CREATE TABLE IF NOT EXISTS `CV` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `utilisateur_id` INT NOT NULL,
    `nom_cv` VARCHAR(255) NOT NULL,
    `modele_choisi` VARCHAR(100) DEFAULT 'modern',
    `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `date_mise_a_jour` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`utilisateur_id`) REFERENCES `Utilisateur`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table InformationsPersonnelles
CREATE TABLE IF NOT EXISTS `InformationsPersonnelles` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `cv_id` INT UNIQUE NOT NULL,
    `nom_complet` VARCHAR(255),
    `titre_poste` VARCHAR(255),
    `email` VARCHAR(255),
    `telephone` VARCHAR(50),
    `adresse` VARCHAR(255),
    `lien_linkedin` VARCHAR(255),
    `lien_github` VARCHAR(255),
    `photo_url` VARCHAR(500),
    `resume_personnel` TEXT,
    FOREIGN KEY (`cv_id`) REFERENCES `CV`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table ExperienceProfessionnelle
CREATE TABLE IF NOT EXISTS `ExperienceProfessionnelle` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `cv_id` INT NOT NULL,
    `poste` VARCHAR(255) NOT NULL,
    `entreprise` VARCHAR(255) NOT NULL,
    `ville` VARCHAR(255),
    `date_debut` DATE,
    `date_fin` DATE,
    `description` TEXT,
    FOREIGN KEY (`cv_id`) REFERENCES `CV`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table Formation
CREATE TABLE IF NOT EXISTS `Formation` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `cv_id` INT NOT NULL,
    `diplome` VARCHAR(255) NOT NULL,
    `etablissement` VARCHAR(255) NOT NULL,
    `ville` VARCHAR(255),
    `date_debut` DATE,
    `date_fin` DATE,
    `description` TEXT,
    FOREIGN KEY (`cv_id`) REFERENCES `CV`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table Competence
CREATE TABLE IF NOT EXISTS `Competence` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `cv_id` INT NOT NULL,
    `nom_competence` VARCHAR(255) NOT NULL,
    `niveau` VARCHAR(50),
    FOREIGN KEY (`cv_id`) REFERENCES `CV`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
