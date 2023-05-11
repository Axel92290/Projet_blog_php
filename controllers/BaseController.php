<?php

namespace Controllers;

use Tools\Config;

/**
 *
 */
class BaseController
{
    /**
     * @var Config
     */
    protected Config $conf;

    /**
     * @var \Twig\Loader\FilesystemLoader
     */
    protected $loader;

    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     *
     */
    public function __construct()
    {
        $this->loader = new \Twig\Loader\FilesystemLoader(APP_DIRECTORY . 'views');
        $this->twig = new \Twig\Environment($this->loader);

        // classe de chargement du fichier de config dans config/dev.ini
        $this->conf = new Config();

        // On passe dans la vue Twig l'url de connexion
        $this->twig->addGlobal('base_url', $this->conf->get('siteUrl'));

        // Si session active, on passe les infos dans la vue Twig
        if (isset($_SESSION['user'])) {
            $this->twig->addGlobal('user', $_SESSION['user']);
        }
    }
}