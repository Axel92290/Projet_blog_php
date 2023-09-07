<?php

namespace Controllers;

use Models\Users;

class ResetPwdController extends BaseController
{

    
    /**
     * Gère la réinitialisation du mot de passe.
     *
     * Cette méthode vérifie le token et le formulaire de soumission,
     * puis affiche la page de réinitialisation du mot de passe.
     *
     * @param string $token
     * @return void
     */

     public function resetpwd($token)
     {
         // Chargement du template
         $template = $this->twig->load('resetpwd/resetpwd.html');
     
         // Vérification du formulaire de réinitialisation du mot de passe
         $this->checkFormSubmit($token);
     
         // Rendu du template
         $render = $template->render([
             'title'  => 'Réinitialisation du mot de passe',
             'errors' => $this->errors
         ]);
     
         print_r($render);
     }
     
     private function checkFormSubmit($token)
     {
         // Vérification du jeton CSRF
         $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;
     
         // Instance du modèle utilisateur
         $modelUser = new Users();
     
         // Recherche du jeton dans la base de données
         $tokenFound = $modelUser->checkToken($token);
         $mail = $tokenFound['email'];
     
         // Vérification si le jeton n'existe pas
         if (empty($tokenFound)) {
             $this->errors[] = 'Ce token n\'existe pas';
             $this->redirect('/error/');
             return;
         }
     
         // Vérification si le jeton a expiré
         if ($tokenFound['expireAt'] < date('Y-m-d H:i:s')) {
             $this->errors[] = 'Ce token a expiré';
             $this->redirect('/error/');
             return;
         }
     
         // Vérification de la méthode HTTP (POST) et du jeton CSRF
         if (!$this->isPostRequest() || !$csrf->validateRequest()) {
             return;
         }
     
         // Traitement de la réinitialisation du mot de passe
         $this->handlePasswordReset($mail);
     }
     
     private function isPostRequest()
     {
         // Vérification de la méthode HTTP (POST)
         return $this->httpRequest->isMethod('POST');
     }
     
     private function handlePasswordReset($mail)
     {
         // Vérification du champ "newPwd"
         if (!$this->httpRequest->request->get('newPwd')) {
             $this->errors[] = 'Veuillez remplir le champ mot de passe ';
         } else if ($this->httpRequest->request->get('confNewPwd') === FALSE) {
             $this->errors[] = 'Veuillez remplir le champ confirmation du mot de passe ';
         } else if ($this->httpRequest->request->get('confNewPwd') != $this->httpRequest->request->get('newPwd')) {
             $this->errors[] = 'Les mots de passe ne correspondent pas';
         } else {
             // Vérification si l'email existe dans la base de données
             $mailFound = $this->checkUserByEmail($mail);
     
             if (empty($mailFound)) {
                 $this->errors[] = 'Cet email n\'existe pas';
             } else {
                 // Récupération et hachage du nouveau mot de passe
                 $newPwd = $this->cleanXSS($this->httpRequest->request->get('newPwd'));
                 $newPwd = password_hash($newPwd, PASSWORD_ARGON2ID);
     
                 // Mise à jour du mot de passe dans la base de données
                 $updatePwd = $this->updatePwd($mail, $newPwd);
     
                 if ($updatePwd === TRUE) {
                     $this->successes[] = 'Votre mot de passe a bien été modifié';
                     $this->redirect('/connexion/');
                 }
             }
         }
     }
     
     private function checkUserByEmail($email)
     {
         // Recherche de l'utilisateur par email dans la base de données
         $modelUser = new Users();
         return $modelUser->checkUserByEmail($email);
     }
     
     private function updatePwd($email, $newPwd)
     {
         // Mise à jour du mot de passe dans la base de données
         $modelUser = new Users();
         return $modelUser->updatePwd($email, $newPwd);
     }
}
