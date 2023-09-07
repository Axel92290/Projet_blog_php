<?php

namespace Controllers;

use Models\Users;
use Tools\Config;



class ForgotPwdController extends BaseController
{


    public function forgotpwd()
    {

        
        $this->checkFormSubmit();

        $template = $this->twig->load('forgotpwd/forgotpwd.html');


        $render = $template->render([
            'title' => 'Mot de passe oublié',
            'errors' => $this->errors,
            'successes' => $this->successes


        ]);

        print_r($render);

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
                $modelUser = new Users();
    
                $mail = $this->cleanXSS($this->httpRequest->request->get('mail'));
                $token = md5(time().uniqid());
                $expireAt = date('Y-m-d H:i:s', strtotime('+2 day'));
                $forgotpwd = $modelUser->forgotpwd($token, $expireAt, $mail);
                if(!$forgotpwd){
                    $this->errors[] = 'Erreur lors de la réinitialisation du mot de passe';
                }else{
                    $lien = $this->conf->get('siteUrl') .'/resetpwd/' . $token;
                }
                
    
                // Sujet
                $subject = 'Réinitialisation de votre mot de passe';
    
                // message
                $message = 'Bonjour,

                            
                            Voici le lien vous permettant de réinitialiser votre mot de passe :
                            
                            '. $lien .'
                            
                            Cordialement,
                            l\'Equipe du blog
                            ';
    


                            try{
                        
                                $to = $mail ;
                                $subject = $subject;
                                $message = $message;
                                $headers = 'From: ' . $conf->get('admin.mailhog');
                        
                                // Envoi de l'e-mail
                                if (mail($to, $subject, $message,
                                    $headers
                                )) {
                                    $this->successes[] = 'Email envoyé avec succès. ';

                                } else {
                                    $this->errors[] = 'Erreur lors de l\'envoi de l\'email';
                                }
                            } catch(\Exception $e){
                                $errorMessage = $e->getMessage();
                                $this->errors[] = $errorMessage;
                            }
            }
        }
    }
}
