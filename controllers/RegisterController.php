<?php
namespace Controllers;

use Models\Users;

class RegisterController extends BaseController
{
    private string $errors = '';


    public function register()
    {
        // on choisi la template à appeler
        $template = $this->twig->load('register/register.html');

        // Puis on affiche la page avec la méthode render
        echo $template->render([
            'title' => 'Inscription',
            'error' => $this->errors,
        ]);


        // if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['mail']) && isset($_POST['pwd']) && isset($_POST['confPwd'])) {
        if (isset($_REQUEST['inscription'])) {

            if(empty($_POST['nom'])){
                $this->errors = 'Veuillez remplir le champ nom';
            }else{
                $nom = $_POST['nom'];
                $nom = (string) ucfirst(trim($nom));
            }

            if(empty($_POST['prenom'])){
                $this->errors = 'Veuillez remplir le champ prenom';
            }else{
                $prenom = $_POST['prenom'];
                $prenom = (string) ucfirst(trim($prenom));
            }

            if(empty($_POST['mail'])){
                $this->errors = 'Veuillez remplir le champ email ';
            }elseif(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
                $this->errors = 'Veuillez entrer un email valide';
            }elseif(!empty($_POST['mail'])) {
                $modelUser = new Users();
                $mailFound = $modelUser->checkUserByEmail($_POST['mail'] );
                if (!empty($mailFound)) {
                    $this->errors = 'Cet email est déjà utilisé';
                }else{
                    $mail = $_POST['mail'];
                    $mail = (string) trim($mail);
                }
            }


            if(empty($_POST['pwd'])){
                $this->errors = 'Veuillez remplir le champ mot de passe ';
            }else{
                $pwd = $_POST['pwd'];
                $pwd = (string) trim($pwd);
                
            }

            if(empty($_POST['confPwd'])){
                $this->errors = 'Veuillez remplir le champ confirmation du mot de passe ';
            }elseif($_POST['confPwd'] != $_POST['pwd']) {
                $this->errors = 'Les mots de passe ne correspondent pas';
            }else{
                $confpassword = $_POST['confPwd'];
                $confpassword = (string) trim($confpassword);
                
            }            
            
            $createdAt = date('Y-m-d H:i:s');
            $UpdateAt = date('Y-m-d H:i:s');
            $pwd = password_hash($pwd, PASSWORD_ARGON2ID);


            $modelUser = new Users();
            $insertUser = $modelUser->insertData($nom, $prenom, $mail, $pwd, $confpassword, $createdAt, $UpdateAt);

            if ($insertUser) {
                header('Location: /connexion');
                exit;
            } else {
                $this->errors = 'Erreur lors de l\'inscription';
            }




        }else{
            $this->errors = 'Veuillez remplir tous les champs';
        }
    }



}