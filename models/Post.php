<?php
namespace Models;
use PDO;

class Post extends Database
{


    public function createPost($title, $contenu, $dateCreation, $dateModification, $idUser)
    {
        $req = $this->connexion->prepare("INSERT INTO posts (titre, contenu, dateCreation, dateModification, idUser) VALUES (:title, :contenu, :dateCreation, :dateModification, :idUser)");
        $req->bindValue('titre', $title);
        $req->bindValue('contenu', $contenu);
        $req->bindValue('dateCreation', $dateCreation);
        $req->bindValue('dateModification', $dateModification);
        $req->bindValue('idUser', $idUser);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result;


       
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
        $req = $this->connexion->prepare("DELETE FROM posts WHERE id = :id");
        $req->bindValue('id', $id);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result;
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