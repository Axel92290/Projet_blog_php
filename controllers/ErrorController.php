<?php

namespace Controllers;

class ErrorController extends BaseController
{
    public function error()
    {

        // on choisi la template à appeler
        $template = $this->twig->load('error/error.html');


        // Puis on affiche la page avec la méthode render
        $render = $template->render([
            'title' => '404 Error',
        ]);

        echo $render;
    }


}