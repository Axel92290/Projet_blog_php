<?php

namespace Controllers;

use Models\Users;

class RegisterController extends BaseController
{


    public function register()
    {
        $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;

        $this->checkSession();
        // on choisi la template à appeler
        $template = $this->twig->load('register/register.html');


        if ($this->httpRequest->isMethod('POST') && $csrf->validateRequest()) {
            $nom = $this->cleanXSS($this->httpRequest->request->get('nom'));
            if (!$nom) {
                $this->errors[] = 'Veuillez remplir le champ nom';
            } else {
                $nom = ucfirst($this->antiXss->xss_clean($this->httpRequest->request->get('nom')));
            }

            if (!$this->httpRequest->request->get('prenom')) {
                $this->errors[] = 'Veuillez remplir le champ prenom';
            } else {
                $prenom = ucfirst($this->antiXss->xss_clean($this->httpRequest->request->get('prenom')));
            }

            if (!$this->httpRequest->request->get('mail')) {
                $this->errors[] = 'Veuillez remplir le champ email ';
            } elseif (!filter_var($this->httpRequest->request->get('mail'), FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = 'Veuillez entrer un email valide';
            } else {
                $modelUser = new Users();

                $mailFound = $modelUser->checkUserByEmail($this->httpRequest->request->get('mail'));
                if (!empty($mailFound)) {
                    $this->errors[] = 'Cet email est déjà utilisé';
                } else {
                    $mail = (string) $this->antiXss->xss_clean(lcfirst(($this->httpRequest->request->get('mail'))));
                }
            }


            if (!$this->httpRequest->request->get('pwd')) {
                $this->errors[] = 'Veuillez remplir le champ mot de passe ';
            } else {
                $pwd = $this->httpRequest->request->get('pwd');
                $pwd = (string) $this->antiXss->xss_clean($pwd);
            }

            if (!$this->httpRequest->request->get('confPwd')) {
                $this->errors[] = 'Veuillez remplir le champ confirmation du mot de passe ';
            } elseif ($this->httpRequest->request->get('confPwd') != $this->httpRequest->request->get('pwd')) {
                $this->errors[] = 'Les mots de passe ne correspondent pas';
            } else {
                $confpassword = $this->httpRequest->request->get('confPwd');
                $confpassword = (string) $this->antiXss->xss_clean($confpassword);
            }

            if (empty($this->errors)) {
                $pwd = password_hash($pwd, PASSWORD_ARGON2ID);
                $insertUser = $modelUser->insertData($nom, $prenom, $mail, $pwd, $confpassword);
                if ($insertUser) {
                    $url = $this->conf->get('siteUrl');
                    $this->redirect("$url/connexion/");
                    return;
                } else {
                    $this->errors[] = 'Erreur lors de l\'inscription';
                }
            }
        }

        // Puis on affiche la page avec la méthode render
        $render = $template->render([
            'title' => 'Inscription',
            'errors' => $this->errors,
        ]);

        echo $render;
    }

    private function checkSession()
    {
        if ($this->httpSession->get('user')) {
            $this->redirect('/connexion/');
            return;
        }
    }
}
