<?php

namespace Controllers;

use Models\Users;

class ForgotPwdController extends BaseController
{
    private array $errors = [];

    public function forgotpwd(){

         $template = $this->twig->load('forgotpwd/forgotpwd.html');

            $this->checkFormSubmit();

         echo $template->render([
            'title' => 'Mot de passe oublié',
            'errors' => $this->errors

        ]);

    }

    private function checkFormSubmit(){
            
            if (!empty($_POST)) {
    
                if(empty($_POST['mail'])){
                    $this->errors[] = 'Veuillez remplir le champ email ';
                }elseif(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
                    $this->errors[] = 'Veuillez entrer un email valide';
                }elseif(empty($_POST['newPwd'])){
                    $this->errors[] = 'Veuillez remplir le champ mot de passe ';
                }elseif(empty($_POST['confNewPwd'])){
                    $this->errors[] = 'Veuillez remplir le champ confirmation du mot de passe ';
                }elseif($_POST['confNewPwd'] != $_POST['newPwd']) {
                    $this->errors[] = 'Les mots de passe ne correspondent pas';
                }elseif(!empty($_POST['mail'])) {
                    $modelUser = new Users();
                    $mailFound = $modelUser->checkUserByEmail($_POST['mail'] );
                    if (empty($mailFound)) {
                        $this->errors[] = 'Cet email n\'existe pas';
                    }else{
                        $mail = (string) trim($_POST['mail']);
                        $newPwd = (string) trim($_POST['newPwd']);
                        $newPwd = password_hash($newPwd, PASSWORD_ARGON2ID);
                        $udpatePwd = $modelUser->updatePwd($mail, $newPwd);
                        if($udpatePwd){
                            header('Location: /connexion/');
                        }
                    }
                }
    
            }

    }

}