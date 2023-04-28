<?php
namespace Tools;


class Config {

    /**
     * @var array|false
     */
    private $iniConfFile = [];

    public function __construct()
    {
        $this->iniConfFile = parse_ini_file(dirname(__FILE__)."/../config/dev.ini", false);
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function get($name)
    {
        $result = null;
        if (isset($this->iniConfFile[$name])) {
            $result = $this->iniConfFile[$name];
        }
        return $result;
    }

}