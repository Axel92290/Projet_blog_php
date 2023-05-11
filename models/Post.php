<?php

namespace Models;
use PDO;

class Post extends Database
{
    // public function getPosts()
    // {
    //     $posts = $this->connexion->query('SELECT * FROM posts');
    //     return $posts;
    // }


    public function insertPost($title, $content, $dateCreation, $dateModification, $id_user)
    {
        $req = $this->connexion->prepare("INSERT INTO post(title, contenu, dateCreation, dateModification, idUser) VALUES (?, ?, ?, ?, ?)");
        $req->execute(array($title, $content, $dateCreation, $dateModification, $id_user));
    }


    public function createPost($title, $content, $dateCreation, $dateModification, $idUser)
    {
        $req = $this->connexion->prepare("INSERT INTO posts(titre, contenu, dateCreation, dateModification, idUser ) VALUES (?, ?, ?, ?, ?)");
        $req->execute(array($title, $content, $dateCreation, $dateModification, $idUser));
    }

    public function getPosts()
    {
        $posts = $this->connexion->query('SELECT * FROM posts ORDER BY ordre ASC');
        return $posts;
    }
    

    public function getPost($id)
    {
        $req = $this->connexion->prepare('SELECT * FROM posts WHERE id = ?');
        $req->execute(array($id));
        $post = $req->fetch();
        return $post;
    }
}