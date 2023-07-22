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
        try {
            // var_dump(self::getInstance());
            // die;
            $stmt = self::getInstance()->getConnexion()->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->bindParam('email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            die;
        }
    }

    /**
     * @param $email
     * @param $pwd
     * @return bool
     */

    public function insertData($nom, $prenom, $mail, $pwd): mixed
    {
        try{
        $stmt = self::getInstance()->getConnexion()->prepare('INSERT INTO users (firstname, lastname, email, pwd, createdAt) VALUES (:firstname, :lastname, :email, :pwd , NOW())');
        $stmt->bindValue('firstname', $prenom);
        $stmt->bindValue('lastname', $nom);
        $stmt->bindValue('email', $mail);
        $stmt->bindValue('pwd', $pwd);
        return $stmt->execute();

        }catch (\PDOException $e){
            echo $e->getMessage();
            die;
        }

    }

    public function updateDateConnexion($email, $updatedAt) : mixed
    {
        try {
            $stmt = self::getInstance()->getConnexion()->prepare('UPDATE users SET updatedAt = :updatedAt WHERE email = :email');
            $stmt->bindValue('updatedAt', $updatedAt);
            $stmt->bindValue('email', $email);
            return $stmt->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            die;
        }
        
    }


    public function checkUserByEmail($email) : mixed
    {      
        try {    
            $req = self::getInstance()->getConnexion()->prepare("SELECT id FROM users WHERE email = :email ");
            $req->bindValue('email', $email);
            $req->execute();
            $result = $req->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            die;
        }

    }

    public function checkConnexion($email, $pwd) : mixed
    {

        try {
            $req = self::getInstance()->getConnexion()->prepare("SELECT * FROM users WHERE email = ? AND pwd = ?");
            $req->bindValue('email', $email);
            $req->bindValue('pwd', $pwd);
            $req->execute();
            $result = $req->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            die;
        }
    }

    public function updatePwd($mail, $newPwd) :mixed
    {
        try{
            $req = self::getInstance()->getConnexion()->prepare("UPDATE users SET pwd = :pwd WHERE email = :email");
            $req->bindValue('pwd', $newPwd);
            $req->bindValue('email', $mail);
            return $req->execute();
        }catch(\PDOException $e){
            echo $e->getMessage();
            die;
        }
    }

    public function updateProfile($facebook, $twitter, $instagram, $linkedin, $github, $catchPhrase, $cv, $id) :mixed 
    {
        try{

            $req = self::getInstance()->getConnexion()->prepare("UPDATE users SET facebook = :facebook, twitter = :twitter, instagram = :instagram, linkedin = :linkedin, github = :github, catchPhrase = :catchPhrase, cv = :cv WHERE id = :id");
            $req->bindValue('facebook', $facebook);
            $req->bindValue('twitter', $twitter);
            $req->bindValue('instagram', $instagram);
            $req->bindValue('linkedin', $linkedin);
            $req->bindValue('github', $github);
            $req->bindValue('catchPhrase', $catchPhrase);
            $req->bindValue('cv', $cv);
            $req->bindValue('id', $id);
            return $req->execute();

        }catch(\PDOException $e){
            echo $e->getMessage();
            die;
        }

    }
    public function getUsers($id = null)
    {
        try {
            $sql = "SELECT id, firstname, lastname, email, role FROM users";
            if ($id != null) {
                $sql .= " WHERE id = :id";
            }
            $req = self::getInstance()->getConnexion()->prepare($sql);
            if ($id != null) {
                $req->bindValue('id', $id);
            }
            $req->execute();
            $result = $req->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            die;
        }
    }

    public function updateRole($role, $id)
    {
        try{
            $req = self::getInstance()->getConnexion()->prepare("UPDATE users SET role = :role WHERE id = :id");
            $req->bindValue('role', $role);
            $req->bindValue('id', $id);
            return $req->execute();
        }catch(\PDOException $e){
            echo $e->getMessage();
            die;
        }
    }


}