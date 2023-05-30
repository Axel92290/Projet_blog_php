<?php
namespace Controllers;

use Models\Users;

class RegisterController extends BaseController
{
    private array $errors = [];


    public function register()
    {

        $this->checkSession();
        // on choisi la template à appeler
        $template = $this->twig->load('register/register.html');



        if (!empty($_POST)) {

            if(empty($_POST['nom'])){
                $this->errors[] = 'Veuillez remplir le champ nom';
            }else{
                $nom = $_POST['nom']; 
                $nom = (string) ucfirst(trim($nom)); 
            }

            if(empty($_POST['prenom'])){
                $this->errors[] = 'Veuillez remplir le champ prenom';
            }else{
                $prenom = $_POST['prenom'];
                $prenom = (string) ucfirst(trim($prenom));
            }

            if(empty($_POST['mail'])){
                $this->errors[] = 'Veuillez remplir le champ email ';
            }elseif(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = 'Veuillez entrer un email valide';
            }elseif(!empty($_POST['mail'])) {
                $modelUser = new Users();
         
                $mailFound = $modelUser->checkUserByEmail($_POST['mail'] );
                if (!empty($mailFound)) {
                    $this->errors[] = 'Cet email est déjà utilisé';
                }else{
                    $mail = (string) lcfirst(trim($_POST['mail']));
                }
            }


            if(empty($_POST['pwd'])){
                $this->errors[] = 'Veuillez remplir le champ mot de passe ';
            }else{
                $pwd = $_POST['pwd'];
                $pwd = (string) trim($pwd);
                
            }

            if(empty($_POST['confPwd'])){
                $this->errors[] = 'Veuillez remplir le champ confirmation du mot de passe ';
            }elseif($_POST['confPwd'] != $_POST['pwd']) {
                $this->errors[] = 'Les mots de passe ne correspondent pas';
            }else{
                $confpassword = $_POST['confPwd'];
                $confpassword = (string) trim($confpassword);
                
            }            

            if(empty($this->errors)){
                $pwd = password_hash($pwd, PASSWORD_ARGON2ID);
                $insertUser = $modelUser->insertData($nom, $prenom, $mail, $pwd, $confpassword);
                if ($insertUser) {
                    $url = $this->conf->get('siteUrl');
                    header("Location: $url/connexion/");
                    exit;
                } else {
                    $this->errors[] = 'Erreur lors de l\'inscription';
                } 
            }
        }
        
        
        // Puis on affiche la page avec la méthode render
        echo $template->render([
            'title' => 'Inscription',
            'errors' => $this->errors,
        ]);

        
    }

    private function checkSession()
    {
        if (isset($_SESSION['user'])) {
            header('Location: /connexion/');
            exit;
        }
    }

    


}