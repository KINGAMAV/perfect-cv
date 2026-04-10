<?php
// API d'authentification - Register

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$rawInput = file_get_contents("php://input");
$data = json_decode($rawInput, true);

if (!$data || !isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "error" => "Email et mot de passe requis"
    ]);
    exit;
}

$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$password = $data['password'];

// Validation email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "error" => "Email invalide"
    ]);
    exit;
}

// Validation mot de passe (min 6 caractères)
if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "error" => "Le mot de passe doit contenir au moins 6 caractères"
    ]);
    exit;
}

require_once __DIR__ . '/../../src/Core/Database.php';
use App\Core\Database;

try {
    $db = Database::getInstance();

    // Vérifier si l'email existe déjà
    $stmt = $db->prepare("SELECT id FROM Utilisateur WHERE email = :email");
    $stmt->execute(['email' => $email]);
    
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode([
            "ok" => false,
            "error" => "Cet email est déjà utilisé"
        ]);
        exit;
    }

    // Créer l'utilisateur
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $db->prepare("INSERT INTO Utilisateur (email, mot_de_passe) VALUES (:email, :password)");
    $stmt->execute([
        'email' => $email,
        'password' => $hashedPassword
    ]);

    $userId = $db->lastInsertId();

    // Générer un token simple (vous pouvez utiliser JWT en production)
    $token = bin2hex(random_bytes(32));

    // Stocker le token (ou vous pouvez le générer à la volée avec JWT)
    echo json_encode([
        "ok" => true,
        "message" => "Compte créé avec succès",
        "userId" => $userId,
        "token" => $token,
        "email" => $email
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "ok" => false,
        "error" => "Erreur serveur: " . $e->getMessage()
    ]);
}
