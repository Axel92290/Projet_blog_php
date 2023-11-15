<?php

namespace Models;

use Tools;
use PDO;
use Models\UserModel;

class Users extends Database
{



    /**
     * loadUserByEmail
     *
     * Charge un utilisateur à partir de son adresse email.
     * @param  mixed $email
     * @return UserModel
     */
    public function loadUserByEmail($email): UserModel
    {
        try {
            $stmt = self::getInstance()->getConnexion()->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->bindParam('email', $email);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);


            // Vérifiez si un utilisateur a été trouvé
            if ($result !== false) {
                $user = new UserModel();
                $user->setId($result['id']);
                $user->setFirstname($result['firstname']);
                $user->setLastname($result['lastname']);
                $user->setEmail($result['email']);
                $user->setPwd($result['pwd']);
                $user->setRole($result['role']);
                $user->setCreatedAt($result['createdAt']);
                $user->setUpdatedAt($result['updatedAt']);
                $user->setToken($result['token']);
                $user->setExpireAt($result['expireAt']);

                return $user; // Retourne les données de l'utilisateur sous forme de tableau
            } else {
                // Lance une exception UserModelNotFound si aucun utilisateur n'est trouvé
                throw new Tools\UserModelNotFound();
            }
        } catch (\PDOException $e) {
            // Gère l'erreur (journalisation, traitement, etc.)
            $errorMessage = $e->getMessage();
            // Gère $errorMessage en conséquence (journalisation, traitement, etc.)
            throw $e; // Re-lance l'exception après avoir géré l'erreur
        }
    } // End loadUserByEmail().


    /**
     * Insère un nouvel utilisateur dans la base de données.
     *
     * @param mixed $nom Le nom de l'utilisateur.
     * @param mixed $prenom Le prénom de l'utilisateur.
     * @param mixed $mail L'adresse email de l'utilisateur.
     * @param mixed $pwd Le mot de passe de l'utilisateur.
     * @return bool Retourne vrai si l'insertion a réussi, sinon retourne faux en cas d'erreur.
     */

    public function insertData($nom, $prenom, $mail, $pwd): bool
    {
        try {
            // Crée une instance de UserModel
            $user = new UserModel();

            // Utilise les setters pour définir les propriétés de l'utilisateur
            $user->setFirstname($prenom);
            $user->setLastname($nom);
            $user->setEmail($mail);
            $user->setPwd($pwd);

            // Prépare la requête d'insertion d'un nouvel utilisateur
            $stmt = self::getInstance()->getConnexion()->prepare('INSERT INTO users (firstname, lastname, email, pwd, createdAt) VALUES (:firstname, :lastname, :email, :pwd , NOW())');

            // Lie les valeurs des paramètres aux getters de l'objet UserModel
            $stmt->bindValue(':firstname', $user->getFirstname());
            $stmt->bindValue(':lastname', $user->getLastname());
            $stmt->bindValue(':email', $user->getEmail());
            $stmt->bindValue(':pwd', $user->getPwd());

            // Exécute la requête
            $stmt->execute();

            return true; // Retourne vrai, car l'insertion a réussi
        } catch (\PDOException $e) {
            // Gère l'erreur PDO (journalisation, traitement, etc.)
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false; // Retourne faux en cas d'erreur
        }
    } // End insertData().


    /**
     * Met à jour la date de connexion d'un utilisateur dans la base de données.
     *
     * @param mixed $email L'adresse email de l'utilisateur.
     * @param mixed $updatedAt La nouvelle date de connexion à enregistrer.
     * @return bool Retourne vrai si la mise à jour a réussi, sinon retourne faux en cas d'erreur.
     */

    public function updateDateConnexion($email, $updatedAt): bool
    {
        try {
            // Crée une instance de UserModel
            $user = new UserModel();

            // Utilise les setters pour définir les propriétés de l'utilisateur
            $user->setEmail($email);
            $user->setUpdatedAt($updatedAt);

            // Prépare la requête de mise à jour de la date de connexion
            $stmt = self::getInstance()->getConnexion()->prepare('UPDATE users SET updatedAt = :updatedAt WHERE email = :email');

            // Lie les valeurs des paramètres aux getters de l'objet UserModel
            $stmt->bindValue(':updatedAt', $user->getUpdatedAt());
            $stmt->bindValue(':email', $user->getEmail());

            // Exécute la requête
            $stmt->execute();

            return true; // Retourne vrai, car la mise à jour a réussi
        } catch (\PDOException $e) {
            // Gère l'erreur PDO (journalisation, traitement, etc.)
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false; // Retourne faux en cas d'erreur
        }
    } // End updateDateConnexion().


    /**
     * Vérifie si un utilisateur existe déjà en recherchant son adresse email.
     *
     * @param mixed $email L'adresse email à vérifier.
     * @return array Retourne un tableau associatif contenant l'ID de l'utilisateur s'il existe, sinon retourne faux en cas d'erreur ou si l'utilisateur n'existe pas.
     */

    public function checkUserByEmail($email): array
    {
        try {
            // Crée une instance de UserModel
            $user = new UserModel();

            // Utilise le setter pour définir la propriété de l'utilisateur
            $user->setEmail($email);

            // Prépare la requête de vérification de l'existence de l'utilisateur par email
            $req = self::getInstance()->getConnexion()->prepare("SELECT id FROM users WHERE email = :email ");

            // Lie la valeur du paramètre au getter de l'objet UserModel
            $req->bindValue('email', $user->getEmail());

            // Exécute la requête
            $req->execute();

            $result = $req->fetch(PDO::FETCH_ASSOC);

            if ($result !== false) {
                return $result; // Retourne les données de l'utilisateur sous forme de tableau
            } else {
                return array(); // Aucun utilisateur trouvé, retourne un tableau vide
            }
        } catch (\PDOException $e) {
            // Gère l'erreur PDO (journalisation, traitement, etc.)
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return array();
        }
    } // End checkUserByEmail().



    /**
     * checkConnexion
     * Vérifie les informations de connexion d'un utilisateur.
     *
     * @param  mixed $email
     * @param  mixed $pwd
     * @return array
     */
    public function checkConnexion($email, $pwd): array
    {
        try {
            // Crée une instance de UserModel
            $user = new UserModel();

            // Utilise les setters pour définir les propriétés de l'utilisateur
            $user->setEmail($email);
            $user->setPwd($pwd);

            // Prépare la requête de vérification des informations de connexion
            $req = self::getInstance()->getConnexion()->prepare("SELECT * FROM users WHERE email = :email AND pwd = :pwd");

            // Lie les valeurs des paramètres aux getters de l'objet UserModel
            $req->bindValue('email', $user->getEmail());
            $req->bindValue('pwd', $user->getPwd());

            // Exécute la requête
            $req->execute();

            $result = $req->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            // Gère l'erreur PDO (journalisation, traitement, etc.)
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return array();
        }
    } // End checkConnexion().


    /**
     * Met à jour le mot de passe d'un utilisateur dans la base de données.
     *
     * @param mixed $mail L'adresse email de l'utilisateur.
     * @param mixed $newPwd Le nouveau mot de passe à enregistrer.
     * @return bool Retourne vrai en cas de succès de la mise à jour, sinon retourne faux en cas d'erreur.
     */
    public function updatePwd($mail, $newPwd): bool
    {
        try {
            // Crée une instance de UserModel
            $user = new UserModel();

            // Utilise les setters pour définir les propriétés de l'utilisateur
            $user->setEmail($mail);
            $user->setPwd($newPwd);

            // Prépare la requête de mise à jour du mot de passe
            $req = self::getInstance()->getConnexion()->prepare("UPDATE users SET pwd = :pwd WHERE email = :email");

            // Lie les valeurs des paramètres aux getters de l'objet UserModel
            $req->bindValue('pwd', $user->getPwd());
            $req->bindValue('email', $user->getEmail());

            // Exécute la requête
            $req->execute();

            return true;
        } catch (\PDOException $e) {
            // Gère l'erreur PDO (journalisation, traitement, etc.)
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }
    } // End updatePwd().

    /**
     * Récupère les informations des utilisateurs dans la base de données.
     *
     * @param int|null $id L'identifiant de l'utilisateur à récupérer (facultatif).
     * @return array Retourne un tableau associatif des informations des utilisateurs ou un utilisateur spécifique si l'identifiant est fourni.
     */
    public function getUsers($id = null): array
    {
        try {
            // Crée une instance de UserModel
            $user = new UserModel();

            // Utilise les setters pour définir les propriétés de l'utilisateur
            $user->setId($id);

            // Prépare la requête de récupération des utilisateurs
            $sql = "SELECT id, firstname, lastname, email, role FROM users";
            if ($user->getId() != null) {
                $sql .= " WHERE id = :id";
            }
            $req = self::getInstance()->getConnexion()->prepare($sql);
            if ($user->getId() != null) {
                $req->bindValue('id', $user->getId());
            }

            // Exécute la requête
            $req->execute();

            // Récupère les résultats sous forme de tableau associatif
            $result = $req->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (\PDOException $e) {
            // Gère l'erreur PDO (journalisation, traitement, etc.)
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return array();
        }
    } // End getUsers().

    /**
     * Met à jour le rôle d'un utilisateur dans la base de données.
     *
     * @param mixed $role Le nouveau rôle de l'utilisateur.
     * @param int $id L'identifiant de l'utilisateur à mettre à jour.
     * @return bool Retourne true en cas de succès ou false en cas d'erreur.
     */
    public function updateRole($role, $id): bool
    {
        try {
            // Crée une instance de UserModel
            $user = new UserModel();

            // Utilise les setters pour définir les propriétés de l'utilisateur
            $user->setRole($role);
            $user->setId($id);

            // Prépare la requête de mise à jour du rôle de l'utilisateur
            $req = self::getInstance()->getConnexion()->prepare("UPDATE users SET role = :role WHERE id = :id");
            $req->bindValue('role', $user->getRole());
            $req->bindValue('id', $user->getId());

            // Exécute la requête
            $req->execute();

            return true;
        } catch (\PDOException $e) {
            // Gère l'erreur PDO (journalisation, traitement, etc.)
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }
    } // End updateRole().

    /**
     * Met à jour le token de réinitialisation de mot de passe et sa date d'expiration dans la base de données.
     *
     * @param mixed $token Le nouveau token.
     * @param mixed $expireAt La date d'expiration du token.
     * @param mixed $email L'adresse email de l'utilisateur.
     * @return bool Retourne true en cas de succès ou false en cas d'erreur.
     */

    public function forgotpwd($token, $expireAt, $email): bool
    {
        try {
            // Crée une instance de UserModel
            $user = new UserModel();

            // Utilise les setters pour définir les propriétés de l'utilisateur
            $user->setToken($token);
            $user->setExpireAt($expireAt);
            $user->setEmail($email);

            // Prépare la requête de mise à jour du token et de l'expiration du mot de passe
            $req = self::getInstance()->getConnexion()->prepare("UPDATE users SET token = :token, expireAt = :expireAt WHERE email = :email");
            $req->bindValue('token', $user->getToken());
            $req->bindValue('expireAt', $user->getExpireAt());
            $req->bindValue('email', $user->getEmail());

            // Exécute la requête
            $req->execute();

            return true;
        } catch (\PDOException $e) {
            // Gère l'erreur PDO (journalisation, traitement, etc.)
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }
    } // End forgotpwd().

    /**
     * Vérifie l'existence d'un token de réinitialisation de mot de passe dans la base de données.
     *
     * @param mixed $token Le token à vérifier.
     * @return array Retourne un tableau associatif contenant les informations du token s'il existe, ou false s'il n'existe pas ou en cas d'erreur.
     */
    public function checkToken($token): array
    {
        try {
            // Crée une instance de UserModel
            $user = new UserModel();

            // Utilise le setter pour définir la propriété du token
            $user->setToken($token);

            // Prépare la requête de vérification du token
            $req = self::getInstance()->getConnexion()->prepare("SELECT email, token, expireAt FROM users WHERE token = :token");
            $req->bindValue('token', $user->getToken());

            // Exécute la requête
            $req->execute();

            // Récupère le résultat
            $result = $req->fetch(PDO::FETCH_ASSOC);
            return $result ? $result : array();
        } catch (\PDOException $e) {
            // Gère l'erreur PDO (journalisation, traitement, etc.)
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return array();
        }
    } // End checkToken().
}