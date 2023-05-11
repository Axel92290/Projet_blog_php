<?php

namespace Models;

use PDO;
use Tools\Config;

class Database
{
    /**
     * @var PDO
     */
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
        } catch (PDOException $e) {
            echo $e->getMessage();
            die;
        }
    }

}