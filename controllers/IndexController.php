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

        if (!empty($_POST)) {
            $nom = ucfirst(trim($_POST['nom']));
            $prenom = ucfirst(trim($_POST['prenom']));
            $email = lcfirst(trim($_POST['email']));
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
