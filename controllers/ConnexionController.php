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
        if (!empty($_POST['mail']) && !empty($_POST['pwd'])) {

            $email = lcfirst($_POST['mail']);
            $password = $_POST['pwd'];

            $modelUser = new Users();
            $userFound = $modelUser->loadUserByEmail($email);

            if ($userFound) {
                $passwordHash = $userFound['pwd'];
                if (password_verify($password, $passwordHash)) {

                    $updatedAt = date('Y-m-d H:i:s');
                    $_SESSION['user'] = [
                        'id' => $userFound['id'],
                        'email' => $email,
                        'firstname' => $userFound['firstname'],
                        'lastname' => $userFound['lastname'],
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