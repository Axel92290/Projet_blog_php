<?php
namespace Controllers;

class ContactController extends BaseController
{
    public function contact()
    {

        // on choisi la template à appeler
        $template = $this->twig->load('contact/contact.html');


        // Puis on affiche la page avec la méthode render
        echo $template->render(['title' => 'Contact']);
    }
}