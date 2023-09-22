<?php

namespace Models;

use PDO;

class Post extends Database
{


    /**
     * Crée un nouveau post dans la base de données.
     *
     * @param string $titre Le titre du post.
     * @param string $chapo Le chapo du post.
     * @param string $contenu Le contenu du post.
     * @param int $idUser L'ID de l'utilisateur associé au post.
     * @return bool Retourne true si le post a été créé avec succès, ou false en cas d'erreur.
     */
    public function createPost($titre, $chapo, $contenu, $idUser): mixed
    {
        try {


            $req = self::getInstance()->getConnexion()->prepare("INSERT INTO posts (titre, chapo, contenu, dateModification, idUser) VALUES (:titre, :chapo, :contenu, NOW(), :idUser)");
            $req->bindValue('titre', $titre);
            $req->bindValue('chapo', $chapo);
            $req->bindValue('contenu', $contenu);
            $req->bindValue('idUser', $idUser);
            $req->execute();
            return true;
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }
        
    } // End createPost().


    /**
     * Récupère la liste des posts de la base de données.
     *
     * @param int|null $idPost L'ID du post à récupérer (optionnel).
     * @return array Retourne un tableau associatif des posts si $idPost est null, ou un tableau associatif du post spécifié par $idPost s'il est fourni. Retourne false en cas d'erreur.
     */
    public function getPosts($idPost = null): array
    {

        try {
            $sql = "SELECT p.id, p.titre, p.chapo, p.contenu, p.dateCreation, p.dateModification, u.firstname FROM posts p INNER JOIN users u ON u.id = p.idUser";

            if ($idPost) {

                $sql .= " WHERE p.id = :idPost";
            } else {
                $sql .= " ORDER BY dateCreation DESC";
            }

            $req = self::getInstance()->getConnexion()->prepare($sql);

            if ($idPost) {
                $req->bindValue('idPost', $idPost);
            }
            $req->execute();
            $result = $req->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return array();
        }

    } // End getPosts().


    /**
     * Met à jour un post dans la base de données.
     *
     * @param string $titre Le nouveau titre du post.
     * @param string $chapo Le nouveau chapo du post.
     * @param string $contenu Le nouveau contenu du post.
     * @param int $id L'ID du post à mettre à jour.
     * @return bool Retourne true si la mise à jour réussit, ou false en cas d'erreur.
     */
    public function updatePost($titre, $chapo, $contenu, $id): bool
    {
        try {
            $req = self::getInstance()->getConnexion()->prepare("UPDATE posts SET titre = :titre, chapo = :chapo, contenu = :contenu WHERE id = :id");
            $req->bindValue('id', $id);
            $req->bindValue('titre', $titre);
            $req->bindValue('chapo', $chapo);
            $req->bindValue('contenu', $contenu);
            $req->execute();
            return true;
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }

    } // End updatePost().


    /**
     * Supprime un post de la base de données.
     *
     * @param int $id L'ID du post à supprimer.
     * @return bool Retourne true si la suppression réussit, ou false en cas d'erreur.
     */
    public function deletePost($id): bool
    {
        try {
            $req = self::getInstance()->getConnexion()->prepare("DELETE FROM posts WHERE id = :id");
            $req->bindValue('id', $id);
            $req->execute();
            return true;
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }

    } // End deletePost().


    /**
     * Crée un commentaire dans la base de données.
     *
     * @param string $comment Le contenu du commentaire.
     * @param int $idUser L'ID de l'utilisateur qui a créé le commentaire.
     * @param int $idPost L'ID du post auquel le commentaire est associé.
     * @return bool Retourne true si la création réussit, ou false en cas d'erreur.
     */
    public function createComment($comment, $idUser, $idPost)
    {
        try {
            $req = self::getInstance()->getConnexion()->prepare("INSERT INTO comments (contenu, dateCreation, idUser, idPost) VALUES (:comment, NOW(), :idUser, :idPost)");
            $req->bindValue('comment', $comment);
            $req->bindValue('idUser', $idUser);
            $req->bindValue('idPost', $idPost);
            $req->execute();

            return true;
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }

    }  // End createComment().


    /**
     * Récupère les commentaires de la base de données.
     *
     * @param int|null $idPost L'ID du post auquel les commentaires sont associés (optionnel).
     * @param bool|null $adminPage Indique si c'est une page d'administration (optionnel).
     * @return array|bool Retourne un tableau de commentaires si la récupération réussit, ou false en cas d'erreur.
     */
    public function getComments($idPost = null, $adminPage = null)
    {
        try {
            $sql = "SELECT c.id, c.contenu, c.dateModification, c.idPost, c.statut, u.firstname FROM comments c INNER JOIN users u ON u.id = c.idUser";

            if ($adminPage === true) {
                if ($idPost) {
                    $sql .= " WHERE c.idPost = :idPost ORDER BY c.dateModification DESC";
                } else {
                    $sql .= " WHERE c.statut = 0 ORDER BY c.dateModification DESC";
                }
            } else {
                $sql .= " WHERE c.idPost = :idPost AND c.statut = 1 ORDER BY c.dateModification DESC";
            }

            $req = self::getInstance()->getConnexion()->prepare($sql);


            if ($idPost) {
                $req->bindValue('idPost', $idPost);
            }

            $req->execute();

            $result = $req->fetchAll(PDO::FETCH_ASSOC);


            return $result;
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return array();
        }

    } // End getComments().
    

    /**
     * Met à jour le statut d'un commentaire dans la base de données.
     *
     * @param int $idComment L'ID du commentaire à mettre à jour.
     * @param string $statut Le nouveau statut du commentaire ('approuver' ou 'refuser').
     * @return bool Retourne true si la mise à jour réussit, sinon false.
     */
    public function updateStatut($idComment, $statut): bool
    {

        try {
            $sql = "UPDATE comments SET statut = :statut WHERE id = :idComment";
            if ($statut == 'refuser') {
                $sql = "UPDATE comments SET statut = 2 WHERE id = :idComment";
            } else {
                $sql = "UPDATE comments SET statut = 1 WHERE id = :idComment";
            }
            $req = self::getInstance()->getConnexion()->prepare($sql);
            $req->bindValue('idComment', $idComment);
            $req->execute();

            return true;
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }

    } // End updateStatut().
}
