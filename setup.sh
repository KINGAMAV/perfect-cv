#!/bin/bash
# Script pour importer la base de données Perfect CV (Linux/Mac)

echo "=== Perfect CV - Configuration ==="
echo ""
echo "Assurez-vous que:"
echo "- Votre serveur local est lancé (XAMPP, MAMP, WAMP)"
echo "- MySQL est en cours d'exécution"
echo ""
read -p "Appuyez sur Entrée pour continuer..."

echo ""
echo "Début de l'importation de la base de données..."
echo ""

# Chemin du fichier SQL
SQL_FILE="$(dirname "$0")/database/perfect_cv.sql"

# Vérifier si le fichier existe
if [ ! -f "$SQL_FILE" ]; then
    echo "ERREUR: Fichier SQL non trouvé"
    echo "Cherché à: $SQL_FILE"
    exit 1
fi

# Importer la base de données
mysql -u root -p < "$SQL_FILE"

if [ $? -eq 0 ]; then
    echo ""
    echo "SUCCESS: Base de données importée avec succès!"
    echo ""
    echo "Vous pouvez maintenant accéder à:"
    echo "- http://localhost/perfect-cv/frontend/public/test.php"
    echo "- http://localhost/perfect-cv/frontend/public/index.php"
    echo "- http://localhost/perfect-cv/frontend/public/models.php"
else
    echo ""
    echo "ERREUR: L'importation a échoué"
fi

read -p "Appuyez sur Entrée pour terminer..."
