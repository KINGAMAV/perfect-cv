<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Modèles - Perfect CV</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <a href="index.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Accueil</a>
                    <a href="models.php" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Mes Modèles</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Mes Modèles de CV</h1>
            <p class="mt-2 text-lg text-gray-600">Choisissez un modèle et commencez à créer votre CV</p>
        </div>

        <!-- Templates Grid -->
        <div id="templatesContainer" class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Les templates seront chargés ici par JavaScript -->
            <div class="text-center py-12">
                <p class="text-gray-500">Chargement des modèles...</p>
            </div>
        </div>

        <!-- Modal pour créer un CV -->
        <div id="createCVModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-8 max-w-md w-full">
                <h2 class="text-xl font-bold mb-4">Créer un nouveau CV</h2>
                <div class="mb-4">
                    <label for="cvName" class="block text-sm font-medium text-gray-700 mb-2">Nom du CV</label>
                    <input type="text" id="cvName" placeholder="Mon CV" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div id="selectedTemplateInfo" class="mb-4 p-3 bg-gray-100 rounded-md">
                    <!-- Affichera le modèle sélectionné -->
                </div>
                <div class="flex gap-3">
                    <button id="cancelBtn" class="flex-1 px-4 py-2 bg-gray-300 text-gray-900 rounded-md hover:bg-gray-400">Annuler</button>
                    <button id="createBtn" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Créer</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        let selectedTemplate = null;
        let templates = [];

        // Charger les modèles disponibles
        async function loadTemplates() {
            try {
                const response = await fetch('../../backend/api/templates/list.php');
                const result = await response.json();

                if (result.ok) {
                    templates = result.templates;
                    displayTemplates();
                } else {
                    console.error('Erreur:', result.error);
                }
            } catch (error) {
                console.error('Erreur réseau:', error);
            }
        }

        // Afficher les modèles dans le grid
        function displayTemplates() {
            const container = document.getElementById('templatesContainer');
            container.innerHTML = '';

            templates.forEach(template => {
                const card = document.createElement('div');
                card.className = 'bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer p-6';
                card.innerHTML = `
                    <div class="h-40 bg-gray-200 rounded-md mb-4 flex items-center justify-center">
                        <span class="text-gray-500 text-sm">Aperçu</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">${template.label}</h3>
                    <p class="text-gray-600 text-sm mb-4">${template.description}</p>
                    <button onclick="selectTemplate('${template.name}', '${template.label}')" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Choisir ce modèle
                    </button>
                `;
                container.appendChild(card);
            });
        }

        // Sélectionner un template
        function selectTemplate(templateName, label) {
            selectedTemplate = templateName;
            document.getElementById('selectedTemplateInfo').innerHTML = `<strong>Modèle sélectionné:</strong> ${label}`;
            document.getElementById('createCVModal').classList.remove('hidden');
            document.getElementById('cvName').focus();
        }

        // Créer un CV
        async function createCV() {
            const cvName = document.getElementById('cvName').value.trim() || 'Mon CV';

            if (!selectedTemplate) {
                alert('Veuillez sélectionner un modèle');
                return;
            }

            try {
                const response = await fetch('../../backend/api/cv/create.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        cvName: cvName,
                        template: selectedTemplate
                    })
                });

                const result = await response.json();
                if (result.ok) {
                    // Rediriger vers la page d'édition
                    localStorage.setItem('selectedTemplate', selectedTemplate);
                    localStorage.setItem('cvName', cvName);
                    window.location.href = 'index.php?template=' + selectedTemplate;
                } else {
                    alert('Erreur: ' + result.error);
                }
            } catch (error) {
                console.error('Erreur réseau:', error);
                alert('Erreur de connexion au serveur');
            }
        }

        // Événements
        document.getElementById('cancelBtn').addEventListener('click', () => {
            document.getElementById('createCVModal').classList.add('hidden');
            document.getElementById('cvName').value = '';
            selectedTemplate = null;
        });

        document.getElementById('createBtn').addEventListener('click', createCV);

        document.getElementById('cvName').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') createCV();
        });

        // Charger les modèles au chargement
        document.addEventListener('DOMContentLoaded', loadTemplates);
    </script>
</body>
</html>
