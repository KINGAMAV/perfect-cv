<?php
// API pour sauvegarder le CV dans la BD

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
        "error" => "Données invalides"
    ]);
    exit;
}

// Vérifier qu'on a userId et token
if (!isset($data['userId']) || !isset($data['token'])) {
    http_response_code(401);
    echo json_encode([
        "ok" => false,
        "error" => "Authentification requise"
    ]);
    exit;
}

require_once __DIR__ . '/../../src/Core/Database.php';
use App\Core\Database;

try {
    $db = Database::getInstance();
    $userId = intval($data['userId']);
    $cvName = $data['cvName'] ?? 'Mon CV';
    $template = $data['template'] ?? 'modern';

    // Récupérer les données du CV
    $fullName = $data['fullName'] ?? '';
    $title = $data['title'] ?? '';
    $email = $data['email'] ?? '';
    $phone = $data['phone'] ?? '';
    $summary = $data['summary'] ?? '';
    $photoUrl = $data['photoUrl'] ?? null;
    $experience = $data['experience'] ?? [];
    $education = $data['education'] ?? [];
    $skills = $data['skills'] ?? [];

    // Créer le CV
    $stmt = $db->prepare("INSERT INTO CV (utilisateur_id, nom_cv, modele_choisi) VALUES (:userId, :cvName, :template)");
    $stmt->execute([
        'userId' => $userId,
        'cvName' => $cvName,
        'template' => $template
    ]);
    $cvId = $db->lastInsertId();

    // Sauvegarder les informations personnelles
    $stmt = $db->prepare("INSERT INTO InformationsPersonnelles (cv_id, nom_complet, titre_poste, email, telephone, photo_url, resume_personnel) VALUES (:cvId, :fullName, :title, :email, :phone, :photoUrl, :summary)");
    $stmt->execute([
        'cvId' => $cvId,
        'fullName' => $fullName,
        'title' => $title,
        'email' => $email,
        'phone' => $phone,
        'photoUrl' => $photoUrl,
        'summary' => $summary
    ]);

    // Sauvegarder les expériences
    foreach ($experience as $exp) {
        $stmt = $db->prepare("INSERT INTO ExperienceProfessionnelle (cv_id, poste, entreprise, date_debut, date_fin, description) VALUES (:cvId, :role, :company, :start, :end, :details)");
        $stmt->execute([
            'cvId' => $cvId,
            'role' => $exp['role'] ?? '',
            'company' => $exp['company'] ?? '',
            'start' => $exp['start'] ?? null,
            'end' => $exp['end'] ?? null,
            'details' => $exp['details'] ?? ''
        ]);
    }

    // Sauvegarder les formations
    foreach ($education as $edu) {
        $stmt = $db->prepare("INSERT INTO Formation (cv_id, diplome, etablissement, date_debut, date_fin, description) VALUES (:cvId, :degree, :school, :start, :end, :description)");
        $stmt->execute([
            'cvId' => $cvId,
            'degree' => $edu['degree'] ?? '',
            'school' => $edu['school'] ?? '',
            'start' => $edu['start'] ?? null,
            'end' => $edu['end'] ?? null,
            'description' => ''
        ]);
    }

    // Sauvegarder les compétences
    foreach ($skills as $skill) {
        $stmt = $db->prepare("INSERT INTO Competence (cv_id, nom_competence) VALUES (:cvId, :skill)");
        $stmt->execute([
            'cvId' => $cvId,
            'skill' => $skill
        ]);
    }

    echo json_encode([
        "ok" => true,
        "message" => "CV sauvegardé avec succès",
        "cvId" => $cvId
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "ok" => false,
        "error" => "Erreur serveur: " . $e->getMessage()
    ]);
}
