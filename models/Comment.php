<?php

namespace Models;

use Tools;
use PDO;

class Comment extends Database
{
    /**
     * Crée un commentaire dans la base de données.
     *
     * @param string $comment Le contenu du commentaire.
     * @param int $idUser L'ID de l'utilisateur qui a créé le commentaire.
     * @param int $idPost L'ID du post auquel le commentaire est associé.
     * @return bool Retourne true si la création réussit, ou false en cas d'erreur.
     */
    public function createComment($contenu, $idUser, $idPost): mixed
    {
        try {
            $comment = new CommentModel();
            $comment->setContenu($contenu);
            $comment->setIdUser($idUser);
            $comment->setIdPost($idPost);

            $req = self::getInstance()->getConnexion()->prepare("INSERT INTO comments (contenu, dateCreation, idUser, idPost) VALUES (:comment, NOW(), :idUser, :idPost)");
            $req->bindValue('comment', $comment->getContenu());
            $req->bindValue('idUser', $comment->getIdUser());
            $req->bindValue('idPost', $comment->getIdPost());
            $req->execute();
            return true;
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }

    } // End createComment().


    /**
     * Récupère les commentaires de la base de données.
     *
     * @param int|null $idPost L'ID du post auquel les commentaires sont associés (optionnel).
     * @param bool|null $adminPage Indique si c'est une page d'administration (optionnel).
     * @return array|bool Retourne un tableau de commentaires si la récupération réussit, ou false en cas d'erreur.
     */
    public function getComments($idPost = null, $adminPage = null): mixed
    {
        try {

            $comment = new CommentModel();
            $comment->setIdPost($idPost);
            $comment->setAdminPage($adminPage);

            $sql = "SELECT c.id, c.contenu, c.dateModification, c.idPost, c.statut, u.firstname FROM comments c INNER JOIN users u ON u.id = c.idUser";

            if ($comment->getAdminPage() === true) {
                if ($comment->getIdPost()) {
                    $sql .= " WHERE c.idPost = :idPost ORDER BY c.dateModification DESC";
                } else {
                    $sql .= " WHERE c.statut = 0 ORDER BY c.dateModification DESC";
                }
            } else {
                $sql .= " WHERE c.idPost = :idPost AND c.statut = 1 ORDER BY c.dateModification DESC";
            }

            $req = self::getInstance()->getConnexion()->prepare($sql);


            if ($comment->getIdPost()) {
                $req->bindValue('idPost', $idPost);
            }

            $req->execute();

            $result = $req->fetchAll(PDO::FETCH_ASSOC);


            return $result;
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }

    } // End getComments().


    /**
     * Met à jour le statut d'un commentaire dans la base de données.
     *
     * @param int $idComment L'ID du commentaire à mettre à jour.
     * @param string $statut Le nouveau statut du commentaire ('approuver' ou 'refuser').
     * @return bool Retourne true si la mise à jour réussit, sinon false.
     */
    public function updateStatut($idComment, $statut): mixed
    {

        try {

            $comment = new CommentModel();

            $comment->setId($idComment);
            $comment->setStatut($statut);




            $sql = "UPDATE comments SET statut = :statut WHERE id = :idComment";
            if ($comment->getStatut() == 'refuser') {
                $sql = "UPDATE comments SET statut = 2 WHERE id = :idComment";
            } else {
                $sql = "UPDATE comments SET statut = 1 WHERE id = :idComment";
            }
            $req = self::getInstance()->getConnexion()->prepare($sql);
            $req->bindValue('idComment', $comment->getId());
            $req->execute();

            return true;
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }

    } // End updateStatut().
} // End class.