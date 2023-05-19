<?php
namespace Models;

use PDO;

class Users extends Database
{
    /**
     * @param $email
     * @return mixed
     */
    public function loadUserByEmail($email): mixed
    {

        $stmt = $this->connexion->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(array('email' => $email));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param $email
     * @param $pwd
     * @return bool
     */
    public function insertData($nom, $prenom, $mail, $pwd): bool
    {
        
        $stmt = $this->connexion->prepare('INSERT INTO users (firstname, lastname, email, pwd, createdAt) VALUES (:firstname, :lastname, :email, :pwd , NOW())');
        $stmt->bindValue('firstname', $prenom);
        $stmt->bindValue('lastname', $nom);
        $stmt->bindValue('email', $mail);
        $stmt->bindValue('pwd', $pwd);
        return $stmt->execute();
    }

    public function updateDateConnexion($mail, $updatedAt) : mixed
    {

        $req = $this->connexion->prepare("UPDATE users SET updatedAt = :updatedAt WHERE email = :email");
        $req->bindValue('updatedAt', $updatedAt);
        $req->bindValue('email', $mail);
        return $req->execute();
    }

    public function checkUserByEmail($mail) : mixed
    {

        $req = $this->connexion->prepare("SELECT id FROM users WHERE email = :email");
        $req->bindValue('email', $mail);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
        
        return $result;
    }

    public function checkConnexion($mail, $pwd) : mixed
    {

        $req = $this->connexion->prepare("SELECT * FROM users WHERE email = :email AND pword = :pword");

        $req->bindValue('email', $mail);
        $req->bindValue('pwd', $pwd);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_CLASS);
        return $result;
    }
}