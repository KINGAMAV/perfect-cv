<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfect CV - Générateur de CV</title>
    <!-- Utilisation de Tailwind CSS via CDN pour le prototype -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../src/css/style.css">
</head>
<body class="bg-gray-50 font-sans text-gray-900">

    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center">
                    <span class="text-2xl font-bold text-indigo-600">Perfect CV</span>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="#" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Accueil</a>
                    <a href="models.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Mes Modèles</a>
                </div>
                <div class="flex items-center">
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition duration-150 ease-in-out">Créer un CV</button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center">
            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                <span class="block">Créez votre CV parfait</span>
                <span class="block text-indigo-600">en quelques minutes</span>
            </h1>
            <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                Optimisez votre CV en fonction du modèle choisi et du domaine visé. Plus jamais de remarques sur le design.
            </p>
            <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                <div class="rounded-md shadow">
                    <a href="#form" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                        Commencer maintenant
                    </a>
                </div>
                <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                    <a href="#features" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                        Voir les modèles
                    </a>
                </div>
            </div>
        </div>

        <!-- CV Form Section -->
        <div id="form" class="mt-24 bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Remplissez vos informations</h2>
            <form id="cvForm" class="space-y-6">
                <!-- Section Authentification -->
                <div id="authSection" class="p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                    <p class="text-sm text-blue-900 mb-3">
                        📝 <strong>Connexion requise pour sauvegarder et télécharger votre CV en PDF</strong>
                    </p>
                    <div class="flex gap-2">
                        <button type="button" onclick="showLoginModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">
                            Se connecter
                        </button>
                        <button type="button" onclick="showRegisterModal()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">
                            Créer un compte
                        </button>
                    </div>
                </div>

                <!-- Utilisateur Connecté -->
                <div id="userSection" class="p-4 bg-green-50 border border-green-200 rounded-lg hidden">
                    <p class="text-sm text-green-900">
                        ✓ Connecté en tant que: <strong id="userEmail"></strong>
                    </p>
                    <button type="button" onclick="logout()" class="mt-2 px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm">
                        Se déconnecter
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="fullName" class="block text-sm font-medium text-gray-700">Nom complet</label>
                        <input type="text" id="fullName" name="fullName" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Titre du poste</label>
                        <input type="text" id="title" name="title" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                        <input type="tel" id="phone" name="phone" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <!-- Photo de Profil -->
                <div>
                    <label for="profilePhoto" class="block text-sm font-medium text-gray-700">Photo de profil (JPG, PNG, GIF - max 5MB)</label>
                    <div class="mt-2 flex items-center gap-4">
                        <input type="file" id="profilePhoto" name="profilePhoto" accept="image/*" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <img id="photoPreview" class="hidden w-20 h-20 rounded-full object-cover border-2 border-indigo-600" src="" alt="Aperçu">
                    </div>
                    <p id="photoStatus" class="text-xs text-gray-500 mt-1"></p>
                </div>

                <div>
                    <label for="summary" class="block text-sm font-medium text-gray-700">Résumé personnel</label>
                    <textarea id="summary" name="summary" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                <div>
                    <label for="skills" class="block text-sm font-medium text-gray-700">Compétences (séparées par des virgules)</label>
                    <input type="text" id="skills" name="skills" placeholder="PHP, JavaScript, MySQL..." class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Expériences professionnelles</label>
                    <div id="experienceContainer">
                        <div class="experience-item grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 border border-gray-200 rounded">
                            <input type="text" name="experience[0][role]" placeholder="Poste" class="px-3 py-2 border border-gray-300 rounded-md">
                            <input type="text" name="experience[0][company]" placeholder="Entreprise" class="px-3 py-2 border border-gray-300 rounded-md">
                            <input type="text" name="experience[0][start]" placeholder="Date début (MM/YYYY)" class="px-3 py-2 border border-gray-300 rounded-md">
                            <input type="text" name="experience[0][end]" placeholder="Date fin (MM/YYYY)" class="px-3 py-2 border border-gray-300 rounded-md">
                            <textarea name="experience[0][details]" placeholder="Description" rows="2" class="md:col-span-2 px-3 py-2 border border-gray-300 rounded-md"></textarea>
                        </div>
                    </div>
                    <button type="button" id="addExperience" class="mt-2 px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Ajouter une expérience</button>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Formations</label>
                    <div id="educationContainer">
                        <div class="education-item grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 border border-gray-200 rounded">
                            <input type="text" name="education[0][degree]" placeholder="Diplôme" class="px-3 py-2 border border-gray-300 rounded-md">
                            <input type="text" name="education[0][school]" placeholder="Établissement" class="px-3 py-2 border border-gray-300 rounded-md">
                            <input type="text" name="education[0][start]" placeholder="Date début (MM/YYYY)" class="px-3 py-2 border border-gray-300 rounded-md">
                            <input type="text" name="education[0][end]" placeholder="Date fin (MM/YYYY)" class="px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                    </div>
                    <button type="button" id="addEducation" class="mt-2 px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Ajouter une formation</button>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Créer un aperçu</button>
                    <button type="button" id="saveCvBtn" onclick="saveCv()" class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 hidden">💾 Sauvegarder</button>
                    <button type="button" id="downloadPdfBtn" onclick="downloadPdf()" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 hidden">📥 Télécharger PDF</button>
                </div>
            </form>
