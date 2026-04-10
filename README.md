# Perfect CV - Générateur de CV 🚀

Perfect CV est une application web permettant de générer des CV professionnels optimisés en fonction du modèle choisi et du domaine visé.

## 🛠️ Stack Technique
- **Backend** : PHP (Architecture MVC personnalisée)
- **Base de données** : MySQL (SQL)
- **Frontend** : HTML5, Tailwind CSS, JavaScript (Vanilla)
- **Déploiement** : Compatible avec tout serveur PHP local (XAMPP, WAMP, MAMP)

## 📁 Structure du Projet
```text
perfect-cv/
├── backend/                # Logique serveur (API, Modèles, Contrôleurs)
│   ├── src/
│   │   ├── Core/           # Connexion BDD (Singleton PDO)
│   │   ├── Models/         # Classes d'accès aux données
│   │   └── Controllers/    # Logique métier
│   ├── api/                # Points d'entrée de l'API
│   └── .env.example        # Configuration de la BDD
├── frontend/               # Interface utilisateur
│   ├── public/             # Fichiers accessibles publiquement
│   └── src/                # Assets (CSS, JS)
├── database/               # Scripts SQL d'initialisation
└── templates/              # Modèles de CV (HTML/Tailwind)
```

## 🚀 Installation Locale (XAMPP / WAMP / MAMP)

### 1. Prérequis
- Avoir un serveur local installé (ex: [XAMPP](https://www.apachefriends.org/fr/index.html)).
- PHP 7.4 ou supérieur.
- MySQL / MariaDB.

### 2. Clonage du projet
Clonez ce repository dans votre dossier `htdocs` (XAMPP) ou `www` (WAMP) :
```bash
git clone https://github.com/KINGAMAV/perfect-cv.git
```

### 3. Configuration de la Base de Données
1. Lancez **Apache** et **MySQL** depuis votre panneau de contrôle (XAMPP/WAMP).
2. Allez sur [phpMyAdmin](http://localhost/phpmyadmin).
3. Créez une nouvelle base de données nommée `perfect_cv`.
4. Cliquez sur l'onglet **Importer** et sélectionnez le fichier `database/perfect_cv.sql` situé dans le projet.
5. Cliquez sur **Exécuter**.

### 4. Configuration du Backend
1. Dans le dossier `backend/`, copiez le fichier `.env.example` et renommez-le en `.env`.
2. Modifiez les informations de connexion si nécessaire (par défaut : `root` sans mot de passe).

### 5. Lancement
Ouvrez votre navigateur et accédez à :
`http://localhost/perfect-cv/frontend/public/index.php`

## 📝 Fonctionnalités
- [x] Architecture MVC propre.
- [x] Design moderne avec Tailwind CSS.
- [x] Génération de CV à partir de templates.
- [x] Base de données relationnelle complète.


