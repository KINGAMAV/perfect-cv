<?php
// API pour créer un CV avec un modèle sélectionné

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

if (!$data) {
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "error" => "JSON invalide"
    ]);
    exit;
}

$cvName = $data["cvName"] ?? "Mon CV";
$template = $data["template"] ?? "modern";

// Valider le template
$allowedTemplates = ['modern', 'minimal', 'creative'];
if (!in_array($template, $allowedTemplates)) {
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "error" => "Modèle invalide"
    ]);
    exit;
}

// Données à stocker en session ou local storage
// Pour l'instant, on retourne un succès
echo json_encode([
    "ok" => true,
    "message" => "CV créé avec le modèle: " . $template,
    "cvName" => $cvName,
    "template" => $template
]);
