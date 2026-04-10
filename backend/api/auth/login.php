<?php
// API d'authentification - Login

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

require_once __DIR__ . '/../../src/Core/Database.php';
use App\Core\Database;

try {
    $db = Database::getInstance();

    // Récupérer l'utilisateur
    $stmt = $db->prepare("SELECT id, mot_de_passe FROM Utilisateur WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['mot_de_passe'])) {
        http_response_code(401);
        echo json_encode([
            "ok" => false,
            "error" => "Email ou mot de passe incorrect"
        ]);
        exit;
    }

    // Générer un token
    $token = bin2hex(random_bytes(32));

    echo json_encode([
        "ok" => true,
        "message" => "Connexion réussie",
        "userId" => $user['id'],
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
