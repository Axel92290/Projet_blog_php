<?php
namespace Models;

use PDO;

class Connexion extends Database
{


    public function checkConnexion($mail, $pword)
    {

        $req = $this->connexion->prepare("SELECT * FROM utilisateur WHERE mail = ? AND pword = ?");
        $req->execute(array($mail, $pword));
        $result = $req->fetch(PDO::FETCH_CLASS);
        return $result;
    }
}
