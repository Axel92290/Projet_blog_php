<?php

namespace Controllers;

use Models\Users;

class RegisterController extends BaseController
{

    
    /**
     * Affiche la page d'inscription.
     *
     * Cette fonction affiche la page d'inscription du site.
     * Elle permet à un utilisateur de s'inscrire.
     * @return void
     * 
     */
    public function register()
    {
        // Vérification du jeton CSRF.
        $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;

        // Vérification de la session.
        $this->checkSession();

        // Choix de la template à appeler.
        $template = $this->twig->load('register/register.html');

        if ($this->httpRequest->isMethod('POST') && $csrf->validateRequest()) {
            // Validation des données du formulaire.
            $nom = $this->validateFormField('nom', 'Veuillez remplir le champ nom');
            $prenom = $this->validateFormField('prenom', 'Veuillez remplir le champ prénom');
            $mail = $this->validateEmailFormField('mail', 'Veuillez remplir le champ email');
            $pwd = $this->validateFormField('pwd', 'Veuillez remplir le champ mot de passe');
            $confpassword = $this->validateFormField('confPwd', 'Veuillez remplir le champ confirmation du mot de passe');

            if (empty($this->errors)) {
                // Hashage du mot de passe.
                $pwd = password_hash($pwd, PASSWORD_ARGON2ID);
                $insertUser = $this->insertUserData($nom, $prenom, $mail, $pwd, $confpassword);

                if ($insertUser) {
                    $url = $this->conf->get('siteUrl');
                    $this->redirect("$url/connexion/");
                    return;
                } else {
                    $this->errors[] = 'Erreur lors de l\'inscription';
                }
            }
        }

        // Affichage de la page avec la méthode render.
        $render = $template->render([
            'title' => 'Inscription',
            'errors' => $this->errors,
        ]);

        print_r($render);
    }

    private function validateFormField($fieldName, $errorMsg)
    {
        // Récupération de la valeur du champ depuis la requête HTTP.
        $fieldValue = $this->httpRequest->request->get($fieldName);

        // Vérification si le champ est vide.
        if (!$fieldValue) {
            $this->errors[] = $errorMsg;
        } else {
            // Nettoyage XSS et mise en majuscule de la première lettre.
            $fieldValue = ucfirst($this->antiXss->xss_clean($fieldValue));
        }

        return $fieldValue;
    }

    private function validateEmailFormField($fieldName, $errorMsg)
    {
        // Récupération de la valeur du champ depuis la requête HTTP.
        $fieldValue = $this->httpRequest->request->get($fieldName);

        // Vérification si le champ est vide.
        if (!$fieldValue) {
            $this->errors[] = $errorMsg;
        } elseif (!filter_var($fieldValue, FILTER_VALIDATE_EMAIL)) {
            // Vérification de la validité de l'email.
            $this->errors[] = 'Veuillez entrer un email valide';
        } else {
            // Vérification si l'email existe déjà dans la base de données.
            $modelUser = new Users();
            $mailFound = $modelUser->checkUserByEmail($fieldValue);
            if (!empty($mailFound)) {
                $this->errors[] = 'Cet email est déjà utilisé';
            } else {
                // Nettoyage XSS et mise en minuscules de la première lettre.
                $fieldValue = (string) $this->antiXss->xss_clean(lcfirst($fieldValue));
            }
        }

        return $fieldValue;
    }

    private function insertUserData($nom, $prenom, $mail, $pwd, $confpassword)
    {
        // Création d'un nouvel utilisateur dans la base de données.
        $modelUser = new Users();
        return $modelUser->insertData($nom, $prenom, $mail, $pwd, $confpassword);
    }

    private function checkSession()
    {
        // Vérification de la session utilisateur.
        if ($this->httpSession->get('user')) {
            // Redirection vers la page de connexion si un utilisateur est déjà connecté.
            $this->redirect('/connexion/');
            return;
        }
    }
}
