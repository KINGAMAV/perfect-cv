<?php
// CORS - pour autoriser ton front React
header("Access-Control-Allow-Origin: http://localhost:5173");
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

// Générer HTML CV (1 page basique)
$skillsHtml = "";
if (is_array($skills) && count($skills) > 0) {
  foreach ($skills as $s) {
    $skillsHtml .= "<li>" . h($s) . "</li>";
  }
} else {
  $skillsHtml = "<li>—</li>";
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
      <div class='mb-3'>
        <div class='flex justify-between gap-3'>
          <div class='font-semibold'>" . $role . "</div>
          <div class='text-right text-sm text-gray-600'>" . $start . " - " . $end . "</div>
        </div>
        <div class='text-gray-800'>" . $company . "</div>
        <div class='text-sm text-gray-700 mt-1'>" . nl2br($details) . "</div>
      </div>
    ";
  }
} else {
  $expHtml = "<div class='text-sm text-gray-700'>—</div>";
}

$eduHtml = "";
if (is_array($education) && count($education) > 0) {
  foreach ($education as $ed) {
    $school = h($ed["school"] ?? "");
    $degree = h($ed["degree"] ?? "");
    $start = h($ed["start"] ?? "");
    $end = h($ed["end"] ?? "");

    $eduHtml .= "
      <div class='mb-2'>
        <div class='flex justify-between gap-3'>
          <div class='font-semibold'>" . $degree . "</div>
          <div class='text-right text-sm text-gray-600'>" . $start . " - " . $end . "</div>
        </div>
        <div class='text-gray-800'>" . $school . "</div>
      </div>
    ";
  }
} else {
  $eduHtml = "<div class='text-sm text-gray-700'>—</div>";
}

// IMPORTANT: Utilisation d'un template externe pour une meilleure maintenance
// Dans une version réelle, on utiliserait un moteur de template comme Twig ou Blade.
// Ici, on fait un remplacement simple de variables pour l'exemple.

$templatePath = __DIR__ . "/../../../templates/modern/index.html";
if (file_exists($templatePath)) {
    $html = file_get_contents($templatePath);
    
    // Remplacement des variables simples
    $html = str_replace("{{fullName}}", h($fullName), $html);
    $html = str_replace("{{title}}", h($title), $html);
    $html = str_replace("{{email}}", h($email), $html);
    $html = str_replace("{{phone}}", h($phone), $html);
    $html = str_replace("{{summary}}", nl2br(h($summary)), $html);
    
    // Pour les listes (skills, experience, education), une logique plus complexe serait nécessaire.
    // Pour cet exemple, on injecte directement le HTML généré précédemment.
    $html = str_replace("{{#skills}}", "", $html);
    $html = str_replace("{{/skills}}", "", $html);
    $html = str_replace("{{name}}", $skillsHtml, $html); // Simplification pour l'exemple
    
    $html = str_replace("{{#experience}}", "", $html);
    $html = str_replace("{{/experience}}", "", $html);
    $html = str_replace("{{role}}", $expHtml, $html); // Simplification pour l'exemple
    
    $html = str_replace("{{#education}}", "", $html);
    $html = str_replace("{{/education}}", "", $html);
    $html = str_replace("{{degree}}", $eduHtml, $html); // Simplification pour l'exemple
} else {
    // Fallback sur un HTML minimal si le template n'est pas trouvé
    $html = "<h1>" . h($fullName) . "</h1><p>Template non trouvé.</p>";
}

echo json_encode([
  "ok" => true,
  "html" => $html,
  "message" => "CV généré avec succès à partir du template moderne."
]);