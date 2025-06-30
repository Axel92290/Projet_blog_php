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
     * @return mixed Retourne true si le post a été créé avec succès, ou false en cas d'erreur.
     */
    public function createPost(string $titre, string $chapo, string $contenu, int $idUser): bool
    {
        try {
            // Crée une instance de PostModel
            $post = new PostModel();

            // Utilise les setters pour définir les propriétés du post
            $post->setTitre($titre);
            $post->setChapo($chapo);
            $post->setContenu($contenu);
            $post->setIdUser($idUser);

            // Prépare la requête d'insertion du post
            $req = self::getInstance()->getConnexion()->prepare("INSERT INTO posts (titre, chapo, contenu, dateModification, idUser) VALUES (:titre, :chapo, :contenu, NOW(), :idUser)");
            $req->bindValue('titre', $post->getTitre());
            $req->bindValue('chapo', $post->getChapo());
            $req->bindValue('contenu', $post->getContenu());
            $req->bindValue('idUser', $post->getIdUser());

            // Exécute la requête
            return $req->execute();
        } catch (\PDOException $e) {
            // Gère l'erreur PDO (journalisation, traitement, etc.)
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }
    } // End createPost().


    /**
     * Récupère la liste des posts de la base de données.
     *
     * @param int|null $idPost L'ID du post à récupérer (optionnel).
     * @return array|false Retourne un tableau associatif des posts si $idPost est null, ou un tableau associatif du post spécifié par $idPost s'il est fourni. Retourne false en cas d'erreur.
     */
    public function getPosts(int $idPost = null): array|false
    {
        try {

            $post = new PostModel();

            $post->setId($idPost);

            // Construit la requête SQL pour récupérer les informations des posts avec jointure sur la table users
            $sql = "SELECT p.id, p.titre, p.chapo, p.contenu, p.dateCreation, p.dateModification, u.firstname FROM posts p INNER JOIN users u ON u.id = p.idUser";

            // Ajoute la clause WHERE si un identifiant de post est fourni
            if ($post->getId()) {

                $sql .= " WHERE p.id = :idPost";
            } else {
                $sql .= " ORDER BY dateCreation DESC";
            }

            // Prépare la requête SQL
            $req = self::getInstance()->getConnexion()->prepare($sql);

            // Lie la valeur de l'identifiant du post si fourni
            if ($post->getId()) {
                $req->bindValue('idPost', $post->getId());
            }

            // Exécute la requête SQL
            $req->execute();

            // Récupère les résultats de la requête
            $result = $req->fetchAll(PDO::FETCH_ASSOC);

            // Retourne les résultats
            return $result;
        } catch (\PDOException $e) {
            // Gère l'erreur PDO (journalisation, traitement, etc.)
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
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
    public function updatePost(string $titre, string $chapo, string $contenu, int $id): bool
    {
        try {
            $post = new PostModel();

            $post->setTitre($titre);
            $post->setChapo($chapo);
            $post->setContenu($contenu);
            $post->setId($id);

            $req = self::getInstance()->getConnexion()->prepare("
            UPDATE posts 
            SET titre = :titre, chapo = :chapo, contenu = :contenu 
            WHERE id = :id
        ");

            $req->bindValue('id', $post->getId());
            $req->bindValue('titre', $post->getTitre());
            $req->bindValue('chapo', $post->getChapo());
            $req->bindValue('contenu', $post->getContenu());

            return $req->execute();
        } catch (\PDOException $e) {
            print_r($e->getMessage());
            return false;
        }
    } // End updatePost().


    /**
     * Supprime un post de la base de données.
     *
     * @param int $id L'ID du post à supprimer.
     * @return bool Retourne true si la suppression réussit, ou false en cas d'erreur.
     */
    public function deletePost(int $id): bool
    {
        try {
            $post = new PostModel();
            $post->setId($id);

            $req = self::getInstance()->getConnexion()->prepare(
                "DELETE FROM posts WHERE id = :id"
            );
            $req->bindValue('id', $post->getId());

            return $req->execute();
        } catch (\PDOException $e) {
            print_r($e->getMessage());
            return false;
        }
    }

} // End class.