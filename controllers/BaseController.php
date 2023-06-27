<?php

namespace Controllers;


use Tools\Config;
use voku\helper\AntiXSS;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

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
     * @var \AntiXSS;
     */
    protected $antiXss;

    /**
     * @var Request
     */
    protected $httpRequest;
    protected $httpSession;

    /**
     *
     */
    public function __construct()
    {
        $this->loader = new \Twig\Loader\FilesystemLoader(APP_DIRECTORY . 'views');
        $this->twig = new \Twig\Environment($this->loader);

        // classe de chargement du fichier de config dans config/dev.ini
        $this->conf = new Config();

        // classe de protection contre les failles XSS
        $this->antiXss = new AntiXSS();

        // classe de gestion des requêtes HTTP
        $this->httpRequest = Request::createFromGlobals();
        $this->httpSession = new Session();
        $this->httpSession->start();

        // On passe dans la vue Twig l'url de connexion
        $this->twig->addGlobal('base_url', $this->conf->get('siteUrl'));


        // Si session active, on passe les infos dans la vue Twig
        if (isset($_SESSION['user'])) {
            $this->twig->addGlobal('user', $_SESSION['user']);
        }
    }

    protected function cleanXSS($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = $this->antiXss->xss_clean($data);
        return $data;
    }

}