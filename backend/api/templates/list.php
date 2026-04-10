<?php
// API pour récupérer les modèles disponibles

header("Content-Type: application/json; charset=utf-8");

// Dossier des templates
$templatesDir = __DIR__ . "/../../../templates";

if (!is_dir($templatesDir)) {
    http_response_code(404);
    echo json_encode([
        "ok" => false,
        "error" => "Dossier des templates non trouvé"
    ]);
    exit;
}

// Récupérer tous les dossiers de templates
$templates = array_diff(scandir($templatesDir), ['.', '..']);
$templates = array_filter($templates, function($item) use ($templatesDir) {
    return is_dir($templatesDir . '/' . $item);
});

$availableTemplates = [];

foreach ($templates as $template) {
    $templatePath = $templatesDir . '/' . $template;
    
    // Vérifier si le template a un fichier config.json
    $configPath = $templatePath . '/config.json';
    if (file_exists($configPath)) {
        $config = json_decode(file_get_contents($configPath), true);
        $availableTemplates[] = [
            'name' => $template,
            'label' => $config['label'] ?? ucfirst($template),
            'description' => $config['description'] ?? '',
            'preview' => $config['preview'] ?? null
        ];
    } else {
        // Template par défaut sans config
        $availableTemplates[] = [
            'name' => $template,
            'label' => ucfirst($template),
            'description' => '',
            'preview' => null
        ];
    }
}

echo json_encode([
    "ok" => true,
    "templates" => $availableTemplates
]);
