<?php

namespace Controllers;

use Models\Users;


class ConnexionController extends BaseController
{


    public function connexion()
    {
        $this->checkSession();
        $this->checkFormSubmitForm();

        // on choisi la template à appeler
        $template = $this->twig->load('connexion/connexion.html');

        // Puis on affiche la page avec la méthode render

        $render = $template->render([
            'title' => 'Connexion',
            'errors' => $this->errors,
        ]);
        echo $render;
    }

    /**
     * @return void
     */
    private function checkFormSubmitForm()
    {
        $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;
        if ($this->httpRequest->isMethod('POST') && $csrf->validateRequest()) {

            if ($this->httpRequest->request->get('mail') && $this->httpRequest->request->get('pwd')) {

                $email = $this->cleanXSS($this->httpRequest->request->get('mail'));
                $password = $this->cleanXSS($this->httpRequest->request->get('pwd'));

                $modelUser = new Users();
                $userFound = $modelUser->loadUserByEmail($email);

                if ($userFound) {
                    $passwordHash = $userFound['pwd'];
                    if (password_verify($password, $passwordHash)) {

                        $updatedAt = date('Y-m-d H:i:s');
                        $this->httpSession->set('user', [
                            'id' => $userFound['id'],
                            'email' => $userFound['email'],
                            'firstname' => $userFound['firstname'],
                            'lastname' => $userFound['lastname'],
                            'role' => $userFound['role'],
                        ]);
                        $modelUser->updateDateConnexion($email, $updatedAt);

                        $this->redirect('/');
                        return;
                    } else {

                        $this->errors[] = 'Mot de passe incorrect!';
                    }
                } else {
                    $this->errors[] = 'Utilisateur introuvable!';
                }
            }
        }
    }

    private function checkSession()
    {
        if ($this->httpSession->has('user')) {
            $this->redirect('/');
            return;
        }
    }
}
