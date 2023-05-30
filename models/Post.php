<?php
namespace Models;
use PDO;

class Post extends Database
{

    
    public function createPost($titre, $chapo, $contenu, $idUser)
    {
        try{

            $req = $this->connexion->prepare("INSERT INTO posts (titre, chapo, contenu, dateModification, idUser) VALUES (:titre, :chapo, :contenu, NOW(), :idUser)");
            $req->bindValue('titre', $titre);
            $req->bindValue('chapo', $chapo);
            $req->bindValue('contenu', $contenu);
            $req->bindValue('idUser', $idUser);
            return $req->execute();

        }catch(\PDOException $e){
            echo $e->getMessage();
            die;
        }
    }

    public function getPosts($idPost = null)
    {
        try{
        $sql = "SELECT p.id, p.titre, p.chapo, p.contenu, p.dateCreation, p.dateModification, u.firstname FROM posts p INNER JOIN users u ON u.id = p.idUser";

        if($idPost){

            $sql .= " WHERE p.id = :idPost";
        }else{
            $sql .= " ORDER BY dateCreation DESC";
        }

        $req = $this->connexion->prepare($sql);

        if($idPost){
            $req->bindValue('idPost', $idPost);
        }
        $req->execute();
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        return $result;

        }catch(\PDOException $e){
            echo $e->getMessage();
            die;
        }

    }


    public function updatePost($titre, $chapo, $contenu, $id)
    {
        try{
        $req = $this->connexion->prepare("UPDATE posts SET titre = :titre, chapo = :chapo, contenu = :contenu WHERE id = :id");
        $req->bindValue('id', $id);
        $req->bindValue('titre', $titre);
        $req->bindValue('chapo', $chapo);
        $req->bindValue('contenu', $contenu);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result;
        }catch(\PDOException $e){
            echo $e->getMessage();
            die;
        }
    }

    public function deletePost($id)
    {
        try{
        $req = $this->connexion->prepare("DELETE FROM posts WHERE id = :id");
        $req->bindValue('id', $id);
        return $req->execute();

        }catch(\PDOException $e){
            echo $e->getMessage();
            die;
        }

    }

    public function createComment($comment, $idUser, $idPost)
    {
        try{
            $req = $this->connexion->prepare("INSERT INTO comments (contenu, dateCreation, idUser, idPost) VALUES (:comment, NOW(), :idUser, :idPost)");
            $req->bindValue('comment', $comment);
            $req->bindValue('idUser', $idUser);
            $req->bindValue('idPost', $idPost);
            return $req->execute();
        }catch(\PDOException $e){
            echo $e->getMessage();
            die;
        }

    }

    public function getComments($idPost = null, $adminPage = null){
        try{
            $sql = "SELECT c.id, c.contenu, c.dateModification, c.idPost, c.statut, u.firstname FROM comments c INNER JOIN users u ON u.id = c.idUser";
    
            if($adminPage == true){
                if($idPost){
                    $sql .= " WHERE c.idPost = :idPost ORDER BY c.dateModification DESC";
                }else{
                    $sql .= " WHERE c.statut = 0 ORDER BY c.dateModification DESC";
                }
            }else{
                $sql .= " WHERE c.idPost = :idPost AND c.statut = 1 ORDER BY c.dateModification DESC";
            }
    
            $req = $this->connexion->prepare($sql);

    
            if($idPost){
                $req->bindValue('idPost', $idPost);
            }
    
            $req->execute();   

            $result = $req->fetchAll(PDO::FETCH_ASSOC);
            // var_dump($result);
            // die;

            return $result;
    
        }catch(\PDOException $e){
            echo $e->getMessage();
            die;
        }
    }

    public function updateStatut($idComment, $action)
    {
        
        try{
            $statut = ($action === 'refuser') ? 2 : 1;
            $sql = "UPDATE comments SET statut = :statut WHERE id = :idComment";
            $req = $this->connexion->prepare($sql);
            $req->bindValue('idComment', $idComment);
            $req->bindValue('statut', $statut);
            $req->execute();
            $result = $req->fetch(PDO::FETCH_ASSOC);
            return $result;
        }catch(\PDOException $e){
            echo $e->getMessage();
            die;
        }
    }

}