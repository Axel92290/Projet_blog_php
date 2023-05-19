<?php

namespace Controllers;

use Models\Post;
use Models\User;

class IndexController extends BaseController
{
    public function index()
    {

        // on choisi la template à appeler
        $template = $this->twig->load('index/index.html');


        // Puis on affiche la page avec la méthode render
        echo $template->render(['title' => 'Accueil du blog']);
    }
}