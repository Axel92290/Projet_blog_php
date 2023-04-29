<?php

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
}