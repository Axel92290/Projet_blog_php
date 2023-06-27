<?php

namespace Controllers;

use Models\Users;

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

        if ($this->httpRequest->isMethod('POST')) {
            if (!empty($_POST)) {

                $nom = $this->cleanXSS($this->httpRequest->request->get('nom'));
                $prenom = $this->cleanXSS($this->httpRequest->request->get('prenom'));
                $prenom = ucfirst(trim($_POST['prenom']));
                $email = $this->cleanXSS($this->httpRequest->request->get('email'));
                $email = lcfirst(trim($_POST['email']));
                $message = $this->cleanXSS($this->httpRequest->request->get('message'));
                $message = htmlspecialchars($_POST['message']);
                $to  = 'axel.chasseloup@gmail.com';

                // Sujet
                $subject = 'Message de' . $nom . ' ' . $prenom . ' ';

                // message
                $message = '
                        <html>
                        <body>
                        <p>' . $message . '</p>
                        </body>
                        </html>
                        ';

                // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html; charset=iso-8859-1';

                // En-têtes additionnels
                $headers[] = 'To:' . $to . ' ';
                $headers[] = 'From: ' . $email . '';

                // Envoi
                mail($to, $subject, $message, implode("\r\n", $headers));
            }
        }
    }
}
