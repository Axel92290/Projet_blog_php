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
    public function insertData($email, $pwd, $firstname='', $lastname=''): bool
    {
        $stmt = $this->connexion->prepare('INSERT INTO users (firstname, lastname, email, pwd) VALUES (:firstname, :lastname, :email, :pwd)');
        $stmt->bindValue('firstname', $firstname);
        $stmt->bindValue('lastname', $lastname);
        $stmt->bindValue('email', $email);
        $stmt->bindValue('pwd', $pwd);

        return $stmt->execute();
    }
}