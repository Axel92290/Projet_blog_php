<?php

use Controllers\ConnexionController;
use Controllers\CreatePostsController;
use Controllers\DetailsController;
use Controllers\IndexController;
use Controllers\ListingController;
use Controllers\LogoutController;
use Controllers\RegisterController;
use Controllers\ForgotPwdController;
use Controllers\ErrorController;
use Controllers\AdminController;
use Controllers\EditPostController;

require __DIR__ . '/../vendor/autoload.php';


define('APP_DIRECTORY', __DIR__ . '/../');
define('BASE_URL', 'http://blog.localhost');


try {


    // on défini nos routes ici
    $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {

        // page d'accueil
        $r->addRoute(['GET', 'POST'], '/', IndexController::class . '/index');

        // Page d'inscription
        $r->addRoute(['GET', 'POST'], '/register/', RegisterController::class . '/register');

        // Page de connexion
        $r->addRoute(['GET', 'POST'], '/connexion/', ConnexionController::class . '/connexion');

        // Page de mot de passe oublié
        $r->addRoute(['GET', 'POST'], '/forgotpwd/', ForgotPwdController::class . '/forgotpwd');

        // Page du listing des posts
        $r->addRoute(['GET', 'POST'], '/listing-posts/', ListingController::class . '/listing');

        // Page détail d'un post
        $r->addRoute(['GET', 'POST'], '/details-posts/{id:\d+}', DetailsController::class . '/details');

        // Page d'administration
        $r->addRoute(['GET', 'POST'], '/admin/', AdminController::class . '/admin');

        // Page de création d'un post
        $r->addRoute(['GET', 'POST'], '/admin/create-posts/', CreatePostsController::class . '/createPost');

        // Page de modification d'un post
        $r->addRoute(['GET', 'POST'], '/admin/edit-post/{id:\d+}', EditPostController::class . '/editPost');

        // Page d'erreur
        $r->addRoute('GET', '/error/', ErrorController::class . '/error');

        // Page de deconnexion
        $r->addRoute(['GET', 'POST'], '/logout/', LogoutController::class . '/logout');
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
