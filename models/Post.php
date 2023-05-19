<?php
namespace Models;
use PDO;

class Post extends Database
{

    
    public function createPost($title, $content,$id_user)
    {
        try{

            $req = $this->connexion->prepare("INSERT INTO post(title, contenu, dateCreation, idUser) VALUES (:title, :content, NOW(), :idUser)");
            $req->bindValue('title', $title);
            $req->bindValue('content', $content);
            $req->bindValue('idUser', $id_user);
            return $req->execute();

        }catch(\PDOException $e){
            echo $e->getMessage();
            die;
        }

    public function getPosts()
    {
        $req = $this->connexion->prepare("SELECT * FROM posts ORDER BY dateCreation DESC");
        $req->execute();
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        return $result;
     

    }


    public function getPost($id)
    {
        $req = $this->connexion->prepare("SELECT * FROM posts WHERE id = :id");
        $req->bindValue('id', $id);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function updatePost($id, $titre, $contenu, $dateModification)
    {
        $req = $this->connexion->prepare("UPDATE posts SET titre = :titre, contenu = :contenu, dateModification = :dateModification WHERE id = :id");
        $req->bindValue('id', $id);
        $req->bindValue('titre', $titre);
        $req->bindValue('contenu', $contenu);
        $req->bindValue('dateModification', $dateModification);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function deletePost($id)
    {
        try{
        $req = $this->connexion->prepare("DELETE FROM posts WHERE id = :id");
        $req->bindValue('id', $id);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result;
        }catch(\PDOException $e){
            echo $e->getMessage();
            die;
        }

    }

    public function getDatas(){
        $req = $this->connexion->prepare("SELECT p.*, u.prenom,
                                          FROM posts p 
                                          INNER JOIN utilisateur u ON u.id = p.idUsers 
                                          ORDER BY p.dateCreation DESC");
        $req->execute();
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    
    }






}