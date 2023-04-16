<?php

class Inscription extends Database
{

    public function insertData($nom, $prenom, $mail, $crypt_pword, $date_creation, $date_connexion)
    {

        $req = $Database->prepare("INSERT INTO utilisateur(nom, prenom, mail, pword, date_creation, date_connexion) VALUES (?, ?, ?, ?, ?, ?)");
        $req->execute(array($nom, $prenom, $mail, $crypt_pword, $date_creation, $date_connexion));
    }
}