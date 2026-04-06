# Conception de l'architecture BDD et de la structure du projet

## 1. Modélisation de la Base de Données (BDD)

Pour un générateur de CV intelligent, nous aurons besoin de plusieurs tables pour stocker les informations relatives aux utilisateurs et à leurs CV. Voici une proposition de schéma de base de données :

### Entités et Relations

*   **Utilisateur** : Gère les informations de connexion et d'identification.
*   **CV** : Représente un CV spécifique créé par un utilisateur. Un utilisateur peut avoir plusieurs CV.
*   **InformationsPersonnelles** : Détails personnels de l'utilisateur pour un CV donné. Un CV a une seule entrée d'informations personnelles.
*   **ExpérienceProfessionnelle** : Détails des expériences de travail. Un CV peut avoir plusieurs expériences.
*   **Formation** : Détails des parcours académiques. Un CV peut avoir plusieurs formations.
*   **Compétence** : Liste des compétences. Un CV peut avoir plusieurs compétences.

### Schéma Détaillé des Tables

```sql
-- Table Utilisateur
CREATE TABLE Utilisateur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table CV
CREATE TABLE CV (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT NOT NULL,
    nom_cv VARCHAR(255) NOT NULL,
    modele_choisi VARCHAR(100),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_mise_a_jour DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES Utilisateur(id)
);

-- Table InformationsPersonnelles
CREATE TABLE InformationsPersonnelles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cv_id INT UNIQUE NOT NULL,
    nom_complet VARCHAR(255),
    titre_poste VARCHAR(255),
    email VARCHAR(255),
    telephone VARCHAR(50),
    adresse VARCHAR(255),
    lien_linkedin VARCHAR(255),
    lien_github VARCHAR(255),
    resume_personnel TEXT,
    FOREIGN KEY (cv_id) REFERENCES CV(id)
);

-- Table ExperienceProfessionnelle
CREATE TABLE ExperienceProfessionnelle (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cv_id INT NOT NULL,
    poste VARCHAR(255) NOT NULL,
    entreprise VARCHAR(255) NOT NULL,
    ville VARCHAR(255),
    date_debut DATE,
    date_fin DATE,
    description TEXT,
    FOREIGN KEY (cv_id) REFERENCES CV(id)
);

-- Table Formation
CREATE TABLE Formation (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cv_id INT NOT NULL,
    diplome VARCHAR(255) NOT NULL,
    etablissement VARCHAR(255) NOT NULL,
    ville VARCHAR(255),
    date_debut DATE,
    date_fin DATE,
    description TEXT,
    FOREIGN KEY (cv_id) REFERENCES CV(id)
);

-- Table Compétence
CREATE TABLE Competence (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cv_id INT NOT NULL,
    nom_competence VARCHAR(255) NOT NULL,
    niveau VARCHAR(50), -- Ex: Débutant, Intermédiaire, Avancé, Expert
    FOREIGN KEY (cv_id) REFERENCES CV(id)
);
```

## 2. Structure du Projet

Pour une application PHP plus robuste et maintenable, nous allons adopter une structure de projet inspirée du modèle MVC (Modèle-Vue-Contrôleur), même si nous n'utiliserons pas un framework PHP complet pour le moment afin de rester léger et de se concentrer sur les bases.

```
perfect-cv/
├── public/                 # Point d'entrée de l'application (frontend)
│   ├── index.php           # Fichier principal pour router les requêtes
│   ├── css/                # Fichiers CSS (Tailwind CSS)
│   │   └── style.css
│   └── js/                 # Fichiers JavaScript
│       └── app.js
├── app/                    # Logique métier de l'application
│   ├── Controllers/        # Gère la logique des requêtes HTTP
│   │   ├── CvController.php
│   │   └── AuthController.php
│   ├── Models/             # Interagit avec la base de données
│   │   ├── User.php
│   │   ├── Cv.php
│   │   ├── PersonalInfo.php
│   │   ├── Experience.php
│   │   ├── Education.php
│   │   └── Skill.php
│   ├── Views/              # Contient les templates HTML (pour le rendu côté serveur si nécessaire)
│   │   ├── cv_template_1.php
│   │   └── auth/
│   │       └── login.php
│   └── Core/               # Classes utilitaires et de base
│       ├── Database.php    # Connexion à la BDD
│       └── Router.php      # Gestion des routes
├── backend/                # API PHP (pour la génération de CV et autres services)
│   ├── api/
│   │   └── cv/
│   │       └── generate.php # Script existant, à refactoriser ou intégrer
│   └── config/
│       └── db.php          # Configuration de la base de données
├── templates/              # Dossier pour les différents modèles de CV (HTML/CSS)
│   ├── modern/
│   │   ├── index.html
│   │   └── style.css
│   └── classic/
│       ├── index.html
│       └── style.css
├── .env                    # Variables d'environnement (ex: BDD credentials)
├── composer.json           # Pour la gestion des dépendances PHP (si on utilise Composer)
├── README.md               # Description du projet
└── BDD_Project_Structure.md # Ce document
```

## 3. Intégration de Tailwind CSS

Tailwind CSS sera utilisé pour le stylisme du frontend. Nous allons configurer un processus de build pour générer le fichier `style.css` minifié à partir des classes Tailwind utilisées dans les vues HTML.

## 4. Gestion des Dépendances PHP

Nous utiliserons Composer pour gérer les dépendances PHP, notamment pour la connexion à la base de données et potentiellement pour un gestionnaire de templates si nous décidons d'en utiliser un.

## 5. Refactorisation de `generate.php`

Le script `backend/api/cv/generate.php` existant sera refactorisé pour s'intégrer dans cette nouvelle structure. Il deviendra probablement une méthode au sein du `CvController` ou un service dédié à la génération de CV, utilisant les données récupérées via les modèles.

Cette structure permettra une meilleure organisation du code, une maintenance facilitée et une évolutivité accrue pour les futures fonctionnalités.
