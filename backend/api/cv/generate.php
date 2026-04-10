<?php
// CORS - pour autoriser les requêtes depuis le frontend
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Si la requête est OPTIONS (préflight), on répond direct
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

header("Content-Type: application/json; charset=utf-8");

// Récupérer le JSON envoyé par React
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

// Template sélectionné
$templateName = $data["template"] ?? "modern";
$allowedTemplates = ['modern', 'minimal', 'creative'];

// Valider le template
if (!in_array($templateName, $allowedTemplates)) {
  http_response_code(400);
  echo json_encode([
    "ok" => false,
    "error" => "Template non valide"
  ]);
  exit;
}

// Données CV (avec valeurs par défaut)
$fullName = $data["fullName"] ?? "Votre Nom";
$title    = $data["title"] ?? "Développeur";
$email    = $data["email"] ?? "";
$phone    = $data["phone"] ?? "";
$summary  = $data["summary"] ?? "";
$skills   = $data["skills"] ?? [];
$experience = $data["experience"] ?? [];
$education  = $data["education"] ?? [];

// Helpers
function h($str) {
  return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

// Générer HTML pour les compétences
$skillsHtml = "";
if (is_array($skills) && count($skills) > 0) {
  foreach ($skills as $s) {
    $skillsHtml .= "<li class=\"flex items-center\"><span class=\"w-2 h-2 bg-indigo-500 rounded-full mr-2\"></span>" . h($s) . "</li>";
  }
} else {
  $skillsHtml = "<li>—</li>";
}

// Générer HTML pour les compétences (format simple pour templates)
$skillsText = "";
if (is_array($skills) && count($skills) > 0) {
  $skillsText = implode(", ", array_map('h', $skills));
} else {
  $skillsText = "—";
}

$expHtml = "";
if (is_array($experience) && count($experience) > 0) {
  foreach ($experience as $e) {
    $role = h($e["role"] ?? "");
    $company = h($e["company"] ?? "");
    $start = h($e["start"] ?? "");
    $end = h($e["end"] ?? "");
    $details = h($e["details"] ?? "");

    $expHtml .= "
      <div>
        <div class=\"flex justify-between items-baseline\">
          <h3 class=\"font-bold text-gray-900\">" . $role . "</h3>
          <span class=\"text-xs font-semibold text-indigo-600\">" . $start . " - " . $end . "</span>
        </div>
        <p class=\"text-sm text-gray-600 italic mb-2\">" . $company . "</p>
        <p class=\"text-sm text-gray-700\">" . nl2br($details) . "</p>
      </div>
    ";
  }
} else {
  $expHtml = "<div class=\"text-sm text-gray-700\">—</div>";
}

$eduHtml = "";
if (is_array($education) && count($education) > 0) {
  foreach ($education as $ed) {
    $school = h($ed["school"] ?? "");
    $degree = h($ed["degree"] ?? "");
    $start = h($ed["start"] ?? "");
    $end = h($ed["end"] ?? "");

    $eduHtml .= "
      <div>
        <div class=\"flex justify-between items-baseline\">
          <h3 class=\"font-bold text-gray-900\">" . $degree . "</h3>
          <span class=\"text-xs font-semibold text-indigo-600\">" . $start . " - " . $end . "</span>
        </div>
        <p class=\"text-sm text-gray-600\">" . $school . "</p>
      </div>
    ";
  }
} else {
  $eduHtml = "<div class=\"text-sm text-gray-700\">—</div>";
}

// IMPORTANT: Utilisation d'un template externe pour une meilleure maintenance
// Dans une version réelle, on utiliserait un moteur de template comme Twig ou Blade.
// Ici, on fait un remplacement simple de variables pour l'exemple.

$templatePath = __DIR__ . "/../../../templates/" . $templateName . "/index.html";
if (file_exists($templatePath)) {
    $html = file_get_contents($templatePath);
    
    // Remplacement des variables simples
    $html = str_replace("{{fullName}}", h($fullName), $html);
    $html = str_replace("{{title}}", h($title), $html);
    $html = str_replace("{{email}}", h($email), $html);
    $html = str_replace("{{phone}}", h($phone), $html);
    $html = str_replace("{{summary}}", nl2br(h($summary)), $html);
    
    // Remplacement des sections HTML (pour formats avancés)
    $html = str_replace("{{skillsHtml}}", $skillsHtml, $html);
    $html = str_replace("{{skillsText}}", $skillsText, $html);
    $html = str_replace("{{expHtml}}", $expHtml, $html);
    $html = str_replace("{{eduHtml}}", $eduHtml, $html);
} else {
    // Fallback sur un HTML minimal si le template n'est pas trouvé
    $html = "<h1>" . h($fullName) . "</h1><p>Template non trouvé.</p>";
}

echo json_encode([
  "ok" => true,
  "html" => $html,
  "message" => "CV généré avec succès à partir du template " . $templateName . "."
]);