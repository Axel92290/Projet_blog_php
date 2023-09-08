<?php

namespace Tools;


class Config
{
    /**
     * @var array|false
     */
    private $iniConfFile = [];
    

    /**
     * Constructeur de la classe Config.
     *
     * Initialise la configuration en chargeant un fichier INI.
     */
    public function __construct()
    {
        $this->iniConfFile = parse_ini_file(dirname(__FILE__) . "/../config/dev.ini", false);
        
    } // End __construct().


    /**
     * Obtient la valeur associée à une clé dans un tableau de configuration INI.
     *
     * @param string $name Le nom de la clé que l'on souhaite récupérer.
     * @return mixed|null La valeur associée à la clé si elle existe, sinon null.
     */
    public function get($name)
    {
        $result = null;
        if (isset($this->iniConfFile[$name])) {
            $result = $this->iniConfFile[$name];
        }
        return $result;

    } // End get().
}
