<?php

namespace Controllers;

use Models\Users;

class ConnexionController extends BaseController
{


    /**
     * Gère la connexion de l'utilisateur.
     *
     * Cette méthode vérifie la session de l'utilisateur et le formulaire de soumission,
     * puis affiche la page de connexion.
     *
     * @return void
     */
    public function connexion()
    {
        $this->checkSession();
        $this->checkFormSubmit();

        // On choisit la template à appeler.
        $template = $this->twig->load('connexion/connexion.html');

        // Puis on affiche la page avec la méthode render.
        $render = $template->render([
                    'title'  => 'Connexion',
                    'errors' => $this->errors,
                  ]);        

        print_r($render);
        
    } // End connexion().


    /**
     * Vérifie le formulaire de soumission.
     *
     * Cette méthode vérifie si la requête est de type POST, valide le jeton CSRF,
     * obtient les valeurs du formulaire, charge l'utilisateur par e-mail, et traite la connexion de l'utilisateur.
     *
     * @return void
     */
    private function checkFormSubmit()
    {
        if (!$this->isPostRequest()) {
            return;
        }

        if (!$this->validateCSRF()) {
            return;
        }

        $email = $this->getRequestValue('mail');
        $password = $this->getRequestValue('pwd');

        if (!$email || !$password) {
            return;
        }

        $userFound = $this->loadUserByEmail($email);

        if (!$userFound) {
            $this->errors[] = 'Utilisateur introuvable!';
            return;
        }

        $this->processUserLogin($userFound, $password);
    }

    /**
     * Vérifie si la requête est de type POST.
     *
     * @return bool True si la requête est de type POST, sinon False.
     */
    private function isPostRequest()
    {
        return $this->httpRequest->isMethod('POST');
    }

    /**
     * Valide le jeton CSRF pour la sécurité.
     *
     * @return bool True si le jeton CSRF est valide, sinon False.
     */
    private function validateCSRF()
    {
        $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;
        return $csrf->validateRequest();
    }

    /**
     * Récupère une valeur de la requête en la nettoyant contre les attaques XSS.
     *
     * @param string $key La clé de la valeur à récupérer.
     * @return string|null La valeur nettoyée ou null si non présente.
     */
    private function getRequestValue($key)
    {
        return $this->cleanXSS($this->httpRequest->request->get($key));
    }

    /**
     * Charge un utilisateur en utilisant son adresse e-mail.
     *
     * @param string $email L'adresse e-mail de l'utilisateur.
     * @return array|null Les données de l'utilisateur ou null si non trouvé.
     */
    private function loadUserByEmail($email)
    {
        $modelUser = new Users();
        return $modelUser->loadUserByEmail($email);
    }

    /**
     * Traite la connexion de l'utilisateur après avoir validé ses informations.
     *
     * @param array $userFound Les données de l'utilisateur trouvé.
     * @param string $password Le mot de passe saisi par l'utilisateur.
     * @return void
     */
    private function processUserLogin($userFound, $password)
    {
        $passwordHash = $userFound['pwd'];

        if (password_verify($password, $passwordHash)) {
            $updatedAt = date('Y-m-d H:i:s');
            $this->httpSession->set('user', [
                     'id'        => $userFound['id'],
                     'email'     => $userFound['email'],
                     'firstname' => $userFound['firstname'],
                     'lastname'  => $userFound['lastname'],
                     'role'      => $userFound['role'],
                   ]);

            $this->updateUserLoginDate($userFound['email'], $updatedAt);
            $this->redirect('/');
        } else {
            $this->errors[] = 'Mot de passe incorrect!';
        }
    }

    /**
     * Met à jour la date de connexion de l'utilisateur dans la base de données.
     *
     * @param string $email     L'adresse e-mail de l'utilisateur.
     * @param string $updatedAt La date de mise à jour.
     * @return void
     */
    private function updateUserLoginDate($email, $updatedAt)
    {
        $modelUser = new Users();
        $modelUser->updateDateConnexion($email, $updatedAt);
    }

    /**
     * Vérifie si l'utilisateur est déjà connecté et le redirige le cas échéant.
     *
     * @return void
     */
    private function checkSession()
    {
        if ($this->httpSession->has('user')) {
            $this->redirect('/');
        }
    }
}
