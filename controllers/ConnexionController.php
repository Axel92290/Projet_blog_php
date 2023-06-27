<?php

namespace Controllers;

use Models\Users;

class ConnexionController extends BaseController
{
    /**
     * @var string
     */
    private string $errors = '';

    public function connexion()
    {
        $this->checkSession();
        $this->checkFormSubmitForm();

        // on choisi la template à appeler
        $template = $this->twig->load('connexion/connexion.html');

        // Puis on affiche la page avec la méthode render
        echo $template->render([
            'title' => 'Connexion',
            'errors' => $this->errors,
        ]);
    }

    /**
     * @return void
     */
    private function checkFormSubmitForm()
    {
        if ($this->httpRequest->isMethod('POST')) {
            if (!empty($_POST['mail']) && !empty($_POST['pwd'])) {

                $email = $this->cleanXSS($this->httpRequest->request->get('mail'));
                $password = $this->cleanXSS($this->httpRequest->request->get('pwd'));

                $modelUser = new Users();
                $userFound = $modelUser->loadUserByEmail($email);

                if ($userFound) {
                    $passwordHash = $userFound['pwd'];
                    if (password_verify($password, $passwordHash)) {

                        $updatedAt = date('Y-m-d H:i:s');
                        $_SESSION['user'] = [
                            'id' => $userFound['id'],
                            'email' => $userFound['email'],
                            'firstname' => $userFound['firstname'],
                            'lastname' => $userFound['lastname'],
                            'role' => $userFound['role'],
                        ];
                        $modelUser->updateDateConnexion($email, $updatedAt);

                        header('Location: /');
                        exit;
                    } else {

                        $this->errors = 'Mot de passe incorrect!';
                    }
                } else {
                    $this->errors = 'Utilisateur introuvable!';
                }
            }
        }
    }

    private function checkSession()
    {
        if (isset($_SESSION['user'])) {
            header('Location: /connexion/');
            exit;
        }
    }
}
