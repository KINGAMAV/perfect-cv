<?php
// API pour générer et télécharger un PDF

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
    echo "Données invalides";
    exit;
}

// Récupérer le template
$template = $data["template"] ?? "modern";
$allowedTemplates = ['modern', 'minimal', 'creative'];

if (!in_array($template, $allowedTemplates)) {
    http_response_code(400);
    echo "Template non valide";
    exit;
}

// Données CV
$fullName = $data["fullName"] ?? "Votre Nom";
$title = $data["title"] ?? "Développeur";
$email = $data["email"] ?? "";
$phone = $data["phone"] ?? "";
$summary = $data["summary"] ?? "";
$skills = $data["skills"] ?? [];
$experience = $data["experience"] ?? [];
$education = $data["education"] ?? [];
$photoUrl = $data["photoUrl"] ?? null;

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

// Générer HTML pour les compétences (texte simple)
$skillsText = "";
if (is_array($skills) && count($skills) > 0) {
    $skillsText = implode(", ", array_map('h', $skills));
} else {
    $skillsText = "—";
}

// Générer HTML pour les expériences
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

// Générer HTML pour les formations
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

// Générer la photoadditional HTML
$photoHtml = "";
if ($photoUrl) {
    // Convertir URL relative en absolue si nécessaire
    if (strpos($photoUrl, 'http') !== 0) {
        if (strpos($photoUrl, '/') === 0) {
            $photoUrl = 'http://localhost' . $photoUrl;
        } else {
            $photoUrl = 'http://localhost/' . $photoUrl;
        }
    }
    $photoHtml = "<img src=\"" . h($photoUrl) . "\" alt=\"Photo\" class=\"w-32 h-32 rounded-full object-cover\">";
}

// Charger le template
$templatePath = __DIR__ . "/../../../templates/" . $template . "/index.html";
if (file_exists($templatePath)) {
    $html = file_get_contents($templatePath);
    
    // Remplacement des variables
    $html = str_replace("{{fullName}}", h($fullName), $html);
    $html = str_replace("{{title}}", h($title), $html);
    $html = str_replace("{{email}}", h($email), $html);
    $html = str_replace("{{phone}}", h($phone), $html);
    $html = str_replace("{{summary}}", nl2br(h($summary)), $html);
    $html = str_replace("{{photoHtml}}", $photoHtml, $html);
    $html = str_replace("{{skillsHtml}}", $skillsHtml, $html);
    $html = str_replace("{{skillsText}}", $skillsText, $html);
    $html = str_replace("{{expHtml}}", $expHtml, $html);
    $html = str_replace("{{eduHtml}}", $eduHtml, $html);

    // Envoyer le HTML pour que le client le capture et génère un PDF
    header("Content-Type: text/html; charset=utf-8");
    echo $html;
} else {
    http_response_code(404);
    echo "Template non trouvé";
}
