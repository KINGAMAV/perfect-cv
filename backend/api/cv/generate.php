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

// IMPORTANT: HTML simple, style inline minimal (pas besoin Tailwind côté PHP)
$html = "
<!doctype html>
<html>
<head>
  <meta charset='utf-8' />
  <meta name='viewport' content='width=device-width, initial-scale=1' />
  <title>CV</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; background: #f3f4f6; }
    .page { max-width: 900px; margin: 24px auto; background: white; padding: 28px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,.08); }
    h1 { margin: 0; font-size: 26px; }
    .title { margin-top: 6px; font-size: 14px; color: #374151; font-weight: 600; }
    .contact { margin-top: 10px; color: #4b5563; font-size: 13px; }
    .grid { display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 22px; margin-top: 18px; }
    h2 { font-size: 15px; margin: 0 0 10px 0; color: #111827; letter-spacing: .2px; }
    .section { margin-bottom: 16px; }
    ul { padding-left: 18px; margin: 8px 0 0; }
    li { margin: 4px 0; font-size: 13px; color: #111827; }
    .muted { color: #4b5563; font-size: 13px; }
    .small { font-size: 13px; }
    .line { height: 1px; background: #e5e7eb; margin: 14px 0; }
    .footer-note { margin-top: 18px; font-size: 12px; color: #6b7280; }
  </style>
</head>
<body>
  <div class='page'>
    <div>
      <h1>" . h($fullName) . "</h1>
      <div class='title'>" . h($title) . "</div>
      <div class='contact'>
        " . ($email ? "<div><b>Email:</b> " . h($email) . "</div>" : "") . "
        " . ($phone ? "<div><b>Tél:</b> " . h($phone) . "</div>" : "") . "
      </div>
    </div>

    <div class='grid'>
      <div>
        <div class='section'>
          <h2>Profil</h2>
          <div class='muted small'>" . nl2br(h($summary)) . "</div>
        </div>

        <div class='line'></div>

        <div class='section'>
          <h2>Expériences</h2>
          " . $expHtml . "
        </div>
      </div>

      <div>
        <div class='section'>
          <h2>Compétences</h2>
          <ul>" . $skillsHtml . "</ul>
        </div>

        <div class='line'></div>

        <div class='section'>
          <h2>Formations</h2>
          " . $eduHtml . "
        </div>
      </div>
    </div>

    <div class='footer-note'>CV généré automatiquement (v1)</div>
  </div>
</body>
</html>
";

echo json_encode([
  "ok" => true,
  "html" => $html
]);