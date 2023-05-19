<?php

namespace Controllers;

use Models\User;

class ForgotPwdController extends BaseController
{
    private string $errors = '';

    public function index()
    {
        

        // on choisi la template à appeler
        $template = $this->twig->load('forgotpwd/forgotpwd**.html');


        // Puis on affiche la page avec la méthode render
        echo $template->render([
            'title' => 'Mot de passe oublié',
            'errors' => $this->errors,

        ]);
    }
}