<?php

namespace Models;

use PDO;

class Users extends Database
{


    /**
     * Charge un utilisateur à partir de son adresse email.
     *
     * @param string $email L'adresse email de l'utilisateur à charger.
     * @return mixed Retourne un tableau associatif contenant les informations de l'utilisateur si trouvé, sinon retourne faux en cas d'erreur ou si l'utilisateur n'existe pas.
     */
    public function loadUserByEmail($email): mixed
    {
        try {
            $stmt = self::getInstance()->getConnexion()->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->bindParam('email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }
    }

    /**
     * Insère un nouvel utilisateur dans la base de données.
     *
     * @param string $nom Le nom de l'utilisateur.
     * @param string $prenom Le prénom de l'utilisateur.
     * @param string $mail L'adresse email de l'utilisateur.
     * @param string $pwd Le mot de passe de l'utilisateur.
     * @return mixed Retourne vrai si l'insertion a réussi, sinon retourne faux en cas d'erreur.
     */
    public function insertData($nom, $prenom, $mail, $pwd): mixed
    {
        try {
            $stmt = self::getInstance()->getConnexion()->prepare('INSERT INTO users (firstname, lastname, email, pwd, createdAt) VALUES (:firstname, :lastname, :email, :pwd , NOW())');
            $stmt->bindValue('firstname', $prenom);
            $stmt->bindValue('lastname', $nom);
            $stmt->bindValue('email', $mail);
            $stmt->bindValue('pwd', $pwd);
            return $stmt->execute();
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }
    }

    /**
     * Met à jour la date de connexion d'un utilisateur dans la base de données.
     *
     * @param string $email L'adresse email de l'utilisateur.
     * @param string $updatedAt La nouvelle date de connexion à enregistrer.
     * @return mixed Retourne vrai si la mise à jour a réussi, sinon retourne faux en cas d'erreur.
     */
    public function updateDateConnexion($email, $updatedAt): mixed
    {
        try {
            $stmt = self::getInstance()->getConnexion()->prepare('UPDATE users SET updatedAt = :updatedAt WHERE email = :email');
            $stmt->bindValue('updatedAt', $updatedAt);
            $stmt->bindValue('email', $email);
            return $stmt->execute();
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }
    }

    /**
     * Vérifie si un utilisateur existe déjà en recherchant son adresse email.
     *
     * @param string $email L'adresse email à vérifier.
     * @return mixed Retourne un tableau associatif contenant l'ID de l'utilisateur s'il existe, sinon retourne faux en cas d'erreur ou si l'utilisateur n'existe pas.
     */
    public function checkUserByEmail($email): mixed
    {
        try {
            $req = self::getInstance()->getConnexion()->prepare("SELECT id FROM users WHERE email = :email ");
            $req->bindValue('email', $email);
            $req->execute();
            $result = $req->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }
    }

    /**
     * Vérifie les informations de connexion d'un utilisateur.
     *
     * @param string $email L'adresse email de l'utilisateur.
     * @param string $pwd Le mot de passe de l'utilisateur.
     * @return mixed Retourne un tableau associatif contenant les informations de l'utilisateur en cas de succès de la vérification, sinon retourne faux en cas d'erreur ou si les informations sont incorrectes.
     */
    public function checkConnexion($email, $pwd): mixed
    {

        try {
            $req = self::getInstance()->getConnexion()->prepare("SELECT * FROM users WHERE email = ? AND pwd = ?");
            $req->bindValue('email', $email);
            $req->bindValue('pwd', $pwd);
            $req->execute();
            $result = $req->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }
    }

    /**
     * Met à jour le mot de passe d'un utilisateur dans la base de données.
     *
     * @param string $mail L'adresse email de l'utilisateur.
     * @param string $newPwd Le nouveau mot de passe à enregistrer.
     * @return mixed Retourne vrai en cas de succès de la mise à jour, sinon retourne faux en cas d'erreur.
     */
    public function updatePwd($mail, $newPwd): mixed
    {
        try {
            $req = self::getInstance()->getConnexion()->prepare("UPDATE users SET pwd = :pwd WHERE email = :email");
            $req->bindValue('pwd', $newPwd);
            $req->bindValue('email', $mail);
            return $req->execute();
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }
    }

    /**
     * Récupère les informations des utilisateurs dans la base de données.
     *
     * @param int|null $id L'identifiant de l'utilisateur à récupérer (facultatif).
     * @return mixed Retourne un tableau associatif des informations des utilisateurs ou un utilisateur spécifique si l'identifiant est fourni.
     */
    public function getUsers($id = null): mixed
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
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }
    }

    /**
     * Met à jour le rôle d'un utilisateur dans la base de données.
     *
     * @param string $role Le nouveau rôle de l'utilisateur.
     * @param int $id L'identifiant de l'utilisateur à mettre à jour.
     * @return mixed Retourne true en cas de succès ou false en cas d'erreur.
     */
    public function updateRole($role, $id): mixed
    {
        try {
            $req = self::getInstance()->getConnexion()->prepare("UPDATE users SET role = :role WHERE id = :id");
            $req->bindValue('role', $role);
            $req->bindValue('id', $id);
            return $req->execute();
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }
    }

    /**
     * Met à jour le token de réinitialisation de mot de passe et sa date d'expiration dans la base de données.
     *
     * @param string $token Le nouveau token.
     * @param string $expireAt La date d'expiration du token.
     * @param string $email L'adresse email de l'utilisateur.
     * @return mixed Retourne true en cas de succès ou false en cas d'erreur.
     */
    public function forgotpwd($token, $expireAt, $email): mixed
    {
        try {

            $req = self::getInstance()->getConnexion()->prepare("UPDATE users SET token = :token, expireAt = :expireAt WHERE email = :email");
            $req->bindValue('token', $token);
            $req->bindValue('expireAt', $expireAt);
            $req->bindValue('email', $email);
            return $req->execute();
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }
    }

    /**
     * Vérifie l'existence d'un token de réinitialisation de mot de passe dans la base de données.
     *
     * @param string $token Le token à vérifier.
     * @return mixed Retourne un tableau associatif contenant les informations du token s'il existe, ou false s'il n'existe pas ou en cas d'erreur.
     */
    public function checkToken($token): mixed
    {
        try {

            $req = self::getInstance()->getConnexion()->prepare("SELECT email, token, expireAt FROM users WHERE token = :token");
            $req->bindValue('token', $token);
            $req->execute();
            $result = $req->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }
    }
}
