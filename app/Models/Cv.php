<?php

namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * Modèle Cv pour gérer les opérations CRUD sur la table CV.
 */
class Cv {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Récupère tous les CV d'un utilisateur.
     * 
     * @param int $userId L'ID de l'utilisateur.
     * @return array Liste des CV.
     */
    public function getAllByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM CV WHERE utilisateur_id = :userId ORDER BY date_creation DESC");
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère un CV par son ID.
     * 
     * @param int $cvId L'ID du CV.
     * @return array|null Les données du CV ou null si non trouvé.
     */
    public function getById($cvId) {
        $stmt = $this->db->prepare("SELECT * FROM CV WHERE id = :cvId");
        $stmt->execute(['cvId' => $cvId]);
        return $stmt->fetch();
    }

    /**
     * Crée un nouveau CV.
     * 
     * @param int $userId L'ID de l'utilisateur.
     * @param string $name Le nom du CV.
     * @param string $template Le modèle choisi.
     * @return int L'ID du CV créé.
     */
    public function create($userId, $name, $template = 'modern') {
        $stmt = $this->db->prepare("INSERT INTO CV (utilisateur_id, nom_cv, modele_choisi) VALUES (:userId, :name, :template)");
        $stmt->execute([
            'userId' => $userId,
            'name' => $name,
            'template' => $template
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Met à jour un CV.
     * 
     * @param int $cvId L'ID du CV.
     * @param string $name Le nouveau nom du CV.
     * @param string $template Le nouveau modèle choisi.
     * @return bool Succès ou échec.
     */
    public function update($cvId, $name, $template) {
        $stmt = $this->db->prepare("UPDATE CV SET nom_cv = :name, modele_choisi = :template WHERE id = :cvId");
        return $stmt->execute([
            'cvId' => $cvId,
            'name' => $name,
            'template' => $template
        ]);
    }

    /**
     * Supprime un CV.
     * 
     * @param int $cvId L'ID du CV.
     * @return bool Succès ou échec.
     */
    public function delete($cvId) {
        $stmt = $this->db->prepare("DELETE FROM CV WHERE id = :cvId");
        return $stmt->execute(['cvId' => $cvId]);
    }
}
