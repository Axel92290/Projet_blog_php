<?php

class Inscription extends Database
{

    public function insertData($nom, $prenom, $mail, $cryptPwd, $dateCreation, $dateConnexion)
    {

        $req = $this->connexion->prepare("INSERT INTO utilisateur(nom, prenom, mail, pword, dateCreation, dateConnexion) VALUES (?, ?, ?, ?, ?, ?)");
        $req->execute(array($nom, $prenom, $mail, $cryptPwd, $dateCreation, $dateConnexion));
    }

    public function verifMail($mail)
    {

        $req = $this->connexion->prepare("SELECT id FROM utilisateur WHERE mail = ?");
        $req->execute(array($mail));
        $result = $req->fetch();
        return $result;
    }
}