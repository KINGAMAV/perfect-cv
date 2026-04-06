<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Classe Database pour gérer la connexion à la base de données via PDO.
 * Cette classe utilise le pattern Singleton pour assurer une seule connexion active.
 */
class Database {
    private static $instance = null;
    private $connection;

    // Informations de connexion (à déplacer dans un fichier .env ou config plus tard)
    private $host = 'localhost';
    private $db_name = 'perfect_cv';
    private $username = 'root';
    private $password = '';

    /**
     * Constructeur privé pour empêcher l'instanciation directe.
     */
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERR_MODE => PDO::ERR_MODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            // En production, ne pas afficher l'erreur brute
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    /**
     * Récupère l'instance unique de la connexion.
     * 
     * @return PDO
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }
}
