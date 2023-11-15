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
     */public function createPost($titre, $chapo, $contenu, $idUser): bool
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
     * @return mixed Retourne un tableau associatif des posts si $idPost est null, ou un tableau associatif du post spécifié par $idPost s'il est fourni. Retourne false en cas d'erreur.
     */
    public function getPosts($idPost = null): mixed
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
     * @return mixed Retourne true si la mise à jour réussit, ou false en cas d'erreur.
     */
    public function updatePost($titre, $chapo, $contenu, $id): mixed
    {
        try {
            $post = new PostModel();

            $post->setTitre($titre);
            $post->setChapo($chapo);
            $post->setContenu($contenu);
            $post->setId($id);

            // Prépare la requête SQL pour mettre à jour les informations du post
            $req = self::getInstance()->getConnexion()->prepare("UPDATE posts SET titre = :titre, chapo = :chapo, contenu = :contenu WHERE id = :id");

            // Lie les valeurs des paramètres de la requête SQL
            $req->bindValue('id', $post->getId());
            $req->bindValue('titre', $post->getTitre());
            $req->bindValue('chapo', $post->getChapo());
            $req->bindValue('contenu', $post->getContenu());

            // Exécute la requête SQL
            $req->execute();

            // Récupère les nouvelles informations du post après la mise à jour
            $result = $req->fetch(PDO::FETCH_ASSOC);

            // Retourne les nouvelles informations du post
            return $result;
        } catch (\PDOException $e) {
            // Gère l'erreur PDO (journalisation, traitement, etc.)
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }
    } // End updatePost().


    /**
     * Supprime un post de la base de données.
     *
     * @param int $id L'ID du post à supprimer.
     * @return mixed Retourne true si la suppression réussit, ou false en cas d'erreur.
     */
    public function deletePost($id): mixed
    {
        try {

            $post = new PostModel();
            $post->setId($id);

            $req = self::getInstance()->getConnexion()->prepare("DELETE FROM posts WHERE id = :id");
            $req->bindValue('id', $post->getId());
            return $req->execute();
        } catch (\PDOException $e) {
            $errorMessage = $e->getMessage();
            print_r($errorMessage);
            return false;
        }

    } // End deletePost().
} // End class.