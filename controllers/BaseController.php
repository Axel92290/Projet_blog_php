<?php

namespace Controllers;

use Tools\Config;
use voku\helper\AntiXSS;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use ParagonIE\AntiCSRF\AntiCSRF;

/**
 * Classe de base pour les contrôleurs.
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
     * @var AntiXSS;
     */
    protected $antiXss;

    /**
     * @var Request
     */
    protected $httpRequest;

    /**
     * @var Session
     */
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
     * Constructeur de la classe BaseController.
     */
    public function __construct()
    {
        // Initialisation du chargeur de templates Twig.
        $this->loader = new \Twig\Loader\FilesystemLoader(APP_DIRECTORY . 'views');
        $this->twig = new \Twig\Environment($this->loader);

        // Classe de chargement du fichier de configuration dev.ini.
        $this->conf = new Config();

        // Classe de protection contre les failles XSS.
        $this->antiXss = new AntiXSS();

        // Classe de gestion des requêtes HTTP.
        $this->httpRequest = Request::createFromGlobals();
        $this->httpSession = new Session();
        $this->httpSession->start();

        // Passage de l'URL de base à la vue Twig.
        $this->twig->addGlobal('base_url', $this->conf->get('siteUrl'));

        // Ajout de la fonction form_token à Twig pour la gestion CSRF.
        $this->twig->addFunction(
            new \Twig\TwigFunction(
                'form_token',
                function ($lock_to = null) {
                    static $csrf;
                    if ($csrf === null) {
                        $csrf = new AntiCSRF();
                    }
                    return $csrf->insertToken($lock_to, false);
                },
                ['is_safe' => ['html']]
            )
        );

        // Si une session est active, passage des informations de l'utilisateur à la vue Twig.
        if ($this->httpSession->has('user')) {
            $this->twig->addGlobal('user', $this->httpSession->get('user'));
        }
        
    } // End __construct().


    /**
     * Nettoie les données d'entrée pour prévenir les attaques XSS (Cross-Site Scripting).
     *
     * @param string $data Les données à nettoyer.
     * @return string Les données nettoyées.
     */
    protected function cleanXSS($data)
    {
        $data = trim($data);
        $data = htmlspecialchars($data);
        $data = $this->antiXss->xss_clean($data);
        return $data;

    } // End cleanXSS().
    
    
    /**
     * Redirige vers l'URL cible.
     *
     * Cette fonction effectue une redirection vers l'URL spécifiée en utilisant une réponse de redirection.
     *
     * @param string $targetUrl L'URL vers laquelle effectuer la redirection.
     * @return void
     */
    protected function redirect($targetUrl)
    {
        $response = new RedirectResponse($targetUrl);
        $response->send();

    } // End redirect().
}
