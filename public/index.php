<?php

session_start();

use Controllers\ConnexionController;
use Controllers\ContactController;
use Controllers\CreatePostsController;
use Controllers\DetailsController;
use Controllers\IndexController;
use Controllers\InscriptionController;
use Controllers\ListingController;
use Controllers\LogoutController;
use Controllers\RegisterController;

require __DIR__ . '/../vendor/autoload.php';


define('APP_DIRECTORY', __DIR__ . '/../');
define('BASE_URL', 'http://blog.localhost');


try {


    // on défini nos routes ici
    $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {

        // page d'accueil
        $r->addRoute('GET', '/', IndexController::class . '/index');

        // Page des posts
        $r->addRoute('GET', '/listing-posts/', ListingController::class . '/listing');

        // Page de création d'un post
        $r->addRoute('GET', '/create-posts/', CreatePostsController::class . '/createPost');

        // Page d'inscription
        $r->addRoute(['GET', 'POST'], '/register/', RegisterController::class . '/index');

        // Page de connexion
        $r->addRoute(['GET', 'POST'], '/connexion/', ConnexionController::class . '/index');
        // $r->addRoute('POST', '/connexion/', ConnexionController::class . '/connexion');

        // Page détail d'un post
        // $r->addRoute('GET', '/details-posts/{id:\d+}', DetailsController::class . '/details');
        $r->addRoute('GET', '/details-posts/', DetailsController::class . '/details');

        // Page de contact
        $r->addRoute('GET', '/contact/', ContactController::class . '/contact');

        // Page de deconnexion
        $r->addRoute('GET', '/logout/', LogoutController::class . '/index');
    });

    // Fetch method and URI from somewhere
    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];


    // Strip query string (?foo=bar) and decode URI
    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }
    $uri = rawurldecode($uri);


    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            // ... 404 Not Found
            // Todo : definir une page d'erreur
            echo 'PAGE NOT FOUND';
            break;


        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $allowedMethods = $routeInfo[1];
            // ... 405 Method Not Allowed
            die('405');
            break;


        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];
            list($class, $method) = explode("/", $handler, 2);
            // on appelle automatiquement notre controlleur, avec la bonne méthode et les bons paramètres donnés à notre fonction
            // Exemple pour la syntaxe "IndexController::class . '/index'", voici ce qui sera appelé : "IndexController->index()"
            call_user_func_array(array(new $class, $method), $vars);
            break;
    }
} catch (Exception $e) {
    echo $e->getMessage();
    die;
}