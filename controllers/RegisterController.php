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
        
    } // End register().

    /**
     * Valide un champ de formulaire et retourne sa valeur nettoyée.
     *
     * Cette fonction prend en paramètre le nom du champ à valider et un message d'erreur personnalisé.
     * Elle récupère la valeur du champ depuis la requête HTTP, vérifie s'il est vide, nettoie la valeur
     * contre les attaques XSS, met la première lettre en majuscule, et retourne la valeur nettoyée.
     * Si le champ est vide, elle enregistre un message d'erreur.
     *
     * @param string $fieldName Le nom du champ de formulaire à valider.
     * @param string $errorMsg  Le message d'erreur à afficher en cas de champ vide.
     * @return string|null      La valeur nettoyée du champ ou null en cas d'erreur.
     */
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

    /**
     * Valide un champ de formulaire d'adresse e-mail et retourne sa valeur nettoyée.
     *
     * Cette fonction prend en paramètre le nom du champ d'adresse e-mail à valider et un message d'erreur personnalisé.
     * Elle récupère la valeur du champ depuis la requête HTTP, vérifie s'il est vide, s'il est au format d'adresse e-mail valide,
     * et si l'adresse e-mail n'existe pas déjà dans la base de données. Si toutes les vérifications passent, elle nettoie la valeur
     * contre les attaques XSS, met la première lettre en minuscule, et retourne la valeur nettoyée.
     * En cas d'erreur, elle enregistre un message d'erreur.
     *
     * @param string $fieldName Le nom du champ d'adresse e-mail à valider.
     * @param string $errorMsg  Le message d'erreur à afficher en cas de champ vide ou d'adresse e-mail invalide.
     * @return string|null      La valeur nettoyée du champ d'adresse e-mail ou null en cas d'erreur.
     */
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

    /**
     * Insère les données d'un nouvel utilisateur dans la base de données.
     *
     * Cette fonction prend en paramètre le nom, le prénom, l'adresse e-mail, le mot de passe et la confirmation du mot de passe
     * de l'utilisateur à créer. Elle utilise le modèle Users pour insérer ces données dans la base de données.
     * Elle renvoie le résultat de l'opération d'insertion, soit true si l'insertion réussit, sinon false.
     *
     * @param string $nom          Le nom de l'utilisateur.
     * @param string $prenom       Le prénom de l'utilisateur.
     * @param string $mail         L'adresse e-mail de l'utilisateur.
     * @param string $pwd          Le mot de passe de l'utilisateur.
     * @param string $confpassword La confirmation du mot de passe de l'utilisateur.
     * @return bool                True si l'insertion réussit, sinon False.
     */
    private function insertUserData($nom, $prenom, $mail, $pwd, $confpassword)
    {
        // Création d'un nouvel utilisateur dans la base de données.
        $modelUser = new Users();
        return $modelUser->insertData($nom, $prenom, $mail, $pwd, $confpassword);
    }
    
    /**
     * Vérifie la session utilisateur.
     *
     * Si un utilisateur est déjà connecté, cette fonction redirige vers la page de connexion.
     */
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
