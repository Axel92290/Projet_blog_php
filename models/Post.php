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


    }


    public function getPosts()
    {
        try{

            $req = $this->connexion->prepare('SELECT * FROM posts ORDER BY id DESC');
            $req->execute();
            $posts = $req->fetchAll(PDO::FETCH_ASSOC);
            return $posts;

        }catch(\PDOException $e){
            echo $e->getMessage();
            die;
        }

    }
    

    public function getPost($id)
    {
        try{
            $req = $this->connexion->prepare('SELECT * FROM posts WHERE id = :id');
            $req->bindValue('id', $id);
            $req->execute();
            $getPost = $req->fetch(PDO::FETCH_ASSOC);
            return $getPost;
        }catch(\PDOException $e){
            echo $e->getMessage();
            die;
        }

    }
}