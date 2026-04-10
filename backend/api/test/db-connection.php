<?php
// API de test pour vérifier la connexion à la base de données

header("Content-Type: application/json; charset=utf-8");

// Vérifier la connexion à la base de données
require_once __DIR__ . '/../../src/Core/Database.php';

use App\Core\Database;

try {
    // Tenter de récupérer une connexion
    $db = Database::getInstance();
    
    // Tester avec une simple requête
    $stmt = $db->prepare("SELECT 1");
    $stmt->execute();
    
    echo json_encode([
        "ok" => true,
        "message" => "Connexion à la base de données réussie",
        "database" => "perfect_cv",
        "host" => "localhost"
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "ok" => false,
        "error" => "Erreur de connexion à la base de données",
        "details" => $e->getMessage()
    ]);
}
