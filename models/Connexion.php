<?php

class Connexion extends Database
{


    public function checkConnexion($mail, $pword)
    {

        $req = $this->connexion->prepare("SELECT * FROM utilisateur WHERE mail = ? AND pword = ?");
        $req->execute(array($mail, $pword));
        $result = $req->fetch();
        return $result;
    }
}
