<?php

namespace Models;

use PDO;
use Tools\Config;

class Database
{
    private static ?Database $instance = null;
    protected PDO $connexion;

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
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
                )
            );
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }
    }

    public function getConnexion() : PDO{
        return $this->connexion;
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}