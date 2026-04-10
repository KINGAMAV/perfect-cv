<?php
// API pour uploader une photo de profil

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Vérifier que un fichier est uploadé
if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "error" => "Aucun fichier uploadé ou erreur lors du téléchargement"
    ]);
    exit;
}

$file = $_FILES['photo'];

// Valider le type de fichier
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$mimeType = mime_content_type($file['tmp_name']);

if (!in_array($mimeType, $allowedTypes)) {
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "error" => "Type de fichier non autorisé. Utilisez JPG, PNG, GIF ou WebP"
    ]);
    exit;
}

// Valider la taille (max 5MB)
$maxSize = 5 * 1024 * 1024; // 5MB
if ($file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "error" => "La photo est trop grande (max 5MB)"
    ]);
    exit;
}

// Créer le dossier de destination s'il n'existe pas
$uploadDir = __DIR__ . '/../../uploads/photos/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Générer un nom de fichier unique
$filename = uniqid('photo_') . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
$filepath = $uploadDir . $filename;

// Déplacer le fichier
if (move_uploaded_file($file['tmp_name'], $filepath)) {
    // Retourner l'URL relative
    $photoUrl = '/perfect-cv/backend/uploads/photos/' . $filename;
    
    echo json_encode([
        "ok" => true,
        "message" => "Photo uploadée avec succès",
        "photoUrl" => $photoUrl,
        "filename" => $filename
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "ok" => false,
        "error" => "Erreur lors du déplacement du fichier"
    ]);
}
