<?php

namespace Controllers;

use Tools\Config;


class IndexController extends BaseController
{
    public function index()
    {

        // on choisi la template à appeler
        $template = $this->twig->load('index/index.html');

        $this->contact();



        // Puis on affiche la page avec la méthode render

        $render = $template->render([
            'title' => 'Accueil du blog',
            'successes' => $this->successes
        ]);

        print_r($render);
    }

    private function contact()
    {
        $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;
        if (!$this->httpRequest->isMethod('POST') || !$csrf->validateRequest()) {
            return;
        }

        $conf = new Config();

        $nom = $this->cleanXSS($this->httpRequest->request->get('nom'));
        $prenom = ucfirst($this->cleanXSS($this->httpRequest->request->get('prenom')));
        $email = lcfirst($this->cleanXSS($this->httpRequest->request->get('email')));
        $message = $this->cleanXSS($this->httpRequest->request->get('message'));

        // Sujet
        $subject = 'Message de ' . $nom . ' ' . $prenom . ' ';

        try {
            // Envoi d'un e-mail de test
            $to = $email;
            $subject = $subject;
            $message = $message;
            $headers = 'From: ' . $conf->get('admin.mailhog');

            // Envoi de l'e-mail
            if (mail($to, $subject, $message, $headers)) {
                $this->successes[] = 'Email envoyé !';
            } else {
                $this->successes[] = 'Erreur lors de l\'envoi de l\'email';
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $this->errors[] = $errorMessage;
        }
    }
}
