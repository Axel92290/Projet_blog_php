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
        echo $template->render([
            'title' => 'Accueil du blog',
        ]);
    }

    private function contact()
    {
        $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;
        if ($this->httpRequest->isMethod('POST') && $csrf->validateRequest()) {

            echo 'test';
            die;

            $conf = new Config();


            $nom = $this->cleanXSS($this->httpRequest->request->get('nom'));
            $prenom = ucfirst($this->cleanXSS($this->httpRequest->request->get('prenom')));
            $email = lcfirst($this->cleanXSS($this->httpRequest->request->get('email')));
            $message = $this->cleanXSS($this->httpRequest->request->get('message'));

            // Sujet
            $subject = 'Message de ' . $nom . ' ' . $prenom . ' ';

            try{
                ini_set(
                    'SMTP',
                    'localhost'
                );
                ini_set('smtp_port', 1025);
        
                // Envoi d'un e-mail de test
                $to = 'axel.chasseloup@gmail.com';
                $subject = $subject;
                $message = $message;
                $headers = 'From: ' . $email ;
        
                // Envoi de l'e-mail
                if (mail($to, $subject, $message,
                    $headers
                )) {
                    echo 'Email sent successfully!';
                } else {
                    echo 'Failed to send email.';
                }
            } catch(\Exception $e){
                echo $e->getMessage();
            }
    
        }
    }
}
