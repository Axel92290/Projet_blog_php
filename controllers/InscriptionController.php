<?php

class InscriptionController extends BaseController
{
    public function inscription()
    {
        // on choisi la template à appeler
        $template = $this->twig->load('inscription/inscription.html');

        // Puis on affiche la page avec la méthode render
        echo $template->render([
            'title' => 'Inscription'
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

    private $errNom;
    private $errPrenom;
    private $errMail;
    private $errPwd;
    private $valid;


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

        $this->verifNom($nom);

        $this->verifPrenom($prenom);

        $this->verifMail($mail);

        $this->verifPwd($pwd, $confpassword);




        if ($this->valid) {

            $cryptPwd = password_hash($pwd, PASSWORD_ARGON2ID);
            $dateCreation = date('Y-m-d H:i:s');
            $DateConnexion = date('Y-m-d H:i:s');


            $inscription = new Inscription();
            $inscription->insertData($nom, $prenom, $mail, $cryptPwd, $dateCreation, $DateConnexion);

            header('Location: /connexion/');
            exit;
        }
        return [$this->errNom, $this->errPrenom, $this->errMail, $this->errPwd];
    }

    private function verifNom($nom)
    {


        if (empty($nom)) {
            $this->valid = false;
            $this->errNom = "Ce champ ne peut pas être vide";
        }

        return [$this->errNom];
    }


    private function verifPrenom($prenom)
    {

        if (empty($prenom)) {
            $this->valid = false;
            $this->errPrenom = "Ce champ ne peut pas être vide";
        }
    }


    private function verifMail($mail)
    {

        $inscription = new Inscription();

        if (empty($mail)) {
            $this->valid = false;
            $this->errMail = "Ce champ ne peut pas être vide";
        } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $this->valid = false;
            $this->errMail = "Le format du mail est invalide.";
        } else {

            $result = $inscription->verifMail($mail);

            if (isset($result['id'])) {
                $this->valid = false;
                $this->errMail = "Ce mail est déjà utilisé";
            }
        }
    }


    private function verifPwd($pwd, $confpassword)
    {


        if (empty($pwd)) {
            $this->valid  = false;
            $this->errPwd = "Ce champ ne peut pas être vide";
        } elseif ($pwd <> $confpassword) {
            $this->valid = false;
            $this->errPwd = "Le mot de passe est différent de la confirmation";
        }
    }
}