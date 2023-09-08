<?php

namespace Controllers;

use Models\Users;
use Tools\Config;

class ForgotPwdController extends BaseController
{


    /**
     * Affiche la page de réinitialisation du mot de passe.
     *
     * Cette fonction affiche la page de réinitialisation du mot de passe.
     * Elle permet à un utilisateur de faire une demande de réinitialisation du mot de passe.
     * @return void
     * 
     */
    public function forgotpwd()
    {
        // Vérifie si le formulaire a été soumis.
        $this->checkFormSubmit();

        // Charge la template
        $template = $this->twig->load('forgotpwd/forgotpwd.html');

        // Affiche la page avec la méthode render.
        $render = $template->render([
                    'title' => 'Mot de passe oublié',
                    'errors' => $this->errors,
                    'successes' => $this->successes,
                  ]);

        print_r($render);
        
    } // End forgotpwd().
    

    /**
     * Vérifie et traite la soumission du formulaire de réinitialisation de mot de passe.
     *
     * Cette fonction vérifie la méthode de soumission, valide le jeton CSRF, et traite la réinitialisation
     * du mot de passe si les conditions sont remplies, en envoyant un e-mail avec un lien de réinitialisation.
     * Elle gère également les erreurs et les succès de l'opération.
     *
     * @return void
     */
    private function checkFormSubmit()
    {
        // Vérifie le jeton CSRF.
        $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;

        if ($this->httpRequest->isMethod('POST') && $csrf->validateRequest()) {
            if (!$this->httpRequest->request->get('mail')) {
                $this->errors[] = 'Veuillez remplir le champ email';
            } elseif (!filter_var($this->httpRequest->request->get('mail'), FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = 'Veuillez entrer un email valide';
            } else {
                $conf = new Config();
                $modelUser = new Users();

                $mail = $this->cleanXSS($this->httpRequest->request->get('mail'));
                $token = md5(time() . uniqid());
                $expireAt = date('Y-m-d H:i:s', strtotime('+2 day'));

                // Demande de réinitialisation du mot de passe.
                $forgotpwd = $modelUser->forgotpwd($token, $expireAt, $mail);

                if (!$forgotpwd) {
                    $this->errors[] = 'Erreur lors de la réinitialisation du mot de passe';
                } else {
                    $lien = $conf->get('siteUrl') . '/resetpwd/' . $token;
                }

                // Sujet de l'email.
                $subject = 'Réinitialisation de votre mot de passe';

                // Corps de l'email.
                $message = 'Bonjour,

                            Voici le lien vous permettant de réinitialiser votre mot de passe :
                            ' . $lien . '

                            Cordialement,
                            l\'Equipe du blog';

                try {
                    // Envoi de l'e-mail.
                    $to = $mail;
                    $subject = $subject;
                    $message = $message;
                    $headers = 'From: ' . $conf->get('admin.mailhog');

                    if (mail($to, $subject, $message, $headers)) {
                        $this->successes[] = 'Email envoyé avec succès.';
                    } else {
                        $this->errors[] = 'Erreur lors de l\'envoi de l\'email';
                    }
                } catch (\Exception $e) {
                    $errorMessage = $e->getMessage();
                    $this->errors[] = $errorMessage;
                }
            }
        }
        
    } // End checkFormSubmit().
}
