<?php

namespace Controllers;

use Models\Users;

class ResetPwdController extends BaseController

{
    protected array $errors = [];

    public function resetpwd($token)
    {

        $template = $this->twig->load('resetpwd/resetpwd.html');



        $this->checkFormSubmit($token);

        echo $template->render([
            'title' => 'Réinitialisation du mot de passe',
            'errors' => $this->errors

        ]);
    }

    private function checkFormSubmit($token)
    {

        $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;
        $modelUser = new Users();
        $tokenFound = $modelUser->checkToken($token);
        $mail = $tokenFound['email'];

        

        if (empty($tokenFound)) {
            $this->errors[] = 'Ce token n\'existe pas';
            $this->redirect('/error/');
        } elseif ($tokenFound['expireAt'] < date('Y-m-d H:i:s')) {
            $this->errors[] = 'Ce token a expiré';
            $this->redirect('/error/');
        } else {

            if ($this->httpRequest->isMethod('POST') && $csrf->validateRequest()) {

                if (!$this->httpRequest->request->get('newPwd')) {
                    $this->errors[] = 'Veuillez remplir le champ mot de passe ';
                } elseif (!$this->httpRequest->request->get('confNewPwd')) {
                    $this->errors[] = 'Veuillez remplir le champ confirmation du mot de passe ';
                } elseif ($this->httpRequest->request->get('confNewPwd') != $this->httpRequest->request->get('newPwd')) {
                    $this->errors[] = 'Les mots de passe ne correspondent pas';
                } else {
                    $mailFound = $modelUser->checkUserByEmail($mail);
                    if (empty($mailFound)) {
                        $this->errors[] = 'Cet email n\'existe pas';
                    } else {
                        $newPwd = $this->cleanXSS($this->httpRequest->request->get('newPwd'));
                        $newPwd = password_hash($newPwd, PASSWORD_ARGON2ID);
                        $udpatePwd = $modelUser->updatePwd($mail, $newPwd);
                        if ($udpatePwd) {
                            $this->successes[] = 'Votre mot de passe a bien été modifié';
                            $this->redirect('/connexion/');
                        }
                    }
                }
            }
        }
    }
}