<div id="cvResult" class="mt-8"></div>
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                            </div>
                            <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Design Professionnel</h3>
                            <p class="mt-5 text-base text-gray-500">
                                Des modèles de CV conçus par des experts pour attirer l'attention des recruteurs.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="pt-6">
                    <div class="flow-root bg-white rounded-lg px-6 pb-8 shadow-sm border border-gray-100">
                        <div class="-mt-6">
                            <div>
                                <span class="inline-flex items-center justify-center p-3 bg-indigo-500 rounded-md shadow-lg">
                                    <!-- Icon -->
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </span>
                            </div>
                            <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Génération Rapide</h3>
                            <p class="mt-5 text-base text-gray-500">
                                Remplissez vos informations et générez votre CV en un clic au format PDF.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="pt-6">
                    <div class="flow-root bg-white rounded-lg px-6 pb-8 shadow-sm border border-gray-100">
                        <div class="-mt-6">
                            <div>
                                <span class="inline-flex items-center justify-center p-3 bg-indigo-500 rounded-md shadow-lg">
                                    <!-- Icon -->
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                </span>
                            </div>
                            <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Personnalisation Totale</h3>
                            <p class="mt-5 text-base text-gray-500">
                                Adaptez chaque section de votre CV en fonction du poste recherché.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
</div>
    </main>

    <!-- Modal Login -->
    <div id="loginModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <h2 class="text-xl font-bold mb-4">Se connecter</h2>
            <div class="space-y-4">
                <div>
                    <label for="loginEmail" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="loginEmail" placeholder="votre@email.com" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="loginPassword" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <input type="password" id="loginPassword" placeholder="Minimum 6 caractères" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <p id="loginError" class="text-sm text-red-600 hidden"></p>
                <div class="flex gap-3">
                    <button onclick="hideLoginModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-900 rounded-md hover:bg-gray-400">Annuler</button>
                    <button onclick="handleLogin()" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Connexion</button>
                </div>
                <p class="text-center text-sm text-gray-600">Pas encore de compte? <a href="#" onclick="switchToRegister()" class="text-indigo-600 hover:text-indigo-700">S'inscrire</a></p>
            </div>
        </div>
    </div>

    <!-- Modal Register -->
    <div id="registerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <h2 class="text-xl font-bold mb-4">Créer un compte</h2>
            <div class="space-y-4">
                <div>
                    <label for="regEmail" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="regEmail" placeholder="votre@email.com" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="regPassword" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <input type="password" id="regPassword" placeholder="Minimum 6 caractères" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="regPasswordConfirm" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                    <input type="password" id="regPasswordConfirm" placeholder="Répétez le mot de passe" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <p id="registerError" class="text-sm text-red-600 hidden"></p>
                <div class="flex gap-3">
                    <button onclick="hideRegisterModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-900 rounded-md hover:bg-gray-400">Annuler</button>
                    <button onclick="handleRegister()" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">S'inscrire</button>
                </div>
                <p class="text-center text-sm text-gray-600">Déjà inscrit? <a href="#" onclick="switchToLogin()" class="text-indigo-600 hover:text-indigo-700">Se connecter</a></p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-24">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-base text-gray-400">
                &copy; 2026 Perfect CV. Tous droits réservés.
            </p>
        </div>
    </footer>

    <script src="../src/js/app.js"></script>
</body>
</html>
