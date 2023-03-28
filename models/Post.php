<?php

class Post extends Database
{
    public function getPosts()
    {
        $posts = $this->connexion->query('SELECT * FROM posts');
        return $posts;
    }
}