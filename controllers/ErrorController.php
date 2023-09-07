<?php

namespace Controllers;

class ErrorController extends BaseController
{
    public function error()
    {

        // On choisi la template à appeler
        $template = $this->twig->load('error/error.html');


        // Puis on affiche la page avec la méthode render
        $render = $template->render([
            'title' => '404 Error',
        ]);

        print_r($render);
    }


}