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


    public function updatePost($id, $titre, $contenu, $dateModification)
    {
        try{
        $req = $this->connexion->prepare("UPDATE posts SET titre = :titre, contenu = :contenu, dateModification = :dateModification WHERE id = :id");
        $req->bindValue('id', $id);
        $req->bindValue('titre', $titre);
        $req->bindValue('contenu', $contenu);
        $req->bindValue('dateModification', $dateModification);
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
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result;
        }catch(\PDOException $e){
            echo $e->getMessage();
            die;
        }

    }







}