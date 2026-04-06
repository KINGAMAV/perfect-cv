/**
 * Logique frontend pour Perfect CV.
 * Gère les interactions utilisateur, les formulaires et les appels API.
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('Perfect CV - Frontend chargé');

    // Exemple de gestionnaire d'événement pour le bouton "Créer un CV"
    const createBtn = document.querySelector('button');
    if (createBtn) {
        createBtn.addEventListener('click', () => {
            alert('Fonctionnalité de création de CV en cours de développement !');
        });
    }
});

/**
 * Fonction pour envoyer les données du CV au backend pour génération.
 * @param {Object} cvData Les données du CV.
 */
async function generateCv(cvData) {
    try {
        const response = await fetch('../../backend/api/cv/generate.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(cvData)
        });

        const result = await response.json();
        if (result.ok) {
            // Afficher le CV généré ou proposer le téléchargement
            console.log('CV généré avec succès');
            // On pourrait ouvrir une nouvelle fenêtre avec le HTML généré
            const win = window.open("", "CV Généré", "width=800,height=600");
            win.document.write(result.html);
        } else {
            console.error('Erreur lors de la génération du CV:', result.error);
        }
    } catch (error) {
        console.error('Erreur réseau:', error);
    }
}
