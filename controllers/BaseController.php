<?php

namespace Controllers;



use Tools\Config;
use voku\helper\AntiXSS;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use ParagonIE\AntiCSRF\AntiCSRF;


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
     * @var array 
     */
    protected array $errors = [];

    /**
     * @var array
    */

    protected array $successes = [];


    /**
     * @var 
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
        $this->twig->addFunction(
            new \Twig\TwigFunction(
                'form_token',
                function($lock_to = null) {
                    static $csrf;
                    if ($csrf === null) {
                        $csrf = new AntiCSRF();
                    }
                    return $csrf->insertToken($lock_to, false);
                },
                ['is_safe' => ['html']]
            )
        );


        // Si session active, on passe les infos dans la vue Twig
        if ($this->httpSession->has('user')) {
            $this->twig->addGlobal('user', $this->httpSession->get('user'));
        }


    }

    /**
     * @var array
     */
    protected function cleanXSS($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = $this->antiXss->xss_clean($data);
        return $data;
    }

    protected function redirect($targetUrl)
    {

        $response = new RedirectResponse($targetUrl);
        $response->send();
    }




}