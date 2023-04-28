<?php
namespace Controllers;

use Models\Users;

class RegisterController extends BaseController
{
    private string $errors = '';


    public function index()
    {
        // on choisi la template à appeler
        $template = $this->twig->load('inscription/inscription.html');

        // Puis on affiche la page avec la méthode render
        echo $template->render([
            'title' => 'Inscription',
            'error' => $this->errors,
        ]);


        // if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['mail']) && isset($_POST['pwd']) && isset($_POST['confPwd'])) {
        if (isset($_REQUEST['inscription'])) {

            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $mail = $_POST['mail'];
            $pwd = $_POST['pwd'];
            $confpassword = $_POST['confPwd'];

            $this->verifInscription($nom, $prenom, $mail, $pwd, $confpassword);
        }
    }


    private function verifInscription($nom, $prenom, $mail, $pwd, $confpassword)
    {



        // Variables d'entrées
        $nom = (string) ucfirst(trim($nom));
        $prenom = (string) ucfirst(trim($prenom));
        $mail = (string) trim($mail);
        $pwd = (string) trim($pwd);
        $confpassword  = (string) trim($confpassword);

        // Variables déclarées
        $this->errNom = (string) '';
        $this->errPrenom = (string) '';
        $this->errMail = (string) '';
        $this->errPwd = (string) '';
        $this->valid = (bool) true;

        if ($this->valid) {

            $cryptPwd = password_hash($pwd, PASSWORD_ARGON2ID);
            $inscription = new Users();
            $inscription->insertData($mail, $cryptPwd, $nom, $prenom);

            header('Location: /');
            exit;
        }
        return [$this->errNom, $this->errPrenom, $this->errMail, $this->errPwd];
    }
}