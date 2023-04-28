<?php
namespace Controllers;

class CreatePostsController extends BaseController
{
    public function createPost()
    {

        // on choisi la template à appeler
        $template = $this->twig->load('create-posts/create.html');

        // $post = new Post();
        // $listPost = $post->getPosts();


        // Puis on affiche la page avec la méthode render
        echo $template->render(['title' => 'Création d\'un post']);
    }
}