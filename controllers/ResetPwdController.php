<?php

namespace Controllers;

use Models\Users;

class ResetPwdController extends BaseController
{
    private array $errors = [];

    public function resetpwd()
    {

        $template = $this->twig->load('resetpwd/resetpwd.html');

        $this->checkFormSubmit();

        echo $template->render([
            'title' => 'Réinitialisation du mot de passe',
            'errors' => $this->errors

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
            } elseif (!$this->httpRequest->request->get('newPwd')) {
                $this->errors[] = 'Veuillez remplir le champ mot de passe ';
            } elseif (!$this->httpRequest->request->get('confNewPwd')) {
                $this->errors[] = 'Veuillez remplir le champ confirmation du mot de passe ';
            } elseif ($this->httpRequest->request->get('confNewPwd')!= $this->httpRequest->request->get('newPwd')) {
                $this->errors[] = 'Les mots de passe ne correspondent pas';
            } else{
                $modelUser = new Users();
                $mailFound = $modelUser->checkUserByEmail($this->httpRequest->request->get('mail'));
                if (empty($mailFound)) {
                    $this->errors[] = 'Cet email n\'existe pas';
                } else {
                    $mail = $this->cleanXSS($this->httpRequest->request->get('mail'));
                    $newPwd = $this->cleanXSS($this->httpRequest->request->get('newPwd'));
                    $newPwd = password_hash($newPwd, PASSWORD_ARGON2ID);
                    $udpatePwd = $modelUser->updatePwd($mail, $newPwd);
                    if ($udpatePwd) {
                        header('Location: /connexion/');
                    }
                }
            }
        }
    }
}
