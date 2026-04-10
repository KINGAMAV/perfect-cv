<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Connexion - Perfect CV</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen p-4">
    <div class="max-w-2xl mx-auto py-12">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Diagnostic du Projet</h1>
            <p class="text-gray-600 mb-8">Vérifiez que tout fonctionne correctement</p>

            <!-- Test Connexion BD -->
            <div class="mb-6 p-4 border-l-4 border-blue-500 bg-blue-50">
                <div class="flex items-center mb-2">
                    <span class="text-lg font-semibold text-gray-900">Connexion Base de Données</span>
                    <span id="dbStatus" class="ml-4 inline-block px-3 py-1 rounded-full text-sm font-semibold bg-gray-200 text-gray-700">Test en cours...</span>
                </div>
                <div id="dbResult" class="text-sm text-gray-700 mt-2"></div>
            </div>

            <!-- Test Templates -->
            <div class="mb-6 p-4 border-l-4 border-green-500 bg-green-50">
                <div class="flex items-center mb-2">
                    <span class="text-lg font-semibold text-gray-900">Modèles Disponibles</span>
                    <span id="templatesStatus" class="ml-4 inline-block px-3 py-1 rounded-full text-sm font-semibold bg-gray-200 text-gray-700">Test en cours...</span>
                </div>
                <div id="templatesResult" class="text-sm text-gray-700 mt-2"></div>
            </div>

            <!-- Liens Utiles -->
            <div class="mb-6 p-4 border-l-4 border-purple-500 bg-purple-50">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Liens Utiles</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="http://localhost/phpmyadmin" target="_blank" class="text-blue-600 hover:text-blue-800 underline">🗄️ phpMyAdmin</a></li>
                    <li><a href="index.php" class="text-blue-600 hover:text-blue-800 underline">📝 Créer un CV</a></li>
                    <li><a href="models.php" class="text-blue-600 hover:text-blue-800 underline">🎨 Mes Modèles</a></li>
                </ul>
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
                <button onclick="runTests()" class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold">
                    🔄 Relancer les Tests
                </button>
                <button onclick="location.href='index.php'" class="flex-1 px-6 py-3 bg-gray-300 text-gray-900 rounded-lg hover:bg-gray-400 font-semibold">
                    ➡️ Aller à l'accueil
                </button>
            </div>
        </div>
    </div>

    <script>
        async function testDatabase() {
            try {
                const response = await fetch('../../backend/api/test/db-connection.php');
                const result = await response.json();

                if (result.ok) {
                    document.getElementById('dbStatus').innerHTML = '<span class="bg-green-500 text-white">✓ Connecté</span>';
                    document.getElementById('dbResult').innerHTML = `
                        <p class="font-semibold">✓ Base de données: ${result.database}</p>
                        <p>Serveur: ${result.host}</p>
                        <p class="mt-2 text-green-700">${result.message}</p>
                    `;
                } else {
                    document.getElementById('dbStatus').innerHTML = '<span class="bg-red-500 text-white">✗ Erreur</span>';
                    document.getElementById('dbResult').innerHTML = `
                        <p class="font-semibold text-red-700">✗ Erreur: ${result.error}</p>
                        <p class="text-red-600 text-xs mt-1">${result.details}</p>
                    `;
                }
            } catch (error) {
                document.getElementById('dbStatus').innerHTML = '<span class="bg-red-500 text-white">✗ Erreur Réseau</span>';
                document.getElementById('dbResult').innerHTML = `<p class="text-red-700">Erreur réseau: ${error.message}</p>`;
            }
        }

        async function testTemplates() {
            try {
                const response = await fetch('../../backend/api/templates/list.php');
                const result = await response.json();

                if (result.ok && result.templates.length > 0) {
                    document.getElementById('templatesStatus').innerHTML = `<span class="bg-green-500 text-white">✓ ${result.templates.length} modèle(s)</span>`;
                    const templatesList = result.templates.map(t => `<li>• ${t.label}</li>`).join('');
                    document.getElementById('templatesResult').innerHTML = `
                        <p class="font-semibold mb-2">Modèles trouvés:</p>
                        <ul class="ml-4">${templatesList}</ul>
                    `;
                } else {
                    document.getElementById('templatesStatus').innerHTML = '<span class="bg-orange-500 text-white">⚠ Aucun modèle</span>';
                    document.getElementById('templatesResult').innerHTML = '<p class="text-orange-700">Aucun modèle trouvé</p>';
                }
            } catch (error) {
                document.getElementById('templatesStatus').innerHTML = '<span class="bg-red-500 text-white">✗ Erreur</span>';
                document.getElementById('templatesResult').innerHTML = `<p class="text-red-700">Erreur: ${error.message}</p>`;
            }
        }

        function runTests() {
            testDatabase();
            testTemplates();
        }

        // Lancer les tests au chargement
        document.addEventListener('DOMContentLoaded', runTests);
    </script>
</body>
</html>
