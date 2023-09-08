<?php

namespace Models;

use PDO;
use Tools\Config;

class Database
{


    private static ?Database $instance = null;
    protected PDO $connexion;


    /**
     * Constructeur de la classe Database.
     *
     * Ce constructeur crée une nouvelle connexion à la base de données en utilisant les informations de configuration
     * définies dans la classe Config. Il initialise la connexion à la base de données et configure quelques options
     * comme le jeu de caractères et le mode d'erreur pour les requêtes.
     *
     * @throws \PDOException En cas d'échec de la connexion à la base de données.
     */
    public function __construct()
    {
        try {
            $conf = new Config();
            $this->connexion = new PDO(
                'mysql:host=' . $conf->get('host') . ';dbname=' . $conf->get('dbname') . ';port=' . $conf->get('port'),
                $conf->get('user'),
                $conf->get('password'),
                array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
                )
                
            );
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }
    } // End __construct().


    /**
     * Récupère l'objet PDO représentant la connexion à la base de données.
     *
     * Cette fonction renvoie l'objet PDO utilisé pour la connexion à la base de données.
     *
     * @return PDO L'objet PDO représentant la connexion à la base de données.
     */
    public function getConnexion(): PDO
    {
        return $this->connexion;

    } // End getConnexion().

    
    /**
     * Obtient l'instance unique de la classe Database.
     *
     * Cette méthode implémente le modèle de conception Singleton pour s'assurer qu'il n'y a qu'une seule instance
     * de la classe Database dans l'application. Si aucune instance n'existe, elle en crée une nouvelle et la retourne.
     * Sinon, elle retourne l'instance existante.
     *
     * @return Database L'instance unique de la classe Database.
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
        
    } // End getInstance().
}
