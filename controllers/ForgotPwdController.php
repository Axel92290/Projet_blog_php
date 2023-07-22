<?php

namespace Controllers;

use Models\Users;
use Tools\Config;
use PHPMailer\PHPMailer\PHPMailer;


class ForgotPwdController extends BaseController
{


    public function forgotpwd()
    {

        
        $this->checkFormSubmit();
        $template = $this->twig->load('forgotpwd/forgotpwd.html');
        echo $template->render([
            'title' => 'Mot de passe oublié',
            'errors' => $this->errors,
            'successes' => $this->successes


        ]);

    }

    private function checkFormSubmit()
    {

        $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;
        if ($this->httpRequest->isMethod('POST') && $csrf->validateRequest()) {
            if (!$this->httpRequest->request->get('mail')) {
                $this->errors[] = 'Veuillez remplir le champ email ';
            } elseif (filter_var(!$this->httpRequest->request->get('mail'), FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = 'Veuillez entrer un email valide';
            } else{
                $conf = new Config();  
    
                $mail = $this->cleanXSS($this->httpRequest->request->get('mail'));

    
                // Sujet
                $subject = 'Réinitialisation de votre mot de passe';
    
                // message
                $message = '
                            <html>
                            <body>
                            <p>Bonjour,

                            Vous avez fait une demande de réinitialisation de mot de passe.
                            Voici le lien vous permettant de réinitialiser votre mot de passe :
                            
                            '. $this->conf->get('siteUrl') .'/resetpwd/
                            
                            Cordialement,
                            l\'Equipe du blog
                            </p>
                            </body>
                            </html>
                            ';
    

                            try{
                                ini_set(
                                    'SMTP',
                                    'localhost'
                                );
                                ini_set('smtp_port', $this->conf->get('port.mailhog'));
                        
                                $to = $mail ;
                                $subject = $subject;
                                $message = $message;
                                $headers = 'From: ' . $this->conf->get('admin.mailhog');
                        
                                // Envoi de l'e-mail
                                if (mail($to, $subject, $message,
                                    $headers
                                )) {
                                    $this->successes[] = 'Email envoyé avec succès. ';

                                } else {
                                    $this->errors[] = 'Erreur lors de l\'envoi de l\'email';
                                }
                            } catch(\Exception $e){
                                echo $e->getMessage();
                            }
            }
        }
    }
}
