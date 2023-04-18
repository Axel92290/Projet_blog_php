<?php

class InscriptionController extends BaseController
{
    public function inscription()
    {

        // on choisi la template à appeler
        $template = $this->twig->load('inscription/inscription.html');

        // $post = new Post();
        // $listPost = $post->getPosts();


        // Puis on affiche la page avec la méthode render
        echo $template->render(['title' => 'Inscription']);
    }

    private $err_nom;
    private $err_prenom;
    private $err_mail;
    private $err_pword;
    private $valid;


    public function verif_registration($nom, $prenom, $mail, $confmail, $pword, $confpassword)
    {

        global $DB;

        // Variables d'entrées
        $nom = (string) ucfirst(trim($nom));
        $prenom = (string) ucfirst(trim($prenom));
        $mail = (string) trim($mail);
        $confmail  = (string) trim($confmail);
        $pword = (string) trim($pword);
        $confpassword  = (string) trim($confpassword);

        // Variables déclarées
        $this->err_nom = (string) '';
        $this->err_prenom = (string) '';
        $this->err_mail = (string) '';
        $this->err_pword = (string) '';
        $this->valid = (bool) true;

        $this->verif_nom($nom);

        $this->verif_prenom($prenom);

        $this->verif_mail($mail, $confmail);

        $this->verif_pword($pword, $confpassword);




        if ($this->valid) {

            $crypt_pword = password_hash($pword, PASSWORD_ARGON2ID);
            $date_creation = date('Y-m-d H:i:s');
            $date_connexion = date('Y-m-d H:i:s');


            $req = $DB->prepare("INSERT INTO utilisateur(nom, prenom, mail, pword, date_creation, date_connexion) VALUES (?, ?, ?, ?, ?, ?)");
            $req->execute(array($nom, $prenom, $mail, $crypt_pword, $date_creation, $date_connexion));

            header('Location: connexion.php');
            exit;
        }
        return [$this->err_nom, $this->err_prenom, $this->err_mail, $this->err_pword];
    }

    private function verif_nom($nom)
    {

        global $DB;

        if (empty($nom)) {
            $this->valid = false;
            $this->err_nom = "Ce champ ne peut pas être vide";
        }

        return [$this->err_nom];
    }


    private function verif_prenom($prenom)
    {

        global $DB;

        if (empty($prenom)) {
            $this->valid = false;
            $this->err_prenom = "Ce champ ne peut pas être vide";
        }
    }


    private function verif_mail($mail, $confmail)
    {

        global $DB;

        if (empty($mail)) {
            $this->valid = false;
            $this->err_mail = "Ce champ ne peut pas être vide";
        } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $this->valid = false;
            $this->err_mail = "Le format du mail est invalide.";
        } elseif ($mail <> $confmail) {
            $this->valid = false;
            $this->err_mail = "Le mail est différent de la confirmation.";
        } else {
            $req = $DB->prepare("SELECT id FROM utilisateur WHERE mail = ?");

            $req->execute(array($mail));

            $req = $req->fetch();

            if (isset($req['id'])) {
                $this->valid = false;
                $this->err_mail = "Ce mail est déjà utilisé";
            }
        }
    }


    private function verif_pword($pword, $confpassword)
    {
        global $DB;

        if (empty($pword)) {
            $this->valid  = false;
            $this->err_pword = "Ce champ ne peut pas être vide";
        } elseif ($pword <> $confpassword) {
            $this->valid = false;
            $this->err_pword = "Le mot de passe est différent de la confirmation";
        }
    }
}