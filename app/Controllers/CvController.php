<?php

namespace App\Controllers;

use App\Models\Cv;

/**
 * Contrôleur CvController pour gérer les requêtes liées aux CV.
 */
class CvController {
    private $cvModel;

    public function __construct() {
        $this->cvModel = new Cv();
    }

    /**
     * Liste tous les CV de l'utilisateur connecté.
     * 
     * @param int $userId L'ID de l'utilisateur.
     * @return array Liste des CV.
     */
    public function list($userId) {
        return $this->cvModel->getAllByUserId($userId);
    }

    /**
     * Crée un nouveau CV.
     * 
     * @param int $userId L'ID de l'utilisateur.
     * @param array $data Les données du CV.
     * @return int L'ID du CV créé.
     */
    public function create($userId, $data) {
        $name = $data['name'] ?? 'Nouveau CV';
        $template = $data['template'] ?? 'modern';
        return $this->cvModel->create($userId, $name, $template);
    }

    /**
     * Met à jour un CV existant.
     * 
     * @param int $cvId L'ID du CV.
     * @param array $data Les nouvelles données du CV.
     * @return bool Succès ou échec.
     */
    public function update($cvId, $data) {
        $name = $data['name'] ?? 'CV sans nom';
        $template = $data['template'] ?? 'modern';
        return $this->cvModel->update($cvId, $name, $template);
    }

    /**
     * Supprime un CV.
     * 
     * @param int $cvId L'ID du CV.
     * @return bool Succès ou échec.
     */
    public function delete($cvId) {
        return $this->cvModel->delete($cvId);
    }

    /**
     * Génère le HTML du CV à partir des données.
     * 
     * @param int $cvId L'ID du CV.
     * @return string Le HTML généré.
     */
    public function generateHtml($cvId) {
        // Logique pour récupérer toutes les données liées au CV (infos perso, exp, formations, compétences)
        // et les passer à un template HTML.
        // Pour l'instant, on peut s'inspirer de backend/api/cv/generate.php
        return "HTML généré pour le CV ID: " . $cvId;
    }
}
